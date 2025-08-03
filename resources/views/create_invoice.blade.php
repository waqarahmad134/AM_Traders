@extends('welcome')
@section('content')
@section('title', 'Create Invoice | Admin')
<div id="main-content">
    <div class="block-header">
        <div class="row clearfix">
            <div class="col-md-6 col-sm-12">
                <h2>Create Invoice</h2>
            </div>
            <div class="col-md-6 col-sm-12 text-right">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html"><i class="icon-home"></i></a></li>
                    <li class="breadcrumb-item">Create Invoice</li>
                </ul>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                    <div class="header d-flex justify-content-between pb-0 mb-0">
                        <h2>Create Invoice</h2>
                        <div>Date : {{ \Carbon\Carbon::now()->format('Y-m-d') }}</div>
                    </div>
                    <div class="body">
                        <form action="{{ route('invoice.store') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label>Select User</label>
                                <select name="user_id" id="userSelect" class="form-control">
                                    <option value="">-- Select Existing User --</option>
                                    <option value="new_user">-- New User --</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div id="newUserFields" style="display: none;">
                                <h6>Create New User</h6>
                                <div class="row">
                                    <div class="col-4 my-2">
                                        <input type="text" name="name" class="form-control" placeholder="User Name">
                                    </div>
                                    <div class="col-4 my-2">
                                        <input type="text" name="contact" class="form-control" placeholder="03214141410">
                                    </div>
                                    <div class="col-4 my-2">
                                        <input type="text" name="ntn_strn" class="form-control" placeholder="ntn_strn">
                                    </div>
                                    <div class="col-4 my-2">
                                        <input type="text" name="license_no" class="form-control" placeholder="license_no">
                                    </div>
                                    <div class="col-8 my-2">
                                        <input type="text" name="address" class="form-control" placeholder="address">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Search Item</label>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <input list="itemsList" id="itemInput" class="form-control mr-2" placeholder="Search by item code or name">
                                    <button type="button" class="btn btn-sm btn-link" id="addNewItemToggle">+ Add New Item</button>
                                </div>
                                <datalist id="itemsList">
                                    @foreach($items as $item)
                                        <option data-code="{{ $item->item_code }}" value="{{ $item->item_code }} - {{ $item->item_name }}">
                                    @endforeach
                                </datalist>
                                <div id="newItemFields" style="display: none;">
                                    <div class="form-group">
                                        <label>Item Name</label>
                                        <input type="text" name="new_item_name" class="form-control" placeholder="Enter Item Name">
                                    </div>
                                    <div class="form-group">
                                        <label>Item Code</label>
                                        <input type="text" name="new_item_code" class="form-control" placeholder="Enter Item Code">
                                    </div>
                                </div>
                            </div>

                            <div id="itemDetails" style="display:none;">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Item Name</th>
                                            <th>Item Code</th>
                                            <th>Qty</th>
                                            <th>Amount</th>
                                            <th>FOC</th>
                                            <th>Rate</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><span id="selectedItemName"></span></td>
                                            <td><span id="selectedItemCode"></span></td>
                                            <td><input type="number" name="qty" class="form-control"></td>
                                            <td><input type="number" name="amount" class="form-control"></td>
                                            <td><input type="number" name="foc" class="form-control"></td>
                                            <td><input type="number" name="rate" class="form-control"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <button type="submit" class="btn btn-primary">Create Invoice</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.getElementById('userSelect').addEventListener('change', function() {
        const newUserFields = document.getElementById('newUserFields');
        newUserFields.style.display = this.value === "new_user" ? 'block' : 'none';
    });

    document.getElementById('itemInput').addEventListener('input', function() {
        const value = this.value.toLowerCase();
        const datalist = document.getElementById('itemsList');
        const options = datalist.options;

        for (let i = 0; i < options.length; i++) {
            if (options[i].value.toLowerCase() === value) {
                document.getElementById('selectedItemName').textContent = options[i].value;
                document.getElementById('selectedItemCode').textContent = options[i].getAttribute('data-code');
                document.getElementById('itemDetails').style.display = 'block';
                break;
            }
        }
    });
</script>

<script>
    const toggleBtn = document.getElementById('addNewItemToggle');
    const itemInput = document.getElementById('itemInput');
    const newItemFields = document.getElementById('newItemFields');

    toggleBtn.addEventListener('click', function () {
        const isAddingNew = newItemFields.style.display === 'block';

        if (isAddingNew) {
            // Switch to selecting from entries
            newItemFields.style.display = 'none';
            itemInput.style.display = 'block';
            toggleBtn.textContent = '+ Add New Item';
        } else {
            // Switch to adding new item
            itemInput.style.display = 'none';
            newItemFields.style.display = 'block';
            toggleBtn.textContent = '← Select From Entries';
        }
    });
</script>

@endsection
