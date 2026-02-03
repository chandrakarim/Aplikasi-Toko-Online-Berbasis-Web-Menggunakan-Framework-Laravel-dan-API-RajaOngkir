@extends('user.app')
@section('content')

<!-- Breadcrumb -->
<nav class="bg-light border-bottom py-3" aria-label="breadcrumb">
    <div class="container">
        <ol class="breadcrumb mb-0 bg-transparent p-0 align-items-center">
            <li class="breadcrumb-item">
                <a href="{{ route('home') }}" class="text-muted text-decoration-none">
                    Home
                </a>
            </li>

            <li class="breadcrumb-item text-muted">
                Kategori Produk
            </li>

            <li class="breadcrumb-item active fw-semibold text-dark" aria-current="page">
                {{ optional($produk->category)->name ?? '-' }}
            </li>
        </ol>
    </div>
</nav>


<div class="container py-5">
    <div class="row align-items-start">

        <!-- IMAGE -->
        <div class="col-md-6 mb-4">
            <div class="zoom-wrapper">
                <!-- Gambar utama -->
                <div class="zoom-image-container">
                    <img
                        src="{{ asset('storage/'.$produk->image) }}"
                        id="mainImage"
                        data-large="{{ asset('storage/'.$produk->image) }}"
                        alt="{{ $produk->name }}">
                </div>

                <!-- Area zoom -->
                <div class="zoom-preview" id="zoomPreview"></div>
            </div>
        </div>


        <!-- DETAIL -->
        <div class="col-md-6">
            <h3 class="fw-bold mb-2">{{ $produk->name }}</h3>
            <p class="text-muted">{{ $produk->description }}</p>

            <h4 class="text-primary fw-bold mb-3">
                Rp {{ number_format($produk->price,0,',','.') }}
            </h4>

            <!-- STOK (TANPA BUTTON) -->
            <div class="stok-info mb-3">
                Sisa stok: <strong>{{ $produk->stok }}</strong>
            </div>

            <form action="{{ route('user.keranjang.simpan') }}" method="POST">
                @csrf
                <input type="hidden" name="products_id" value="{{ $produk->id }}">
                <input type="hidden" id="stokMax" value="{{ $produk->stok }}">

                <!-- QTY -->
                <label class="fw-semibold mb-1">Jumlah</label>
                <div class="d-flex align-items-center mb-4">
                    <button type="button" id="minus" class="btn btn-outline-primary">−</button>

                    <input type="text"
                        id="qty"
                        name="qty"
                        class="form-control text-center mx-2"
                        value="1"
                        readonly
                        style="width:80px">

                    <button type="button" id="plus" class="btn btn-outline-primary">+</button>
                </div>

                <button type="submit" class="btn btn-primary w-100">
                    Tambah ke Keranjang
                </button>
            </form>
        </div>
    </div>
</div>

<!-- MODAL ZOOM -->
<div class="modal fade" id="zoomModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center p-3">
                <img src="{{ asset('storage/'.$produk->image) }}"
                    class="img-fluid rounded"
                    alt="{{ $produk->name }}">
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
<script>
    $(function() {

        // ZOOM IMAGE
        $('#openZoom').on('click', function() {
            $('#zoomModal').modal('show');
        });

        // QTY CONTROL
        var qty = 1;
        var max = parseInt($('#stokMax').val());

        $('#minus').on('click', function() {
            if (qty > 1) {
                qty--;
                $('#qty').val(qty);
            }
        });

        $('#plus').on('click', function() {
            if (qty < max) {
                qty++;
                $('#qty').val(qty);
            } else {
                alert('Stok tersisa hanya ' + max);
            }
        });

    });

    // script zoom
    $(function() {

        const $img = $('#mainImage');
        const $preview = $('#zoomPreview');

        const img = $img[0];
        const lens = $('<div class="zoom-lens"></div>');

        $img.parent().append(lens);

        let cx, cy;

        function initZoom() {
            $preview.show();

            const previewWidth = $preview.width();
            const previewHeight = $preview.height();

            cx = previewWidth / lens.width();
            cy = previewHeight / lens.height();

            $preview.css({
                backgroundImage: `url('${$img.data('large')}')`,
                backgroundSize: `${img.width * cx}px ${img.height * cy}px`
            });
        }

        function moveLens(e) {
            const pos = getCursorPos(e);

            let x = pos.x - lens.width() / 2;
            let y = pos.y - lens.height() / 2;

            if (x < 0) x = 0;
            if (y < 0) y = 0;
            if (x > img.width - lens.width()) x = img.width - lens.width();
            if (y > img.height - lens.height()) y = img.height - lens.height();

            lens.css({
                left: x + 'px',
                top: y + 'px'
            });

            $preview.css(
                'backgroundPosition',
                `-${x * cx}px -${y * cy}px`
            );
        }

        function getCursorPos(e) {
            const rect = img.getBoundingClientRect();
            return {
                x: e.pageX - rect.left - window.pageXOffset,
                y: e.pageY - rect.top - window.pageYOffset
            };
        }

        // Desktop only
        if (window.innerWidth > 768) {
            $img.on('mouseenter', function() {
                initZoom();
                lens.show();
            });

            $img.on('mousemove', moveLens);

            $img.on('mouseleave', function() {
                lens.hide();
                $preview.hide();
            });
        }
    });
</script>
@endsection