<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cobro Facil — API</title>
    <meta name="theme-color" content="#3fb568" id="themeColorMeta">
    <link rel="icon" type="image/x-icon" href="{{ asset('img/app/favicon.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <script>
        (function() { // aplica preferencia guardada (por defecto claro)
            try {
                const s = localStorage.getItem('theme');
                if (s === 'dark') document.documentElement.classList.add('dark');
            } catch {}
        })();
    </script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Nunito', 'ui-sans-serif', 'system-ui']
                    },
                    boxShadow: {
                        soft: '0 10px 30px rgba(0,0,0,.08)',
                        softlg: '0 20px 40px rgba(0,0,0,.12)'
                    }
                }
            }
        }
    </script>

    <style>
        :root {
            --ion-color-primary: #3fb568;
            /* verde CobroFacil */
            --ion-color-primary-rgb: 63, 181, 104;
            --ion-color-primary-shade: #318b51;
            --ion-color-secondary: #0163aa;
            --ion-color-tertiary: #6030ff;
            --ion-color-success: #2dd55b;
            --ion-color-warning: #ffc409;
            --ion-color-danger: #c5000f;
            --ion-color-light: #f6f8fc;
            --ion-color-medium: #5f5f5f;
            --ion-color-dark: #2f2f2f;

            /* Claro por defecto */
            --app-background: #f9fafb;
            --card-background: #ffffff;
            --text-primary: #1f2937;
            --text-secondary: #6b7280;
            --border-color: #e5e7eb;
            --ion-background-color: #f9fafb;
            --ion-text-color: #1f2937;
            --ion-item-background: #ffffff;
            --ion-card-background: #ffffff;
            --ion-header-background: #ffffff;
            --ui-background: #ffffff;
        }

        .dark {
            --app-background: #0f1115;
            --card-background: #151922;
            --text-primary: #f9fafb;
            --text-secondary: #cbd5e1;
            --border-color: #273042;
            --ion-background-color: #0f1115;
            --ion-text-color: #f9fafb;
            --ion-item-background: #151922;
            --ion-card-background: #151922;
            --ion-header-background: #151922;
            --ui-background: #151922;
        }

        .text-primary {
            color: var(--ion-color-primary)
        }

        .bg-primary {
            background-color: var(--ion-color-primary)
        }

        .bg-primary\/10 {
            background-color: rgba(var(--ion-color-primary-rgb), .10)
        }

        .from-primary {
            --tw-gradient-from: var(--ion-color-primary);
            --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, rgba(63, 181, 104, 0))
        }

        .to-primary-shade {
            --tw-gradient-to: var(--ion-color-primary-shade)
        }

        .bg-ui {
            background-color: var(--ion-header-background)
        }

        .ui-surface {
            background: var(--card-background)
        }

        .ui-border {
            border-color: var(--border-color)
        }

        .ui-text {
            color: var(--text-primary)
        }

        .ui-subtext {
            color: var(--text-secondary)
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(10px)
            }

            to {
                opacity: 1;
                transform: translateY(0)
            }
        }

        .fade-up {
            animation: fadeUp .6s ease-out both
        }

        @keyframes pop {
            0% {
                transform: scale(.98)
            }

            100% {
                transform: scale(1)
            }
        }

        .pop {
            animation: pop .25s ease-out both
        }
    </style>
</head>

