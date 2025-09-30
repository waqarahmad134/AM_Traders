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
                <form action="{{ route('invoice.store') }}" method="POST" class="card" onsubmit="return validateEmployee(this)">
                    @csrf
                    <div class="header d-flex justify-content-between pb-0 mb-0">
                        <h2>Create Invoice</h2>
                        
                        <div class="d-flex align-items-center">
                            <label class="mr-2 mb-0">Date:</label>
                            <input type="date" name="invoice_date" class="form-control" style="width: auto;" 
                                   value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" required>
                        </div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between mr-3">
                        <!-- <div class="d-flex align-items-center ml-3">
                            <label class="mr-2 mb-0">Edit Invoice:</label>
                            <select id="editInvoiceSelect" class="form-control" style="width: 320px;">
                                <option value="">-- Select Invoice to Edit --</option>
                                @foreach($saleReports->groupBy(function($r){ return basename($r->pdf_path, '.pdf'); }) as $invoiceName => $reports)
                                    <option value="{{ $invoiceName }}">{{ $invoiceName }} ({{ $reports->count() }})</option>
                                @endforeach
                            </select>
                        </div> -->
                        <select id="editInvoiceSelect" class="form-control" style="width: 320px;">
                            <option value="">-- Select Invoice to Edit --</option>
                            @foreach($saleReports->groupBy(function($r){ return basename($r->pdf_path, '.pdf'); }) as $invoiceName => $reports)
                                <option value="{{ $invoiceName }}" data-employee="{{ $reports[0]->employee_id }}">
                                    {{ $invoiceName }} ({{ $reports->count() }})
                                </option>
                            @endforeach
                        </select>

                        <div class="d-flex align-items-center ml-3">
                            <label class="mr-2 mb-0">Employee ID:</label>
                            <select id="employee_id" name="employee_id" class="form-control" style="width: 320px;" required>
                                <option value="">-- Select Employee --</option>
                                @foreach($users as $user)
                                    @if($user->usertype === 'employee')
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                            <span id="employeeError" style="color: red; display: none;">Please select employee.</span>
                        </div>
                    </div>
                    <div class="body">
                        <div>
                            <div class="form-group">
                                <label>Select User</label>
                                <select name="user_id" id="userSelect" class="form-control">
                                    <option value="">-- Select Existing User --</option>
                                    <option value="new_user">-- New User --</option>
                                    @foreach($users as $user)
                                        @if($user->usertype === 'customer')
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <input type="hidden" name="item_name" id="hiddenItemName">

                            <div id="newUserFields" style="display: none;">
                                <h6>Create New User</h6>
                                <div class="row">
                                    <div class="col-4 my-2">
                                        <input type="text" name="name" class="form-control" placeholder="Name">
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
                                    <div class="col-4 my-2">
                                        <input type="email" name="email" class="form-control" placeholder="Email (optional)">
                                    </div>
                                    <div class="col-4 my-2">
                                        <input type="text" name="address" class="form-control" placeholder="address">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <!-- <label>Search Item <p style="display:none;" id="inStocks">Total Items In Stock : </p></label>
                                <label> <p style="display:none;" id="batch_code"></p></label>
                                <label> <p style="display:none;" id="expiry"></p></label>
                                <label> <p style="display:none;" id="purchase_rate"></p></label> -->
                                <div class="mb-3">
    <label>Item Details:</label>
    <table class="table table-sm table-bordered">
        <thead class="thead-light">
            <tr>
                <th>In Stock</th>
                <th>Batch Code</th>
                <th>Expiry</th>
                <th>Purchase Rate</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td id="inStocks">0</td>
                <td id="batch_code">-</td>
                <td id="expiry">-</td>
                <td id="purchase_rate">0</td>
            </tr>
        </tbody>
    </table>
