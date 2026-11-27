@extends('layouts.admin')

@section('page-title', 'Antrean Verifikasi Pembayaran')
@section('page-subtitle', 'Periksa bukti transfer privat dan terbitkan E-Tiket 3D dengan menyetujui pembayaran.')

@section('admin-content')
    <div class="soft-card rounded-[2.2rem] p-6 border-2 border-white/85">
        <div class="flex flex-wrap items-center justify-between gap-3 mb-5">
            <div class="flex items-center gap-2 text-xs">
                @php $curr = request('status', 'pending_verification'); @endphp
                <a href="{{ route('admin.payments.index', ['status' => 'pending_verification']) }}" class="px-4 py-2 rounded-full font-bold {{ $curr === 'pending_verification' ? 'bg-maroon text-cream' : 'bg-white text-ink border border-gold/30' }}">Menunggu Verifikasi</a>
                <a href="{{ route('admin.payments.index', ['status' => 'verified']) }}" class="px-4 py-2 rounded-full font-bold {{ $curr === 'verified' ? 'bg-maroon text-cream' : 'bg-white text-ink border border-gold/30' }}">Disetujui</a>
                <a href="{{ route('admin.payments.index', ['status' => 'rejected']) }}" class="px-4 py-2 rounded-full font-bold {{ $curr === 'rejected' ? 'bg-maroon text-cream' : 'bg-white text-ink border border-gold/30' }}">Ditolak</a>
                <a href="{{ route('admin.payments.index', ['status' => 'all']) }}" class="px-4 py-2 rounded-full font-bold {{ $curr === 'all' ? 'bg-maroon text-cream' : 'bg-white text-ink border border-gold/30' }}">Semua</a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-gold/40 text-ink/60 uppercase text-[10px]">
                        <th class="py-3 px-2">Booking & Penumpang</th>
                        <th class="py-3 px-2">Rute</th>
                        <th class="py-3 px-2">Metode & Percobaan</th>
                        <th class="py-3 px-2">Nominal</th>
                        <th class="py-3 px-2">Status</th>
                        <th class="py-3 px-2 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gold/20">
                    @forelse ($payments as $payment)
                        <tr class="hover:bg-white/60">
                            <td class="py-3 px-2">
                                <span class="font-mono font-bold text-maroon">{{ $payment->booking->booking_code }}</span>
                                <p class="font-semibold text-ink">{{ $payment->booking->user->name }}</p>
                            </td>
                            <td class="py-3 px-2">
                                {{ $payment->booking->schedule->route->origin }} → {{ $payment->booking->schedule->route->destination }}
                            </td>
                            <td class="py-3 px-2">
                                {{ $payment->payment_method === 'qris_manual' ? 'QRIS Manual' : 'Transfer Bank' }} (#{{ $payment->attempt_number }})
                            </td>
                            <td class="py-3 px-2 font-bold text-maroon">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                            <td class="py-3 px-2">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold {{ $payment->status === 'verified' ? 'bg-emerald-100 text-emerald-800' : ($payment->status === 'rejected' ? 'bg-red-100 text-vred' : 'bg-amber-100 text-amber-800') }}">
                                    {{ strtoupper(str_replace('_', ' ', $payment->status)) }}
                                </span>
                            </td>
                            <td class="py-3 px-2 text-right">
                                <a href="{{ route('admin.payments.show', $payment) }}" class="px-3.5 py-1.5 rounded-xl bg-maroon text-cream font-bold">Periksa Bukti →</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="py-6 text-center text-ink/60">Tidak ada data pembayaran pada filter ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $payments->links() }}</div>
    </div>
@endsection
