@extends('welcome')
@section('content')

@section('title', 'User Management | Express Ease')
<div id="main-content">
    <div class="block-header">
    </div>
    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                    <div class="header">
                        <h2>User Management</h2>
                        <ul class="header-dropdown dropdown dropdown-animated scale-left">
                            <li> <a href="javascript:void(0);" data-toggle="cardloading" data-loading-effect="pulse"><i class="icon-refresh"></i></a></li>
                            <li><a href="javascript:void(0);" class="full-screen"><i class="icon-size-fullscreen"></i></a></li>
                        </ul>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <!--<table class="table table-bordered table-hover js-basic-example dataTable table-custom">-->
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
                                        <th>Bookings</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $count = 1;
                                    ?>
                                    @foreach($data as $key=>$d)
                                    <tr>
                                        <td>{{$count++}}</td>
                                        <td>{{$d->name}}</td>
                                        <td>{{$d->contact}}</td>
                                        <td>{{$d->email}}</td>
                                        <td>{{date('d,M Y h:i:s',strtotime($d->created_at))}}</td>
                                        <td>
                                            @if($d->status==1)
                                            <span>Active</span>
                                            @else
                                            <span>Block</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($d->status==1)
                                            <a href="{{route('block_admin',['id'=>$d->id])}}" class="btn" style="background-color: #c70032; color: white;">Block</a>
                                            @else
                                            <a href="{{route('active_admin',['id'=>$d->id])}}" class="btn" style="background-color: #002E63; color: white;">Active</a>
                                            @endif
                                        </td>
                                        <td><a href="{{route('booking_data',['id'=>$d->id])}}" class="btn btn-info">View</a></td>
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

<script>
    $(document).ready(function() {
        $('#example1').DataTable({
            dom: 'Bfrtip',
            buttons: [{
                    extend: 'copy',
                    exportOptions: {
                        columns: ':not(:nth-child(8)):not(:nth-child(9))'
                    }
                },
                {
                    extend: 'csv',
                    exportOptions: {
                        columns: ':not(:nth-child(8)):not(:nth-child(9))'
                    }
                },
                {
                    extend: 'print',
                    exportOptions: {
                        columns: ':not(:nth-child(8)):not(:nth-child(9))'
                    }
                }
            ]
        });
    });
</script>
@endsection