</div>

                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <input list="itemsList" id="itemInput" class="form-control mr-2" placeholder="Search by item name">
                                </div>
                                <datalist id="itemsList">
                                    @foreach($items as $item)
                                        <option data-name="{{ $item->item_name }}" value="{{ $item->item_name }}" data-id="{{ $item->id }}" >
                                    @endforeach
                                </datalist>
                            </div>

                            <div id="itemDetails" style="display:none;">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Item Name</th>
                                            <th>Qty</th>
                                            <th>Rate</th>
                                            <th>FOC</th>
                                            <th>Amount</th>
                                            <th>Tax</th>
                                            <th>Previous Balance</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><span id="selectedItemName"></span></td>
                                            <td>
                                                <input type="number" id="qty" name="qty" class="form-control">
                                                <span id="qtyError" style="color: red;"></span>
                                            </td>
                                            <td><input type="number" id="rate" name="rate" class="form-control"></td>
                                            <td><input type="number" id="foc" name="foc" class="form-control"></td>
                                            <td><input type="number" id="amount" name="amount" class="form-control" readonly></td>
                                            <td><input type="number" name="tax" class="form-control"></td>
                                            <td><input type="number" name="previous_balance" class="form-control"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-4">
                                <h5>Selected Items</h5>
                                <table class="table table-bordered" id="selectedItemsTable">
                                    <thead>
                                        <tr>
                                            <th>Item Name</th>
                                            <th>Qty</th>
                                            <th>Rate</th>
                                            <th>FOC</th>
                                            <th>Amount</th>
                                            <th>Tax</th>
                                            <th>Previous Balance</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>

                            <input type="hidden" name="invoice_number" id="invoice_number" value="">
                            <button type="submit" id="createInvoiceBtn" class="btn btn-primary">Create Invoice</button>
                            <a href="#" id="addNewItem" class="btn btn-secondary">Add Item</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
const stocks = @json($stocks);
const purchaseRecord = @json($purchaseRecord);
let selectedItems = []; // store added items

