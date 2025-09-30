@extends('welcome')
@section('content')

@section('title', 'User Management | Express Ease')

@section('content')
<div id="main-content">
<div class="block-header">
        <div class="row clearfix">
            <div class="col-md-6 col-sm-12">
                <h2>List Admin</h2>
            </div>
            <div class="col-md-6 col-sm-12 text-right">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('homess')}}"><i class="icon-home"></i></a></li>
                    <li class="breadcrumb-item">Admin</li>
                    <li class="breadcrumb-item active">List Admin</li>
                    <li class="breadcrumb-item active"><button class="btn btn-styling1 text-capitalize mt-2" onClick="printData('DivIdToPrint');">Print</button></li>
                </ul>

            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row clearfix">
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
            </div>
            @endif

            @if($errors->any())
            <div class="alert alert-danger mt-2">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif  
        </div>
    </div>
    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                    <div class="header d-flex justify-content-between pb-0 mb-0">
                        <h2>Supplier Mangement</h2>
                        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#exampleModalCenter">Add New Supplier</button>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table id="example1" class="table table-bordered table-hover js-basic-example dataTable table-custom">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Phone</th>
                                        <th>Email</th>
                                        <th>Created At</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $count = 1; @endphp
                                    @foreach($data as $key => $d)
                                    <tr>
                                        <td>{{ $count++ }}</td>
                                        <td>{{ $d->name }}</td>
                                        <td>{{ $d->contact }}</td>
                                        <td>{{ $d->email }}</td>
                                        <td>{{ date('d,M Y h:i:s', strtotime($d->created_at)) }}</td>
                                        <td>
                                            @if($d->status == "active")
                                                <span>Active</span>
                                            @else
                                                <span>Block</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('edit_user', ['id' => $d->id]) }}" class="btn btn-primary btn-sm">Edit</a>
                                            @if($d->status == "active")
                                                <a href="{{ route('update_status', ['id' => $d->id]) }}" class="btn btn-danger btn-sm">Block</a>
                                            @else
                                                <a href="{{ route('update_status', ['id' => $d->id]) }}" class="btn btn-success btn-sm">Active</a>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form method="POST" id="myForm" action="{{route('add_user')}}" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Add Supplier</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="erur" class="alert alert-danger" style="display:none;"></div>
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
                    <div class="row">
                        <div class="col-md-6 col-lg-6">
                            <label>Email</label>
                            <input name="email" type="email" class="form-control" autocomplete="off" placeholder="Enter Email Address Here" required>
                        </div>
                        <div class="col-md-6 col-lg-6">
                            <label>Phone</label>

                            <input class="form-control tel" type="tel" name="leyka_donor_phone" inputmode="tel" value="" / required>
                            <input class="form-control tel" id="countrycode" type="hidden" name="countrycode" value="" / required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-lg-6">
                            <label for="passInput">Password</label>
                            <input name="password" type="password" id="passInput" class="form-control" placeholder="Enter Password Here" required autocomplete="off">
                            <input type="checkbox" id="showPass">&nbsp; Show Password
                        </div>
                    </div>
                    <div class="form-group mt-3">
                        <label for="usertype">User Type</label>
                        <select name="usertype" class="form-control" required>
                            <option value="supplier">Supplier</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModel()"  data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add User</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
     function closeModel(){
        $('#myForm')[0].reset();
    }
   
    function validateEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }
</script>


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
    })

</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>




@endsection
