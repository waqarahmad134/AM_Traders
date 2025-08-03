    <!doctype html>
<html lang="en">

<head>
<title>::  :: Sign Up</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<meta name="description" content="Antrak Admin Template">
<meta name="author" content="WrapTheme, www.thememakker.com">

<link rel="icon" href="favicon.ico" type="image/x-icon">
<!-- VENDOR CSS -->
<link rel="stylesheet" href="{{asset('public/assets/vendor/bootstrap/css/bootstrap.min.css')}}">
<link rel="stylesheet" href="{{asset('public/assets/vendor/font-awesome/css/font-awesome.min.css')}}">

<!-- MAIN CSS -->
<link rel="stylesheet" href="{{asset('public/assets/css/main.css')}}">
<link rel="stylesheet" href="{{asset('public/assets/css/color_skins.css')}}">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

</head>



<body class="theme-orange">

    <!-- WRAPPER -->
    <div id="wrapper" class="auth-main">
        <div class="container">
            <div class="row clearfix">
                <div class="col-12">
                    <nav class="navbar navbar-expand-lg">
                        <a class="navbar-brand" href="javascript:void(0);"><img src="https://demo.zeeshannawaz.com/antrack_backend/public/assets/images/user.png" class="d-inline-block align-top mr-2" alt=""></a>
                        <!--<ul class="navbar-nav">-->
                        <!--    <li class="nav-item"><a class="nav-link" href="javascript:void(0);">Documentation</a></li>-->
                        <!--    <li class="nav-item"><a class="nav-link" href="page-login.html">Sign In</a></li>-->
                        <!--</ul>-->
                    </nav>
                </div>
                <div class="col-lg-8">
                    <!--<div class="auth_detail">-->
                    <!--    <h2 class="text-monospace">-->
                    <!--        Everything<br> you need for-->
                    <!--        <div id="carouselExampleControls" class="carousel vert slide" data-ride="carousel" data-interval="1500">-->
                    <!--            <div class="carousel-inner">-->
                    <!--                <div class="carousel-item active">your Admin</div>-->
                    <!--                <div class="carousel-item">your Project</div>-->
                    <!--                <div class="carousel-item">your Dashboard</div>-->
                    <!--                <div class="carousel-item">your Application</div>-->
                    <!--                <div class="carousel-item">your Client</div>-->
                    <!--            </div>-->
                    <!--        </div>-->
                    <!--    </h2>-->
                    <!--    <p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.</p>-->
                       
                        
                    <!--</div>-->
                </div>
                <div class="col-lg-4">
                    <div class="card" style="background-color: lightgrey; border-color: lightgrey;">
                        <div class="header">
                            <p class="lead" style="color: black;">Create an account</p>
                        </div>
                        <div class="body">
                            <form class="form-auth-small" method="POST" action="{{route('registers')}}">
                                @csrf
                                <div class="form-group">
                                    <label for="signup-email" class="control-label sr-only">First Name</label>
                                    <input name="firstName" type="text" class="form-control" id="signup-email" placeholder="First Name">
                                </div>
                                <div class="form-group">
                                    <label for="signup-email" class="control-label sr-only">Last Name</label>
                                    <input name="lastName" type="text" class="form-control" id="signup-email" placeholder="Last Name">
                                </div>
                                <div class="form-group">
                                    <label for="signup-email" class="control-label sr-only">Email</label>
                                    <input name="email" type="email" class="form-control" id="signup-email" placeholder="Your email">
                                </div>
                                <div class="form-group">
                                    <label for="signup-email" class="control-label sr-only">Phone</label>
                                    <input name="phoneNum" type="text" class="form-control" id="signup-email" placeholder="Phone">
                                </div>
                                <div class="form-group">
                                    <label for="signup-password" class="control-label sr-only">Password</label>
                                    <input name="password" type="password" class="form-control" id="signup-password" placeholder="Password">
                                </div>
                                <button onclick="register()" type="submit" class="btn btn-primary btn-lg btn-block" style="background-color: #215fa6; border-color: #215fa6;">REGISTER</button>
                            </form>
                            <!--<div class="separator-linethrough"><span>OR</span></div>-->
                            <!--<button class="btn btn-signin-social"><i class="fa fa-facebook-official facebook-color"></i> Sign in with Facebook</button>-->
                            <!--<button class="btn btn-signin-social"><i class="fa fa-twitter twitter-color"></i> Sign in with Twitter</button>-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END WRAPPER -->
<div class="modal fade" id="formular">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="alert alert-block alert-danger">
                    <h4>Error !</h4>
                    <span id="error" >Email or Password incorrect!</span>
                </div>
            </div>
        </div>
    </div>
</div>


<script src="{{asset('public/assets/bundles/libscripts.bundle.js')}}"></script>
<script src="{{asset('public/assets/bundles/vendorscripts.bundle.js')}}"></script>
<script src="{{asset('public/assets/bundles/mainscripts.bundle.js')}}"></script>

<script>
    
    function register()
    {
        event.preventDefault();

        firstName=$("input[name=firstName]").val();
        lastName=$("input[name=lastName]").val();
        email=$("input[name=email]").val();
        phoneNum=$("input[name=phoneNum]").val();
     
        password=$("input[name=password]").val();
       
       
        $.ajax({
        url:"{{route('registers')}}",
        type: "post",
        data: {
            "_token": "{{ csrf_token() }}",
            "email": email,
            "password": password,
            "firstName": firstName,
            "lastName": lastName,
            "phoneNum": phoneNum,
          
        } ,
        success: function (response) {
            console.log(response);
            if(response.ResponseCode==1)
            {
                window.location.replace("https://demo.zeeshannawaz.com/antrack_backend/");
            }

           if(response.ResponseCode==0)
            {
                toastr.error(response.errors, 'Error');
                // $("#formular").modal('show');
            }
           
          
        },
        error: function(jqXHR, textStatus, errorThrown) {
           console.log(textStatus, errorThrown);
        }
    });
    }
    </script>
      <script>
      
        @if(Session::has('error'))
        toastr.options =
        {
            "closeButton" : true,
            "progressBar" : true
        }
            toastr.error("{!! session('error') !!}");
            
        @endif
      
        @if(Session::has('info'))
        toastr.options =
        {
            "closeButton" : true,
            "progressBar" : true
        }
            toastr.info("{{ session('info') }}");
            
        @endif
      </script>   
</body>
