@extends('user.app')
@section('content')

<div class="bg-light py-3">
    <div class="container">
        <div class="row">
            <div class="col-md-12 mb-0">
                <a href="{{ route('home') }}">Home</a>
                <span class="mx-2">/</span>
                <strong>Keranjang</strong>
            </div>
        </div>
    </div>
</div>

<div class="site-section">
    <div class="container">

        <form method="post" action="{{ route('user.keranjang.update') }}">
            @csrf

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Gambar</th>
                        <th>Produk</th>
                        <th>Harga</th>
                        <th>Jumlah</th>
                        <th>Total</th>
                        <th>Hapus</th>
                    </tr>
                </thead>

                <tbody>
                    @php $subtotal = 0; @endphp

                    @foreach($keranjangs as $k)
                    @php
                    $total = $k->price * $k->qty;
                    $subtotal += $total;
                    @endphp

                    <tr>
                        <td width="150">
                            <img src="{{ asset('storage/'.$k->image) }}" class="img-fluid">
                        </td>

                        <td>{{ $k->nama_produk }}</td>

                        <td>Rp {{ number_format($k->price,0,',','.') }}</td>

                        <td width="180">
                            <input type="hidden" name="id[]" value="{{ $k->id }}">

                            <div class="input-group">
                                <button type="button"
                                    class="btn btn-outline-primary minus"
                                    data-target="qty{{ $k->id }}">-</button>

                                <input type="text"
                                    id="qty{{ $k->id }}"
                                    name="qty[]"
                                    class="form-control text-center"
                                    value="{{ $k->qty }}"
                                    data-max="{{ $k->stok }}"
                                    readonly>

                                <button type="button"
                                    class="btn btn-outline-primary plus"
                                    data-target="qty{{ $k->id }}">+</button>
                            </div>
                        </td>

                        <td>Rp {{ number_format($total,0,',','.') }}</td>

                        <td>
                            <a href="{{ route('user.keranjang.delete',$k->id) }}"
                                class="btn btn-danger btn-sm">X</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <button class="btn btn-primary btn-sm">Update Keranjang</button>

        </form>

        {{-- ================= PILIH KURIR ================= --}}
        @if($cekalamat > 0)

        <hr>

        <h5>Pilih Kurir</h5>

        <div class="row">
            <div class="col-md-6 mb-3">
                <select id="courierSelect" class="form-control">
                    <option value="">-- pilih kurir --</option>
                    @foreach($couriers as $c)
                    <option value="{{ $c->code }}">{{ $c->title }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <select id="serviceSelect" class="form-control">
                    <option>-- pilih layanan --</option>
                </select>
            </div>
        </div>

        <div class="mb-3">
            Ongkir: <strong id="ongkirText">Rp 0</strong>
        </div>

        @endif

        {{-- ================= TOTAL ================= --}}
        <div class="row mt-5">
            <div class="col-md-6 offset-md-6">

                <h4>Total Belanja</h4>

                <div class="d-flex justify-content-between">
                    <span>Subtotal</span>
                    <strong id="subtotalText">
                        Rp {{ number_format($subtotal,0,',','.') }}
                    </strong>
                </div>

                <div class="d-flex justify-content-between">
                    <span>Total + Ongkir</span>
                    <strong id="grandTotalText">
                        Rp {{ number_format($subtotal,0,',','.') }}
                    </strong>
                </div>

                @if($cekalamat > 0)
                <a href="{{ route('user.checkout') }}"
                    class="btn btn-primary btn-lg btn-block mt-3">
                    Checkout
                </a>
                <small>Jika Merubah Quantity Pada Keranjang Maka Klik Update Keranjang Dulu Sebelum Melakukan Checkout</small>
                @else
                <a href="{{ route('user.alamat') }}"
                    class="btn btn-warning btn-lg btn-block mt-3">
                    Atur Alamat
                </a>
                <small>Anda Belum Mengatur Alamat</small>
                @endif

            </div>
        </div>

    </div>
</div>

<script>
/* ================= QTY BUTTON ================= */

document.querySelectorAll('.plus').forEach(btn => {
    btn.onclick = () => {
        let input = document.getElementById(btn.dataset.target);
        let max = parseInt(input.dataset.max);
        let val = parseInt(input.value);
        if (val < max) input.value = val + 1;
    };
});

document.querySelectorAll('.minus').forEach(btn => {
    btn.onclick = () => {
        let input = document.getElementById(btn.dataset.target);
        let val = parseInt(input.value);
        if (val > 1) input.value = val - 1;
    };
});


/* ================= ONGKIR ================= */

/* 🔴 FIX blade variable */
const subtotal = {{ $subtotal }};
const weight   = {{ $totalBerat ?? 1000 }};

const courierSelect = document.getElementById('courierSelect');
const serviceSelect = document.getElementById('serviceSelect');
const ongkirText = document.getElementById('ongkirText');
const grandText  = document.getElementById('grandTotalText');


if (courierSelect && serviceSelect) {

    courierSelect.addEventListener('change', function () {

        if (!this.value) return;

        serviceSelect.innerHTML = '<option>Loading...</option>';

        fetch("{{ route('cek.ongkir') }}", {
            method: "POST",
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                courier: this.value,
                weight: weight
            })
        })
        .then(r => r.json())
        .then(res => {

            console.log("ONGKIR RES:", res);

            serviceSelect.innerHTML = '<option value="">-- pilih layanan --</option>';

            if (!res.data || res.data.length === 0) {
                serviceSelect.innerHTML += '<option>Tidak ada layanan</option>';
                return;
            }

            /* ✅ FORMAT KOMERCE */
            res.data.forEach(s => {

                serviceSelect.innerHTML += `
                    <option
                        data-cost="${s.cost}"
                        data-service="${s.service}">
                        ${s.service} (${s.description})
                        - Rp ${Number(s.cost).toLocaleString()}
                    </option>`;

            });

        })
        .catch(err => {
            console.log("ONGKIR ERROR:", err);
            serviceSelect.innerHTML = '<option>Error ambil ongkir</option>';
        });

    });


    serviceSelect.addEventListener('change', function () {

        let opt = this.selectedOptions[0];
        if (!opt) return;

        let cost = parseInt(opt.dataset.cost || 0);
        let service = opt.dataset.service;
        let courier = courierSelect.value;

        /* tampilkan ongkir */
        ongkirText.innerText = 'Rp ' + cost.toLocaleString();

        /* hitung total */
        let grand = subtotal + cost;
        grandText.innerText = 'Rp ' + grand.toLocaleString();

        /* simpan ke session */
        fetch("{{ route('set.ongkir.session') }}", {
            method: "POST",
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                ongkir: cost,
                courier: courier,
                service: service
            })
        });

    });

}
</script>


@endsection