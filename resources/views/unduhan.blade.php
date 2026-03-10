<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unduhan - SIPEKA</title>
    <link rel="icon" type="image/png" href="{{ asset('images/sipeka-logo.png') }}">

    {{-- Tailwind CSS CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a'
                        },
                        accent: {
                            50: '#fdf4ff',
                            100: '#fae8ff',
                            200: '#f5d0fe',
                            300: '#f0abfc',
                            400: '#e879f9',
                            500: '#d946ef',
                            600: '#c026d3',
                            700: '#a21caf',
                            800: '#86198f',
                            900: '#701a75'
                        },
                    },
                    fontFamily: {
                        sans: ['Inter', 'system-ui', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <style>
        * {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Inter', system-ui, sans-serif;
        }

        .gradient-text {
            background: linear-gradient(135deg, #2563eb, #7c3aed, #d946ef);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-gradient {
            background: linear-gradient(135deg, #eff6ff 0%, #f5f3ff 50%, #fdf4ff 100%);
        }

        .cta-gradient {
            background: linear-gradient(135deg, #1e3a8a 0%, #5b21b6 50%, #86198f 100%);
        }

        .glass {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.4);
        }

        .nav-link {
            position: relative;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 0;
            width: 0;
            height: 2px;
            background: linear-gradient(135deg, #2563eb, #7c3aed);
            transition: width 0.3s ease;
            border-radius: 2px;
        }

        .nav-link:hover::after,
        .nav-link.active::after {
            width: 100%;
        }
    </style>
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="bg-white text-gray-800 overflow-x-hidden">
    {{-- ==================== NAVBAR ==================== --}}
    <nav class="fixed top-0 left-0 right-0 z-50 glass shadow-sm" id="navbar">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 md:h-20">
                {{-- Logo --}}
                <a href="{{ url('/') }}" class="flex items-center gap-3 group">
                    <img src="{{ asset('images/sipeka-logo.png') }}" alt="SIPEKA Logo"
                        class="h-10 w-10 md:h-12 md:w-12 rounded-xl shadow-md group-hover:shadow-lg transition-shadow">
                    <div>
                        <span class="text-xl md:text-2xl font-extrabold gradient-text">SIPEKA</span>
                        <p class="text-[10px] md:text-xs text-gray-500 -mt-1 hidden sm:block">Sistem Penilaian Proyek &
                            Kinerja</p>
                    </div>
                </a>

                {{-- Desktop Nav --}}
                <div class="hidden md:flex items-center gap-8">
                    <a href="{{ url('/') }}"
                        class="nav-link text-sm font-medium text-gray-700 hover:text-primary-600 transition-colors">Beranda</a>
                    <a href="{{ url('/') }}#fitur"
                        class="nav-link text-sm font-medium text-gray-700 hover:text-primary-600 transition-colors">Fitur</a>
                    <a href="{{ url('/') }}#cara-kerja"
                        class="nav-link text-sm font-medium text-gray-700 hover:text-primary-600 transition-colors">Cara
                        Kerja</a>
                    <a href="{{ url('/') }}#tentang"
                        class="nav-link text-sm font-medium text-gray-700 hover:text-primary-600 transition-colors">Tentang</a>
                    <a href="{{ url('/unduhan') }}"
                        class="nav-link active text-sm font-medium text-primary-600 transition-colors">Unduhan</a>
                    <a href="{{ url('/admin/login') }}"
                        class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-primary-600 to-primary-700 text-white rounded-full text-sm font-semibold shadow-lg shadow-primary-500/30 hover:shadow-primary-500/50 hover:from-primary-700 hover:to-primary-800 transition-all duration-300">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1">
                            </path>
                        </svg>
                        Masuk
                    </a>
                </div>

                {{-- Mobile Menu Button --}}
                <button onclick="toggleMobile()" class="md:hidden p-2 rounded-lg hover:bg-gray-100 transition-colors"
                    id="mobileBtn">
                    <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>

            {{-- Mobile Nav --}}
            <div class="md:hidden hidden pb-4 border-t border-gray-200/50" id="mobileNav">
                <div class="flex flex-col gap-2 pt-4">
                    <a href="{{ url('/') }}"
                        class="px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-primary-50 hover:text-primary-600 rounded-lg transition-colors">Beranda</a>
                    <a href="{{ url('/') }}#fitur"
                        class="px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-primary-50 hover:text-primary-600 rounded-lg transition-colors">Fitur</a>
                    <a href="{{ url('/') }}#cara-kerja"
                        class="px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-primary-50 hover:text-primary-600 rounded-lg transition-colors">Cara
                        Kerja</a>
                    <a href="{{ url('/') }}#tentang"
                        class="px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-primary-50 hover:text-primary-600 rounded-lg transition-colors">Tentang</a>
                    <a href="{{ url('/unduhan') }}"
                        class="px-4 py-2.5 text-sm font-bold text-primary-600 bg-primary-50 rounded-lg transition-colors">Unduhan</a>
                    <a href="{{ url('/admin/login') }}"
                        class="mx-4 mt-2 text-center px-6 py-2.5 bg-gradient-to-r from-primary-600 to-primary-700 text-white rounded-full text-sm font-semibold shadow-lg">Masuk</a>
                </div>
            </div>
        </div>
    </nav>

    {{-- HEADER SECTION --}}
    <section class="pt-32 pb-16 hero-gradient relative border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-white/60 backdrop-blur-sm border border-primary-200 rounded-full text-sm font-medium text-primary-700 mb-6"
                data-aos="zoom-in">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                </svg>
                Pusat Sumber Daya
            </div>
            <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 mb-4 tracking-tight" data-aos="fade-up">Pusat
                <span class="gradient-text">Unduhan</span>
            </h1>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto" data-aos="fade-up" data-aos-delay="100">Dapatkan berbagai
                file template, format excel, dan file asesmen yang dibutuhkan untuk menunjang kegiatan penilaian.</p>
        </div>
    </section>

    {{-- MAIN CONTENT --}}
    <section class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-8 lg:gap-12">
                {{-- Template Excel --}}
                <div class="bg-white rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100 p-8 hover:-translate-y-1 transition-transform duration-300"
                    data-aos="fade-up" data-aos-delay="200">
                    <div class="flex items-center gap-4 mb-8">
                        <div
                            class="w-14 h-14 bg-gradient-to-br from-green-400 to-green-600 shadow-lg shadow-green-500/30 rounded-2xl flex items-center justify-center text-white">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">Template Excel</h2>
                            <p class="text-sm text-gray-500 mt-1">Format impor data massal</p>
                        </div>
                    </div>

                    <ul class="space-y-4" x-data="{ expanded: false }">

                        {{-- download aspek --}}
                        <li
                            class="flex flex-col sm:flex-row sm:items-center justify-between p-5 bg-gray-50/80 hover:bg-green-50/50 rounded-2xl border border-gray-100 hover:border-green-100 transition-colors gap-4">
                            <div class="flex items-start gap-3">
                                <div class="p-2 bg-white rounded-lg shadow-sm">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <span class="font-bold text-gray-800 block">Template Aspek Penilaian</span>
                                    <span class="text-xs text-gray-500">.xlsx format</span>
                                </div>
                            </div>
                            <div class="flex gap-2 max-md:flex-col">
                                <a href="https://docs.google.com/spreadsheets/d/1TBPoVo8VEv0rMus4RIYtdKNpn1FL5EJT/edit?usp=sharing&ouid=113513031459863788859&rtpof=true&sd=true"
                                    target="_blank"
                                    class="px-5 py-2.5 bg-white border border-gray-200 text-sm font-semibold rounded-xl hover:border-green-500 hover:text-green-600 transition-colors text-center shadow-sm">Lihat</a>
                                <a href="{{ asset('download/Template_Import_Aspek.xlsx') }}" download
                                    class="px-5 py-2.5 bg-green-600 hover:bg-white text-white border border-gray-200 text-sm font-semibold rounded-xl hover:border-green-500 hover:text-green-600 transition-colors text-center shadow-sm">Unduh</a>
                            </div>
                        </li>

                        {{-- download indikator --}}
                        <li
                            class="flex flex-col sm:flex-row sm:items-center justify-between p-5 bg-gray-50/80 hover:bg-green-50/50 rounded-2xl border border-gray-100 hover:border-green-100 transition-colors gap-4">
                            <div class="flex items-start gap-3">
                                <div class="p-2 bg-white rounded-lg shadow-sm">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <span class="font-bold text-gray-800 block">Template Indikator Penilaian</span>
                                    <span class="text-xs text-gray-500">.xlsx format</span>
                                </div>
                            </div>
                            <div class="flex gap-2 max-md:flex-col">
                                <a href="https://docs.google.com/spreadsheets/d/1nrEv0Q4wLDqRyV5hSIn9UVkmJmZ2m_7-/edit?usp=sharing&ouid=113513031459863788859&rtpof=true&sd=true"
                                    target="_blank"
                                    class="px-5 py-2.5 bg-white border border-gray-200 text-sm font-semibold rounded-xl hover:border-green-500 hover:text-green-600 transition-colors text-center shadow-sm">Lihat</a>
                                <a href="{{ asset('download/Template_Import_Indikator.xlsx') }}" download
                                    class="px-5 py-2.5 bg-green-600 hover:bg-white text-white border border-gray-200 text-sm font-semibold rounded-xl hover:border-green-500 hover:text-green-600 transition-colors text-center shadow-sm">Unduh</a>
                            </div>
                        </li>

                        {{-- download data siswa --}}
                        <li
                            class="flex flex-col sm:flex-row sm:items-center justify-between p-5 bg-gray-50/80 hover:bg-green-50/50 rounded-2xl border border-gray-100 hover:border-green-100 transition-colors gap-4">
                            <div class="flex items-start gap-3">
                                <div class="p-2 bg-white rounded-lg shadow-sm">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <span class="font-bold text-gray-800 block">Template Data Siswa</span>
                                    <span class="text-xs text-gray-500">.xlsx format</span>
                                </div>
                            </div>
                            <div class="flex gap-2 max-md:flex-col">
                                <a href="https://docs.google.com/spreadsheets/d/1TsHKQ0_aCCt6s9uqNImogvXlSvlGfnM9/edit?usp=sharing&ouid=113513031459863788859&rtpof=true&sd=true"
                                    target="_blank"
                                    class="px-5 py-2.5 bg-white border border-gray-200 text-sm font-semibold rounded-xl hover:border-green-500 hover:text-green-600 transition-colors text-center shadow-sm">Lihat</a>
                                <a href="{{ asset('download/Template_Import_Siswa.xlsx') }}" download
                                    class="px-5 py-2.5 bg-green-600 hover:bg-white text-white border border-gray-200 text-sm font-semibold rounded-xl hover:border-green-500 hover:text-green-600 transition-colors text-center shadow-sm">Unduh</a>
                            </div>

                        </li>

                        {{-- Hidden Items --}}
                        <div x-show="expanded" x-transition.opacity.duration.300ms class="space-y-4">
                            {{-- download mata pelajaran --}}
                            <li
                                class="flex flex-col sm:flex-row sm:items-center justify-between p-5 bg-gray-50/80 hover:bg-green-50/50 rounded-2xl border border-gray-100 hover:border-green-100 transition-colors gap-4">
                                <div class="flex items-start gap-3">
                                    <div class="p-2 bg-white rounded-lg shadow-sm">
                                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                            </path>
                                        </svg>
                                    </div>
                                    <div>
                                        <span class="font-bold text-gray-800 block">Template Mata Pelajaran</span>
                                        <span class="text-xs text-gray-500">.xlsx format</span>
                                    </div>
                                </div>
                                <div class="flex gap-2 max-md:flex-col">
                                    <a href="https://docs.google.com/spreadsheets/d/1rz5M962WfP3u97QCiFcJlsr3R2PllfoQ/edit?usp=sharing&ouid=113513031459863788859&rtpof=true&sd=true"
                                        target="_blank"
                                        class="px-5 py-2.5 bg-white border border-gray-200 text-sm font-semibold rounded-xl hover:border-green-500 hover:text-green-600 transition-colors text-center shadow-sm">Lihat</a>
                                    <a href="{{ asset('download/Template_Import_Mapel.xlsx') }}" download
                                        class="px-5 py-2.5 bg-green-600 hover:bg-white text-white border border-gray-200 text-sm font-semibold rounded-xl hover:border-green-500 hover:text-green-600 transition-colors text-center shadow-sm">Unduh</a>
                                </div>
                            </li>

                            {{-- Download Capaian Pembelajaran --}}
                            <li
                                class="flex flex-col sm:flex-row sm:items-center justify-between p-5 bg-gray-50/80 hover:bg-green-50/50 rounded-2xl border border-gray-100 hover:border-green-100 transition-colors gap-4">
                                <div class="flex items-start gap-3">
                                    <div class="p-2 bg-white rounded-lg shadow-sm">
                                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                            </path>
                                        </svg>
                                    </div>
                                    <div>
                                        <span class="font-bold text-gray-800 block">Template Capaian
                                            Pembelajaran</span>
                                        <span class="text-xs text-gray-500">.xlsx format</span>
                                    </div>
                                </div>
                                <div class="flex gap-2 max-md:flex-col">
                                    <a href="https://docs.google.com/spreadsheets/d/1eF8NO3wMV3M0dtaklXFxQ0aEbrh8SKBu/edit?usp=sharing&ouid=113513031459863788859&rtpof=true&sd=true"
                                        target="_blank"
                                        class="px-5 py-2.5 bg-white border border-gray-200 text-sm font-semibold rounded-xl hover:border-green-500 hover:text-green-600 transition-colors text-center shadow-sm">Lihat</a>
                                    <a href="{{ asset('download/Template_Import_CP.xlsx') }}" download
                                        class="px-5 py-2.5 bg-green-600 hover:bg-white text-white border border-gray-200 text-sm font-semibold rounded-xl hover:border-green-500 hover:text-green-600 transition-colors text-center shadow-sm">Unduh</a>
                                </div>
                            </li>

                            {{-- Download Tujuan Pembelajaran --}}
                            <li
                                class="flex flex-col sm:flex-row sm:items-center justify-between p-5 bg-gray-50/80 hover:bg-green-50/50 rounded-2xl border border-gray-100 hover:border-green-100 transition-colors gap-4">
                                <div class="flex items-start gap-3">
                                    <div class="p-2 bg-white rounded-lg shadow-sm">
                                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                            </path>
                                        </svg>
                                    </div>
                                    <div>
                                        <span class="font-bold text-gray-800 block">Template Tujuan Pembelajaran</span>
                                        <span class="text-xs text-gray-500">.xlsx format</span>
                                    </div>
                                </div>
                                <div class="flex gap-2 max-md:flex-col">
                                    <a href="https://docs.google.com/spreadsheets/d/1U9wOZNfNK8_r4rLm3unqBetpq-NDIP2L/edit?usp=sharing&ouid=113513031459863788859&rtpof=true&sd=true"
                                        target="_blank"
                                        class="px-5 py-2.5 bg-white border border-gray-200 text-sm font-semibold rounded-xl hover:border-green-500 hover:text-green-600 transition-colors text-center shadow-sm">Lihat</a>
                                    <a href="{{ asset('download/Template_Import_TP.xlsx') }}" download
                                        class="px-5 py-2.5 bg-green-600 hover:bg-white text-white border border-gray-200 text-sm font-semibold rounded-xl hover:border-green-500 hover:text-green-600 transition-colors text-center shadow-sm">Unduh</a>
                                </div>
                            </li>

                            {{-- Download Refleksi --}}
                            <li
                                class="flex flex-col sm:flex-row sm:items-center justify-between p-5 bg-gray-50/80 hover:bg-green-50/50 rounded-2xl border border-gray-100 hover:border-green-100 transition-colors gap-4">
                                <div class="flex items-start gap-3">
                                    <div class="p-2 bg-white rounded-lg shadow-sm">
                                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                            </path>
                                        </svg>
                                    </div>
                                    <div>
                                        <span class="font-bold text-gray-800 block">Template Refleksi Guru</span>
                                        <span class="text-xs text-gray-500">.xlsx format</span>
                                    </div>
                                </div>
                                <div class="flex gap-2 max-md:flex-col">
                                    <a href="https://docs.google.com/spreadsheets/d/1s_cFYzPKZwXS8WdH4sbjbsmf5sTy-86R/edit?usp=sharing&ouid=113513031459863788859&rtpof=true&sd=true"
                                        target="_blank"
                                        class="px-5 py-2.5 bg-white border border-gray-200 text-sm font-semibold rounded-xl hover:border-green-500 hover:text-green-600 transition-colors text-center shadow-sm">Lihat</a>
                                    <a href="{{ asset('download/Template_Import_Refleksi.xlsx') }}" download
                                        class="px-5 py-2.5 bg-green-600 hover:bg-white text-white border border-gray-200 text-sm font-semibold rounded-xl hover:border-green-500 hover:text-green-600 transition-colors text-center shadow-sm">Unduh</a>
                                </div>
                            </li>
                        </div>

                        {{-- Show more toggle button --}}
                        <div class="pt-4 text-center">
                            <button @click="expanded = !expanded"
                                class="inline-flex items-center justify-center gap-2 px-6 py-2 bg-green-50 text-green-700 hover:bg-green-100 rounded-full text-sm font-semibold transition-colors w-full sm:w-auto">
                                <span x-text="expanded ? 'Tampilkan Lebih Sedikit' : 'Lihat Semua Template (6)'"></span>
                                <svg x-cloak :class="{ 'rotate-180': expanded }"
                                    class="w-4 h-4 transition-transform duration-300" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                        </div>
                    </ul>
                </div>

                {{-- File Asesmen --}}
                <div class="bg-white rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100 p-8 hover:-translate-y-1 transition-transform duration-300"
                    data-aos="fade-up" data-aos-delay="300">
                    <div class="flex items-center gap-4 mb-8">
                        <div
                            class="w-14 h-14 bg-gradient-to-br from-purple-500 to-purple-600 shadow-lg shadow-purple-500/30 rounded-2xl flex items-center justify-center text-white">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">File & Dokumen Asesmen</h2>
                            <p class="text-sm text-gray-500 mt-1">Panduan dan Asesmen</p>
                        </div>
                    </div>

                    <ul class="space-y-4">
                        <li
                            class="flex flex-col sm:flex-row sm:items-center justify-between p-5 bg-gray-50/80 hover:bg-purple-50/50 rounded-2xl border border-gray-100 hover:border-purple-100 transition-colors gap-4">
                            <div class="flex items-start gap-3">
                                <div class="p-2 bg-white rounded-lg shadow-sm">
                                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <span class="font-bold text-gray-800 block">Panduan Pembelajaran dan Asesmen Edisi
                                        Revisi 2025</span>
                                    <span class="text-xs text-gray-500">PDF Document</span>
                                </div>
                            </div>
                            <a href="https://drive.google.com/file/d/1ROU5E1Ar7bXBOPxAN2KZX0AHeK-n6C3k/view?usp=sharing"
                                target="_blank"
                                class="px-5 py-2.5 bg-white border border-gray-200 text-sm font-semibold rounded-xl hover:border-purple-500 hover:text-purple-600 transition-colors text-center shadow-sm">Unduh</a>
                        </li>
                        <li
                            class="flex flex-col sm:flex-row sm:items-center justify-between p-5 bg-gray-50/80 hover:bg-purple-50/50 rounded-2xl border border-gray-100 hover:border-purple-100 transition-colors gap-4">
                            <div class="flex items-start gap-3">
                                <div class="p-2 bg-white rounded-lg shadow-sm">
                                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <span class="font-bold text-gray-800 block">Pembelajaran Mendalam</span>
                                    <span class="text-xs text-gray-500">PDF Document</span>
                                </div>
                            </div>
                            <a href="https://drive.google.com/file/d/1EjeO_lTz0rRRY7ipIRxdGtVWuQp-OK1S/view?usp=sharing"
                                target="_blank"
                                class="px-5 py-2.5 bg-white border border-gray-200 text-sm font-semibold rounded-xl hover:border-purple-500 hover:text-purple-600 transition-colors text-center shadow-sm">Unduh</a>
                        </li>

                        {{-- Link Kurikulum Kemendikdasmen --}}
                        <li
                            class="flex flex-col sm:flex-row sm:items-center justify-between p-5 bg-gradient-to-r from-primary-50 to-indigo-50 rounded-2xl border border-primary-100 gap-4 relative overflow-hidden">
                            {{-- Decor element --}}
                            <div class="absolute -right-4 -top-4 w-16 h-16 bg-white/40 rounded-full blur-xl"></div>

                            <div class="flex items-start gap-3 relative z-10">
                                <div class="p-2 bg-primary-600 rounded-lg shadow-sm text-white shadow-primary-500/30">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <span class="font-bold text-gray-800 block">Kurikulum Kemendikdasmen</span>
                                    <span class="text-xs text-primary-600 font-medium tracking-wide">WEBSITE
                                        RESMI</span>
                                </div>
                            </div>
                            <a href="https://kurikulum.kemendikdasmen.go.id/" target="_blank" rel="noopener noreferrer"
                                class="relative z-10 px-5 py-2.5 bg-white border border-primary-200 text-sm font-semibold text-primary-700 rounded-xl hover:bg-primary-600 hover:text-white hover:border-primary-600 transition-all shadow-sm flex items-center gap-2 text-center group">
                                Kunjungi Link
                                <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14">
                                    </path>
                                </svg>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    {{-- ==================== FOOTER ==================== --}}
    <footer class="bg-gray-900 text-white mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
            <div class="grid md:grid-cols-3 gap-8 lg:gap-12">
                {{-- Brand --}}
                <div class="md:col-span-1">
                    <div class="flex items-center gap-3 mb-4">
                        <img src="{{ asset('images/sipeka-logo.png') }}" alt="SIPEKA" class="h-10 w-10 rounded-xl">
                        <span class="text-xl font-extrabold">SIPEKA</span>
                    </div>
                    <p class="text-gray-400 text-sm leading-relaxed mb-4">
                        Solusi penilaian digital untuk Jenjang Pendidikan Dasar Indonesia.
                    </p>
                </div>

                {{-- Links --}}
                <div>
                    <h4 class="text-sm font-bold uppercase tracking-wider text-gray-400 mb-4">Navigasi</h4>
                    <ul class="space-y-3">
                        <li><a href="{{ url('/') }}#beranda"
                                class="text-sm text-gray-300 hover:text-white transition-colors">Beranda</a></li>
                        <li><a href="{{ url('/') }}#fitur"
                                class="text-sm text-gray-300 hover:text-white transition-colors">Fitur</a>
                        </li>
                        <li><a href="{{ url('/') }}#cara-kerja"
                                class="text-sm text-gray-300 hover:text-white transition-colors">Cara
                                Kerja</a></li>
                        <li><a href="{{ url('/') }}#tentang"
                                class="text-sm text-gray-300 hover:text-white transition-colors">Tentang</a></li>
                    </ul>
                </div>

                {{-- Info --}}
                <div>
                    <h4 class="text-sm font-bold uppercase tracking-wider text-gray-400 mb-4">Informasi</h4>
                    <ul class="space-y-3">
                        <li class="flex items-center gap-2 text-sm text-gray-300">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                </path>
                            </svg>
                            Penelitian Tesis
                        </li>
                        <li class="flex items-center gap-2 text-sm text-gray-300">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                </path>
                            </svg>
                            Kurikulum Merdeka
                        </li>
                        <li class="flex items-center gap-2 text-sm text-gray-300">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                </path>
                            </svg>
                            Berbasis Web
                        </li>
                    </ul>
                </div>
            </div>

            {{-- Divider --}}
            <div
                class="border-t border-gray-800 mt-10 pt-8 flex flex-col md:flex-row items-center justify-between gap-4">
                <p class="text-sm text-gray-500">&copy; {{ date('Y') }} SIPEKA. Dikembangkan sebagai penelitian
                    tesis.
                </p>
            </div>
        </div>
    </footer>

    {{-- ==================== SCRIPTS ==================== --}}
    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
    <script>
        // Init AOS
        AOS.init({
            once: true,
            duration: 800,
            easing: 'ease-out-cubic'
        });

        // Mobile Nav Toggle
        function toggleMobile() {
            const nav = document.getElementById('mobileNav');
            nav.classList.toggle('hidden');
        }

        // Close mobile nav on link click
        document.querySelectorAll('#mobileNav a').forEach(link => {
            link.addEventListener('click', () => {
                document.getElementById('mobileNav').classList.add('hidden');
            });
        });

        // Navbar shadow on scroll
        window.addEventListener('scroll', () => {
            const nav = document.getElementById('navbar');
            if (window.scrollY > 10) {
                nav.classList.add('shadow-md');
                nav.classList.remove('shadow-sm');
            } else {
                nav.classList.remove('shadow-md');
                nav.classList.add('shadow-sm');
            }
        });
    </script>
</body>

</html>