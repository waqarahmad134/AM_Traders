@extends('welcome')
@section('title', 'Purchase Records | Admin')
@section('content')

<div id="main-content">
    <div class="block-header">
        <div class="row clearfix">
            <div class="col-md-6 col-sm-12">
                <h2>Purchase Records</h2>
            </div>
            <div class="col-md-6 col-sm-12 text-right">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#"><i class="icon-home"></i></a></li>
                    <li class="breadcrumb-item">Purchase Management</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                    <div class="header">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addPurchaseModal">Add Purchase Record</button>
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
                            <table id="example1" class="table table-bordered table-hover js-basic-example dataTable table-custom">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Item Name</th>
                                        <th>Pack Qty</th>
                                        <th>Purchase Rate</th>
                                        <th>Purchase Qty</th>
                                        <th>Sale Rate</th>
                                        <th>Batch Code</th>
                                        <th>Expiry</th>
                                        <th>Remarks</th>
                                        <th>Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($purchaseRecords as $index => $record)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $record->item->item_name }}</td>
                                        <td>{{ $record->pack_qty }}</td>
                                        <td>{{ $record->purchase_rate }}</td>
                                        <td>{{ $record->purchase_qty }}</td>
                                        <td>{{ $record->sale_rate }}</td>
                                        <td>{{ $record->batch_code }}</td>
                                        <td>{{ $record->expiry }}</td>
                                        <td>{{ $record->remarks }}</td>
                                        <td>{{ $record->created_at->format('Y-m-d H:i') }}</td>
                                        <td>
                                            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editPurchaseModal{{ $record->id }}">Edit</button>
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

    <!-- Add Purchase Record Modal -->
    <div class="modal fade" id="addPurchaseModal" tabindex="-1" role="dialog" aria-labelledby="addPurchaseModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content" style="width:200%;padding:5%">
                <form method="POST" action="{{ route('purchase_record.store') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Add Purchase Record</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <label for="itemInput">Select Item</label>
                        <input list="itemList" id="itemInput" name="item_display" class="form-control" placeholder="Type item code or name" required>

                        <datalist id="itemList">
                        @foreach($items as $item)
                            <option value="{{ $item->item_name }}" data-id="{{ $item->id }}">
                        @endforeach
                        </datalist>

                        <input type="hidden" name="item_id" id="itemIdField">


                        <label>Pack Quantity</label>
                        <input type="number" name="pack_qty" class="form-control" required step="0.01">

                        <label>Purchase Rate</label>
                        <input type="number" name="purchase_rate" class="form-control" required step="0.01">

                        <label>Purchase Quantity</label>
                        <input type="number" name="purchase_qty" class="form-control" required step="0.01">

                        <label>Sale Rate</label>
                        <input type="number" name="sale_rate" class="form-control" required step="0.01">

                        <label>Batch Code</label>
                        <input type="text" name="batch_code" class="form-control">

                        <label>Expiry</label>
                        <input type="date" name="expiry" class="form-control">

                        <label>Remarks</label>
                        <textarea name="remarks" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add Purchase</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@foreach($purchaseRecords as $record)
<!-- Edit Purchase Record Modal for {{ $record->item->item_name }} -->
<div class="modal fade" id="editPurchaseModal{{ $record->id }}" tabindex="-1" role="dialog" aria-labelledby="editPurchaseModalTitle{{ $record->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="width:200%;padding:5%">
            <form method="POST" action="{{ route('purchase_record.update', $record->id) }}">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="editPurchaseModalTitle{{ $record->id }}">Edit Purchase Record - {{ $record->item->item_name }}</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <label for="editItemInput{{ $record->id }}">Select Item</label>
                    <input list="editItemList{{ $record->id }}" id="editItemInput{{ $record->id }}" name="item_display" class="form-control" 
                           placeholder="Type item code or name" value="{{ $record->item->item_name }}" required>

                    <datalist id="editItemList{{ $record->id }}">
                    @foreach($items as $item)
                        <option value="{{ $item->item_name }}" data-id="{{ $item->id }}" {{ $item->id == $record->item_id ? 'selected' : '' }}>
                    @endforeach
                    </datalist>

                    <input type="hidden" name="item_id" id="editItemIdField{{ $record->id }}" value="{{ $record->item_id }}">

                    <label>Pack Quantity</label>
                    <input type="number" name="pack_qty" class="form-control" value="{{ $record->pack_qty }}" required step="0.01">

                    <label>Purchase Rate</label>
                    <input type="number" name="purchase_rate" class="form-control" value="{{ $record->purchase_rate }}" required step="0.01">

                    <label>Purchase Quantity</label>
                    <input type="number" name="purchase_qty" class="form-control" value="{{ $record->purchase_qty }}" required step="0.01">

                    <label>Sale Rate</label>
                    <input type="number" name="sale_rate" class="form-control" value="{{ $record->sale_rate }}" required step="0.01">

                    <label>Batch Code</label>
                    <input type="text" name="batch_code" class="form-control" value="{{ $record->batch_code }}">

                    <label>Expiry</label>
                    <input type="date" name="expiry" class="form-control" value="{{ $record->expiry }}">

                    <label>Remarks</label>
                    <textarea name="remarks" class="form-control" rows="2">{{ $record->remarks }}</textarea>
                    
                    <label>Date</label>
<input type="datetime-local" name="created_at" class="form-control" 
       value="{{ $record->created_at ? $record->created_at->format('Y-m-d\TH:i') : '' }}">


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update Purchase</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach


<script>
document.addEventListener('DOMContentLoaded', function () {
    // Handle add modal item selection
    const input = document.getElementById('itemInput');
    const hiddenField = document.getElementById('itemIdField');
    const datalist = document.getElementById('itemList');

    input.addEventListener('input', function () {
        const val = this.value;
        const options = datalist.options;

        for (let i = 0; i < options.length; i++) {
            if (options[i].value === val) {
                hiddenField.value = options[i].dataset.id;
                break;
            }
        }
    });

    // Handle edit modals item selection
    @foreach($purchaseRecords as $record)
    const editInput{{ $record->id }} = document.getElementById('editItemInput{{ $record->id }}');
    const editHiddenField{{ $record->id }} = document.getElementById('editItemIdField{{ $record->id }}');
    const editDatalist{{ $record->id }} = document.getElementById('editItemList{{ $record->id }}');

    if (editInput{{ $record->id }}) {
        editInput{{ $record->id }}.addEventListener('input', function () {
            const val = this.value;
            const options = editDatalist{{ $record->id }}.options;

            for (let i = 0; i < options.length; i++) {
                if (options[i].value === val) {
                    editHiddenField{{ $record->id }}.value = options[i].dataset.id;
                    break;
                }
            }
        });
    }
    @endforeach
});
</script>



@endsection
