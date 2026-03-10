<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIPEKA - Sistem Penilaian Proyek dan Kinerja</title>
    <link rel="icon" type="image/png" href="{{ asset('images/sipeka-logo.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/sipeka-logo.png') }}">

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

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    {{-- AOS Animation --}}
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

        .blob {
            border-radius: 50%;
            filter: blur(60px);
            opacity: 0.5;
            position: absolute;
            animation: blobFloat 8s ease-in-out infinite;
        }

        @keyframes blobFloat {

            0%,
            100% {
                transform: translate(0, 0) scale(1);
            }

            33% {
                transform: translate(20px, -30px) scale(1.05);
            }

            66% {
                transform: translate(-15px, 15px) scale(0.95);
            }
        }

        .glass {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.4);
        }

        .feature-card {
            transition: all 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(37, 99, 235, 0.12);
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

        .nav-link:hover::after {
            width: 100%;
        }

        .stat-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.9), rgba(255, 255, 255, 0.6));
            backdrop-filter: blur(12px);
        }

        .step-number {
            background: linear-gradient(135deg, #2563eb, #7c3aed);
        }

        .cta-gradient {
            background: linear-gradient(135deg, #1e3a8a 0%, #5b21b6 50%, #86198f 100%);
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-up {
            animation: fadeInUp 0.8s ease-out forwards;
        }

        .counter {
            font-variant-numeric: tabular-nums;
        }
    </style>
</head>

<body class="bg-white text-gray-800 overflow-x-hidden">

    {{-- ==================== NAVBAR ==================== --}}
    <nav class="fixed top-0 left-0 right-0 z-50 glass shadow-sm" id="navbar">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 md:h-20">
                {{-- Logo --}}
                <a href="#" class="flex items-center gap-3 group">
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
                    <a href="#beranda"
                        class="nav-link text-sm font-medium text-gray-700 hover:text-primary-600 transition-colors">Beranda</a>
                    <a href="#fitur"
                        class="nav-link text-sm font-medium text-gray-700 hover:text-primary-600 transition-colors">Fitur</a>
                    <a href="#cara-kerja"
                        class="nav-link text-sm font-medium text-gray-700 hover:text-primary-600 transition-colors">Cara
                        Kerja</a>
                    <a href="#tentang"
                        class="nav-link text-sm font-medium text-gray-700 hover:text-primary-600 transition-colors">Tentang</a>
                    <a href="{{ url('/unduhan') }}"
                        class="nav-link text-sm font-medium text-gray-700 hover:text-primary-600 transition-colors">Unduhan</a>
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
                    <a href="#beranda"
                        class="px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-primary-50 hover:text-primary-600 rounded-lg transition-colors">Beranda</a>
                    <a href="#fitur"
                        class="px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-primary-50 hover:text-primary-600 rounded-lg transition-colors">Fitur</a>
                    <a href="#cara-kerja"
                        class="px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-primary-50 hover:text-primary-600 rounded-lg transition-colors">Cara
                        Kerja</a>
                    <a href="#tentang"
                        class="px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-primary-50 hover:text-primary-600 rounded-lg transition-colors">Tentang</a>
                    <a href="{{ url('/unduhan') }}"
                        class="px-4 py-2.5 text-sm font-bold text-primary-600 bg-primary-50 rounded-lg transition-colors">Unduhan</a>
                    <a href="{{ url('/admin/login') }}"
                        class="mx-4 mt-2 text-center px-6 py-2.5 bg-gradient-to-r from-primary-600 to-primary-700 text-white rounded-full text-sm font-semibold shadow-lg">Masuk</a>
                </div>
            </div>
        </div>
    </nav>

    {{-- ==================== HERO SECTION ==================== --}}
    <section id="beranda" class="relative min-h-screen flex items-center hero-gradient pt-20 overflow-hidden">
        {{-- Animated Blobs --}}
        <div class="blob w-72 h-72 bg-blue-300 top-20 -left-20" style="animation-delay: 0s;"></div>
        <div class="blob w-96 h-96 bg-purple-300 top-40 right-0" style="animation-delay: 2s;"></div>
        <div class="blob w-64 h-64 bg-pink-200 bottom-20 left-1/3" style="animation-delay: 4s;"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">
                {{-- Left Content --}}
                <div class="text-center lg:text-left" data-aos="fade-right" data-aos-duration="1000">
                    <div
                        class="inline-flex items-center gap-2 px-4 py-2 bg-white/60 backdrop-blur-sm border border-primary-200 rounded-full text-sm font-medium text-primary-700 mb-6">
                        <span class="relative flex h-2.5 w-2.5">
                            <span
                                class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-primary-500"></span>
                        </span>
                        Solusi E-Rapor Modern
                    </div>

                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold leading-tight mb-6">
                        Kelola <span class="gradient-text">Penilaian Siswa</span> dengan Mudah & Efisien
                    </h1>

                    <p class="text-base sm:text-lg text-gray-600 mb-8 max-w-xl mx-auto lg:mx-0 leading-relaxed">
                        SIPEKA adalah sistem penilaian berbasis proyek dan kinerja yang dirancang khusus untuk membantu
                        guru dalam mengelola, menganalisis, dan melaporkan perkembangan siswa secara komprehensif.
                    </p>

                    <div class="flex flex-col sm:flex-row items-center gap-4 justify-center lg:justify-start">
                        <a href="{{ url('/admin/login') }}"
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-8 py-3.5 bg-gradient-to-r from-primary-600 to-primary-700 text-white rounded-full text-base font-semibold shadow-xl shadow-primary-500/30 hover:shadow-primary-500/50 hover:from-primary-700 hover:to-primary-800 transition-all duration-300 group">
                            Mulai Sekarang
                            <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                            </svg>
                        </a>
                        <a href="#fitur"
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-8 py-3.5 bg-white/80 backdrop-blur text-gray-700 rounded-full text-base font-semibold border border-gray-200 hover:border-primary-300 hover:text-primary-600 transition-all duration-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Pelajari Fitur
                        </a>
                    </div>
                </div>

                {{-- Right Illustration --}}
                <div class="relative" data-aos="fade-left" data-aos-duration="1000" data-aos-delay="200">
                    <div
                        class="relative bg-white/40 backdrop-blur-sm rounded-3xl border border-white/60 p-6 sm:p-8 shadow-2xl shadow-primary-500/10">
                        {{-- Mock Dashboard --}}
                        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 mb-4">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="flex gap-1.5">
                                    <div class="w-3 h-3 rounded-full bg-red-400"></div>
                                    <div class="w-3 h-3 rounded-full bg-yellow-400"></div>
                                    <div class="w-3 h-3 rounded-full bg-green-400"></div>
                                </div>
                                <div class="flex-1 h-6 bg-gray-100 rounded-full"></div>
                            </div>
                            <div class="flex items-center gap-3 mb-3">
                                <img src="{{ asset('images/sipeka-logo.png') }}" alt=""
                                    class="w-8 h-8 rounded-lg">
                                <div>
                                    <div class="text-sm font-bold text-gray-800">Dashboard SIPEKA</div>
                                    <div class="text-xs text-gray-400">Panel Guru</div>
                                </div>
                            </div>
                            {{-- Stats Row --}}
                            <div class="grid grid-cols-3 gap-3 mb-4">
                                <div class="bg-blue-50 rounded-xl p-3 text-center">
                                    <div class="text-lg font-bold text-blue-600">28</div>
                                    <div class="text-[10px] text-blue-500">Siswa</div>
                                </div>
                                <div class="bg-purple-50 rounded-xl p-3 text-center">
                                    <div class="text-lg font-bold text-purple-600">85</div>
                                    <div class="text-[10px] text-purple-500">Rata-rata</div>
                                </div>
                                <div class="bg-green-50 rounded-xl p-3 text-center">
                                    <div class="text-lg font-bold text-green-600">96%</div>
                                    <div class="text-[10px] text-green-500">Progres</div>
                                </div>
                            </div>
                            {{-- Chart Placeholder --}}
                            <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-xl p-4">
                                <div class="flex items-end gap-1.5 h-20 justify-center">
                                    <div class="w-6 bg-blue-400 rounded-t-md" style="height: 45%"></div>
                                    <div class="w-6 bg-blue-500 rounded-t-md" style="height: 65%"></div>
                                    <div class="w-6 bg-purple-400 rounded-t-md" style="height: 80%"></div>
                                    <div class="w-6 bg-purple-500 rounded-t-md" style="height: 55%"></div>
                                    <div class="w-6 bg-blue-400 rounded-t-md" style="height: 70%"></div>
                                    <div class="w-6 bg-blue-500 rounded-t-md" style="height: 90%"></div>
                                    <div class="w-6 bg-purple-400 rounded-t-md" style="height: 60%"></div>
                                    <div class="w-6 bg-purple-500 rounded-t-md" style="height: 75%"></div>
                                </div>
                            </div>
                        </div>

                        {{-- Floating Badge --}}
                        <div class="absolute -top-4 -right-4 bg-white rounded-2xl shadow-lg p-3 flex items-center gap-2 border border-gray-100 animate-bounce"
                            style="animation-duration: 3s;">
                            <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <div class="text-xs font-bold text-gray-800">Rapor Selesai!</div>
                                <div class="text-[10px] text-gray-500">28 siswa</div>
                            </div>
                        </div>

                        {{-- Floating Badge Bottom --}}
                        <div class="absolute -bottom-3 -left-3 bg-white rounded-2xl shadow-lg p-3 flex items-center gap-2 border border-gray-100"
                            data-aos="fade-up" data-aos-delay="600">
                            <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <div class="text-xs font-bold text-gray-800">Analisis Kelas</div>
                                <div class="text-[10px] text-gray-500">Realtime</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Wave Divider --}}
        <div class="absolute bottom-0 left-0 right-0">
            <svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M0 120L60 105C120 90 240 60 360 52.5C480 45 600 60 720 67.5C840 75 960 75 1080 67.5C1200 60 1320 45 1380 37.5L1440 30V120H1380C1320 120 1200 120 1080 120C960 120 840 120 720 120C600 120 480 120 360 120C240 120 120 120 60 120H0Z"
                    fill="white" />
            </svg>
        </div>
    </section>

    {{-- ==================== STATS SECTION ==================== --}}
    <section class="py-12 bg-white relative -mt-1">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6" data-aos="fade-up" data-aos-duration="800">
                <div class="stat-card rounded-2xl p-5 md:p-6 text-center border border-gray-100 shadow-sm">
                    <div class="w-12 h-12 mx-auto mb-3 bg-blue-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
                            </path>
                        </svg>
                    </div>
                    <div class="text-2xl md:text-3xl font-extrabold text-gray-800 counter" data-target="500">0+</div>
                    <div class="text-xs md:text-sm text-gray-500 mt-1">Siswa Terdaftar</div>
                </div>
                <div class="stat-card rounded-2xl p-5 md:p-6 text-center border border-gray-100 shadow-sm">
                    <div class="w-12 h-12 mx-auto mb-3 bg-purple-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                            </path>
                        </svg>
                    </div>
                    <div class="text-2xl md:text-3xl font-extrabold text-gray-800 counter" data-target="1000">0+</div>
                    <div class="text-xs md:text-sm text-gray-500 mt-1">Penilaian Dibuat</div>
                </div>
                <div class="stat-card rounded-2xl p-5 md:p-6 text-center border border-gray-100 shadow-sm">
                    <div class="w-12 h-12 mx-auto mb-3 bg-green-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                    </div>
                    <div class="text-2xl md:text-3xl font-extrabold text-gray-800 counter" data-target="200">0+</div>
                    <div class="text-xs md:text-sm text-gray-500 mt-1">Rapor Dicetak</div>
                </div>
                <div class="stat-card rounded-2xl p-5 md:p-6 text-center border border-gray-100 shadow-sm">
                    <div class="w-12 h-12 mx-auto mb-3 bg-orange-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z">
                            </path>
                        </svg>
                    </div>
                    <div class="text-2xl md:text-3xl font-extrabold text-gray-800">99%</div>
                    <div class="text-xs md:text-sm text-gray-500 mt-1">Guru Puas</div>
                </div>
            </div>
        </div>
    </section>

    {{-- ==================== FITUR SECTION ==================== --}}
    <section id="fitur" class="py-20 lg:py-28 bg-gray-50/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16" data-aos="fade-up">
                <div
                    class="inline-flex items-center gap-2 px-4 py-2 bg-primary-50 rounded-full text-sm font-semibold text-primary-600 mb-4">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                    Fitur Unggulan
                </div>
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-gray-900 mb-4">
                    Semua yang Anda <span class="gradient-text">Butuhkan</span>
                </h2>
                <p class="text-gray-600 text-base sm:text-lg">Fitur lengkap untuk mendukung proses penilaian berbasis
                    proyek dan kinerja</p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
                {{-- Feature 1 --}}
                <div class="feature-card bg-white rounded-2xl p-7 border border-gray-100 shadow-sm" data-aos="fade-up"
                    data-aos-delay="0">
                    <div
                        class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center mb-5 shadow-lg shadow-blue-500/25">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Input Penilaian</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">Masukkan skor per indikator untuk setiap siswa
                        dengan antarmuka yang mudah dan intuitif.</p>
                </div>

                {{-- Feature 2 --}}
                <div class="feature-card bg-white rounded-2xl p-7 border border-gray-100 shadow-sm" data-aos="fade-up"
                    data-aos-delay="100">
                    <div
                        class="w-14 h-14 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl flex items-center justify-center mb-5 shadow-lg shadow-purple-500/25">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Analisis Kelas</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">Visualisasi data lengkap: grafik distribusi
                        predikat, peta indikator, dan perbandingan aspek kompetensi.</p>
                </div>

                {{-- Feature 3 --}}
                <div class="feature-card bg-white rounded-2xl p-7 border border-gray-100 shadow-sm" data-aos="fade-up"
                    data-aos-delay="200">
                    <div
                        class="w-14 h-14 bg-gradient-to-br from-green-500 to-green-600 rounded-2xl flex items-center justify-center mb-5 shadow-lg shadow-green-500/25">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Cetak Rapor PDF</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">Generate rapor siswa dalam format PDF yang siap
                        cetak dengan desain profesional dan detail lengkap.</p>
                </div>

                {{-- Feature 4 --}}
                <div class="feature-card bg-white rounded-2xl p-7 border border-gray-100 shadow-sm" data-aos="fade-up"
                    data-aos-delay="300">
                    <div
                        class="w-14 h-14 bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl flex items-center justify-center mb-5 shadow-lg shadow-orange-500/25">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Ekspor Excel</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">Ekspor data rekap nilai ke format Excel untuk
                        keperluan dokumentasi dan pelaporan administratif.</p>
                </div>

                {{-- Feature 5 --}}
                <div class="feature-card bg-white rounded-2xl p-7 border border-gray-100 shadow-sm" data-aos="fade-up"
                    data-aos-delay="400">
                    <div
                        class="w-14 h-14 bg-gradient-to-br from-pink-500 to-pink-600 rounded-2xl flex items-center justify-center mb-5 shadow-lg shadow-pink-500/25">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Import Data</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">Import data siswa dan kurikulum dari file Excel
                        untuk mempercepat proses input data awal.</p>
                </div>

                {{-- Feature 6 --}}
                <div class="feature-card bg-white rounded-2xl p-7 border border-gray-100 shadow-sm" data-aos="fade-up"
                    data-aos-delay="500">
                    <div
                        class="w-14 h-14 bg-gradient-to-br from-teal-500 to-teal-600 rounded-2xl flex items-center justify-center mb-5 shadow-lg shadow-teal-500/25">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Dashboard Interaktif</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">Dashboard lengkap dengan radar chart, bar chart,
                        statistik progres, dan ranking siswa secara realtime.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ==================== CARA KERJA SECTION ==================== --}}
    <section id="cara-kerja" class="py-20 lg:py-28 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16" data-aos="fade-up">
                <div
                    class="inline-flex items-center gap-2 px-4 py-2 bg-purple-50 rounded-full text-sm font-semibold text-purple-600 mb-4">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 9l3 3m0 0l-3 3m3-3H8m13 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Cara Kerja
                </div>
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-gray-900 mb-4">
                    Mudah dalam <span class="gradient-text">4 Langkah</span>
                </h2>
                <p class="text-gray-600 text-base sm:text-lg">Proses sederhana dari awal hingga cetak rapor</p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                {{-- Step 1 --}}
                <div class="relative text-center group" data-aos="fade-up" data-aos-delay="0">
                    <div
                        class="step-number w-16 h-16 rounded-2xl flex items-center justify-center text-white text-2xl font-extrabold mx-auto mb-5 shadow-lg group-hover:scale-110 transition-transform duration-300">
                        1</div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Setting Kurikulum</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">Input mata pelajaran, capaian pembelajaran, tujuan
                        pembelajaran, aspek, dan indikator penilaian.</p>
                    {{-- Connector Line (Desktop) --}}
                    <div
                        class="hidden lg:block absolute top-8 left-[calc(50%+40px)] w-[calc(100%-80px)] h-0.5 bg-gradient-to-r from-primary-300 to-purple-300">
                    </div>
                </div>

                {{-- Step 2 --}}
                <div class="relative text-center group" data-aos="fade-up" data-aos-delay="150">
                    <div
                        class="step-number w-16 h-16 rounded-2xl flex items-center justify-center text-white text-2xl font-extrabold mx-auto mb-5 shadow-lg group-hover:scale-110 transition-transform duration-300">
                        2</div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Input Data Siswa</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">Daftarkan data siswa secara manual atau import
                        dari
                        file Excel dengan cepat.</p>
                    <div
                        class="hidden lg:block absolute top-8 left-[calc(50%+40px)] w-[calc(100%-80px)] h-0.5 bg-gradient-to-r from-purple-300 to-pink-300">
                    </div>
                </div>

                {{-- Step 3 --}}
                <div class="relative text-center group" data-aos="fade-up" data-aos-delay="300">
                    <div
                        class="step-number w-16 h-16 rounded-2xl flex items-center justify-center text-white text-2xl font-extrabold mx-auto mb-5 shadow-lg group-hover:scale-110 transition-transform duration-300">
                        3</div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Penilaian</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">Input skor untuk setiap indikator per siswa.
                        Predikat dan analisis dihitung otomatis.</p>
                    <div
                        class="hidden lg:block absolute top-8 left-[calc(50%+40px)] w-[calc(100%-80px)] h-0.5 bg-gradient-to-r from-pink-300 to-violet-300">
                    </div>
                </div>

                {{-- Step 4 --}}
                <div class="relative text-center group" data-aos="fade-up" data-aos-delay="450">
                    <div
                        class="step-number w-16 h-16 rounded-2xl flex items-center justify-center text-white text-2xl font-extrabold mx-auto mb-5 shadow-lg group-hover:scale-110 transition-transform duration-300">
                        4</div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Cetak Rapor</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">Generate dan cetak rapor siswa dalam format PDF
                        yang profesional dan siap distribusi.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ==================== TENTANG SECTION ==================== --}}
    <section id="tentang" class="py-20 lg:py-28 bg-gray-50/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-12 lg:gap-20 items-center">
                {{-- Left Image/Illustration --}}
                <div class="relative" data-aos="fade-up" data-aos-duration="1000">
                    <div class="bg-gradient-to-br from-primary-100 via-purple-100 to-pink-100 rounded-3xl p-8 md:p-12">
                        <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
                            <div class="flex items-center gap-4 mb-6">
                                <img src="{{ asset('images/sipeka-logo.png') }}" alt="SIPEKA"
                                    class="w-14 h-14 rounded-2xl shadow-md">
                                <div>
                                    <h3 class="text-xl font-extrabold gradient-text">SIPEKA</h3>
                                    <p class="text-xs text-gray-500">Sistem Penilaian Proyek & Kinerja</p>
                                </div>
                            </div>
                            <div class="space-y-3">
                                <div class="flex items-center gap-3 p-3 bg-blue-50 rounded-xl">
                                    <svg class="w-5 h-5 text-blue-600 shrink-0" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span class="text-sm text-gray-700">Penilaian Berbasis Proyek</span>
                                </div>
                                <div class="flex items-center gap-3 p-3 bg-purple-50 rounded-xl">
                                    <svg class="w-5 h-5 text-purple-600 shrink-0" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span class="text-sm text-gray-700">Penilaian Berbasis Kinerja</span>
                                </div>
                                <div class="flex items-center gap-3 p-3 bg-green-50 rounded-xl">
                                    <svg class="w-5 h-5 text-green-600 shrink-0" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span class="text-sm text-gray-700">Analisis Otomatis & Komprehensif</span>
                                </div>
                                <div class="flex items-center gap-3 p-3 bg-orange-50 rounded-xl">
                                    <svg class="w-5 h-5 text-orange-600 shrink-0" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span class="text-sm text-gray-700">Sesuai Kurikulum Merdeka</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Right Content --}}
                <div data-aos="fade-up" data-aos-duration="1000">
                    <div
                        class="inline-flex items-center gap-2 px-4 py-2 bg-green-50 rounded-full text-sm font-semibold text-green-600 mb-4">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Tentang SIPEKA
                    </div>
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900 mb-6">
                        Dibangun untuk <span class="gradient-text">Guru</span> Indonesia
                    </h2>
                    <div class="space-y-4 text-gray-600 leading-relaxed">
                        <p>
                            <strong class="text-gray-800">SIPEKA</strong> (Sistem Penilaian Proyek dan Kinerja) adalah
                            aplikasi E-Rapor yang dikembangkan sebagai bagian dari penelitian tesis untuk membantu para
                            guru dalam mengelola penilaian siswa secara digital.
                        </p>
                        <p>
                            Sistem ini menerapkan pendekatan penilaian berbasis <strong
                                class="text-gray-800">Proyek</strong> dan <strong
                                class="text-gray-800">Kinerja</strong>
                            yang selaras dengan Kurikulum Merdeka, dengan dukungan analisis data yang komprehensif untuk
                            memahami perkembangan setiap siswa.
                        </p>
                        <p>
                            Dengan fitur seperti analisis kelas, dashboard interaktif, pencetakan rapor PDF, dan ekspor
                            data, SIPEKA membantu guru fokus pada hal yang paling penting: <strong
                                class="text-gray-800">mendidik anak</strong>.
                        </p>
                    </div>

                    <div class="mt-8 flex flex-col sm:flex-row gap-4">
                        <a href="{{ url('/admin/login') }}"
                            class="inline-flex items-center justify-center gap-2 px-8 py-3.5 bg-gradient-to-r from-primary-600 to-primary-700 text-white rounded-full text-sm font-semibold shadow-xl shadow-primary-500/30 hover:shadow-primary-500/50 transition-all duration-300">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1">
                                </path>
                            </svg>
                            Masuk ke Aplikasi
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ==================== CTA SECTION ==================== --}}
    <section class="py-20 lg:py-28 cta-gradient relative overflow-hidden">
        {{-- Decorative Elements --}}
        <div class="absolute top-0 left-0 w-72 h-72 bg-white/5 rounded-full -translate-x-1/2 -translate-y-1/2"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-white/5 rounded-full translate-x-1/3 translate-y-1/3"></div>

        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10" data-aos="zoom-in"
            data-aos-duration="800">
            <div
                class="inline-flex items-center gap-2 px-4 py-2 bg-white/10 backdrop-blur-sm border border-white/20 rounded-full text-sm font-medium text-white/90 mb-6">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z">
                    </path>
                </svg>
                Mulai Gunakan SIPEKA
            </div>
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-white mb-6">
                Siap Tingkatkan Kualitas<br>Penilaian Siswa Anda?
            </h2>
            <p class="text-lg text-white/80 mb-10 max-w-2xl mx-auto">
                Bergabunglah dan rasakan kemudahan mengelola penilaian berbasis proyek dan kinerja dengan SIPEKA.
            </p>
            <a href="{{ url('/admin/login') }}"
                class="inline-flex items-center gap-3 px-10 py-4 bg-white text-primary-700 rounded-full text-lg font-bold shadow-2xl hover:shadow-3xl hover:scale-105 transition-all duration-300 group">
                Masuk Sekarang
                <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 8l4 4m0 0l-4 4m4-4H3">
                    </path>
                </svg>
            </a>
        </div>
    </section>

    {{-- ==================== FOOTER ==================== --}}
    <footer class="bg-gray-900 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
            <div class="grid md:grid-cols-3 gap-8 lg:gap-12">
                {{-- Brand --}}
                <div class="md:col-span-1">
                    <div class="flex items-center gap-3 mb-4">
                        <img src="{{ asset('images/sipeka-logo.png') }}" alt="SIPEKA"
                            class="h-10 w-10 rounded-xl">
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
                        <li><a href="#beranda"
                                class="text-sm text-gray-300 hover:text-white transition-colors">Beranda</a></li>
                        <li><a href="#fitur"
                                class="text-sm text-gray-300 hover:text-white transition-colors">Fitur</a>
                        </li>
                        <li><a href="#cara-kerja"
                                class="text-sm text-gray-300 hover:text-white transition-colors">Cara
                                Kerja</a></li>
                        <li><a href="#tentang"
                                class="text-sm text-gray-300 hover:text-white transition-colors">Tentang</a></li>
                    </ul>
                </div>

                {{-- Info --}}
                <div>
                    <h4 class="text-sm font-bold uppercase tracking-wider text-gray-400 mb-4">Informasi</h4>
                    <ul class="space-y-3">
                        <li class="flex items-center gap-2 text-sm text-gray-300">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                </path>
                            </svg>
                            Penelitian Tesis
                        </li>
                        <li class="flex items-center gap-2 text-sm text-gray-300">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                </path>
                            </svg>
                            Kurikulum Merdeka
                        </li>
                        <li class="flex items-center gap-2 text-sm text-gray-300">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
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
            easing: 'ease-out-cubic',
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

        // Counter animation
        function animateCounters() {
            const counters = document.querySelectorAll('.counter');
            counters.forEach(counter => {
                const target = parseInt(counter.getAttribute('data-target'));
                if (!target) return;
                const duration = 2000;
                const step = target / (duration / 16);
                let current = 0;

                const update = () => {
                    current += step;
                    if (current < target) {
                        counter.textContent = Math.floor(current) + '+';
                        requestAnimationFrame(update);
                    } else {
                        counter.textContent = target + '+';
                    }
                };
                update();
            });
        }

        // Trigger counter animation when stats section is visible
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    animateCounters();
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.3
        });

        const statsSection = document.querySelector('.counter');
        if (statsSection) {
            observer.observe(statsSection.closest('section'));
        }
    </script>
</body>

</html>
