@extends('welcome')
@section('content')

@section('title', 'Customers Management | PSO')

@section('content')
<div id="main-content">
    <div class="block-header"></div>
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
    <pre>

    </pre>
    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                    <div class="header d-flex justify-content-between pb-0 mb-0">
                        <h2>User With Transactions</h2>
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
                                            <button class="btn btn-info" onclick='seeTransactions(@json($d->transactions))'>See Transactions</button>
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
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Add Customer</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="transactionList">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModel()"  data-dismiss="modal">Close</button>
                </div>
            </div>
    </div>
</div>

<script>
    function seeTransactions(transactions) {
        const container = document.getElementById('transactionList');
        container.innerHTML = ''; // Clear old content

        if (!transactions || transactions.length === 0) {
            container.innerHTML = '<p>No transactions found.</p>';
        } else {
            let html = '<table class="table table-striped">';
            html += `
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>Mode</th>
                        <th>Date</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
            `;

            transactions.forEach(tx => {
                html += `
                    <tr>
                        <td>${tx.id}</td>
                        <td>${tx.transaction_type}</td>
                        <td>${tx.amount}</td>
                        <td>${tx.payment_mode}</td>
                        <td>${new Date(tx.transaction_date).toLocaleString()}</td>
                        <td>${tx.description}</td>
                    </tr>
                `;
            });

            html += '</tbody></table>';
            container.innerHTML = html;
        }

        // Show the modal
        $('#exampleModalCenter').modal('show');
    }
</script>



@endsection
