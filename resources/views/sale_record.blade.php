@extends('welcome')
@section('title', 'Sale Reports | Admin')
@section('content')

<div id="main-content">
    <div class="block-header">
        <div class="row clearfix">
            <div class="col-md-6 col-sm-12">
                <h2>Sale Reports</h2>
            </div>
            <div class="col-md-6 col-sm-12 text-right">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#"><i class="icon-home"></i></a></li>
                    <li class="breadcrumb-item">Sales Management</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                    <div class="header">
                        <h2>Sale Reports</h2>
                        <!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addSaleModal">Add Sale Record</button> -->
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show mx-4 mt-2" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger mt-2 mx-4">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif  

                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover js-basic-example dataTable table-custom">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Item Code</th>
                                        <th>Item Name</th>
                                        <th>Pack Size</th>
                                        <th>Sale Qty</th>
                                        <th>FOC</th>
                                        <th>Sale Rate</th>
                                        <th>Amount</th>
                                        <th>Curtomer</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($saleReports as $index => $record)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $record->item_code }}</td>
                                        <td>{{ $record->item_name }}</td>
                                        <td>{{ $record->pack_size }}</td>
                                        <td>{{ $record->sale_qty }}</td>
                                        <td>{{ $record->foc }}</td>
                                        <td>{{ number_format($record->sale_rate, 2) }}</td>
                                        <td>{{ number_format($record->amount, 2) }}</td>
                                        <td>{{ $record->user->name ?? 'N/A' }}</td>
                                        <td>{{ $record->created_at->format('Y-m-d H:i') }}</td>
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

    <!-- Add Sale Record Modal -->
    <div class="modal fade" id="addSaleModal" tabindex="-1" role="dialog" aria-labelledby="addSaleModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content" style="width:200%;padding:5%">
                <form method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Add Sale Record</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <label>Item Code</label>
                        <input type="text" name="item_code" class="form-control" required>

                        <label>Item Name</label>
                        <input type="text" name="item_name" class="form-control" required>

                        <label>Pack Size</label>
                        <input type="text" name="pack_size" class="form-control">

                        <label>Sale Quantity</label>
                        <input type="number" name="sale_qty" class="form-control" required step="1">

                        <label>FOC (Free of Cost)</label>
                        <input type="number" name="foc" class="form-control" value="0" step="1">

                        <label>Sale Rate</label>
                        <input type="number" name="sale_rate" class="form-control" required step="0.01">

                        <label>Total Amount</label>
                        <input type="number" name="amount" class="form-control" required step="0.01">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add Sale</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
