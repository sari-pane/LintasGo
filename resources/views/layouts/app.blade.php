<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'LintasGo 3D — Sistem Pemesanan & Operasional Travel Antar Kota')</title>

    <!-- Google Fonts: DM Serif Display & Instrument Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=Instrument+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        display: ['"DM Serif Display"', 'Georgia', 'serif'],
                        sans: ['"Instrument Sans"', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                    },
                    colors: {
                        maroon: '#7A1B2D',
                        vred: '#A3243C',
                        cream: '#F9F4E4',
                        dusk: '#86A5BD',
                        mist: '#D4E4EC',
                        gold: '#C78F76',
                        ink: '#321B1E',
                    }
                }
            }
        }
    </script>

    <!-- Three.js CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>

    <style>
        :root {
            --color-maroon: #7A1B2D;
            --color-vred: #A3243C;
            --color-cream: #F9F4E4;
            --color-dusk: #86A5BD;
            --color-mist: #D4E4EC;
            --color-gold: #C78F76;
            --color-ink: #321B1E;
            --font-display: 'DM Serif Display', Georgia, serif;
            --font-sans: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif;
        }

        body {
            background-color: var(--color-cream);
            color: var(--color-ink);
            font-family: var(--font-sans);
            overflow-x: hidden;
        }

        .soft-rail {
            background: linear-gradient(180deg, #FDFBF4 0%, #F4ECDA 100%);
            box-shadow:
                10px 14px 32px rgba(50, 27, 30, 0.08),
                -6px -6px 20px rgba(255, 255, 255, 0.85),
                inset 0 0 0 1.5px rgba(255, 255, 255, 0.9);
        }

        .soft-card {
            background: rgba(253, 251, 244, 0.90);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            box-shadow:
                0 16px 36px -10px rgba(50, 27, 30, 0.12),
                inset 0 0 0 1.5px rgba(255, 255, 255, 0.85);
        }

        .organic-frame {
            border-radius: 2.5rem 3.2rem 2.6rem 3rem / 2.8rem 2.4rem 3.1rem 2.5rem;
            box-shadow:
                0 24px 50px -12px rgba(50, 27, 30, 0.16),
                0 0 0 8px rgba(255, 255, 255, 0.72),
                inset 0 0 24px rgba(50, 27, 30, 0.06);
        }

        .wavy-backdrop-container {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 490px;
            z-index: 0;
            overflow: hidden;
            pointer-events: none;
        }

        .wavy-bg-image {
            position: absolute;
            inset: 0;
            background-size: cover;
            background-position: center;
            transition: opacity 0.85s ease, transform 1.2s cubic-bezier(0.22, 1, 0.36, 1);
            transform: scale(1.03);
        }

        .wavy-bg-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(
                180deg,
                rgba(50, 27, 30, 0.58) 0%,
                rgba(122, 27, 45, 0.42) 45%,
                rgba(249, 244, 228, 0.86) 88%,
                var(--color-cream) 100%
            );
        }

        .wavy-svg-bottom {
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 100%;
            height: 115px;
            display: block;
        }

        .sidebar-btn {
            background: #FFFFFF;
            color: var(--color-ink);
            box-shadow: 0 6px 14px rgba(50, 27, 30, 0.06), inset 0 0 0 1px rgba(199, 143, 118, 0.25);
            transition: all 0.28s cubic-bezier(0.25, 1, 0.5, 1);
        }

        .sidebar-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(122, 27, 45, 0.14);
        }

        .sidebar-btn.active {
            background: linear-gradient(145deg, var(--color-maroon), var(--color-vred));
            color: var(--color-cream);
            box-shadow: 0 12px 24px -4px rgba(122, 27, 45, 0.42);
        }

        .ticket-perspective { perspective: 1400px; }
        .ticket-card-inner {
            position: relative;
            width: 100%;
            height: 100%;
            transition: transform 0.8s cubic-bezier(0.34, 1.56, 0.64, 1);
            transform-style: preserve-3d;
        }
        .ticket-card-inner.is-flipped { transform: rotateY(180deg); }
        .ticket-face {
            position: absolute;
            inset: 0;
            backface-visibility: hidden;
            -webkit-backface-visibility: hidden;
            border-radius: 2rem;
            overflow: hidden;
        }
        .ticket-back { transform: rotateY(180deg); }
        .holo-sheen {
            background: linear-gradient(
                120deg,
                transparent 20%,
                rgba(255, 255, 255, 0.35) 40%,
                rgba(199, 143, 118, 0.35) 50%,
                rgba(212, 228, 236, 0.35) 60%,
                transparent 80%
            );
            mix-blend-mode: overlay;
            pointer-events: none;
        }

        .book-scene { perspective: 2000px; }
        .book-wrapper {
            position: relative;
            transform-style: preserve-3d;
            transition: transform 0.9s cubic-bezier(0.25, 1, 0.5, 1);
        }
        .book-cover-front {
            transform-origin: left center;
            transform-style: preserve-3d;
            transition: transform 1.1s cubic-bezier(0.645, 0.045, 0.355, 1);
        }
        .book-wrapper.is-open .book-cover-front { transform: rotateY(-165deg); }
        .book-cover-front-outer,
        .book-cover-front-inner {
            position: absolute;
            inset: 0;
            backface-visibility: hidden;
            -webkit-backface-visibility: hidden;
        }
        .book-cover-front-inner { transform: rotateY(180deg); }

        .leather-maroon {
            background-color: var(--color-maroon);
            background-image:
                radial-gradient(rgba(249, 244, 228, 0.07) 1px, transparent 0),
                linear-gradient(145deg, #8B2035 0%, #58111F 100%);
            background-size: 14px 14px, 100% 100%;
            box-shadow:
                inset 6px 0 16px rgba(50, 27, 30, 0.5),
                inset -2px 0 6px rgba(249, 244, 228, 0.2),
                14px 20px 36px rgba(50, 27, 30, 0.28);
        }

        @keyframes pageFlipForward {
            0% { transform: rotateY(0deg); opacity: 1; }
            50% { transform: rotateY(-90deg); opacity: 0.65; }
            100% { transform: rotateY(-180deg); opacity: 0; }
        }
        .page-turning {
            transform-origin: left center;
            animation: pageFlipForward 0.52s ease-in-out forwards;
        }

        @keyframes stampImpact {
            0% { transform: scale(2.5) rotate(-18deg); opacity: 0; }
            65% { transform: scale(0.94) rotate(-11deg); opacity: 1; }
            100% { transform: scale(1) rotate(-11deg); opacity: 0.94; }
        }
        .stamp-badge {
            animation: stampImpact 0.35s cubic-bezier(0.22, 1, 0.36, 1) forwards;
            border-style: double;
            border-width: 4px;
            letter-spacing: 0.14em;
        }

        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: rgba(249, 244, 228, 0.5); }
        ::-webkit-scrollbar-thumb { background: rgba(122, 27, 45, 0.28); border-radius: 9999px; }

        @media print {
            .no-print, .wavy-backdrop-container, aside, header { display: none !important; }
            body { background: #fff !important; }
        }
    </style>

    @stack('styles')
</head>
<body class="@yield('body-class', 'min-h-screen flex flex-col relative selection:bg-maroon selection:text-cream')">

    <!-- WAVY ORGANIC HERO BACKDROP -->
    <div class="wavy-backdrop-container" aria-hidden="true">
        <div id="wavy-bg-photo" class="wavy-bg-image" style="background-image: url('@yield('backdrop-photo', 'https://images.unsplash.com/photo-1588668214407-6ea9a6d8c272?auto=format&fit=crop&w=1800&q=80')');"></div>
        <div id="wavy-bg-tint" class="wavy-bg-overlay"></div>
        <svg class="wavy-svg-bottom" viewBox="0 0 1440 160" preserveAspectRatio="none">
            <path d="M0,45 C240,135 480,-10 760,75 C1040,160 1240,35 1440,85 L1440,160 L0,160 Z" fill="rgba(212, 228, 236, 0.55)"></path>
            <path d="M0,78 C320,15 580,145 890,68 C1150,5 1310,115 1440,62 L1440,160 L0,160 Z" fill="rgba(199, 143, 118, 0.25)"></path>
            <path d="M0,96 C260,158 540,28 840,104 C1110,172 1290,58 1440,108 L1440,160 L0,160 Z" fill="#F9F4E4"></path>
        </svg>
    </div>

    <!-- TOAST CONTAINER -->
    <div id="toast-container" class="fixed bottom-5 right-5 z-50 flex flex-col gap-2 pointer-events-none no-print"></div>

    @guest
        <!-- MODAL LOGIN LINTASGO (MUNCUL SAAT GUEST KLIK FITUR TERKUNCI) -->
        <div id="login-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-ink/45 backdrop-blur-md transition-opacity">
            <div class="soft-card rounded-[2rem] max-w-md w-full p-7 border-2 border-white relative shadow-2xl">
                <button type="button" onclick="closeLoginModal()" class="absolute top-5 right-5 w-9 h-9 rounded-full bg-cream hover:bg-mist text-ink flex items-center justify-center font-bold transition cursor-pointer">✕</button>

                <div class="w-12 h-12 rounded-2xl bg-maroon text-cream font-display text-2xl flex items-center justify-center mb-4 shadow-md">L</div>
                <h3 class="font-display text-2xl text-ink">Masuk ke LintasGo</h3>
                <p class="text-xs text-ink/70 mt-1 leading-relaxed">
                    Pengunjung tanpa akun hanya dapat melihat daftar <strong>Rute Umum</strong>. Silakan masuk terlebih dahulu untuk mencari jadwal berdasarkan tanggal, memilih kursi 3D, atau mengakses E-Tiket.
                </p>

                <form method="POST" action="{{ route('login.attempt') }}" class="space-y-3 mt-5">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-ink mb-1">Email Akun</label>
                        <input type="email" name="email" required placeholder="nama@email.com" class="w-full px-4 py-2.5 rounded-2xl bg-white border border-gold/40 text-sm text-ink focus:outline-none focus:border-maroon">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-ink mb-1">Kata Sandi</label>
                        <input type="password" name="password" required placeholder="••••••••" class="w-full px-4 py-2.5 rounded-2xl bg-white border border-gold/40 text-sm text-ink focus:outline-none focus:border-maroon">
                    </div>
                    <div class="flex items-center justify-between text-xs text-ink/70 pt-1">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="remember" class="rounded border-gold/40 text-maroon focus:ring-maroon">
                            <span>Ingat saya</span>
                        </label>
                        <a href="{{ route('register') }}" class="font-semibold text-maroon hover:underline">Daftar Akun Baru</a>
                    </div>
                    <button type="submit" class="w-full mt-2 py-3 px-4 rounded-2xl bg-maroon hover:bg-vred text-cream font-semibold text-xs sm:text-sm shadow-lg transition cursor-pointer">
                        Masuk Sekarang
                    </button>
                </form>

                <div class="mt-4 pt-3 border-t border-gold/25 flex items-center justify-between text-xs text-ink/70">
                    <span>Petugas Operasional?</span>
                    <a href="{{ route('admin.login') }}" class="font-bold text-ink hover:text-maroon transition">Portal Login Admin →</a>
                </div>
            </div>
        </div>
    @endguest

    @yield('content')

    <script>
        /* =========================================================
           WEB AUDIO API — MUSIK LATAR AMBIENT & EFEK SUARA
           ========================================================= */
        let audioCtx = null;
        let isMusicOn = false;
        let musicTimer = null;

        function ensureAudio() {
            if (!audioCtx) {
                const AudioContextClass = window.AudioContext || window.webkitAudioContext;
                if (AudioContextClass) audioCtx = new AudioContextClass();
            }
            if (audioCtx && audioCtx.state === 'suspended') audioCtx.resume();
        }

        function startAmbientMusic() {
            ensureAudio();
            if (!audioCtx || isMusicOn) return;
            isMusicOn = true;
            const statusEl = document.getElementById('audio-status-text');
            if (statusEl) statusEl.textContent = 'Musik ON';

            const melodyChords = [
                [261.63, 329.63, 392.00],
                [293.66, 349.23, 440.00],
                [220.00, 261.63, 329.63],
                [196.00, 246.94, 392.00]
            ];
            let idx = 0;

            const playStep = () => {
                if (!isMusicOn || !audioCtx) return;
                const now = audioCtx.currentTime;
                const notes = melodyChords[idx % melodyChords.length];
                notes.forEach((freq, i) => {
                    const osc = audioCtx.createOscillator();
                    const gain = audioCtx.createGain();
                    osc.type = 'sine';
                    osc.frequency.setValueAtTime(freq, now + i * 0.14);
                    gain.gain.setValueAtTime(0.0001, now + i * 0.14);
                    gain.gain.exponentialRampToValueAtTime(0.025, now + i * 0.14 + 0.35);
                    gain.gain.exponentialRampToValueAtTime(0.0001, now + 2.9);
                    osc.connect(gain);
                    gain.connect(audioCtx.destination);
                    osc.start(now + i * 0.14);
                    osc.stop(now + 3.0);
                });
                idx++;
            };

            playStep();
            musicTimer = setInterval(playStep, 2800);
        }

        function stopAmbientMusic() {
            isMusicOn = false;
            if (musicTimer) clearInterval(musicTimer);
            const statusEl = document.getElementById('audio-status-text');
            if (statusEl) statusEl.textContent = 'Musik OFF';
        }

        function toggleAudio() {
            if (isMusicOn) {
                stopAmbientMusic();
                showToast('Musik latar dijeda', 'info');
            } else {
                startAmbientMusic();
                showToast('Musik latar LintasGo diputar', 'success');
            }
        }

        function playTone(freqStart, freqEnd, duration = 0.25) {
            ensureAudio();
            if (!audioCtx) return;
            const now = audioCtx.currentTime;
            const osc = audioCtx.createOscillator();
            const gain = audioCtx.createGain();
            osc.type = 'sine';
            osc.frequency.setValueAtTime(freqStart, now);
            osc.frequency.exponentialRampToValueAtTime(freqEnd, now + duration);
            gain.gain.setValueAtTime(0.08, now);
            gain.gain.exponentialRampToValueAtTime(0.001, now + duration);
            osc.connect(gain);
            gain.connect(audioCtx.destination);
            osc.start(now);
            osc.stop(now + duration + 0.02);
        }

        function openLoginModal() {
            playTone(440, 560, 0.15);
            const modal = document.getElementById('login-modal');
            if (modal) modal.classList.remove('hidden');
            else window.location.href = '{{ route('login') }}';
        }

        function closeLoginModal() {
            const modal = document.getElementById('login-modal');
            if (modal) modal.classList.add('hidden');
        }

        function showToast(msg, type = 'info') {
            const box = document.getElementById('toast-container');
            if (!box) return;
            const el = document.createElement('div');
            const styles = {
                success: 'bg-maroon text-cream border-gold',
                error: 'bg-vred text-cream border-cream',
                info: 'bg-ink text-cream border-gold'
            };
            el.className = `px-4 py-3 rounded-2xl border ${styles[type] || styles.info} text-xs font-semibold shadow-xl transition-all duration-300 translate-y-2 opacity-0 pointer-events-auto`;
            el.textContent = msg;
            box.appendChild(el);
            requestAnimationFrame(() => el.classList.remove('translate-y-2', 'opacity-0'));
            setTimeout(() => {
                el.classList.add('opacity-0', 'translate-y-2');
                setTimeout(() => el.remove(), 300);
            }, 3500);
        }

        document.addEventListener('DOMContentLoaded', () => {
            @if (session('success'))
                showToast(@json(session('success')), 'success');
            @endif
            @if (session('error'))
                showToast(@json(session('error')), 'error');
            @endif
            @if ($errors->any())
                showToast(@json($errors->first()), 'error');
            @endif
        });
    </script>

    @stack('scripts')
</body>
</html>
