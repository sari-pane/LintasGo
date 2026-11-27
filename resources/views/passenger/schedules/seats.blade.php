@extends('layouts.passenger', [
    'title' => 'Pilih Kursi Kabin 3D — LintasGo',
    'backdropPhoto' => 'https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?auto=format&fit=crop&w=1800&q=80',
])

@section('header-greeting', $schedule->route->origin . ' → ' . $schedule->route->destination)
@section('header-subtext', $schedule->departure_time->locale('id')->isoFormat('dddd, D MMMM Y • HH:mm') . ' WIB — ' . $schedule->vehicle->brand)

@section('main-content')
    <div class="flex items-center justify-between flex-wrap gap-2">
        <a href="{{ route('jadwal.index', ['date' => $schedule->departure_time->toDateString()]) }}" class="soft-card px-4 py-2 rounded-full text-xs font-bold text-ink hover:text-maroon transition">
            ← Kembali ke Daftar Jadwal
        </a>
        <span class="soft-card px-4 py-2 rounded-full text-xs font-bold text-maroon">
            Maksimal 3 kursi per transaksi
        </span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-start">
        <!-- AREA KABIN 3D INTERAKTIF (8 KOLOM) -->
        <div class="lg:col-span-8 flex flex-col gap-4">
            <div class="relative h-[430px] sm:h-[490px] organic-frame overflow-hidden bg-gradient-to-b from-mist/80 to-cream">
                <div id="cabin-stage-holder" class="absolute inset-0 w-full h-full"></div>

                <!-- Kontrol Sudut Pandang Kamera -->
                <div class="absolute top-4 left-4 right-4 z-10 flex flex-wrap items-center justify-between gap-2 pointer-events-none">
                    <div class="soft-card p-1.5 rounded-full flex items-center gap-1 pointer-events-auto">
                        <button type="button" onclick="setCabinCam('top')" id="cam-btn-top" class="px-3.5 py-1.5 rounded-full text-xs font-bold bg-maroon text-cream shadow transition cursor-pointer">
                            ⬇ Tampilan Atas (Denah)
                        </button>
                        <button type="button" onclick="setCabinCam('front')" id="cam-btn-front" class="px-3.5 py-1.5 rounded-full text-xs font-bold text-ink hover:bg-white transition cursor-pointer">
                            👁 Tampilan Depan Kabin
                        </button>
                        <button type="button" onclick="setCabinCam('side')" id="cam-btn-side" class="px-3.5 py-1.5 rounded-full text-xs font-bold text-ink hover:bg-white transition cursor-pointer">
                            🚪 Pintu Geser
                        </button>
                    </div>

                    <div class="soft-card px-3.5 py-1.5 rounded-full text-[11px] font-bold text-maroon pointer-events-auto">
                        {{ $schedule->departure_time->format('H:i') }} WIB • {{ $schedule->vehicle->brand }}
                    </div>
                </div>

                <!-- Legenda Status Kursi -->
                <div class="absolute bottom-4 left-4 right-4 z-10 flex flex-wrap items-center justify-between gap-2 pointer-events-none">
                    <div class="soft-card px-4 py-2 rounded-full flex items-center gap-4 text-xs font-semibold pointer-events-auto">
                        <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-[#68967B] inline-block"></span> Tersedia</span>
                        <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-maroon inline-block"></span> Dipilih</span>
                        <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-[#9E968E] inline-block"></span> Terisi</span>
                    </div>
                    <span class="soft-card px-3.5 py-1.5 rounded-full text-[11px] font-medium text-ink/80 pointer-events-auto">
                        Klik kursi 3D atau pill di bawah untuk memilih
                    </span>
                </div>
            </div>

            <!-- Denah Pill Kursi Sinkron dengan 3D (Akses Cepat) -->
            <div class="soft-card rounded-[2rem] p-5 border-2 border-white/85">
                <span class="text-xs font-bold text-ink block mb-3">Daftar Kursi Armada ({{ $schedule->vehicle->plate_number }}):</span>
                <div class="flex flex-wrap gap-2">
                    @foreach ($seats as $seat)
                        <button
                            type="button"
                            id="seat-pill-{{ $seat['id'] }}"
                            onclick="toggleSeatSelection({{ $seat['id'] }})"
                            @disabled(!$seat['is_available'])
                            class="px-4 py-2 rounded-2xl text-xs font-bold transition {{ $seat['is_available'] ? 'bg-[#68967B]/15 text-ink border border-[#68967B]/40 hover:border-maroon cursor-pointer' : 'bg-ink/10 text-ink/40 cursor-not-allowed line-through' }}"
                        >
                            Kursi {{ $seat['seat_number'] }}
                        </button>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- FORM DATA PENUMPANG & PEMESANAN (4 KOLOM) -->
        <form method="POST" action="{{ route('pemesanan.store') }}" id="booking-form" class="lg:col-span-4 soft-card rounded-[2.2rem] p-6 flex flex-col gap-4 border-2 border-white/85">
            @csrf
            <input type="hidden" name="schedule_id" value="{{ $schedule->id }}">

            <div class="border-b border-gold/25 pb-3 flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold uppercase text-gold">Langkah 2 • Data Penumpang</span>
                    <h3 class="font-display text-lg text-ink">{{ $schedule->departure_time->format('d M Y • H:i') }} WIB</h3>
                </div>
                <div class="text-right">
                    <span class="text-[10px] text-ink/60 block">Kursi Dipilih</span>
                    <span id="cabin-selected-list" class="font-bold text-sm text-maroon">Belum ada</span>
                </div>
            </div>

            <div id="passengers-fields-container" class="space-y-3 text-xs">
                <div class="p-4 rounded-2xl bg-cream/80 border border-gold/30 text-center text-ink/70">
                    Klik kursi berwarna hijau pada kabin 3D untuk menambahkan data penumpang (maks. 3 kursi).
                </div>
            </div>

            <div class="text-xs">
                <label class="block font-semibold text-ink mb-1">Catatan Penjemputan / Perjalanan (Opsional)</label>
                <textarea name="notes" rows="2" placeholder="Contoh: Jemput di depan stasiun / butuh bagasi ekstra" class="w-full px-3.5 py-2 rounded-2xl bg-white border border-gold/35 font-medium text-ink focus:outline-none focus:border-maroon">{{ old('notes') }}</textarea>
            </div>

            <div class="p-4 rounded-2xl bg-cream border border-gold/30 flex items-center justify-between">
                <div>
                    <span class="text-[11px] text-ink/70 block">Total Tagihan (Dihitung Server)</span>
                    <span id="cabin-price-detail" class="text-[11px] font-semibold text-ink/60">0 kursi × Rp {{ number_format($schedule->price, 0, ',', '.') }}</span>
                </div>
                <span id="cabin-total-amount" class="font-display text-2xl text-maroon">Rp 0</span>
            </div>

            <button type="submit" id="btn-submit-booking" disabled class="w-full py-3.5 rounded-2xl bg-maroon hover:bg-vred disabled:opacity-45 disabled:cursor-not-allowed text-cream font-semibold text-sm shadow-lg shadow-maroon/25 transition cursor-pointer">
                Buat Pemesanan & Lanjut Bayar →
            </button>
        </form>
    </div>
@endsection

@push('scripts')
    @include('partials.three-vehicle-script')
    <script>
        const dbSeats = @json($seats);
        const ticketPrice = {{ (float) $schedule->price }};
        const defaultName = @json(auth()->user()->name);
        const defaultPhone = @json(auth()->user()->phone ?? '');
        let selectedSeatIds = [];
        let passengerDrafts = {};
        let cabinStage = null;

        document.addEventListener('DOMContentLoaded', () => {
            cabinStage = initLintasGoThreeStage({
                containerId: 'cabin-stage-holder',
                brandName: @json($schedule->vehicle->brand),
                seatsData: dbSeats,
                interactiveSeats: true,
                initialDoorOpen: true,
                transparentRoof: true,
                initialPreset: 'top',
                onSeatToggle: (seatData) => {
                    if (seatData.seatId) toggleSeatSelection(seatData.seatId);
                }
            });
        });

        function setCabinCam(preset) {
            if (!cabinStage) return;
            cabinStage.setCameraPreset(preset);
            playTone(520, 680, 0.15);

            ['top', 'front', 'side'].forEach(p => {
                const btn = document.getElementById(`cam-btn-${p}`);
                if (btn) {
                    btn.className = p === preset
                        ? 'px-3.5 py-1.5 rounded-full text-xs font-bold bg-maroon text-cream shadow transition cursor-pointer'
                        : 'px-3.5 py-1.5 rounded-full text-xs font-bold text-ink hover:bg-white transition cursor-pointer';
                }
            });
        }

        function toggleSeatSelection(seatId) {
            const seat = dbSeats.find(s => Number(s.id) === Number(seatId));
            if (!seat) return;

            if (!seat.is_available) {
                playTone(260, 180, 0.15);
                showToast(`Kursi ${seat.seat_number} sudah dipesan penumpang lain.`, 'error');
                return;
            }

            // Preserve typed passenger inputs before re-rendering
            document.querySelectorAll('[data-seat-input]').forEach(input => {
                const sId = input.getAttribute('data-seat-input');
                const field = input.getAttribute('data-field');
                if (!passengerDrafts[sId]) passengerDrafts[sId] = {};
                passengerDrafts[sId][field] = input.value;
            });

            if (selectedSeatIds.includes(seat.id)) {
                selectedSeatIds = selectedSeatIds.filter(id => id !== seat.id);
                playTone(460, 340, 0.12);
            } else {
                if (selectedSeatIds.length >= 3) {
                    playTone(260, 180, 0.15);
                    showToast('Maksimal hanya dapat memesan 3 kursi dalam 1 transaksi.', 'error');
                    return;
                }
                selectedSeatIds.push(seat.id);
                playTone(540, 760, 0.14);
            }

            if (cabinStage) cabinStage.setSelectedIds(selectedSeatIds);
            renderSeatPillsAndForm();
        }

        function renderSeatPillsAndForm() {
            dbSeats.forEach(s => {
                const pill = document.getElementById(`seat-pill-${s.id}`);
                if (!pill || !s.is_available) return;
                if (selectedSeatIds.includes(s.id)) {
                    pill.className = 'px-4 py-2 rounded-2xl text-xs font-bold transition bg-maroon text-cream shadow cursor-pointer';
                } else {
                    pill.className = 'px-4 py-2 rounded-2xl text-xs font-bold transition bg-[#68967B]/15 text-ink border border-[#68967B]/40 hover:border-maroon cursor-pointer';
                }
            });

            const selectedSeats = selectedSeatIds.map(id => dbSeats.find(s => s.id === id)).filter(Boolean);
            document.getElementById('cabin-selected-list').textContent = selectedSeats.length
                ? selectedSeats.map(s => s.seat_number).join(', ')
                : 'Belum ada';

            const total = selectedSeats.length * ticketPrice;
            document.getElementById('cabin-price-detail').textContent = `${selectedSeats.length} kursi × Rp ${ticketPrice.toLocaleString('id-ID')}`;
            document.getElementById('cabin-total-amount').textContent = `Rp ${total.toLocaleString('id-ID')}`;

            const container = document.getElementById('passengers-fields-container');
            const submitBtn = document.getElementById('btn-submit-booking');

            if (selectedSeats.length === 0) {
                submitBtn.disabled = true;
                container.innerHTML = `
                    <div class="p-4 rounded-2xl bg-cream/80 border border-gold/30 text-center text-ink/70">
                        Klik kursi berwarna hijau pada kabin 3D untuk menambahkan data penumpang (maks. 3 kursi).
                    </div>
                `;
                return;
            }

            submitBtn.disabled = false;
            container.innerHTML = selectedSeats.map((seat, idx) => {
                const draft = passengerDrafts[seat.id] || {};
                const pName = draft.name !== undefined ? draft.name : (idx === 0 ? defaultName : '');
                const pPhone = draft.phone !== undefined ? draft.phone : (idx === 0 ? defaultPhone : '');

                return `
                    <div class="p-3.5 rounded-2xl bg-white border border-gold/35 space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="font-bold text-maroon">Penumpang ${idx + 1} • Kursi ${seat.seat_number}</span>
                            <button type="button" onclick="toggleSeatSelection(${seat.id})" class="text-[11px] text-vred hover:underline cursor-pointer">Hapus</button>
                        </div>
                        <input type="hidden" name="passengers[${idx}][seat_id]" value="${seat.id}">
                        <div>
                            <label class="block text-[11px] font-semibold text-ink/80 mb-0.5">Nama Lengkap</label>
                            <input type="text" required name="passengers[${idx}][name]" data-seat-input="${seat.id}" data-field="name" value="${escapeHtml(pName)}" placeholder="Nama penumpang" class="w-full px-3 py-2 rounded-xl bg-cream/40 border border-gold/35 text-xs text-ink focus:outline-none focus:border-maroon">
                        </div>
                        <div>
                            <label class="block text-[11px] font-semibold text-ink/80 mb-0.5">Nomor Telepon / WA</label>
                            <input type="tel" required name="passengers[${idx}][phone]" data-seat-input="${seat.id}" data-field="phone" value="${escapeHtml(pPhone)}" placeholder="0812xxxxxxxx" class="w-full px-3 py-2 rounded-xl bg-cream/40 border border-gold/35 text-xs text-ink focus:outline-none focus:border-maroon">
                        </div>
                    </div>
                `;
            }).join('');
        }

        function escapeHtml(str) {
            return String(str || '').replace(/[&<>"']/g, m => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[m]));
        }
    </script>
@endpush