document.addEventListener('DOMContentLoaded', function() {

    // USER SELECTION LOGIC
    document.getElementById('userSelect').addEventListener('change', function() {
        const newUserFields = document.getElementById('newUserFields');
        newUserFields.style.display = this.value === "new_user" ? 'block' : 'none';
    });

    // ITEM SEARCH + STOCK DISPLAY
    const itemInput = document.getElementById('itemInput');
    itemInput.addEventListener('input', function() {
        const value = this.value.trim();
        console.log("🚀 ~ value:", value)
        const datalist = document.getElementById('itemsList');
        const selectedOption = datalist.querySelector(`option[value="${value}"]`);
        console.log("🚀 ~ selectedOption:", selectedOption)
        const inStocksParagraph = document.getElementById('inStocks');
        const batch_code = document.getElementById('batch_code');
        const expiry = document.getElementById('expiry');
        const purchase_rate = document.getElementById('purchase_rate');

        if (!value) {
            inStocksParagraph.style.display = 'none';
            document.getElementById('itemDetails').style.display = 'none';
            return;
        }

        if (selectedOption) {
            const itemName = selectedOption.getAttribute('data-name');
            const itemID = selectedOption.getAttribute('data-id');
            const selectedPurchaseItem = purchaseRecord?.find(data => data.item_id == itemID);
            const selectedItem = stocks?.find(stock => stock.item === itemName);

            if (!selectedItem) {
                toastr.error('Item not found in stock');
                return;
            }

            document.getElementById('selectedItemName').textContent = selectedItem.item;
            document.getElementById('hiddenItemName').value = selectedItem.item;

            const inStocksCell = document.getElementById('inStocks');
            const batchCodeCell = document.getElementById('batch_code');
            const expiryCell = document.getElementById('expiry');
            const purchaseRateCell = document.getElementById('purchase_rate');

            inStocksCell.textContent = selectedItem.in_stock ?? 0;
            batchCodeCell.textContent = selectedPurchaseItem?.batch_code ?? '-';
            expiryCell.textContent = selectedPurchaseItem?.expiry ?? '-';
            purchaseRateCell.textContent = selectedPurchaseItem?.purchase_rate ?? 0;
            document.getElementById('itemDetails').style.display = 'block';
            calculateAndValidate();
        } else {
            inStocksParagraph.style.display = 'none';
            document.getElementById('itemDetails').style.display = 'none';
        }
    });

    // QUANTITY + RATE CALCULATION
    const qtyInput = document.getElementById('qty');
    const rateInput = document.getElementById('rate');

    qtyInput.addEventListener('input', calculateAndValidate);
    rateInput.addEventListener('input', calculateAndValidate);

    function calculateAndValidate() {
        const qty = parseFloat(qtyInput.value) || 0;
        const rate = parseFloat(rateInput.value) || 0;
        const subtotal = qty * rate;
        document.getElementById('amount').value = subtotal.toFixed(0);

        const itemName = document.getElementById('selectedItemName').textContent;
        const selectedItem = stocks.find(stock => stock.item === itemName);
        const qtyError = document.getElementById('qtyError');
        const createInvoiceBtn = document.getElementById('createInvoiceBtn');

        if (selectedItem && qty > selectedItem.in_stock) {
            qtyError.textContent = `Only ${selectedItem.in_stock} items in stock.`;
            createInvoiceBtn.disabled = true;
        } else {
            qtyError.textContent = '';
            createInvoiceBtn.disabled = false;
        }
    }


    // ADD NEW ITEM INTO TABLE
document.getElementById('addNewItem').addEventListener('click', function(e) {
    e.preventDefault();

    const itemName = document.getElementById('selectedItemName').textContent;
    const qty = parseFloat(document.getElementById('qty').value) || 0;
    const rate = parseFloat(document.getElementById('rate').value) || 0;
    const foc = parseFloat(document.getElementById('foc').value) || 0;
    const amount = parseFloat(document.getElementById('amount').value) || 0;
    const tax = parseFloat(document.querySelector('input[name="tax"]').value) || 0;
    const prevBalance = parseFloat(document.querySelector('input[name="previous_balance"]').value) || 0;

    if (!itemName || qty <= 0) {
        alert("Please select an item and enter valid quantity.");
        return;
    }

    // Check if item already exists in selectedItems
    let existingItem = selectedItems.find(i => i.itemName === itemName);
    console.log("🚀 ~ existingItem:", existingItem)

    if (existingItem) {
        // Update existing entry
        existingItem.qty += qty;
        existingItem.foc += foc;
        existingItem.amount = existingItem.qty * rate; // recalc
        existingItem.rate = rate; // update latest rate
        existingItem.tax = tax; // overwrite with latest tax
        existingItem.prevBalance = prevBalance;

        // Update row in table
        const rows = document.querySelectorAll("#selectedItemsTable tbody tr");
        rows.forEach(row => {
            if (row.cells[0].textContent === itemName) {
                row.cells[1].textContent = existingItem.qty;
                row.cells[2].textContent = existingItem.rate;
                row.cells[3].textContent = existingItem.foc;
                row.cells[4].textContent = existingItem.amount;
                row.cells[5].textContent = existingItem.tax;
                row.cells[6].textContent = existingItem.prevBalance;
                row.querySelector("input[name='items[]']").value = JSON.stringify(existingItem);
            }
        });

    } else {
        // Create new entry
        const newItem = { itemName, qty, rate, foc, amount, tax, prevBalance };
        selectedItems.push(newItem);

        const tbody = document.querySelector("#selectedItemsTable tbody");
        const row = document.createElement("tr");
        row.innerHTML = `
            <td>${itemName}</td>
            <td>${qty}</td>
            <td>${rate}</td>
            <td>${foc}</td>
            <td>${amount}</td>
            <td>${tax}</td>
            <td>${prevBalance}</td>
            <td><button type="button" class="btn btn-danger btn-sm removeItem">Remove</button></td>
            <input type="hidden" name="items[]" value='${JSON.stringify(newItem)}'>
        `;
        tbody.appendChild(row);

        // REMOVE ITEM
        row.querySelector(".removeItem").addEventListener("click", function() {
            tbody.removeChild(row);
            selectedItems = selectedItems.filter(i => i.itemName !== newItem.itemName);
        });
    }

    // Reset fields after adding
    document.getElementById('itemInput').value = '';
    document.getElementById('itemDetails').style.display = 'none';
    document.getElementById('inStocks').style.display = 'none';

    document.getElementById('qty').value = '';
    document.getElementById('rate').value = '';
    document.getElementById('foc').value = '';
    document.getElementById('amount').value = '';
    document.querySelector('input[name="tax"]').value = '';
    let prevBalanceInput = document.querySelector('input[name="previous_balance"]');
    if (prevBalanceInput.value && prevBalanceInput.value.trim() !== '') {
        prevBalanceInput.setAttribute('disabled', true);
    } else {
        prevBalanceInput.removeAttribute('disabled');
        prevBalanceInput.value = '';
    }

    document.getElementById('selectedItemName').textContent = '';
});


});

