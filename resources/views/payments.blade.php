@extends('welcome')
@section('title', 'Payments | Admin')
@section('content')

<div id="main-content">
    <div class="block-header">
        <div class="row clearfix">
            <div class="col-md-6 col-sm-12">
                <h2>Payments</h2>
            </div>
            <div class="col-md-6 col-sm-12 text-right">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#"><i class="icon-home"></i></a></li>
                    <li class="breadcrumb-item">Payments Management</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                    <div class="header d-flex justify-content-between align-items-center">
                        <h2>User Payments</h2>
                        <div class="d-flex">
                            <div class="d-flex align-items-center">
                                Paid    
                                <span class="badge badge-success mr-2" style="font-size:1rem;">
                                    {{ $paidCount }}
                                </span>
                                Unpaid
                                <span class="badge badge-danger" style="font-size:1rem;">
                                    {{ $unpaidCount }}
                                </span>
                            </div>
                            <div class="d-flex align-items-center">
                                Paid Amount    
                                <span class="badge badge-success mr-2" style="font-size:1rem;">
                                    {{ number_format($paidTotal, 2) }}
                                </span>
                                Unpaid Amount
                                <span class="badge badge-danger" style="font-size:1rem;">
                                    {{ number_format($unpaidTotal, 2) }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 text-right">
                        <form method="GET" action="{{ route('payments') }}" class="form-inline justify-content-end">
                            <input type="date" name="start_date" class="form-control mr-2" value="{{ request('start_date') }}">
                            <input type="date" name="end_date" class="form-control mr-2" value="{{ request('end_date') }}">
                            <button type="submit" class="btn btn-primary mr-2">Filter</button>
                            <a href="{{ route('payments') }}" class="btn btn-secondary">Clear</a>
                        </form>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table id="example1" class="table table-bordered table-hover js-basic-example dataTable table-custom">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Invoice No</th>
                                        <th>Employee</th>
                                        <th>Payment Method</th>
                                        <th>Amount Paid</th>
                                        <th>Status</th>
                                        <th>Paid Date</th>
                                        <th>Created Date</th>
                                        <th>Updated Date</th>
                                        <th>Action</th> <!-- NEW COLUMN ADDED -->
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data as $d)
                                    <tr>
                                        <td>{{ $d->id }}</td>
                                        <td>{{ $d->invoice_number }}</td>
                                        <td>{{ $d->employee->name ?? 'N/A' }}</td>
                                        <!-- Payment Method -->
                                        <td>{{ $d->payment_method ?? 'N/A' }}</td>
                                        <!-- Amount Paid (sub_total) -->
                                        <td>{{ number_format($d->sub_total, 2) }}</td>
                                        <!-- Status -->
                                        <td>
                                            <span class="badge @if($d->status == 'completed') badge-success @elseif($d->status == 'partial') badge-warning @else badge-danger @endif">
                                                {{ Str::ucfirst($d->status) }}
                                            </span>
                                        </td>
                                        <!-- Paid Date -->
                                        <td>{{ $d->paid_at ? $d->paid_at->format('d-m-Y') : 'N/A' }}</td>
                                        <!-- Created Date -->
                                        <td>{{ $d->created_at->format('d-m-Y') }}</td>
                                        <!-- Updated Date -->
                                        <td>{{ $d->updated_at->format('d-m-Y H:i') }}</td>
                                        <!-- Action -->
                                        <td>
                                        <button type="button" class="btn btn-sm btn-primary edit-payment-btn"
                                            data-toggle="modal"
                                            data-target="#editPaymentModal"
                                            data-id="{{ $d->id }}"
                                            data-method="{{ $d->payment_method ?? '' }}"
                                            data-status="{{ $d->status }}"
                                            data-paidat="{{ $d->paid_at?->format('Y-m-d') }}"
                                        >
                                            Edit
                                        </button>
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
    
    <!-- Edit Payment Modal -->
    <div class="modal fade" id="editPaymentModal" tabindex="-1" role="dialog" aria-labelledby="editPaymentModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form method="POST" action="{{ route('payments.update') }}">
                    @csrf
                    <input type="hidden" name="payment_id" id="edit_payment_id">

                    <div class="modal-header">
                        <h5 class="modal-title">Edit Payment</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <label>Payment Method</label>
                        <select name="payment_method" id="payment_method" class="form-control" required>
                            <option value="cash">Cash</option>
                            <option value="bank">Bank Transfer</option>
                            <option value="cheque">Cheque</option>
                        </select>

                        <label>Status</label>
                        <select name="status" id="edit_status" class="form-control" required>
                            <option value="paid">Paid</option>
                            <option value="unpaid">Unpaid</option>
                        </select>

                        <label>Paid Date</label>
                        <input type="date" name="paid_at" id="edit_paid_at" class="form-control">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update Payment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Select all edit buttons
    const editButtons = document.querySelectorAll('.edit-payment-btn');

    editButtons.forEach(btn => {
        btn.addEventListener('click', function () {
            // Fetch data attributes from the clicked button
            const id = this.dataset.id;
            const method = this.dataset.method || '';
            const status = this.dataset.status || '';
            const paidAt = this.dataset.paidat || '';

            // Populate the modal inputs
            document.getElementById('edit_payment_id').value = id;
            document.getElementById('edit_payment_method').value = method;
            document.getElementById('edit_status').value = status;
            document.getElementById('edit_paid_at').value = paidAt;
        });
    });
});
</script>

@endsection
