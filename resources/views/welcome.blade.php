<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <title>@yield('title')</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta name="description" content="">
    <meta name="author" content="sigiTheme">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

    <link rel="icon" href="{{asset('/public/assets/images/user.png')}}" type="image/x-icon">
    <!-- VENDOR CSS -->
    <link rel="stylesheet" href="{{asset('/public/assets/vendor/bootstrap/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('/public/assets/vendor/font-awesome/css/font-awesome.min.css')}}">
    <link rel="stylesheet" href="{{asset('/public/assets/vendor/dropify/css/dropify.min.css')}}">
    <link rel="stylesheet" href="{{asset('/public/assets/vendor/summernote/dist/summernote.css')}}">
    <link rel="stylesheet" href="{{asset('/public/assets/vendor/jquery-datatable/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('/public/assets/vendor/bootstrap-datepicker/css/bootstrap-datepicker3.min.css')}}">

    <!-- MAIN CSS -->
    <link rel="stylesheet" href="{{asset('/public/assets/css/main.css')}}">
    <link rel="stylesheet" href="{{asset('/public/assets/css/color_skins.css')}}">
    <link rel="stylesheet" href="{{asset('/public/assets/css/switzer.css')}}">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" type="text/css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

</head>

<style>
    .theme-orange .navbar-fixed-top {
        background: #4A006D;
    }

    .sidebar-nav .metismenu>li i {
        top: 0px !important;
    }

    .Parentbuttondiv {
        display: inline-flex !important;
        gap: 20px;
    }

    .buttons-print,
    .buttons-copy,
    .buttons-csv {
        color: #00000099 !important;
        border-color: #00000099 !important;
        background: transparent !important;
        border-radius: 5px !important;
    }

    .btn-alignment {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .iti {
        display: block !important;
    }

    hr {
        margin-top: 1rem !important;
        margin-bottom: 1rem !important;
        border-top: 1px solid white !important;
        width: 100% !important;
    }
</style>


<body class="theme-orange">
    <!-- Page Loader -->
    <div class="page-loader-wrapper">
        <div class="loader">
            <div class="m-t-30">
                <img src="{{asset('/public/assets/images/user.png')}}" alt="Logo" width="100">
            </div>
        </div>
    </div>
    <!-- Overlay For Sidebars -->
    <div class="overlay"></div>

    <div id="wrapper">
        <nav class="navbar navbar-fixed-top">
            <div class="container-fluid">
                <div class="navbar-left">
                    <div class="navbar-btn">
                        <a href="#"><img src="{{asset('/public/assets/images/user.png')}}" alt="Antrak Logo" class="img-fluid logo"></a>
                        <button type="button" class="btn-toggle-offcanvas"><i class="lnr lnr-menu fa fa-bars"></i></button>
                    </div>
                    <a href="javascript:void(0);" class="icon-menu btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a>

                </div>
                <div class="navbar-right">
                    <div id="navbar-menu">
                        <ul class="nav navbar-nav">
                            <!-- <li><a href="javascript:void();" class="img-fluid mr-4"><img src="{{asset('/public/public/assets/images/notification.png')}}" alt="avatar" width="30"></a></li> -->
                            <li><a href="{{ route('logout') }}" class="img-fluid" style="color:#FFFFFF99;"><img src="{{asset('/public/assets/images/avatar.png')}}" alt="avatar" width="30">&nbsp; Logout</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>

        <div id="left-sidebar" class="sidebar">
            <div class="sidebar-scroll">
                <div class="user-account">
                    <div class="" style="cursor:pointer;">
                        <img class="img-fluid" src="{{asset('/public/assets/images/user.png')}}" alt="Logo" width="100">
                    </div>
                </div>
                <nav id="left-sidebar-nav" class="sidebar-nav">
                    <ul id="main-menu" class="metismenu">
                        <li class="@if(\Request::route()->getName() == 'homess') active  @endif">
                            <a href="{{route('homess')}}">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none">
                                    <path d="M21 21H13V15H21V21ZM11 21H3V11H11V21ZM21 13H13V3H21V13ZM11 9H3V3H11V9Z" fill="white" fill-opacity="0.6" />
                                </svg>
                                <span>Dashboard / Home</span>
                            </a>
                        </li>

                        <li class="@if(\Request::route()->getName() == 'create_invoice') active  @endif">
                            <a href="{{route('create_invoice')}}">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none">
                                    <path d="M21 21H13V15H21V21ZM11 21H3V11H11V21ZM21 13H13V3H21V13ZM11 9H3V3H11V9Z" fill="white" fill-opacity="0.6" />
                                </svg>
                                <span>Invoice</span>
                            </a>
                        </li>
                        
                        
                        <hr>
                        
                        <li>
                            <h5 class="text-white p-0 m-0">
                                <strong>User Management</strong>
                            </h5>
                        </li>
                        <hr>
                       
                        <!-- <li class="@if(\Request::route()->getName() == 'customers' || \Request::route()->getName() == 'customers' ) active @endif">
                            <a href="#forms" class="has-arrow">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 10.6534C13.7526 10.6534 15.1733 9.25054 15.1733 7.52005C15.1733 5.78956 13.7526 4.38672 12 4.38672C10.2474 4.38672 8.82666 5.78956 8.82666 7.52005C8.82666 9.25054 10.2474 10.6534 12 10.6534Z" fill="white" fill-opacity="0.6" />
                                    <path d="M7.18684 7.8333H7.50684V7.54664C7.50879 6.71304 7.74396 5.89661 8.18577 5.18972C8.62758 4.48282 9.25839 3.91367 10.0068 3.54664C9.78527 3.03635 9.42962 2.59559 8.97767 2.2712C8.52571 1.9468 7.99434 1.75087 7.43998 1.70421C6.88561 1.65756 6.32897 1.76192 5.82915 2.00622C5.32934 2.25052 4.90503 2.62563 4.60128 3.09171C4.29753 3.55779 4.1257 4.09744 4.10402 4.65334C4.08234 5.20924 4.21163 5.76063 4.47816 6.24895C4.74468 6.73727 5.1385 7.14429 5.61776 7.42677C6.09703 7.70925 6.64386 7.85665 7.20018 7.8533L7.18684 7.8333ZM16.5068 7.51997V7.80664H16.8268C17.3757 7.80113 17.913 7.64775 18.3821 7.36263C18.8512 7.07752 19.2347 6.67122 19.4923 6.18651C19.75 5.70179 19.8722 5.15658 19.8461 4.60827C19.82 4.05996 19.6465 3.52883 19.344 3.07078C19.0415 2.61273 18.6211 2.2447 18.1271 2.00544C17.633 1.76617 17.0836 1.66452 16.5367 1.71117C15.9897 1.75783 15.4655 1.95107 15.0191 2.27056C14.5727 2.59005 14.2207 3.02397 14.0002 3.52664C14.7489 3.89223 15.3804 4.45995 15.8233 5.16562C16.2663 5.8713 16.503 6.6868 16.5068 7.51997ZM14.8602 10.9666C16.1857 11.229 17.4673 11.6779 18.6668 12.3C18.8358 12.3928 18.9889 12.5121 19.1202 12.6533H22.6668V10.3733C22.6675 10.2859 22.6442 10.1999 22.5996 10.1247C22.5549 10.0495 22.4906 9.98793 22.4135 9.94664C20.6856 9.04285 18.7635 8.57376 16.8135 8.57997H16.3735C16.1425 9.51912 15.6111 10.3571 14.8602 10.9666ZM4.35351 13.9466C4.35229 13.6109 4.44297 13.2812 4.61571 12.9933C4.78845 12.7054 5.03668 12.4702 5.33351 12.3133C6.53303 11.6912 7.81467 11.2423 9.14018 10.98C8.39289 10.3757 7.86185 9.54519 7.62684 8.61331H7.18684C5.23686 8.6071 3.31474 9.07618 1.58684 9.97997C1.50975 10.0213 1.44541 10.0828 1.40077 10.158C1.35612 10.2332 1.33286 10.3192 1.33351 10.4066V14.6666H4.35351V13.9466ZM14.3068 17.7933H18.2802V18.7266H14.3068V17.7933Z" fill="white" fill-opacity="0.6" />
                                    <path d="M21.8737 14.1734H17.2937V13.5068C17.2937 13.33 17.2235 13.1604 17.0984 13.0354C16.9734 12.9103 16.8038 12.8401 16.627 12.8401C16.4502 12.8401 16.2806 12.9103 16.1556 13.0354C16.0306 13.1604 15.9604 13.33 15.9604 13.5068V14.1734H14.667V12.2868C13.7899 12.1026 12.8966 12.0066 12.0004 12.0001C9.8962 11.9913 7.8221 12.4996 5.96036 13.4801C5.87743 13.5231 5.80802 13.5882 5.75978 13.6682C5.71154 13.7482 5.68636 13.84 5.68702 13.9334V17.6734H10.427V21.7334C10.427 21.9103 10.4973 22.0798 10.6223 22.2048C10.7473 22.3299 10.9169 22.4001 11.0937 22.4001H21.8737C22.0505 22.4001 22.2201 22.3299 22.3451 22.2048C22.4701 22.0798 22.5404 21.9103 22.5404 21.7334V14.8401C22.5404 14.6633 22.4701 14.4937 22.3451 14.3687C22.2201 14.2437 22.0505 14.1734 21.8737 14.1734ZM21.207 21.0801H11.7604V15.5068H15.9604V16.1134C15.9604 16.2903 16.0306 16.4598 16.1556 16.5848C16.2806 16.7099 16.4502 16.7801 16.627 16.7801C16.8038 16.7801 16.9734 16.7099 17.0984 16.5848C17.2235 16.4598 17.2937 16.2903 17.2937 16.1134V15.5068H21.207V21.0801Z" fill="white" fill-opacity="0.6" />
                                </svg>
                                <span>Customers</span></a>
                            <ul>
                                <li class="@if(\Request::route()->getName() == 'customers') active  @endif"><a href="{{route('customers')}}">Customer Listings</a></li>
                                <li class="@if(\Request::route()->getName() == 'customers_balance') active  @endif"><a href="{{route('customers_balance')}}">Customer Balances</a></li>
                            </ul>
                        </li> -->

                        <li class="@if(\Request::route()->getName() == 'customers') active  @endif">
                            <a href="{{route('customers')}}">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none">
                                    <path d="M17 16.9999C17.4167 16.9999 17.771 16.8539 18.063 16.5619C18.355 16.2699 18.5007 15.9159 18.5 15.4999C18.5 15.0832 18.354 14.7289 18.062 14.4369C17.77 14.1449 17.416 13.9992 17 13.9999C16.5833 13.9999 16.229 14.1459 15.937 14.4379C15.645 14.7299 15.4993 15.0839 15.5 15.4999C15.5 15.9166 15.646 16.2709 15.938 16.5629C16.23 16.8549 16.584 17.0006 17 16.9999ZM17 19.9999C17.5167 19.9999 17.9917 19.8789 18.425 19.6369C18.8583 19.3949 19.2083 19.0742 19.475 18.6749C19.1083 18.4582 18.7167 18.2916 18.3 18.1749C17.8833 18.0582 17.45 17.9999 17 17.9999C16.55 17.9999 16.1167 18.0582 15.7 18.1749C15.2833 18.2916 14.8917 18.4582 14.525 18.6749C14.7917 19.0749 15.1417 19.3959 15.575 19.6379C16.0083 19.8799 16.4833 20.0006 17 19.9999ZM17 21.9999C15.6167 21.9999 14.4373 21.5122 13.462 20.5369C12.4867 19.5616 11.9993 18.3826 12 16.9999C12 15.6166 12.4877 14.4372 13.463 13.4619C14.4383 12.4866 15.6173 11.9992 17 11.9999C18.3833 11.9999 19.5627 12.4876 20.538 13.4629C21.5133 14.4382 22.0007 15.6172 22 16.9999C22 18.3832 21.5123 19.5626 20.537 20.5379C19.5617 21.5132 18.3827 22.0006 17 21.9999ZM12 21.9999C9.68333 21.4166 7.77067 20.0872 6.262 18.0119C4.75333 15.9366 3.99933 13.6326 4 11.0999V6.3749C4 5.95824 4.121 5.58324 4.363 5.2499C4.605 4.91657 4.91733 4.6749 5.3 4.5249L11.3 2.2749C11.5333 2.19157 11.7667 2.1499 12 2.1499C12.2333 2.1499 12.4667 2.19157 12.7 2.2749L18.7 4.5249C19.0833 4.6749 19.396 4.91657 19.638 5.2499C19.88 5.58324 20.0007 5.95824 20 6.3749V10.6749C19.5667 10.4582 19.079 10.2916 18.537 10.1749C17.995 10.0582 17.4827 9.9999 17 9.9999C15.0667 9.9999 13.4167 10.6832 12.05 12.0499C10.6833 13.4166 10 15.0666 10 16.9999C10 18.0332 10.196 18.9666 10.588 19.7999C10.98 20.6332 11.4757 21.3582 12.075 21.9749C12.0583 21.9749 12.046 21.9792 12.038 21.9879C12.03 21.9966 12.0173 22.0006 12 21.9999Z" fill="white" fill-opacity="0.6" />
                                </svg>
                                <span>Customers</span></a>
                        </li>
  
                        <li class="@if(\Request::route()->getName() == 'suppliers') active  @endif">
                            <a href="{{route('suppliers')}}">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none">
                                    <path d="M17 16.9999C17.4167 16.9999 17.771 16.8539 18.063 16.5619C18.355 16.2699 18.5007 15.9159 18.5 15.4999C18.5 15.0832 18.354 14.7289 18.062 14.4369C17.77 14.1449 17.416 13.9992 17 13.9999C16.5833 13.9999 16.229 14.1459 15.937 14.4379C15.645 14.7299 15.4993 15.0839 15.5 15.4999C15.5 15.9166 15.646 16.2709 15.938 16.5629C16.23 16.8549 16.584 17.0006 17 16.9999ZM17 19.9999C17.5167 19.9999 17.9917 19.8789 18.425 19.6369C18.8583 19.3949 19.2083 19.0742 19.475 18.6749C19.1083 18.4582 18.7167 18.2916 18.3 18.1749C17.8833 18.0582 17.45 17.9999 17 17.9999C16.55 17.9999 16.1167 18.0582 15.7 18.1749C15.2833 18.2916 14.8917 18.4582 14.525 18.6749C14.7917 19.0749 15.1417 19.3959 15.575 19.6379C16.0083 19.8799 16.4833 20.0006 17 19.9999ZM17 21.9999C15.6167 21.9999 14.4373 21.5122 13.462 20.5369C12.4867 19.5616 11.9993 18.3826 12 16.9999C12 15.6166 12.4877 14.4372 13.463 13.4619C14.4383 12.4866 15.6173 11.9992 17 11.9999C18.3833 11.9999 19.5627 12.4876 20.538 13.4629C21.5133 14.4382 22.0007 15.6172 22 16.9999C22 18.3832 21.5123 19.5626 20.537 20.5379C19.5617 21.5132 18.3827 22.0006 17 21.9999ZM12 21.9999C9.68333 21.4166 7.77067 20.0872 6.262 18.0119C4.75333 15.9366 3.99933 13.6326 4 11.0999V6.3749C4 5.95824 4.121 5.58324 4.363 5.2499C4.605 4.91657 4.91733 4.6749 5.3 4.5249L11.3 2.2749C11.5333 2.19157 11.7667 2.1499 12 2.1499C12.2333 2.1499 12.4667 2.19157 12.7 2.2749L18.7 4.5249C19.0833 4.6749 19.396 4.91657 19.638 5.2499C19.88 5.58324 20.0007 5.95824 20 6.3749V10.6749C19.5667 10.4582 19.079 10.2916 18.537 10.1749C17.995 10.0582 17.4827 9.9999 17 9.9999C15.0667 9.9999 13.4167 10.6832 12.05 12.0499C10.6833 13.4166 10 15.0666 10 16.9999C10 18.0332 10.196 18.9666 10.588 19.7999C10.98 20.6332 11.4757 21.3582 12.075 21.9749C12.0583 21.9749 12.046 21.9792 12.038 21.9879C12.03 21.9966 12.0173 22.0006 12 21.9999Z" fill="white" fill-opacity="0.6" />
                                </svg>
                                <span>Suppliers</span></a>
                        </li>
                        <li class="@if(\Request::route()->getName() == 'list_admin') active  @endif">
                            <a href="{{route('list_admin')}}">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none">
                                    <path d="M17 16.9999C17.4167 16.9999 17.771 16.8539 18.063 16.5619C18.355 16.2699 18.5007 15.9159 18.5 15.4999C18.5 15.0832 18.354 14.7289 18.062 14.4369C17.77 14.1449 17.416 13.9992 17 13.9999C16.5833 13.9999 16.229 14.1459 15.937 14.4379C15.645 14.7299 15.4993 15.0839 15.5 15.4999C15.5 15.9166 15.646 16.2709 15.938 16.5629C16.23 16.8549 16.584 17.0006 17 16.9999ZM17 19.9999C17.5167 19.9999 17.9917 19.8789 18.425 19.6369C18.8583 19.3949 19.2083 19.0742 19.475 18.6749C19.1083 18.4582 18.7167 18.2916 18.3 18.1749C17.8833 18.0582 17.45 17.9999 17 17.9999C16.55 17.9999 16.1167 18.0582 15.7 18.1749C15.2833 18.2916 14.8917 18.4582 14.525 18.6749C14.7917 19.0749 15.1417 19.3959 15.575 19.6379C16.0083 19.8799 16.4833 20.0006 17 19.9999ZM17 21.9999C15.6167 21.9999 14.4373 21.5122 13.462 20.5369C12.4867 19.5616 11.9993 18.3826 12 16.9999C12 15.6166 12.4877 14.4372 13.463 13.4619C14.4383 12.4866 15.6173 11.9992 17 11.9999C18.3833 11.9999 19.5627 12.4876 20.538 13.4629C21.5133 14.4382 22.0007 15.6172 22 16.9999C22 18.3832 21.5123 19.5626 20.537 20.5379C19.5617 21.5132 18.3827 22.0006 17 21.9999ZM12 21.9999C9.68333 21.4166 7.77067 20.0872 6.262 18.0119C4.75333 15.9366 3.99933 13.6326 4 11.0999V6.3749C4 5.95824 4.121 5.58324 4.363 5.2499C4.605 4.91657 4.91733 4.6749 5.3 4.5249L11.3 2.2749C11.5333 2.19157 11.7667 2.1499 12 2.1499C12.2333 2.1499 12.4667 2.19157 12.7 2.2749L18.7 4.5249C19.0833 4.6749 19.396 4.91657 19.638 5.2499C19.88 5.58324 20.0007 5.95824 20 6.3749V10.6749C19.5667 10.4582 19.079 10.2916 18.537 10.1749C17.995 10.0582 17.4827 9.9999 17 9.9999C15.0667 9.9999 13.4167 10.6832 12.05 12.0499C10.6833 13.4166 10 15.0666 10 16.9999C10 18.0332 10.196 18.9666 10.588 19.7999C10.98 20.6332 11.4757 21.3582 12.075 21.9749C12.0583 21.9749 12.046 21.9792 12.038 21.9879C12.03 21.9966 12.0173 22.0006 12 21.9999Z" fill="white" fill-opacity="0.6" />
                                </svg>
                                <span>Admins</span></a>
                        </li>                       
                      
                        <hr>                        
                        <li>
                            <h5 class="text-white p-0 m-0">
                                <strong>Stock Management</strong>
                            </h5>
                        </li>
                        <hr>
                        <li class="@if(\Request::route()->getName() == 'stock' || \Request::route()->getName() == 'stock' ) ) active @endif">
                            <a href="#forms" class="has-arrow">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 21.251C6.477 21.251 2 16.774 2 11.251C2 5.72798 6.477 1.25098 12 1.25098C17.523 1.25098 22 5.72798 22 11.251C22 16.774 17.523 21.251 12 21.251ZM8.5 13.251V15.251H11V17.251H13V15.251H14C14.663 15.251 15.2989 14.9876 15.7678 14.5187C16.2366 14.0499 16.5 13.414 16.5 12.751C16.5 12.0879 16.2366 11.4521 15.7678 10.9832C15.2989 10.5144 14.663 10.251 14 10.251H10C9.86739 10.251 9.74021 10.1983 9.64645 10.1045C9.55268 10.0108 9.5 9.88358 9.5 9.75098C9.5 9.61837 9.55268 9.49119 9.64645 9.39742C9.74021 9.30365 9.86739 9.25098 10 9.25098H15.5V7.25098H13V5.25098H11V7.25098H10C9.33696 7.25098 8.70107 7.51437 8.23223 7.98321C7.76339 8.45205 7.5 9.08793 7.5 9.75098C7.5 10.414 7.76339 11.0499 8.23223 11.5187C8.70107 11.9876 9.33696 12.251 10 12.251H14C14.1326 12.251 14.2598 12.3037 14.3536 12.3974C14.4473 12.4912 14.5 12.6184 14.5 12.751C14.5 12.8836 14.4473 13.0108 14.3536 13.1045C14.2598 13.1983 14.1326 13.251 14 13.251H8.5Z" fill="white" fill-opacity="0.6" />
                                </svg>
                                <span>Stock</span></a>
                            <ul>
                                <li class="@if(\Request::route()->getName() == 'purchase_record' ) active @endif"><a href="{{ route('purchase_record.index') }}">Purchase Record</a></li>
                                <li class="@if(\Request::route()->getName() == 'stock' ) active @endif"><a href="{{ route('stock.index') }}">Stock Listing</a></li>
                            </ul>
                        </li>
                        <hr>
                        <li>
                            <h5 class="text-white p-0 m-0">
                                <strong>Settings</strong>
                            </h5>
                        </li>
                        <hr>

                        <li>
                            <a href="{{ route('profile') }}">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12.0121 9.90352C11.3113 9.90352 10.6551 10.1754 10.1582 10.6723C9.66366 11.1691 9.38944 11.8254 9.38944 12.5262C9.38944 13.227 9.66366 13.8832 10.1582 14.3801C10.6551 14.8746 11.3113 15.1488 12.0121 15.1488C12.7129 15.1488 13.3691 14.8746 13.866 14.3801C14.3605 13.8832 14.6348 13.227 14.6348 12.5262C14.6348 11.8254 14.3605 11.1691 13.866 10.6723C13.6233 10.4277 13.3345 10.2338 13.0162 10.1018C12.6979 9.96988 12.3566 9.90246 12.0121 9.90352ZM21.6754 15.423L20.1426 14.1129C20.2152 13.6676 20.2527 13.2129 20.2527 12.7605C20.2527 12.3082 20.2152 11.8512 20.1426 11.4082L21.6754 10.098C21.7912 9.99892 21.874 9.8669 21.913 9.71953C21.9519 9.57216 21.9451 9.41643 21.8933 9.27305L21.8723 9.21211C21.4504 8.03248 20.8183 6.93904 20.0066 5.98477L19.9644 5.93555C19.8659 5.81965 19.7345 5.73634 19.5877 5.6966C19.4408 5.65685 19.2853 5.66253 19.1418 5.71289L17.2387 6.39023C16.5355 5.81367 15.7527 5.35898 14.9043 5.04258L14.5363 3.05273C14.5086 2.90283 14.4358 2.76493 14.3278 2.65734C14.2198 2.54975 14.0816 2.47757 13.9316 2.45039L13.8683 2.43867C12.6496 2.21836 11.3652 2.21836 10.1465 2.43867L10.0832 2.45039C9.93318 2.47757 9.79499 2.54975 9.68699 2.65734C9.57898 2.76493 9.50626 2.90283 9.4785 3.05273L9.10819 5.05195C8.26779 5.37088 7.4849 5.82447 6.79022 6.39492L4.87303 5.71289C4.72952 5.66213 4.57395 5.65624 4.42701 5.69602C4.28006 5.73579 4.1487 5.81933 4.05038 5.93555L4.00819 5.98477C3.19794 6.94006 2.56601 8.03322 2.14257 9.21211L2.12147 9.27305C2.016 9.56602 2.10272 9.89414 2.33944 10.098L3.891 11.4223C3.81835 11.8629 3.78319 12.3129 3.78319 12.7582C3.78319 13.2082 3.81835 13.6582 3.891 14.0941L2.34413 15.4184C2.22834 15.5175 2.14546 15.6495 2.10653 15.7969C2.06759 15.9442 2.07444 16.1 2.12616 16.2434L2.14725 16.3043C2.57147 17.4832 3.19725 18.573 4.01288 19.5316L4.05506 19.5809C4.15363 19.6968 4.285 19.7801 4.43185 19.8198C4.57871 19.8596 4.73416 19.8539 4.87772 19.8035L6.79491 19.1215C7.49335 19.6957 8.27147 20.1504 9.11288 20.4645L9.48319 22.4637C9.51095 22.6136 9.58367 22.7515 9.69167 22.8591C9.79968 22.9667 9.93787 23.0388 10.0879 23.066L10.1512 23.0777C11.3819 23.2992 12.6423 23.2992 13.873 23.0777L13.9363 23.066C14.0863 23.0388 14.2245 22.9667 14.3325 22.8591C14.4405 22.7515 14.5132 22.6136 14.541 22.4637L14.909 20.4738C15.7574 20.1551 16.5402 19.7027 17.2433 19.1262L19.1465 19.8035C19.29 19.8543 19.4456 19.8602 19.5925 19.8204C19.7394 19.7806 19.8708 19.6971 19.9691 19.5809L20.0113 19.5316C20.8269 18.5684 21.4527 17.4832 21.8769 16.3043L21.898 16.2434C21.9988 15.9527 21.9121 15.627 21.6754 15.423ZM12.0121 16.6465C9.73631 16.6465 7.89178 14.802 7.89178 12.5262C7.89178 10.2504 9.73631 8.40586 12.0121 8.40586C14.2879 8.40586 16.1324 10.2504 16.1324 12.5262C16.1324 14.802 14.2879 16.6465 12.0121 16.6465Z" fill="white" fill-opacity="0.6" />
                                </svg><span>Edit Profile</span>
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('logout') }}">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12.0121 9.90352C11.3113 9.90352 10.6551 10.1754 10.1582 10.6723C9.66366 11.1691 9.38944 11.8254 9.38944 12.5262C9.38944 13.227 9.66366 13.8832 10.1582 14.3801C10.6551 14.8746 11.3113 15.1488 12.0121 15.1488C12.7129 15.1488 13.3691 14.8746 13.866 14.3801C14.3605 13.8832 14.6348 13.227 14.6348 12.5262C14.6348 11.8254 14.3605 11.1691 13.866 10.6723C13.6233 10.4277 13.3345 10.2338 13.0162 10.1018C12.6979 9.96988 12.3566 9.90246 12.0121 9.90352ZM21.6754 15.423L20.1426 14.1129C20.2152 13.6676 20.2527 13.2129 20.2527 12.7605C20.2527 12.3082 20.2152 11.8512 20.1426 11.4082L21.6754 10.098C21.7912 9.99892 21.874 9.8669 21.913 9.71953C21.9519 9.57216 21.9451 9.41643 21.8933 9.27305L21.8723 9.21211C21.4504 8.03248 20.8183 6.93904 20.0066 5.98477L19.9644 5.93555C19.8659 5.81965 19.7345 5.73634 19.5877 5.6966C19.4408 5.65685 19.2853 5.66253 19.1418 5.71289L17.2387 6.39023C16.5355 5.81367 15.7527 5.35898 14.9043 5.04258L14.5363 3.05273C14.5086 2.90283 14.4358 2.76493 14.3278 2.65734C14.2198 2.54975 14.0816 2.47757 13.9316 2.45039L13.8683 2.43867C12.6496 2.21836 11.3652 2.21836 10.1465 2.43867L10.0832 2.45039C9.93318 2.47757 9.79499 2.54975 9.68699 2.65734C9.57898 2.76493 9.50626 2.90283 9.4785 3.05273L9.10819 5.05195C8.26779 5.37088 7.4849 5.82447 6.79022 6.39492L4.87303 5.71289C4.72952 5.66213 4.57395 5.65624 4.42701 5.69602C4.28006 5.73579 4.1487 5.81933 4.05038 5.93555L4.00819 5.98477C3.19794 6.94006 2.56601 8.03322 2.14257 9.21211L2.12147 9.27305C2.016 9.56602 2.10272 9.89414 2.33944 10.098L3.891 11.4223C3.81835 11.8629 3.78319 12.3129 3.78319 12.7582C3.78319 13.2082 3.81835 13.6582 3.891 14.0941L2.34413 15.4184C2.22834 15.5175 2.14546 15.6495 2.10653 15.7969C2.06759 15.9442 2.07444 16.1 2.12616 16.2434L2.14725 16.3043C2.57147 17.4832 3.19725 18.573 4.01288 19.5316L4.05506 19.5809C4.15363 19.6968 4.285 19.7801 4.43185 19.8198C4.57871 19.8596 4.73416 19.8539 4.87772 19.8035L6.79491 19.1215C7.49335 19.6957 8.27147 20.1504 9.11288 20.4645L9.48319 22.4637C9.51095 22.6136 9.58367 22.7515 9.69167 22.8591C9.79968 22.9667 9.93787 23.0388 10.0879 23.066L10.1512 23.0777C11.3819 23.2992 12.6423 23.2992 13.873 23.0777L13.9363 23.066C14.0863 23.0388 14.2245 22.9667 14.3325 22.8591C14.4405 22.7515 14.5132 22.6136 14.541 22.4637L14.909 20.4738C15.7574 20.1551 16.5402 19.7027 17.2433 19.1262L19.1465 19.8035C19.29 19.8543 19.4456 19.8602 19.5925 19.8204C19.7394 19.7806 19.8708 19.6971 19.9691 19.5809L20.0113 19.5316C20.8269 18.5684 21.4527 17.4832 21.8769 16.3043L21.898 16.2434C21.9988 15.9527 21.9121 15.627 21.6754 15.423ZM12.0121 16.6465C9.73631 16.6465 7.89178 14.802 7.89178 12.5262C7.89178 10.2504 9.73631 8.40586 12.0121 8.40586C14.2879 8.40586 16.1324 10.2504 16.1324 12.5262C16.1324 14.802 14.2879 16.6465 12.0121 16.6465Z" fill="white" fill-opacity="0.6" />
                                </svg><span>Logout</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
        @yield('content')
    </div>
    
    <script>

        $('input[type=number]').on('wheel', function (e) {
            e.preventDefault();
        });
        // Toast notification setup
        @if(Session::has('message'))
        $(document).ready(function() {
            toastr.options = {
                "closeButton": true,
                "progressBar": true
            };
            toastr.success("{{ session('message') }}");
        });
        @endif

        @if(Session::has('error'))
        $(document).ready(function() {
            toastr.options = {
                "closeButton": true,
                "progressBar": true
            };
            toastr.error("{!! session('error') !!}");
        });
        @endif

        @if(Session::has('info'))
        $(document).ready(function() {
            toastr.options = {
                "closeButton": true,
                "progressBar": true
            };
            toastr.info("{{ session('info') }}");
        });
        @endif

        @if(Session::has('warning'))
        $(document).ready(function() {
            toastr.options = {
                "closeButton": true,
                "progressBar": true
            };
            toastr.warning("{{ session('warning') }}");
        });
        @endif


        window.addEventListener('DOMContentLoaded', function() {
            $(window).on("load", function() {
                $.holdReady(false);
                var copyButton = document.querySelector('.buttons-copy');
                var Parentbuttondiv = document.querySelector('.dt-buttons');
                Parentbuttondiv.classList.add('Parentbuttondiv');
                if (copyButton) {
                    addImageToButton(copyButton, "{{asset('/public/copy.png')}}");
                    copyButton.classList.add('btn-alignment');
                }

                // Find the CSV button element
                var csvButton = document.querySelector('.buttons-csv');
                if (csvButton) {
                    addImageToButton(csvButton, "{{asset('/public/download.png')}}");
                    csvButton.classList.add('btn-alignment');
                }

                // Find the print button element
                var printButton = document.querySelector('.buttons-print');
                if (printButton) {
                    addImageToButton(printButton, "{{asset('/public/print.png')}}");
                    printButton.classList.add('btn-alignment');
                }
            });
        });

        function addImageToButton(button, imagePath) {
            // Create the <img> element
            var imgElement = document.createElement('img');
            imgElement.src = imagePath;
            imgElement.alt = "Logo";
            imgElement.classList.add('img-fluid', 'logo');
            imgElement.setAttribute('width', '20');

            // Prepend the <img> element to the button
            button.insertBefore(imgElement, button.firstChild);
        }

    </script>

    <!-- Javascript -->
    <script src="{{asset('/public/assets/bundles/libscripts.bundle.js')}}"></script>
    <script src="{{asset('/public/assets/bundles/vendorscripts.bundle.js')}}"></script>
    <script src="{{asset('/public/assets/bundles/mainscripts.bundle.js')}}"></script>
    <!-- <script src="{{asset('/public/assets/js/index.js')}}"></script> -->

    <!--Data table-->
    <script src="{{asset('/public/assets/bundles/datatablescripts.bundle.js')}}"></script>
    <script src="{{asset('/public/assets/js/pages/tables/jquery-datatable.js')}}"></script>
    <script src="{{asset('/public/assets/vendor/jquery-datatable/buttons/dataTables.buttons.min.js')}}"></script>
    <script src="{{asset('/public/assets/vendor/jquery-datatable/buttons/buttons.bootstrap4.min.js')}}"></script>
    <script src="{{asset('/public/assets/vendor/jquery-datatable/buttons/buttons.colVis.min.js')}}"></script>
    <script src="{{asset('/public/assets/vendor/jquery-datatable/buttons/buttons.html5.min.js')}}"></script>
    <script src="{{asset('/public/assets/vendor/jquery-datatable/buttons/buttons.print.min.js')}}"></script>
    <!--Summer Notes -->
    <script src="{{asset('/public/assets/vendor/summernote/dist/summernote.js')}}"></script>

    <!--Dropify Image Uploader-->
    <script src="{{asset('/public/assets/vendor/dropify/js/dropify.min.js')}}"></script>
    <script src="{{asset('/public/assets/js/pages/forms/dropify.js')}}"></script>

    <!--Date Picker -->
    <script src="{{asset('/public/assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>

</body>

</html>