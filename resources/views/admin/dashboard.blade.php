@extends('layouts.admin')

@section('page-title', 'Buku Pencatatan Hardcover Operasional')
@section('page-subtitle', 'Buka sampul buku untuk melihat manifest penumpang, laporan pendapatan, dan verifikasi pembayaran dengan stempel 3D.')

@section('admin-content')
    <!-- RINGKASAN METRIK -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3">
        <div class="soft-card rounded-2xl p-4 border border-white">
            <span class="text-[10px] font-bold uppercase text-ink/60 block">Pesanan Hari Ini</span>
            <span class="font-display text-2xl text-ink">{{ $metrics['today_bookings_count'] }}</span>
        </div>
        <div class="soft-card rounded-2xl p-4 border border-white">
            <span class="text-[10px] font-bold uppercase text-vred block">Antrean Verifikasi</span>
            <span class="font-display text-2xl text-vred">{{ $metrics['pending_verifications_count'] }}</span>
        </div>
        <div class="soft-card rounded-2xl p-4 border border-white">
            <span class="text-[10px] font-bold uppercase text-ink/60 block">Terkonfirmasi</span>
            <span class="font-display text-2xl text-maroon">{{ $metrics['confirmed_bookings_count'] }}</span>
        </div>
        <div class="soft-card rounded-2xl p-4 border border-white">
            <span class="text-[10px] font-bold uppercase text-ink/60 block">Jadwal Hari Ini</span>
            <span class="font-display text-2xl text-ink">{{ $metrics['today_schedules_count'] }}</span>
        </div>
        <div class="soft-card rounded-2xl p-4 border border-white">
            <span class="text-[10px] font-bold uppercase text-ink/60 block">Check-In Hari Ini</span>
            <span class="font-display text-2xl text-ink">{{ $metrics['today_checked_in_tickets'] }}</span>
        </div>
        <div class="soft-card rounded-2xl p-4 border border-white">
            <span class="text-[10px] font-bold uppercase text-gold block">Total Pemasukan</span>
            <span class="font-display text-lg text-maroon">Rp {{ number_format($metrics['total_revenue'], 0, ',', '.') }}</span>
        </div>
    </div>

    <!-- BARIS KONTROL BUKU HARDCOVER -->
    <div class="soft-card rounded-[2rem] p-4 sm:p-5 flex flex-wrap items-center justify-between gap-4 border-2 border-white/85">
        <div>
            <h2 class="font-display text-xl sm:text-2xl text-ink">Buku Hardcover Arsip Operasional</h2>
            <p class="text-xs text-ink/70">Klik sampul buku maroon atau tombol di kanan untuk membuka lembaran manifest & rekap keuangan.</p>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <button type="button" onclick="toggleHardcoverBook()" class="px-5 py-2.5 rounded-full bg-maroon hover:bg-vred text-cream font-semibold text-xs shadow-md transition cursor-pointer">
                <span id="book-btn-label">Buka Buku Hardcover</span>
            </button>
            <div id="book-tabs-bar" class="hidden items-center gap-1.5 bg-white p-1 rounded-full border border-gold/30">
                <button type="button" onclick="switchBookPage(1)" id="bpage-btn-1" class="px-3.5 py-1 rounded-full text-xs font-bold bg-maroon text-cream cursor-pointer">Hal. 1: Manifest</button>
                <button type="button" onclick="switchBookPage(2)" id="bpage-btn-2" class="px-3.5 py-1 rounded-full text-xs font-bold text-ink cursor-pointer">Hal. 2: Laporan</button>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        <!-- BUKU HARDCOVER 3D (8 KOLOM) -->
        <div class="lg:col-span-8 book-scene flex justify-center">
            <div id="admin-hardcover-book" class="book-wrapper w-full h-[460px] rounded-r-[2rem] rounded-l-xl relative">

                <!-- SAMPUL KULIT MAROON HARDCOVER -->
                <div onclick="toggleHardcoverBook()" class="book-cover-front absolute inset-0 z-30 cursor-pointer rounded-r-[2rem] rounded-l-xl">
                    <div class="book-cover-front-outer leather-maroon rounded-r-[2rem] rounded-l-xl border-2 border-gold/60 p-7 flex flex-col justify-between">
                        <div class="ml-4 border-2 border-gold/50 rounded-3xl h-full p-6 flex flex-col items-center justify-between text-center">
                            <span class="text-[11px] uppercase tracking-[0.25em] text-gold font-bold">Arsip Resmi LintasGo</span>
                            <div class="my-auto">
                                <div class="w-16 h-16 mx-auto rounded-2xl bg-cream text-maroon font-display text-3xl flex items-center justify-center shadow-lg mb-3">L</div>
                                <h3 class="font-display text-2xl sm:text-3xl text-cream">Buku Pencatatan Travel</h3>
                                <p class="text-xs text-cream/80 mt-2 max-w-xs mx-auto">Manifest Penumpang, Rekapitulasi Keuangan & Verifikasi Pembayaran</p>
                            </div>
                            <span class="px-4 py-1.5 rounded-full bg-cream/15 text-cream text-xs font-semibold">Klik Sampul untuk Membuka →</span>
                        </div>
                    </div>

                    <div class="book-cover-front-inner leather-maroon rounded-l-[2rem] rounded-r-xl p-6 flex flex-col justify-between text-cream">
                        <div class="border border-gold/40 rounded-2xl p-5 h-full flex flex-col justify-between">
                            <div>
                                <span class="text-[10px] uppercase tracking-widest text-gold font-bold">Log Operasional</span>
                                <h4 class="font-display text-xl mt-1">Kantor Pusat LintasGo</h4>
                                <p class="text-xs text-cream/75 mt-1">Admin Bertugas: {{ auth()->user()->name }}</p>
                                <p class="text-xs text-cream/75 mt-1">Tanggal: {{ now('Asia/Jakarta')->locale('id')->isoFormat('D MMMM Y') }}</p>
                            </div>
                            <button type="button" onclick="event.stopPropagation(); toggleHardcoverBook();" class="w-full py-2.5 rounded-2xl bg-cream text-maroon font-bold text-xs cursor-pointer">
                                ← Tutup Sampul Buku
                            </button>
                        </div>
                    </div>
                </div>

                <!-- LEMBAR KERTAS CREAM DI DALAM BUKU -->
                <div id="admin-book-paper" class="w-full h-full bg-[#FDFBF4] text-ink rounded-r-[2rem] rounded-l-xl shadow-xl border-r-8 border-b-8 border-gold/30 p-6 flex flex-col justify-between relative overflow-hidden">

                    <!-- HALAMAN 1: MANIFEST PENUMPANG -->
                    <div id="book-content-1" class="h-full flex flex-col justify-between overflow-hidden">
                        <div class="overflow-y-auto pr-1">
                            <div class="flex items-center justify-between border-b-2 border-maroon/20 pb-2.5 mb-3">
                                <h4 class="font-display text-lg text-maroon">Halaman 01 • Manifest Kursi Aktif</h4>
                                <span class="text-xs font-semibold bg-cream px-3 py-1 rounded-full">{{ now('Asia/Jakarta')->format('d M Y') }}</span>
                            </div>
                            <table class="w-full text-left text-xs">
                                <thead>
                                    <tr class="border-b border-gold/40 text-ink/65 uppercase text-[10px]">
                                        <th class="py-2">Kursi</th>
                                        <th class="py-2">Penumpang</th>
                                        <th class="py-2">Jurusan</th>
                                        <th class="py-2">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gold/20 font-medium">
                                    @forelse ($manifestEntries as $entry)
                                        <tr>
                                            <td class="py-2 font-bold text-maroon">{{ $entry->seat->seat_number ?? '-' }}</td>
                                            <td class="py-2">{{ $entry->passenger_name }}</td>
                                            <td class="py-2">{{ $entry->booking?->schedule?->route?->origin }} → {{ $entry->booking?->schedule?->route?->destination }}</td>
                                            <td class="py-2">
                                                @if ($entry->ticket?->status === 'checked_in')
                                                    <span class="px-2 py-0.5 rounded-full bg-dusk/30 text-ink font-bold text-[10px]">Checked-In</span>
                                                @elseif ($entry->booking?->status === 'confirmed')
                                                    <span class="px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 font-bold text-[10px]">Confirmed</span>
                                                @else
                                                    <span class="px-2 py-0.5 rounded-full bg-amber-100 text-amber-800 font-bold text-[10px]">{{ ucfirst($entry->booking?->status ?? 'pending') }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="py-6 text-center text-ink/60">Belum ada penumpang aktif tercatat.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="flex justify-between items-center pt-2 border-t border-gold/20">
                            <a href="{{ route('admin.pemesanan.index') }}" class="text-xs font-bold text-maroon hover:underline">Lihat Semua Pemesanan →</a>
                            <button type="button" onclick="switchBookPage(2)" class="px-4 py-2 rounded-full bg-maroon text-cream text-xs font-semibold cursor-pointer">Balik ke Halaman 2 →</button>
                        </div>
                    </div>

                    <!-- HALAMAN 2: LAPORAN PENDAPATAN -->
                    <div id="book-content-2" class="hidden h-full flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between border-b-2 border-maroon/20 pb-2.5 mb-4">
                                <h4 class="font-display text-lg text-maroon">Halaman 02 • Laporan Pendapatan & Armada</h4>
                                <span class="text-xs font-bold text-maroon">Terverifikasi</span>
                            </div>
                            <div class="grid grid-cols-2 gap-3 mb-4">
                                <div class="p-4 rounded-2xl bg-cream border border-gold/30">
                                    <span class="text-[11px] text-ink/70 block">Total Pemasukan Terkonfirmasi</span>
                                    <span class="font-display text-2xl text-maroon">Rp {{ number_format($metrics['total_revenue'], 0, ',', '.') }}</span>
                                </div>
                                <div class="p-4 rounded-2xl bg-mist/50 border border-dusk/40">
                                    <span class="text-[11px] text-ink/70 block">Okupansi Jadwal Mendatang</span>
                                    <span class="font-display text-2xl text-ink">{{ $occupancyRate }}%</span>
                                </div>
                            </div>

                            <h5 class="text-xs font-bold text-ink mb-2">Jadwal Keberangkatan Terdekat:</h5>
                            <div class="space-y-2 text-xs">
                                @forelse ($upcomingSchedules->take(3) as $sched)
                                    <div class="p-2.5 rounded-xl bg-white border border-gold/25 flex items-center justify-between">
                                        <span><strong>{{ $sched->route->origin }} → {{ $sched->route->destination }}</strong> ({{ $sched->departure_time->format('d M H:i') }})</span>
                                        <a href="{{ route('admin.manifest.show', $sched) }}" class="text-maroon font-bold hover:underline">Manifest →</a>
                                    </div>
                                @empty
                                    <p class="text-ink/60">Belum ada jadwal mendatang.</p>
                                @endforelse
                            </div>
                        </div>
                        <div class="flex justify-between pt-2 border-t border-gold/20">
                            <button type="button" onclick="switchBookPage(1)" class="px-4 py-2 rounded-full bg-cream text-ink text-xs font-semibold cursor-pointer">← Halaman 1</button>
                            <a href="{{ route('admin.laporan.index') }}" class="px-4 py-2 rounded-full bg-maroon text-cream text-xs font-semibold">Buka Laporan Lengkap →</a>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- KARTU VERIFIKASI STRUK & STEMPEL 3D (4 KOLOM) -->
        <div class="lg:col-span-4 soft-card rounded-[2.2rem] p-6 flex flex-col gap-4 border-2 border-white/85">
            <div class="flex items-center justify-between">
                <h3 class="font-display text-xl text-ink">Verifikasi Pembayaran</h3>
                <a href="{{ route('admin.pembayaran.index') }}" class="text-xs font-bold text-maroon hover:underline">Semua ({{ $metrics['pending_verifications_count'] }})</a>
            </div>

            @php $latestPending = $recentPendingPayments->first(); @endphp

            @if ($latestPending)
                <div class="relative bg-white rounded-2xl p-4 border-2 border-dashed border-gold/60 text-xs space-y-2 overflow-hidden">
                    <div class="flex justify-between border-b border-cream pb-2">
                        <span class="font-bold text-maroon">{{ $latestPending->payment_method === 'qris_manual' ? 'BUKTI QRIS' : 'TRANSFER BANK' }}</span>
                        <span class="font-mono text-ink/70">{{ $latestPending->booking->booking_code }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-ink/70">Penumpang:</span>
                        <span class="font-bold">{{ $latestPending->booking->user->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-ink/70">Nominal:</span>
                        <span class="font-bold text-maroon">Rp {{ number_format($latestPending->amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="pt-1">
                        <a href="{{ route('admin.pembayaran.show', $latestPending) }}" class="text-maroon font-bold hover:underline">🔍 Lihat Bukti Privat & Detail →</a>
                    </div>

                    <div id="admin-stamp-layer" class="hidden absolute inset-0 flex items-center justify-center bg-cream/65 backdrop-blur-[1px]">
                        <div id="admin-stamp-text" class="stamp-badge px-4 py-1.5 rounded-xl font-display text-2xl uppercase">VERIFIED</div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-2.5">
                    <form method="POST" action="{{ route('admin.pembayaran.verify', $latestPending) }}" onsubmit="applyAdminStamp('VERIFIED');">
                        @csrf
                        <input type="hidden" name="action" value="approve">
                        <button type="submit" class="w-full py-2.5 rounded-2xl bg-maroon hover:bg-vred text-cream font-semibold text-xs shadow cursor-pointer">
                            ✓ Setujui
                        </button>
                    </form>
                    <a href="{{ route('admin.pembayaran.show', $latestPending) }}" class="py-2.5 rounded-2xl bg-ink hover:bg-ink/85 text-cream font-semibold text-xs shadow text-center">
                        ✕ Periksa / Tolak
                    </a>
                </div>
            @else
                <div class="p-6 rounded-2xl bg-white/80 border border-gold/25 text-center text-xs text-ink/70 space-y-2">
                    <p class="font-bold text-ink">Antrean Verifikasi Kosong</p>
                    <p>Seluruh bukti pembayaran telah diproses.</p>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let bookOpenState = false;
        function toggleHardcoverBook() {
            bookOpenState = !bookOpenState;
            playTone(280, 440, 0.18);
            const book = document.getElementById('admin-hardcover-book');
            const label = document.getElementById('book-btn-label');
            const tabs = document.getElementById('book-tabs-bar');

            book.classList.toggle('is-open', bookOpenState);
            label.textContent = bookOpenState ? 'Tutup Buku Hardcover' : 'Buka Buku Hardcover';
            if (bookOpenState) {
                tabs.classList.remove('hidden');
                tabs.classList.add('flex');
            } else {
                tabs.classList.add('hidden');
                tabs.classList.remove('flex');
            }
        }

        function switchBookPage(page) {
            if (!bookOpenState) toggleHardcoverBook();
            playTone(350, 480, 0.14);
            const paper = document.getElementById('admin-book-paper');
            paper.classList.add('page-turning');
            setTimeout(() => {
                [1, 2].forEach(p => {
                    document.getElementById(`book-content-${p}`).classList.toggle('hidden', p !== page);
                    const b = document.getElementById(`bpage-btn-${p}`);
                    b.className = p === page
                        ? 'px-3.5 py-1 rounded-full text-xs font-bold bg-maroon text-cream cursor-pointer'
                        : 'px-3.5 py-1 rounded-full text-xs font-bold text-ink cursor-pointer';
                });
                paper.classList.remove('page-turning');
            }, 240);
        }

        function applyAdminStamp(type) {
            playTone(160, 70, 0.2);
            const layer = document.getElementById('admin-stamp-layer');
            const badge = document.getElementById('admin-stamp-text');
            if (!layer || !badge) return;
            layer.classList.remove('hidden');
            badge.textContent = type;
            badge.className = 'stamp-badge px-4 py-1.5 rounded-xl font-display text-2xl uppercase text-maroon border-maroon bg-cream/90';
        }
    </script>
@endpush
