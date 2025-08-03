@extends('welcome')
@section('content')

    <div id="main-content">
        <div class="block-header">
            <div class="row clearfix">
                 <div class="col-md-6 col-sm-12">
                     <h2>Add Admin</h2>
                 </div>
                 <div class="col-md-6 col-sm-12 text-right">
                     <ul class="breadcrumb">
                         <li class="breadcrumb-item"><a href="{{route('homess')}}"><i class="icon-home"></i></a></li>
                         <li class="breadcrumb-item">Admin</li>
                         <li class="breadcrumb-item active">Add Admin</li>
                     </ul>
                     
                 </div>
             </div> 
         </div>
        <div class="container-fluid">

            <div class="row clearfix">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="header">
                                <h2>Add Admin</h2>
                            <ul class="header-dropdown dropdown dropdown-animated scale-left">
                                <li> <a href="javascript:void(0);" data-toggle="cardloading" data-loading-effect="pulse"><i class="icon-refresh"></i></a></li>
                                <li><a href="javascript:void(0);" class="full-screen"><i class="icon-size-fullscreen"></i></a></li>
                                <li class="dropdown">
                                    <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"></a>
                                    <ul class="dropdown-menu">
                                        <li><a href="javascript:void(0);">Action</a></li>
                                        <li><a href="javascript:void(0);">Another Action</a></li>
                                        <li><a href="javascript:void(0);">Something else</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    <form method="POST" action="{{route('add_admins')}}" enctype="multipart/form-data">
                        @csrf
                        <div class="body">
                            <div class="row">
                                <div class="col-md-6 col-lg-6">
                                    <label>First Name</label>
                                    <input name="firstName" type="text" class="form-control" placeholder="Enter First Name Here" required>
                                </div>
                                <div class="col-md-6 col-lg-6">
                                    <label>Last Name</label>
                                    <input name="lastName" type="text" class="form-control" placeholder="Enter Last name here" required>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-6 col-lg-6">
                                    <label>Email</label>
                                    <input name="email" type="text" class="form-control" placeholder="Enter Email Address Here" required>
                                </div>
                                <div class="col-md-6 col-lg-6">
                                    <label>Phone</label>
                                    <input name="phoneNum" type="text" class="form-control" placeholder="Enter Phone Here" required>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-6 col-lg-6">
                                    <label>password</label>
                                    <input name="password" type="password" class="form-control" placeholder="Enter Password Here" required>
                                </div>
                            </div>

                            <input type="submit" href="#" class="btn mt-3 mb-3 float-right" style="background-color: #002E63; color: white;" value="add_admin" />
                        </div>
                    </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
