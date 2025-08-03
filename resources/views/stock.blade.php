@extends('welcome')
@section('title', 'Stocks | Admin')
@section('content')

<div id="main-content">
    <div class="block-header">
        <div class="row clearfix">
            <div class="col-md-6 col-sm-12">
                <h2>Stocks</h2>
            </div>
            <div class="col-md-6 col-sm-12 text-right">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#"><i class="icon-home"></i></a></li>
                    <li class="breadcrumb-item">Stock Management</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                    <div class="header">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addStockModal">Add New Stock</button>
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

                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover js-basic-example dataTable table-custom">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Item Code</th>
                                        <th>Item</th>
                                        <th>Supplier</th>
                                        <th>Purchase Qty</th>
                                        <th>Sale Qty</th>
                                        <th>FOC</th>
                                        <th>In Stock</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($stocks as $index => $stock)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $stock->item_code }}</td>
                                        <td>{{ $stock->item }}</td>
                                        <td>{{ $stock->supplier->name ?? 'N/A' }}</td>
                                        <td>{{ $stock->purchase_qty }}</td>
                                        <td>{{ $stock->sale_qty }}</td>
                                        <td>{{ $stock->foc }}</td>
                                        <td>{{ $stock->in_stock }}</td>
                                        <td>{{ $stock->created_at->format('Y-m-d H:i') }}</td>
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

    <!-- Add Stock Modal -->
    <div class="modal fade" id="addStockModal" tabindex="-1" role="dialog" aria-labelledby="addStockModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content" style="width:200%;padding:5%">
                <form method="POST" action="{{ route('stock.store') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Add New Stock</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <label>Item Code</label>
                        <input type="text" name="item_code" class="form-control" required>

                        <label>Item Name</label>
                        <input type="text" name="item" class="form-control" required>

                        <label>Purchase Quantity</label>
                        <input type="number" name="purchase_qty" class="form-control" step="0.01" required>

                        <label>Sale Quantity</label>
                        <input type="number" name="sale_qty" class="form-control" step="0.01" value="0">

                        <label>FOC</label>
                        <input type="number" name="foc" class="form-control" step="0.01" value="0">

                        <label>In Stock</label>
                        <input type="number" name="in_stock" class="form-control" step="0.01" required>

                        <label>Supplier (optional)</label>
                        <select name="supplier_id" class="form-control">
                            <option value="">-- Optional --</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add Stock</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
