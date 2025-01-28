<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cobro Fácil - Gestión de Cobros</title>
    <link rel="icon" type="image/png" href="{{ asset('img/app/favicon.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200;400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Nunito', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="antialiased bg-gradient-to-br from-[#052935]/10 to-[#36829A]/10">
    <div class="min-h-screen flex flex-col items-center justify-center p-4">
        <!-- Hero Section -->
        <div class="text-center space-y-6 max-w-4xl mx-auto">
            <div class="animate-fade-in">
                <img src="{{ asset('img/app/logo.png') }}" alt="Logo de Cobro Fácil" class="mx-auto h-[250px] object-contain">
                <p class="mt-4 text-xl text-[#36829A] font-semibold">Sistema Integral de Gestión de Cobros</p>
                <p class="text-lg text-[#052935]/80">Control y seguimiento de pagos de préstamos</p>
            </div>

            <!-- Features Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-12">
                <!-- Feature 1 -->
                <div class="bg-white rounded-xl shadow-lg p-6 transform transition hover:-translate-y-1 hover:shadow-xl">
                    <div class="w-12 h-12 bg-[#36829A]/10 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6 text-[#052935]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-[#052935] mb-2">Gestión Centralizada</h3>
                    <p class="text-[#36829A]">Seguimiento de pagos diarios, semanales y mensuales con recordatorios automáticos</p>
                </div>

                <!-- Feature 2 -->
                <div class="bg-white rounded-xl shadow-lg p-6 transform transition hover:-translate-y-1 hover:shadow-xl">
                    <div class="w-12 h-12 bg-[#36829A]/10 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6 text-[#052935]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-[#052935] mb-2">Recordatorios Automáticos</h3>
                    <p class="text-[#36829A]">Notificaciones integradas por SMS y email para pagos pendientes</p>
                </div>

                <!-- Feature 3 -->
                <div class="bg-white rounded-xl shadow-lg p-6 transform transition hover:-translate-y-1 hover:shadow-xl">
                    <div class="w-12 h-12 bg-[#36829A]/10 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6 text-[#052935]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-[#052935] mb-2">Reportes Detallados</h3>
                    <p class="text-[#36829A]">Generación de informes financieros y análisis de morosidad en tiempo real</p>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="mt-16 text-center text-sm text-[#36829A]">
            <p class="flex items-center justify-center space-x-2">
                <span>Laravel v{{ Illuminate\Foundation\Application::VERSION }}</span>
                <span>•</span>
                <span>PHP v{{ PHP_VERSION }}</span>
                <span>•</span>
                <span>Seguridad SSL</span>
            </p>
        </footer>
    </div>

    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fadeIn 1s ease-out forwards;
        }
    </style>
</body>
</html>