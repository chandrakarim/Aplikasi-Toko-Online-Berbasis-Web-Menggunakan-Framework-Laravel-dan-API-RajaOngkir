@extends('admin.layout.app')
@section('content')
<div class="content-wrapper">
  <div class="page-header">
    <h3 class="page-title">
      <span class="page-title-icon bg-gradient-primary text-white mr-2">
        <i class="mdi mdi-home"></i>
      </span> Dashboard
    </h3>
    <nav aria-label="breadcrumb">
      <ul class="breadcrumb">
        <li class="breadcrumb-item active" aria-current="page">
          <span></span>Overview <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
        </li>
      </ul>
    </nav>
  </div>
 <div class="row">
    <!-- Pendapatan -->
    <div class="col-md-4 grid-margin">
        <div class="card shadow-sm border-0 text-white bg-gradient-danger dashboard-card">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <p class="mb-1 text-uppercase small opacity-75">Pendapatan</p>
                    <h3 class="mb-0 font-weight-bold">
                        Rp {{ number_format($pendapatan->penghasilan, 0, ',', '.') }}
                    </h3>
                </div>
                <div class="icon-circle bg-white text-danger">
                    <i class="mdi mdi-chart-line mdi-28px"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Transaksi -->
    <div class="col-md-4 grid-margin">
        <div class="card shadow-sm border-0 text-white bg-gradient-info dashboard-card">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <p class="mb-1 text-uppercase small opacity-75">Transaksi</p>
                    <h3 class="mb-0 font-weight-bold">
                        {{ $transaksi->total_order }}
                    </h3>
                </div>
                <div class="icon-circle bg-white text-info">
                    <i class="mdi mdi-cart-outline mdi-28px"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Pelanggan -->
    <div class="col-md-4 grid-margin">
        <div class="card shadow-sm border-0 text-white bg-gradient-success dashboard-card">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <p class="mb-1 text-uppercase small opacity-75">Pelanggan</p>
                    <h3 class="mb-0 font-weight-bold">
                        {{ $pelanggan->total_user }}
                    </h3>
                </div>
                <div class="icon-circle bg-white text-success">
                    <i class="mdi mdi-account-group mdi-28px"></i>
                </div>
            </div>
        </div>
    </div>
</div>

  <div class="row">
    <div class="col-12 grid-margin">
      <div class="card shadow-sm">
        <div class="card-body">

          <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="card-title mb-0">10 Transaksi Terbaru</h4>

            <!-- Search -->
            <input type="text" id="searchTable"
              class="form-control form-control-sm w-25"
              placeholder="Cari invoice / pemesan...">
          </div>

          <div class="table-responsive">
            <table class="table table-hover align-middle" id="transaksiTable">
              <thead class="thead-light">
                <tr>
                  <th>Invoice</th>
                  <th>Pemesan</th>
                  <th>Subtotal</th>
                  <th>Status</th>
                  <th class="text-center">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @foreach($order_baru as $order)
                <tr>
                  <td>
                    <strong>#{{ $order->invoice }}</strong>
                  </td>
                  <td>{{ $order->nama_pemesan }}</td>
                  <td>
                    Rp {{ number_format($order->subtotal + $order->biaya_cod, 0, ',', '.') }}
                  </td>
                  <td>
                    <span class="badge 
                    @if($order->name == 'Belum Bayar') badge-warning
                    @elseif($order->name == 'Sedang Di Proses') badge-info
                    @elseif($order->name == 'Barang Di Kirim') badge-primary
                    @elseif($order->name == 'Barang Telah Sampai') badge-success
                    @else badge-secondary
                    @endif">
                      {{ $order->name }}
                    </span>
                  </td>
                  <td class="text-center">
                    <a href="{{ route('admin.transaksi.detail',['id'=>$order->id]) }}"
                      class="btn btn-sm btn-outline-primary">
                      Detail
                    </a>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          <script>
            document.getElementById("searchTable").addEventListener("keyup", function() {
              const value = this.value.toLowerCase();
              const rows = document.querySelectorAll("#transaksiTable tbody tr");
              rows.forEach(row => {
                const text = row.innerText.toLowerCase();
                row.style.display = text.includes(value) ? "" : "none";
              });
            });
          </script>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection