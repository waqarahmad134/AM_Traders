<!doctype html>
<html lang="en">

<head>
    <title>::  :: Login</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta name="description" content="Antrack Admin">
    <meta name="author" content="Sigi Tech, www.sigitechnologies.com">

    <link rel="icon" href="{{asset('/public/assets/images/user.png')}}" type="image/x-icon">
    <!-- VENDOR CSS -->
    <link rel="stylesheet" href="{{asset('/public/assets/vendor/bootstrap/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('/public/assets/vendor/font-awesome/css/font-awesome.min.css')}}">

    <!-- MAIN CSS -->
    <link rel="stylesheet" href="{{asset('/public/assets/css/main.css')}}">
    <link rel="stylesheet" href="{{asset('/public/assets/css/color_skins.css')}}">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<style>
    input{
        height: 52px !important;
        background:transparent !important;
        border:1px solid #FFFFFF99 !important;
        color: white !important;
    }
    ::placeholder{
        color: #FFFFFF99 !important;
    }
    input:-webkit-autofill,
    input:-webkit-autofill:focus {
      -webkit-box-shadow: 0 0 0px 1000px transparent inset !important;
      background-color: transparent !important;
    }
  
</style>
</head>

<body class="theme-orange">
    <!-- WRAPPER -->
    <div id="wrapper" class="auth-main">
        <div class="container">
            <div class="row clearfix">
                <div class="col-12">
                    <nav class="navbar navbar-expand-lg">
                        <a class="navbar-brand" href="javascript:void(0);">
                            <img src="{{asset('/public/assets/images/logo.png')}}" class="img-fluid d-inline-block align-top mr-2" alt="logo" width="120">
                        </a>
                    </nav>
                </div>
                <div class="col-lg-8">
                    <div class="auth_detail">
                        <h2 class="text-monospace">
                        "Stock Management System"</h2>
                        <p class="text-p-monospace">Log in to access your account and streamline your operations.</p>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div>
                        <h2 class="text-white my-4">Login to your account</h2>
                        <div class="body">
                            <form method="POST" class="form-auth-small" action="{{route('logins')}}">
                                @csrf
                                <div class="form-group">
                                    <label for="signin-email" class="control-label sr-only">Email</label>
                                    <input style="color:#4A006D !important;" name="email" type="email" class="form-control" id="signin-email" value="" placeholder="Email" required autocomplete="off">
                                </div>
                                <div class="form-group">
                                    <label for="passInput" class="control-label sr-only">Password</label>
                                    <input style="color:#4A006D !important;" name="password" type="password" class="form-control" id="passInput" value="" placeholder="Password" required>
                                </div>
                                <div class="d-flex align-items-center">
                                    <input type="checkbox" id="showPass"> 
                                    <label for="showPass" class="text-white p-0 m-0">&nbsp; Show Password</label>
                                </div>
                                <div>
                                    <button type="submit" class="btn btn-primary btn-lg " style="background-color: white; border-color: transparent;color:#4A006D;">LOGIN</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{asset('/public/assets/bundles/libscripts.bundle.js')}}"></script>
    <script src="{{asset('/public/assets/bundles/vendorscripts.bundle.js')}}"></script>
    <script src="{{asset('/public/assets/bundles/mainscripts.bundle.js')}}"></script>

    <script>
        $(document).ready(function() {

            $('#showPass').on('click', function() {
                var passInput = $("#passInput");
                if (passInput.attr('type') === 'password') {
                    passInput.attr('type', 'text');
                } else {
                    passInput.attr('type', 'password');
                }
            })
        });


        // function login() {
        //     event.preventDefault();
        //     email = $("input[name=email]").val();
        //     password = $("input[name=password]").val();
        //     $.ajax({
        //         url: "{{route('logins')}}",
        //         type: "post",
        //         data: {
        //             "_token": "{{ csrf_token() }}",
        //             "email": email,
        //             "password": password,

        //         },
        //         success: function(response) {
        //             console.log(response);
        //             if (response.ResponseCode == 1) {
        //                 var homeUrl = "{{ route('homess') }}";
        //                 window.location.replace(homeUrl);
        //             }
        //             if (response.ResponseCode == 0) {
        //                 toastr.error('Wrong Crediantals.', 'Error');
        //                 // $("#formular").modal('show');
        //             }


        //         },
        //         error: function(jqXHR, textStatus, errorThrown) {
        //             console.log(textStatus, errorThrown);
        //         }
        //     });
        // }
    </script>

    <script>
        @if(Session::has('error'))
        toastr.options = {
            "closeButton": true,
            "progressBar": true
        }
        toastr.error("{!! session('error') !!}");

        @endif

        @if(Session::has('info'))
        toastr.options = {
            "closeButton": true,
            "progressBar": true
        }
        toastr.info("{{ session('info') }}");

        @endif
    </script>
</body>

</html>