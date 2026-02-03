@extends('user.app')

@section('content')

<!-- Breadcrumb -->
<div class="bg-light py-3 border-bottom">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <a href="{{ route('home') }}" class="text-decoration-none text-muted">Home</a>
                <span class="mx-2">/</span>
                <strong class="text-dark">Pesanan Saya</strong>
            </div>
        </div>
    </div>
</div>

<div class="site-section">
    <div class="container">

        <!-- ===================== BELUM DIBAYAR ===================== -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-warning text-white fw-bold">
                Belum Dibayar
            </div>
            <div class="card-body table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Invoice</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($order as $o)
                        <tr>
                            <td><strong>{{ $o->invoice }}</strong></td>
                            <td>Rp {{ number_format($o->subtotal + $o->biaya_cod,0,',','.') }}</td>

                            <td>
                                <span class="badge-status status-pending">
                                   {{ $o->name }}
                                </span>
                            </td>

                            <td class="text-end">
                                <a href="{{ route('user.order.pembayaran',$o->id) }}" class="btn btn-sm btn-success">
                                    Bayar
                                </a>
                                <a href="{{ route('user.order.pesanandibatalkan',$o->id) }}"
                                    onclick="return confirm('Yakin ingin membatalkan pesanan?')"
                                    class="btn btn-sm btn-outline-danger">
                                    Batalkan
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">
                                Tidak ada pesanan belum dibayar
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ===================== SEDANG DIPROSES ===================== -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-info text-white fw-bold">
                Sedang Dalam Proses
            </div>
            <div class="card-body table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Invoice</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dicek as $o)
                        <tr>
                            <td><strong>{{ $o->invoice }}</strong></td>
                            <td>Rp {{ number_format($o->subtotal + $o->biaya_cod,0,',','.') }}</td>
                            <td>
                                @php
                                $statusClass = match($o->name) {
                                'Perlu Di Cek' => 'status-check',
                                'Perlu Di Kirim' => 'status-process',
                                'Barang Di Kirim' => 'status-send',
                                default => 'status-process',
                                };
                                @endphp

                                <span class="badge-status {{ $statusClass }}">
                                    {{ $o->name == 'Perlu Di Cek' ? 'Sedang Dicek' : $o->name }}
                                </span>
                            </td>

                            <td class="text-end">
                                <a href="{{ route('user.order.detail',$o->id) }}"
                                    class="btn btn-sm btn-outline-primary">
                                    Detail
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">
                                Tidak ada pesanan yang sedang diproses
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ===================== RIWAYAT PESANAN ===================== -->
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white fw-bold">
                Riwayat Pesanan
            </div>
            <div class="card-body table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Invoice</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($histori as $o)
                        <tr>
                            <td><strong>{{ $o->invoice }}</strong></td>
                            <td>Rp {{ number_format($o->subtotal + $o->biaya_cod,0,',','.') }}</td>
                            <td>
                                @php
                                $statusClass = match($o->name) {
                                'Selesai' => 'status-done',
                                'Dibatalkan' => 'status-cancel',
                                default => 'status-process',
                                };
                                @endphp

                                <span class="badge-status {{ $statusClass }}">
                                    {{ $o->name }}
                                </span>
                            </td>

                            <td class="text-end">
                                <a href="{{ route('user.order.detail',$o->id) }}"
                                    class="btn btn-sm btn-outline-success">
                                    Detail
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">
                                Belum ada riwayat pesanan
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

@endsection
@push('css')
<style>
    .badge-status {
        padding: 6px 12px;
        font-size: 0.8rem;
        font-weight: 600;
        border-radius: 12px;
        color: #fff;
        letter-spacing: .3px;
    }

    .status-pending {
        background: #f59e0b;
    }

    /* kuning tua */
    .status-check {
        background: #0d6efd;
    }

    /* biru */
    .status-process {
        background: #0dcaf0;
    }

    /* biru muda */
    .status-send {
        background: #6610f2;
    }

    /* ungu */
    .status-done {
        background: #198754;
    }

    /* hijau */
    .status-cancel {
        background: #dc3545;
    }

    /* merah */
</style>