<body class="antialiased min-h-screen"
    style="background:
        radial-gradient(1000px 420px at 10% -10%, rgba(63,181,104,.08), transparent 60%),
        radial-gradient(800px 400px at 110% 10%, rgba(96,48,255,.07), transparent 60%),
        var(--ion-background-color);">

    <div class="min-h-screen flex flex-col">
        <!-- Header -->
        <header
            class="bg-ui/80 backdrop-blur supports-[backdrop-filter]:backdrop-blur sticky top-0 z-10 border-b ui-border">
            <div class="max-w-6xl mx-auto px-5 py-4 flex items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('img/app/logo.png') }}" alt="Cobro Facil" class="h-9 w-auto object-contain">
                    <span class="font-extrabold tracking-tight text-lg ui-text">Cobro <span
                            class="text-primary">Facil</span> — API</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="hidden sm:inline text-xs ui-subtext">
                        Laravel {{ Illuminate\Foundation\Application::VERSION }} · PHP {{ PHP_VERSION }}
                    </span>
                    <!-- Toggle tema -->
                    <button id="modeToggle"
                        class="inline-flex items-center gap-2 text-sm px-3 py-1.5 rounded-full border ui-border ui-text hover:text-primary hover:border-transparent hover:bg-primary/10 transition"
                        aria-label="Cambiar tema" aria-pressed="false">
                        <!-- Sol (claro) -->
                        <svg class="w-4 h-4 dark:hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 3v2m0 14v2m9-9h-2M5 12H3m15.36 6.36l-1.42-1.42M7.05 7.05L5.64 5.64m12.02 0l-1.41 1.41M7.05 16.95l-1.41 1.41M12 8a4 4 0 100 8 4 4 0 000-8z" />
                        </svg>
                        <!-- Luna (oscuro) -->
                        <svg class="w-4 h-4 hidden dark:inline" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 12.79A9 9 0 1111.21 3A7 7 0 0021 12.79z" />
                        </svg>
                    </button>
                </div>
            </div>
        </header>

        <!-- Hero -->
        <main class="flex-1">
            <section class="max-w-6xl mx-auto px-5 pt-14 pb-10 text-center">
                <div
                    class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-primary/10 text-primary text-xs font-semibold mb-4 fade-up">
                    Gestión de cartera y cobranzas · API
                </div>

                <h1 class="text-4xl sm:text-5xl font-extrabold tracking-tight ui-text fade-up">
                    Administra <span class="text-primary">créditos</span> de forma fácil y segura
                </h1>
                <p class="mt-4 text-lg ui-subtext max-w-2xl mx-auto fade-up" style="animation-delay:.06s">
                    Controla clientes, créditos, cuotas y abonos; rastrea la mora en tiempo real y automatiza
                    recordatorios y notificaciones multicanal.
                </p>
            </section>

            <!-- Features orientadas a crédito -->
            <section class="max-w-6xl mx-auto px-5 pb-16">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

                    <!-- Cartera y créditos -->
                    <article class="ui-surface rounded-2xl border ui-border p-6 shadow-soft pop">
                        <div class="w-11 h-11 rounded-xl bg-primary/10 flex items-center justify-center mb-4">
                            <!-- Libro/Cartera -->
                            <svg class="w-6 h-6 text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                    d="M4 5h12a3 3 0 013 3v11H7a3 3 0 01-3-3V5zM7 5v11" />
                            </svg>
                        </div>
                        <h3 class="font-bold text-lg ui-text mb-1">Cartera y créditos</h3>
                        <p class="ui-subtext">CRUD de clientes, créditos, cuotas, abonos y estados. Flujos de creación y
                            refinanciación.</p>
                    </article>

                    <!-- Seguimiento en tiempo real -->
                    <article class="ui-surface rounded-2xl border ui-border p-6 shadow-soft pop">
                        <div class="w-11 h-11 rounded-xl bg-primary/10 flex items-center justify-center mb-4">
                            <!-- Radar / tiempo real -->
                            <svg class="w-6 h-6 text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <circle cx="12" cy="12" r="9" stroke-width="2"></circle>
                                <path d="M12 12l6-6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                </path>
                            </svg>
                        </div>
                        <h3 class="font-bold text-lg ui-text mb-1">Seguimiento en tiempo real</h3>
                        <p class="ui-subtext">Actualizaciones instantáneas de pagos, saldos y mora usando colas y
                            eventos.</p>
                    </article>

                    <!-- Notificaciones multicanal -->
                    <article class="ui-surface rounded-2xl border ui-border p-6 shadow-soft pop">
                        <div class="w-11 h-11 rounded-xl bg-primary/10 flex items-center justify-center mb-4">
                            <!-- Campana -->
                            <svg class="w-6 h-6 text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 17h5l-1.5-1.5A7 7 0 005 11v3l-2 3h12" />
                            </svg>
                        </div>
                        <h3 class="font-bold text-lg ui-text mb-1">Notificaciones multicanal</h3>
                        <p class="ui-subtext">Email, SMS y WhatsApp para recordatorios de pago, vencimientos y promesas
                            de pago.</p>
                    </article>

                    <!-- Recordatorios automáticos -->
                    <article class="ui-surface rounded-2xl border ui-border p-6 shadow-soft pop">
                        <div class="w-11 h-11 rounded-xl bg-primary/10 flex items-center justify-center mb-4">
                            <!-- Reloj -->
                            <svg class="w-6 h-6 text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <circle cx="12" cy="12" r="9" stroke-width="2"></circle>
                                <path d="M12 7v5l3 2" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                </path>
                            </svg>
                        </div>
                        <h3 class="font-bold text-lg ui-text mb-1">Recordatorios automáticos</h3>
                        <p class="ui-subtext">Jobs programados que envían avisos antes y después del vencimiento.</p>
                    </article>


                    <!-- Roles y permisos -->
                    <article class="ui-surface rounded-2xl border ui-border p-6 shadow-soft pop">
                        <div class="w-11 h-11 rounded-xl bg-primary/10 flex items-center justify-center mb-4">
                            <!-- Usuarios -->
                            <svg class="w-6 h-6 text-primary" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor">
                                <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                    d="M16 11c1.657 0 3-1.79 3-4s-1.343-4-3-4-3 1.79-3 4 1.343 4 3 4zM6 13c-2.21 0-4 1.79-4 4v3h8v-3c0-2.21-1.79-4-4-4zM18 13c2.21 0 4 1.79 4 4v3h-6" />
                            </svg>
                        </div>
                        <h3 class="font-bold text-lg ui-text mb-1">Roles y permisos</h3>
                        <p class="ui-subtext">Separación por perfiles: administrador, cobrador, auditor y API clients.
                        </p>
                    </article>

                    <!-- Reportes y analítica -->
                    <article class="ui-surface rounded-2xl border ui-border p-6 shadow-soft pop">
                        <div class="w-11 h-11 rounded-xl bg-primary/10 flex items-center justify-center mb-4">
                            <!-- Gráfica -->
                            <svg class="w-6 h-6 text-primary" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor">
                                <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 3v18h18M7 15l4-4 3 3 5-7" />
                            </svg>
                        </div>
                        <h3 class="font-bold text-lg ui-text mb-1">Reportes y analítica</h3>
                        <p class="ui-subtext">KPIs de recuperación, aging de cartera, proyección de cobros y
                            exportables.</p>
                    </article>

                    <!-- Caja y liquidación -->
                    <article class="ui-surface rounded-2xl border ui-border p-6 shadow-soft pop">
                        <div class="w-11 h-11 rounded-xl bg-primary/10 flex items-center justify-center mb-4">
                            <!-- Recibo -->
                            <svg class="w-6 h-6 text-primary" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor">
                                <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                    d="M6 3h12v18l-3-2-3 2-3-2-3 2V3zM8 7h8M8 11h8M8 15h5" />
                            </svg>
                        </div>
                        <h3 class="font-bold text-lg ui-text mb-1">Caja</h3>
                        <p class="ui-subtext">Registro de abonos, gasto y liquidación con tus empleados.
                        </p>
                    </article>

                    <!-- Integración API segura -->
                    <article class="ui-surface rounded-2xl border ui-border p-6 shadow-soft pop">
                        <div class="w-11 h-11 rounded-xl bg-primary/10 flex items-center justify-center mb-4">
                            <!-- Candado/Escudo -->
                            <svg class="w-6 h-6 text-primary" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor">
                                <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 1l8 4v6c0 6-8 10-8 10S4 17 4 11V5l8-4zm0 7a3 3 0 00-3 3v2h6v-2a3 3 0 00-3-3z" />
                            </svg>
                        </div>
                        <h3 class="font-bold text-lg ui-text mb-1">API segura</h3>
                        <p class="ui-subtext">Tokens, rate limiting y logs de auditoría. Webhooks para eventos clave.
                        </p>
                    </article>

                </div>
            </section>
        </main>

        <!-- Footer -->
        <footer class="border-t ui-border">
            <div class="max-w-6xl mx-auto px-5 py-8 text-center">
                <p class="text-sm ui-subtext">© {{ date('Y') }} Cobro Facil — Créditos organizados dinero asegurado.</p>
            </div>
        </footer>
    </div>

    <script>
        const metaTheme = document.getElementById('themeColorMeta');

        function syncThemeColor() {
            metaTheme.setAttribute('content', document.documentElement.classList.contains('dark') ? '#151922' : '#3fb568');
        }
        syncThemeColor();

        const modeToggle = document.getElementById('modeToggle');
        if (modeToggle) {
            modeToggle.setAttribute('aria-pressed', document.documentElement.classList.contains('dark') ? 'true' : 'false');
            modeToggle.addEventListener('click', () => {
                const el = document.documentElement;
                const willDark = !el.classList.contains('dark');
                el.classList.toggle('dark', willDark);
                localStorage.setItem('theme', willDark ? 'dark' : 'light');
                modeToggle.setAttribute('aria-pressed', willDark ? 'true' : 'false');
                syncThemeColor();
            });
        }
    </script>
</body>

</html>
