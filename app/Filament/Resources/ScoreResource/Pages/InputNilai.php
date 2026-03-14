<?php

namespace App\Filament\Resources\ScoreResource\Pages;

use App\Filament\Resources\ScoreResource;
use App\Models\Aspect;
use App\Models\Indicator;
use App\Models\LearningObjective;
use App\Models\LearningOutcome;
use App\Models\PenilaianConfig;
use App\Models\Score;
use App\Models\Student;
use App\Models\Subject;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;

class InputNilai extends Page
{
    protected static string $resource = ScoreResource::class;

    protected static ?string $title = 'Input Penilaian Siswa';

    protected static string $view = 'filament.resources.score-resource.pages.input-nilai';

    public ?string $student_id = null;

    public ?string $subject_id = null;

    public ?array $cp_ids = [];

    public ?array $tp_ids = [];

    public ?string $jenis_penilaian = null;

    // All aspect IDs to evaluate (from config, now supports multiple)
    public array $aspect_ids = [];

    // Scores array: indicator_id => score_value
    public array $scores = [];

    // Config display names
    public ?string $studentName = null;

    public ?string $subjectName = null;

    public ?string $aspectName = null;

    public array $cpNames = [];

    public array $tpNames = [];

    public function mount(): void
    {
        $tenantId = Filament::getTenant()?->id;
        $userId = auth()->id();

        // Pre-fill student jika datang dari Data Siswa
        $studentId = request()->query('student_id');
        if ($studentId) {
            $this->student_id = $studentId;
            $this->studentName = Student::find($studentId)?->nama;
        }

        // Load konsep penilaian config
        $config = PenilaianConfig::where('school_profile_id', $tenantId)
            ->where('user_id', $userId)
            ->first();

        if ($config) {
            $this->subject_id = $config->subject_id ? (string) $config->subject_id : null;
            $this->jenis_penilaian = $config->jenis_penilaian;
            $this->cp_ids = $config->cp_ids ?? [];
            $this->tp_ids = $config->tp_ids ?? [];

            // Resolve display names
            $this->subjectName = Subject::find($this->subject_id)?->nama_mapel;

            // Determine aspect(s) to evaluate from config
            $configAspectIds = $config->aspect_ids ?? [];

            if (! empty($configAspectIds)) {
                // Specific aspects selected in config
                $this->aspect_ids = array_map('strval', $configAspectIds);
                $aspectNames = Aspect::whereIn('id', $configAspectIds)->pluck('nama_aspek')->toArray();
                $this->aspectName = implode(', ', $aspectNames);
            } elseif ($this->jenis_penilaian) {
                // No specific aspect → load ALL aspects for this jenis_penilaian
                $query = Aspect::where('school_profile_id', $tenantId)
                    ->where('jenis_penilaian', $this->jenis_penilaian);

                if (auth()->user()?->role === 'admin' && auth()->user()?->kelas) {
                    $query->where('kelas', auth()->user()->kelas);
                }

                $aspects = $query->get();
                $this->aspect_ids = $aspects->pluck('id')->map(fn ($id) => (string) $id)->toArray();
                $this->aspectName = 'Semua Aspek';
            }

            if (! empty($this->cp_ids)) {
                $this->cpNames = LearningOutcome::whereIn('id', $this->cp_ids)->pluck('deskripsi')->toArray();
            }
            if (! empty($this->tp_ids)) {
                $this->tpNames = LearningObjective::whereIn('id', $this->tp_ids)->pluck('deskripsi')->toArray();
            }
        }

        // Load scores jika student dan aspek sudah terisi
        if ($this->student_id && ! empty($this->aspect_ids)) {
            $this->loadScores();
        }
    }

    /**
     * Load existing scores for all selected aspects.
     */
    public function loadScores(): void
    {
        if (! $this->student_id || empty($this->aspect_ids)) {
            $this->scores = [];

            return;
        }

        $indicators = Indicator::whereIn('aspect_id', $this->aspect_ids)->get();

        $query = Score::where('student_id', $this->student_id)
            ->whereIn('indicator_id', $indicators->pluck('id'));

        // Filter by subject_id if set
        if ($this->subject_id) {
            $query->where('subject_id', $this->subject_id);
        }

        // Filter by current cp_ids context – only show scores that match current CP selection
        if (! empty($this->cp_ids)) {
            $query->where(function ($q) {
                foreach ($this->cp_ids as $cpId) {
                    $q->orWhereJsonContains('cp_ids', (string) $cpId);
                }
            });
        }

        // Filter by current tp_ids context – only show scores that match current TP selection
        if (! empty($this->tp_ids)) {
            $query->where(function ($q) {
                foreach ($this->tp_ids as $tpId) {
                    $q->orWhereJsonContains('tp_ids', (string) $tpId);
                }
            });
        }

        $existingScores = $query->pluck('score_value', 'indicator_id')
            ->toArray();

        $this->scores = [];
        foreach ($indicators as $ind) {
            $this->scores[(string) $ind->id] = isset($existingScores[$ind->id])
                ? (string) $existingScores[$ind->id]
                : null;
        }
    }

    /**
     * Get aspects with their indicators for the scoring view.
     */
    public function getAspectsWithIndicatorsProperty(): \Illuminate\Support\Collection
    {
        if (empty($this->aspect_ids)) {
            return collect();
        }

        return Aspect::whereIn('id', $this->aspect_ids)
            ->with('indicators')
            ->get();
    }

    /**
     * Check if there are any indicators to score.
     */
    public function getHasIndicatorsProperty(): bool
    {
        return $this->aspectsWithIndicators->flatMap->indicators->isNotEmpty();
    }

    /**
     * Save all scores for the selected student + all aspect indicators.
     */
    public function save(): void
    {
        if (! $this->student_id || empty($this->aspect_ids)) {
            Notification::make()
                ->warning()
                ->title('Peringatan')
                ->body('Pastikan konsep penilaian sudah disetting dan siswa sudah dipilih!')
                ->send();

            return;
        }

        $tenantId = Filament::getTenant()?->id;
        $savedCount = 0;

        foreach ($this->scores as $indicatorId => $value) {
            if ($value !== null && $value !== '') {
                $score = Score::firstOrNew([
                    'student_id' => $this->student_id,
                    'indicator_id' => $indicatorId,
                    'subject_id' => $this->subject_id,
                ]);

                $existingCpIds = is_array($score->cp_ids) ? $score->cp_ids : [];
                $existingTpIds = is_array($score->tp_ids) ? $score->tp_ids : [];

                $mergedCpIds = collect(array_merge($existingCpIds, $this->cp_ids ?? []))
                    ->filter(fn ($id) => $id !== null && $id !== '')
                    ->map(fn ($id) => (string) $id)
                    ->unique()
                    ->values()
                    ->toArray();

                $mergedTpIds = collect(array_merge($existingTpIds, $this->tp_ids ?? []))
                    ->filter(fn ($id) => $id !== null && $id !== '')
                    ->map(fn ($id) => (string) $id)
                    ->unique()
                    ->values()
                    ->toArray();

                $score->score_value = (int) $value;
                $score->school_profile_id = $tenantId;
                $score->cp_ids = $mergedCpIds;
                $score->tp_ids = $mergedTpIds;
                $score->save();

                $savedCount++;
            }
        }

        $studentName = Student::find($this->student_id)?->nama ?? 'Siswa';

        Notification::make()
            ->success()
            ->title('Berhasil!')
            ->body("$savedCount nilai untuk $studentName berhasil disimpan.")
            ->send();

        $this->redirect(\App\Filament\Resources\StudentResource::getUrl('index'));
    }
}
