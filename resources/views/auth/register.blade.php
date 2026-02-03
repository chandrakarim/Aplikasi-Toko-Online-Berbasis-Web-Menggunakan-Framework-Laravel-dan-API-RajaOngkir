<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Purple Admin</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="{{ asset('adminassets') }}/assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="{{ asset('adminassets') }}/assets/vendors/css/vendor.bundle.base.css">
    <!-- endinject -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <!-- Plugin css for this page -->
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <!-- endinject -->
    <!-- Layout styles -->
    <link rel="stylesheet" href="{{ asset('adminassets') }}/assets/css/style.css">
    <!-- End layout styles -->
    <link rel="shortcut icon" href="{{ asset('adminassets') }}/assets/images/favicon.png" />
  </head>
  <style>
    .buttonWrap {
  position: relative;
}
#blocker {
  position: absolute;
  height: 100%;
  width: 100%;
  background: rgba(255,255,255,0.7);
}
  </style>
  <body>
    <div class="container-scroller">
      <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="content-wrapper d-flex align-items-center auth">
          <div class="row flex-grow">
            <div class="col-lg-4 mx-auto">
              <div class="auth-form-light text-center p-5">
                <div class="brand-logo">
                  <!-- <img src="{{ asset('adminassets') }}/assets/images/logo.svg"> -->
                  <h2>Toko Merlyn</h2>
                </div>
                <h4>New here?</h4>
                <h6 class="font-weight-light">Signing up is easy. It only takes a few steps</h6>
                <form class="pt-3" method="POST" action="{{ route('register') }}">
                @csrf
                  <div class="form-group">
                  <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="Nama">

                    @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror


                  </div>

                  <div class="form-group">
                      
                                <select name="jk" id="jk" class="form-control">
                                <option selected disabled>Pilih Jenis Kelamin</option>
                                    <option value="Laki-laki">Laki-laki</option>
                                    <option value="Perempuan">Perempuan</option>
                                </select>
                            </div>
                   
                    <div class="form-group">
                  <input id="no_tlp" type="text" class="form-control @error('no_tlp') is-invalid @enderror" name="no_tlp" value="{{ old('no_tlp') }}" required autocomplete="no_tlp" autofocus placeholder="Nomor Telp">

                    @error('no_tlp')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror


                  </div> 
                  <div class="form-group">
                  <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="Email">

                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                  </div>
                  <div class="form-group">
                  <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="Password">

                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
                  </div>
                  <div class="form-group">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" placeholder="Konfirmasi Password">
                            </div>
                  <div class="mb-4">
                    <div class="form-check">
                      <label class="form-check-label text-muted">
                        <input id="checkbox" type="checkbox" class="form-check-input"> I agree to all Terms & Conditions </label>
                    </div>
                  </div>
                  <div class="mt-3">
                  <div class="buttonWrap">
                  <div id="blocker"></div>
                    <button  id="button" type="submit" class="btn btn-block btn-gradient-primary btn-lg font-weight-medium auth-form-btn" >Daftar</button>
                  </div>
                  <div class="text-center mt-4 font-weight-light"> Already have an account? <a href="/login" class="text-primary">Login</a>
                  </div>
                </form>
              </div>
              <script>
                    $(document).ready(function() {
                      // Disable blocker.
                      function unblock() {
                        $("#blocker").css("display", "none");
                      }

                      // Enable blocker.
                      function block() {
                        $("#blocker").css("display", "block");
                      }

                      // Monitor checkbox.
                      $('body').on('click', '#checkbox', function() {
                        if( $(this).prop("checked") ) {
                            unblock();
                        } else {
                            block();
                        }
                      });
                     
                      // Monitor blocker.
                      $('#blocker').on('click', function() {
                        alert('Maaf,Anda Belum Mencentang [✔] I agree to all Terms & Conditions !!!');
                      });
                    });
              </script>
            </div>
          </div>
        </div>
        <!-- content-wrapper ends -->
      </div>
      <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <!-- plugins:js -->
    <script src="{{ asset('adminassets') }}/assets/vendors/js/vendor.bundle.base.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="{{ asset('adminassets') }}/assets/js/off-canvas.js"></script>
    <script src="{{ asset('adminassets') }}/assets/js/hoverable-collapse.js"></script>
    <script src="{{ asset('adminassets') }}/assets/js/misc.js"></script>
    <!-- endinject -->
  </body>
</html>