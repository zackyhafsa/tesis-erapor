<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use App\Models\SchoolProfile;
use App\Filament\Pages\Tenancy\EditSchoolProfile;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->profile(\App\Filament\Pages\Auth\EditProfile::class)
            ->tenant(SchoolProfile::class, slugAttribute: 'id')
            ->tenantProfile(EditSchoolProfile::class)
            ->brandName('SIPEKA')
            ->brandLogo(function () {
                // Cek apakah url saat ini adalah halaman login
                if (request()->is('admin/login')) {
                    // JIKA DI HALAMAN LOGIN: Tampilkan gambar logonya saja (Dibuat agak besar di tengah)
                    return new HtmlString('
                        <img src="'.asset('images/sipeka-logo.png').'" alt="Logo SIPEKA" style=" margin: 0 auto;">
                    ');
                }

                return new HtmlString('
                    <div class="flex items-center gap-2">
                        <img src="'.asset('images/sipeka-logo.png').'" alt="Logo" class="h-10 w-auto rounded-lg">
                        <span class="text-2xl font-bold tracking-wide text-gray-800 dark:text-white">SIPEKA</span>
                    </div>
                ');
            })
            // Ketinggian logo juga otomatis berubah (6rem di login, 2.5rem di dashboard)
            ->brandLogoHeight(request()->is('admin/login') ? '6rem' : '2.5rem')
            ->favicon(asset('images/sipeka-logo.png'))
            ->colors([
                'primary' => Color::Sky,
            ])
            ->renderHook(
                PanelsRenderHook::AUTH_LOGIN_FORM_BEFORE,
                fn (): string => Blade::render('
                    <div class="text-center -mt-4 mb-4">
                    <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-200">Selamat Datang di SIPEKA</h1>
                        <p class="text-sm font-semibold text-gray-500 dark:text-gray-400">
                            Sistem Penilaian Proyek dan Kinerja
                        </p>
                    </div>
                ')
            )
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                \App\Filament\Widgets\WelcomeWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
