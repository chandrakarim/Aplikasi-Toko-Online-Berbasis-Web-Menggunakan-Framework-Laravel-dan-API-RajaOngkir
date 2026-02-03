@extends('user.app')
@section('content')
<div class="bg-light py-3">
    <div class="container">
    <div class="row">
        <div class="col-md-12 mb-0" class="{{ Request::path() === '/' ? '' : '' }}"><a href="{{ route('home') }}">Home</a> <span class="mx-2 mb-0">/</span> <strong class="text-black">Cart</strong></div>
    </div>
    </div>
</div>

<div class="site-section">
    <div class="container">
    <div class="row mb-3">
        <div class="col-md-12 text-center">
            <h2 class="display-5">Detail Pesanan Anda</h2>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">      
                <div class="card-body">
                <div class="col-md-12 text-right">
                    @if($order->status_order_id == 3)
                    <a href="{{ route('user.order.pesanandibatalkan',['id' => $order->id]) }}" onclick="return confirm('Yakin ingin membatalkan pesanan')" class="btn btn-danger">Batalkan Transaksi</a>
                    <p class="text-danger">
                            <small>Jika Ingin Membatalkan Transaksi COD,Silahkan Klik Tombol Di Atas</small>
                    </p>
                    @endif
                </div>
                <div class="row">
                <div class="col-md-8">
                    <table>
                        <tr>
                            <th>No Invoice</th>
                            <td>:</td>
                            <td>{{ $order->invoice }}</td>
                        </tr>
                        <tr>
                            <th>No Resi</th>
                            <td>:</td>
                            <td>{{ $order->no_resi }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Pesan</th>
                            <td>:</td>
                            <td>{{ $order->created_at }}</td>
                        </tr>
                        <tr>
                            <th>Status Pesanan</th>
                            <td>:</td>
                            <td>{{ $order->status }}</td>
                        </tr>
                        <tr>
                            <th>Metode Pembayaran</th>
                            <td>:</td>
                            <td>
                            @if($order->metode_pembayaran == 'trf')
                                Transfer Bank
                            @else
                                COD
                            @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Total Pembayaran</th>
                            <td>:</td>
                            <td>Rp. {{ number_format($order->subtotal + $order->biaya_cod,2,',','.') }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-4 text-right">
                    @if($order->status_order_id == 4)
                    <a href="{{ route('user.order.pesananditerima',['id' => $order->id]) }}" onclik="return confirm('Yakin ingin melanjutkan ?')" class="btn btn-primary">Pesanan Di Terima</a><br>
                    <p class="text-danger">
                         <small>Jika pesanan belum datang harap jangan tekan tombol ini</small>
                    </p>
                    @endif
                </div>
                </div>
                <div class="row mb-5">
                    <div class="col-md-12">
                        <table class="table table-bordered">
                        <thead>
                            <tr>
                            <th class="product-thumbnail">Gambar</th>
                            <th class="product-name">Nama Produk</th>
                            <th class="product-price">Jumlah</th>
                            <th class="product-quantity" width="20%">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($detail as $o)
                            <tr>
                                <td><img src="{{ asset('storage/'.$o->image) }}" alt="" srcset="" width="50"></td>
                                <td>{{ $o->nama_produk }}</td>
                                <td>{{ $o->qty }}</td>
                                <td>Rp.{{ number_format($o->qty * $o->price) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        </table>
                    
                </div>
                </div>
            </div>
        </div>
    </div>
    

    </div>
</div>
@endsection