// FORM SUBMIT HANDLER
document.querySelector("form").addEventListener("submit", function(e) {
    if (selectedItems.length === 0) {
        const itemName = document.getElementById('selectedItemName').textContent;
        const qty = parseFloat(document.getElementById('qty').value) || 0;
        const rate = parseFloat(document.getElementById('rate').value) || 0;
        const foc = parseFloat(document.getElementById('foc').value) || 0;
        const amount = parseFloat(document.getElementById('amount').value) || 0;
        const tax = parseFloat(document.querySelector('input[name="tax"]').value) || 0;
        const prevBalance = parseFloat(document.querySelector('input[name="previous_balance"]').value) || 0;

        if (!itemName || qty <= 0) {
            alert("Please select an item and enter valid quantity before submitting.");
            e.preventDefault();
            return;
        }

        const singleItem = { itemName, qty, rate, foc, amount, tax, prevBalance };
        const hiddenInput = document.createElement("input");
        hiddenInput.type = "hidden";
        hiddenInput.name = "items[]";
        hiddenInput.value = JSON.stringify(singleItem);
        this.appendChild(hiddenInput);
    }
});
</script>


<script>
const saleReports = @json($saleReports);

function invoiceNameFromPath(path) {
    return path.replace(/^invoices\//, '').replace(/\.pdf$/, '');
}

document.addEventListener('DOMContentLoaded', function () {
    const editInvoiceSelect = document.getElementById('editInvoiceSelect');
    const userSelect = document.getElementById('userSelect');

    // item detail fields
    const itemDetailsDiv = document.getElementById('itemDetails');
    const selectedItemName = document.getElementById('selectedItemName');
    const qtyInput = document.getElementById('qty');
    const rateInput = document.getElementById('rate');
    const focInput = document.getElementById('foc');
    const amountInput = document.getElementById('amount');

    // button + hidden input
    const createInvoiceBtn = document.getElementById('createInvoiceBtn');
    const hiddenInvoiceInput = document.getElementById('invoice_number');

    editInvoiceSelect.addEventListener('change', function () {
        const invoiceName = this.value;

        // ---------- CREATE MODE: reset everything ----------
        if (!invoiceName) {
            createInvoiceBtn.textContent = "Create Invoice";
            createInvoiceBtn.classList.remove("btn-warning");
            createInvoiceBtn.classList.add("btn-primary");
            hiddenInvoiceInput.value = "";

            // clear selectedItems and table
            selectedItems = [];
            const tbodyEmpty = document.querySelector('#selectedItemsTable tbody');
            tbodyEmpty.innerHTML = '';
            // clear detail panel
            itemDetailsDiv.style.display = 'none';
            selectedItemName.textContent = '';
            qtyInput.value = '';
            rateInput.value = '';
            focInput.value = '';
            amountInput.value = '';
            // reset user select
            userSelect.value = '';
            return;
        }

        // ---------- EDIT MODE ----------
        // find first match by pdf_path
        const firstMatch = saleReports.find(r => invoiceNameFromPath(r.pdf_path) === invoiceName);
        console.log("🚀 ~ firstMatch:", firstMatch)
        if (!firstMatch) return;


        const employeeSelect = document.getElementById('employee_id');
        employeeSelect.value = firstMatch.employee_id;  
        employeeSelect.disabled = true;                 



        // get all reports by invoice_number
        const reports = saleReports.filter(r => r.invoice_number === firstMatch.invoice_number);
        if (!reports.length) return;

        // select user for this invoice (first record)
        const first = reports[0];
        const userId = (first.user && first.user.id) ? first.user.id : first.user_id;
        if (userId) {
            const option = userSelect.querySelector(`option[value="${userId}"]`);
            userSelect.value = option ? userId : 'new_user';
            userSelect.dispatchEvent(new Event('change'));
        }

        // prepare table and selectedItems
        selectedItems = [];
        const tbody = document.querySelector('#selectedItemsTable tbody');
        tbody.innerHTML = '';

        // USE a simple for loop to add rows
        for (let i = 0; i < reports.length; i++) {
            const rep = reports[i];

            const itemObj = {
                itemName: rep.item_name,
                qty: rep.sale_qty,
                rate: rep.sale_rate,
                foc: rep.foc || 0,
                amount: rep.amount,
                tax: rep.tax || 0,
                prevBalance: rep.previous_balance || 0
            };

            // push to selectedItems array
            selectedItems.push(itemObj);


            // build DOM row
            const tr = document.createElement('tr');

            const tdName = document.createElement('td'); tdName.textContent = itemObj.itemName;
            const tdQty = document.createElement('td'); tdQty.textContent = itemObj.qty;
            const tdRate = document.createElement('td'); tdRate.textContent = itemObj.rate;
            const tdFoc = document.createElement('td'); tdFoc.textContent = itemObj.foc;
            const tdAmount = document.createElement('td'); tdAmount.textContent = itemObj.amount;
            const tdTax = document.createElement('td'); tdTax.textContent = itemObj.tax;
            const tdPrev = document.createElement('td'); tdPrev.textContent = itemObj.prevBalance;

            const tdAct = document.createElement('td');
            const btnRemove = document.createElement('button');
            btnRemove.type = 'button';
            btnRemove.className = 'btn btn-danger btn-sm remove-item';
            btnRemove.textContent = 'Remove';
            tdAct.appendChild(btnRemove);

            // hidden input so backend receives items[]
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'items[]';
            hiddenInput.value = JSON.stringify(itemObj);

            // append tds and hidden to tr
            tr.appendChild(tdName);
            tr.appendChild(tdQty);
            tr.appendChild(tdRate);
            tr.appendChild(tdFoc);
            tr.appendChild(tdAmount);
            tr.appendChild(tdTax);
            tr.appendChild(tdPrev);
            tr.appendChild(tdAct);
            tr.appendChild(hiddenInput);

            // append row to tbody
            tbody.appendChild(tr);

            // remove handler: remove row DOM and remove from selectedItems
            (function(localItem, localTr){
                btnRemove.addEventListener('click', function () {
                    // remove DOM row
                    if (localTr && localTr.parentNode) localTr.parentNode.removeChild(localTr);

                    // remove first matching item from selectedItems
                    for (let k = 0; k < selectedItems.length; k++) {
                        const it = selectedItems[k];
                        if (it.itemName === localItem.itemName && Number(it.qty) === Number(localItem.qty) && Number(it.rate) === Number(localItem.rate)) {
                            selectedItems.splice(k, 1);
                            break;
                        }
                    }
                });
            })(itemObj, tr);
        } // end for loop

        // Show first item in detail panel (so user can use it for edits/add)
        // const firstItem = reports[0];
        // if (firstItem) {
        //     itemDetailsDiv.style.display = 'block';
        //     selectedItemName.textContent = firstItem.item_name + "1waqar";
        //     qtyInput.value = firstItem.sale_qty;
        //     rateInput.value = firstItem.sale_rate;
        //     focInput.value = firstItem.foc || 0;
        //     amountInput.value = firstItem.amount;
        // }

        // Show ALL items in detail panel (not just index 0)


// Show ALL items in detail panel as a table
itemDetailsDiv.style.display = 'block';

// Build table header
let tableHTML = `
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Item Name</th>
                <th>Qty</th>
                <th>Rate</th>
                <th>FOC</th>
                <th>Amount</th>
                <th>Tax</th>
                <th>Previous Balance</th>
            </tr>
        </thead>
        <tbody>
`;

// Loop through all items and add rows
for (let i = 0; i < reports.length; i++) {
    const rep = reports[i];
    tableHTML += `
        <tr>
            <td>
                <span>${rep.item_name}</span>
                <input type="hidden" name="items[${i}][itemName]" value="${rep.item_name}">
            </td>
            <td>
                <input type="number" name="items[${i}][qty]" value="${rep.sale_qty}" class="form-control">
                <span class="text-danger" id="qtyError_${i}"></span>
            </td>
            <td><input type="number" name="items[${i}][rate]" value="${rep.sale_rate}" class="form-control"></td>
            <td><input type="number" name="items[${i}][foc]" value="${rep.foc || 0}" class="form-control"></td>
            <td><input type="number" name="items[${i}][amount]" value="${rep.amount}" class="form-control" readonly></td>
            <td><input type="number" name="items[${i}][tax]" value="${rep.tax || 0}" class="form-control"></td>
            <td><input type="number" name="items[${i}][prevBalance]" value="${rep.previous_balance || 0}" class="form-control"></td>
        </tr>
    `;
}

// Close table
tableHTML += `</tbody></table>`;

// Insert into DOM
itemDetailsDiv.innerHTML = tableHTML;


        // set hidden invoice_number for backend
        hiddenInvoiceInput.value = first.invoice_number;

        // change create -> update
        createInvoiceBtn.textContent = "Update Invoice";
        createInvoiceBtn.classList.remove('btn-primary');
        createInvoiceBtn.classList.add('btn-warning');
    });
});
</script>

<!-- 
<script>
    document.addEventListener("DOMContentLoaded", function() {
        window.validateEmployee = function() {
            const empSelect = document.getElementById('employee_id');
            const errorSpan = document.getElementById('employeeError');

            if (!empSelect || !empSelect.value) {
                toastr.error("Please select an employee before submitting!", "Validation Error", {
                closeButton: true,
                progressBar: true,
                timeOut: 3000
            });
            empSelect?.focus();
            return false; 
            }
            errorSpan.style.display = 'none';
            return true;
        }
    });
</script> -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    window.validateEmployee = function(form) {
        const empSelect = document.getElementById('employee_id');
        const errorSpan = document.getElementById('employeeError');

        if (!empSelect || !empSelect.value) {
            toastr.error("Please select an employee before submitting!", "Validation Error", {
                closeButton: true,
                progressBar: true,
                timeOut: 3000
            });
            empSelect?.focus();
            return false; 
        }

        errorSpan.style.display = 'none';

        // Disable submit button to prevent multiple clicks
        const submitBtn = form.querySelector('button[type="submit"]');
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.textContent = 'Submitting...';
        }

        return true;
    }
});
</script>


<link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>
<script>
     new TomSelect('#editInvoiceSelect', {
        create: false,
        sortField: { field: "text", direction: "asc" }
    });

</script>



@endsection
