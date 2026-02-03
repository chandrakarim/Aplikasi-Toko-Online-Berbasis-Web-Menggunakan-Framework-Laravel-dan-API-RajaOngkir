@extends('admin.layout.app')
@section('content')
<div class="content-wrapper">
  <div class="page-header">
    <h3 class="page-title">
      <span class="page-title-icon bg-gradient-primary text-white mr-2">
        <i class="mdi mdi-home"></i>
      </span> Alamat Toko
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
    <div class="col-12 grid-margin">
      <div class="card">
        <div class="card-body">
          @if($cekalamat < 1)
            <form action="{{ route('admin.pengaturan.simpanalamat') }}" method="POST">
            @csrf

            <label>Provinsi</label>
            <select required name="province_id" id="province_id" class="form-control">
              <option value="">Pilih Provinsi</option>
              @foreach($provinces as $province)
              <option value="{{ $province->id }}">{{ $province->title }}</option>
              @endforeach
            </select>

            <label class="mt-3">Kota / Kabupaten</label>
            <select name="cities_id" id="cities_id" class="form-control" required></select>

            <label class="mt-3">Detail Alamat</label>
            <input type="text" class="form-control" name="detail" required>

            <button class="btn btn-success mt-3">Simpan</button>
            </form>
        </div>
      @endif
      <div class="row">
        <div class="col-md-12">
          <table>
            <tr>
              <th>Alamat Sekarang</th>
              <th>:</th>
              <td>
                @if($alamat)
                {{ $alamat->detail }}, {{ $alamat->kota }}, {{ $alamat->prov }}
                @else
                <em>Alamat toko belum diatur</em>
                @endif
              </td>

            </tr>
          </table>
          <small>@if($alamat)
            <small>
              <a href="{{ route('admin.pengaturan.ubahalamat', $alamat->id) }}">
                Klik untuk mengubah alamat toko
              </a>
            </small>
            @else
            <small>
              <span class="text-muted">
                Silakan isi alamat toko di atas
              </span>
            </small>
            @endif
          </small>
        </div>
      </div>
    </div>
  </div>
</div>
</div>
</div>
@endsection
@section('js')
<script type="text/javascript">
  $.ajax({
    type: 'GET',
    url: "{{ route('admin.pengaturan.getCity', '') }}/" + id,
    dataType: 'json',
    success: function(data) {
      var op = '<option value="">Pilih Kota</option>';
      if (data.length > 0) {
        data.forEach(function(item) {
          op += `<option value="${item.id}">${item.title}</option>`;
        });
      }
      $('[name="cities_id"]').html(op);
    }
  });
</script>
@endsection