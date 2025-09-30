@extends('welcome')
@section('content')

@section('title', 'Edit User | PSO')

@section('content')
<div id="main-content">
    <div class="block-header">
        <div class="row clearfix">
            <div class="col-md-6 col-sm-12">
                <h2>Edit User</h2>
            </div>
            <div class="col-md-6 col-sm-12 text-right">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('homess')}}"><i class="icon-home"></i></a></li>
                    <li class="breadcrumb-item">Admin</li>
                    <li class="breadcrumb-item active">Edit User</li>
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

            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
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
                    <div class="header">
                        <h2>Edit User Information</h2>
                    </div>
                    <div class="body">
                        <form method="POST" action="{{route('update_user', $user->id)}}" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 col-lg-6">
                                    <label>First Name</label>
                                    <input name="firstName" type="text" class="form-control" placeholder="Enter First Name Here" 
                                           value="{{ old('firstName', explode(' ', $user->name)[0] ?? '') }}" required>
                                </div>
                                <div class="col-md-6 col-lg-6">
                                    <label>Last Name</label>
                                    <input name="lastName" type="text" class="form-control" placeholder="Enter Last name here" 
                                           value="{{ old('lastName', explode(' ', $user->name, 2)[1] ?? '') }}" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 col-lg-6">
                                    <label>Email</label>
                                    <input name="email" type="email" class="form-control" autocomplete="off" 
                                           placeholder="Enter Email Address Here" value="{{ old('email', $user->email) }}">
                                </div>
                                <div class="col-md-6 col-lg-6">
                                    <label>Phone</label>
                                    <input class="form-control tel" type="tel" name="leyka_donor_phone" inputmode="tel" 
                                           value="{{ old('leyka_donor_phone', $user->contact) }}" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 col-lg-6">
                                    <label for="passInput">Password</label>
                                    <input name="password" type="password" id="passInput" class="form-control" 
                                           placeholder="Leave blank to keep current password" autocomplete="off">
                                    <input type="checkbox" id="showPass">&nbsp; Show Password
                                </div>
                                <div class="col-md-6">
                                    <label>Customer ID</label>
                                    <input name="customer_id" type="text" class="form-control" 
                                           placeholder="Enter Customer ID" value="{{ old('customer_id', $user->customer_id) }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label>N.T.N / S.T.R.N</label>
                                    <input name="ntn_strn" type="text" class="form-control" 
                                           placeholder="Enter NTN / STRN" value="{{ old('ntn_strn', $user->ntn_strn) }}">
                                </div>
                                <div class="col-md-6">
                                    <label>License No</label>
                                    <input name="license_no" type="text" class="form-control" 
                                           placeholder="Enter License No" value="{{ old('license_no', $user->license_no) }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Area</label>
                                    <input name="area" type="text" class="form-control" 
                                           placeholder="Area" value="{{ old('area', $user->area) }}">
                                </div>
                                <div class="col-md-6">
                                    <label>Address</label>
                                    <input name="address" type="text" class="form-control" 
                                           placeholder="Enter Address" value="{{ old('address', $user->address) }}">
                                </div>
                            </div>
                            <div class="form-group mt-3">
                                <label for="usertype">User Type</label>
                                <select name="usertype" class="form-control" required>
                                    <option value="admin" {{ old('usertype', $user->usertype) == 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="staff" {{ old('usertype', $user->usertype) == 'staff' ? 'selected' : '' }}>Staff</option>
                                    <option value="customer" {{ old('usertype', $user->usertype) == 'customer' ? 'selected' : '' }}>Customer</option>
                                    <option value="supplier" {{ old('usertype', $user->usertype) == 'supplier' ? 'selected' : '' }}>Supplier</option>
                                </select>
                            </div>
                            <div class="form-group mt-4">
                                <button type="submit" class="btn btn-primary">Update User</button>
                                <a href="{{ url()->previous() }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Show/Hide Password functionality
    document.getElementById('showPass').addEventListener('change', function() {
        const passwordInput = document.getElementById('passInput');
        if (this.checked) {
            passwordInput.type = 'text';
        } else {
            passwordInput.type = 'password';
        }
    });
</script>

@endsection
