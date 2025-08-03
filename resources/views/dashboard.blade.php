@extends('welcome')
@section('content')

<style>
    .dashboard-cards {
        color: white;
        border-radius: 8px;
        
    }

    .card-title {
        color: #00000099 !important;
        font-size: 16px !important;
        font-weight: 500;
    }

    .card-text {
        color: #000000 !important;
        font-size: 26px !important;
        font-weight: 600;
    }

    @media only screen and (max-width: 600px) {
        .responsive {
            overflow: auto;
        }
    }

    ul {
        padding-inline-start: 1px !important;
    }

    svg[aria-label="A chart."] {
        border-radius: 5px;
    }
</style>


<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

@section('title', 'Home |  MUDASSAR FILLING STATION')
<div id="main-content">
    <div class="block-header">
        <div class="row clearfix">
            <div class="col-md-6 col-sm-12">
                <h2>Dashboard</h2>
            </div>
            <div class="col-md-6 col-sm-12 text-right">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('homess')}}"><i class="icon-home"></i></a></li>
                    <li class="breadcrumb-item ">Dashboard</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-9 col-lg-9">
                <div class="row" style="row-gap: 10px">
                    
                    <div class="col-md-4 col-lg-4">
                        <div class="card shadow-lg dashboard-cards">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h5 class="card-title">All Customers</h5>

                                    </div>
                                    <div>
                                        <img src="{{asset('/public/2.png')}}" width="60" alt="">
                                    </div>
                                </div>
                                <div>
                                    <h4 class="card-text"> {{ count($users->where('usertype', 'customer')) }} </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-lg-4">
                        <div class="card shadow-lg dashboard-cards">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h5 class="card-title">All Staff</h5>
                                    </div>
                                    <div>
                                        <img src="{{asset('/public/3.png')}}" width="60" alt="">
                                    </div>
                                </div>
                                <div>
                                    <h4 class="card-text"> {{ count($users->where('usertype', 'staff')) }} </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-lg-4">
                        <div class="card shadow-lg dashboard-cards">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h5 class="card-title">Administrator</h5>
                                    </div>
                                    <div>
                                        <img src="{{asset('/public/4.png')}}" width="60" alt="">
                                    </div>
                                </div>
                                <div>
                                    <h4 class="card-text"> {{ count($users->where('usertype', 'admin')) }}  </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                   
                </div>
               
            </div>
            <div class="col-md-3 col-lg-3">
                <div class="card shadow-lg dashboard-cards ">
                    <div class="card-body">
                        <div>
                            <h2 style="font-size: 24px;color:#000000;font-weight:600;">Hi, Adnan</h2>
                            <p class="text-dark">Here is how you're doing </p>
                            <p class="mt-4 text-dark">Total Receivable</p>
                            <h2 style="font-size:24px;color:#4A006D;font-weight:600px;">PKR 10,851,151.00</h2>
                            <p class="mt-4 text-dark">Today's Sales</p>
                            <h2 style="font-size:24px;color:#4A006D;font-weight:600px;">PKR 3,400.00</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




<script>
    
</script>


@endsection