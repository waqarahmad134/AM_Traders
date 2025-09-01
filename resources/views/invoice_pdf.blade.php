<!-- @php
    // Temporary invoice dummy values
    $invoice = (object) [
        'id' => rand(1000, 9999),
        'created_at' => now(),
        'user' => (object) [
            'name' => 'Test Customer',
            'contact' => '0300-1234567',
            'ntn_strn' => '1234567-8',
            'address' => '123 Model Town, Lahore',
        ],
        'items' => collect([
            (object) [
                'item_code' => 'P001',
                'item_name' => 'Sample Product A',
                'pack_size' => '1L',
                'sale_qty' => 2,
                'foc' => 0,
                'sale_rate' => 500,
                'amount' => 1000,
            ],
            (object) [
                'item_code' => 'P002',
                'item_name' => 'Sample Product B',
                'pack_size' => '500ml',
                'sale_qty' => 1,
                'foc' => 1,
                'sale_rate' => 300,
                'amount' => 300,
            ],
        ]),
    ];

    $invoiceDate = $invoice->created_at->format('Y-m-d');
    $currentTime = now()->format('H:i:s');
@endphp -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $invoice->id }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 4px; text-align: center; }
        .no-border { border: none; }
        .footer { text-align: center; font-size: 10px; margin-top: 20px; }
        .signature { margin-top: 50px; }
        
    </style>
    <style>
@page {
    margin: 20px 20px 40px 20px; /* top right bottom left */
}
.footer img {
    width: 100%;
    height: auto;
    margin: 0;
    padding: 0;
}
</style>

</head>
<body>
<table style="width:100%; border:none; border-collapse:collapse;">
    <tr>
        <!-- Logo on the left -->
        <td style="width:40%; text-align:left; vertical-align:middle; border:none;">
            <img src="{{ public_path('assets/images/user.png') }}" 
                 alt="Logo" 
                 style="height:100px;">
        </td>

        <!-- Company Info on the right -->
        <td style="width:60%; text-align:right; vertical-align:middle; border:none;">
            <h1 style="margin:0; font-size:22px;">AM TRADERS</h1>
            <p style="margin:0; font-size:14px; line-height:1.4;">
                66-Block B Model Town, Lahore, Pakistan<br>
                0345-5170181 | amtraders123@gmail.com
            </p>
        </td>
    </tr>
</table>


</div>
    <h3 style="text-align: center; background-color: #8ea9db; padding: 5px;font-size: 18px;">Customer Detail</h3>
    <table>
        <tr>
            <td>Customer Name: {{ $invoice->user->name }}</td>
            <td>Invoice No: {{ $invoice->id }}</td>
        </tr>
        <tr>
            <td>Phone: {{ $invoice->user->contact }}</td>
            <td>Date: {{ $invoice->created_at->format('Y-m-d') }}</td>
        </tr>
        <tr>
            <td colspan="2">NTN / STRN: {{ $invoice->user->ntn_strn }}</td>
        </tr>
        <tr>
            <td colspan="2">Address: {{ $invoice->user->address }}</td>
        </tr>
    </table>

    <h3 style="text-align: center; background-color: #8ea9db; padding: 5px;font-size: 18px;">Invoice Detail</h3>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Item Code</th>
                <th>Item</th>
                <th>Pack Size</th>
                <th>Qty</th>
                <th>FOC</th>
                <th>Rate</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($invoice->items as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->item_code }}</td>
                <td>{{ $item->item_name }}</td>
                <td>{{ $item->pack_size ?? '-' }}</td>
                <td>{{ $item->sale_qty }}</td>
                <td>{{ $item->foc }}</td>
                <td>{{ number_format($item->sale_rate, 2) }}</td>
                <td>{{ number_format($item->amount, 2) }}</td>
            </tr>
            @endforeach
            <tr>
                <td colspan="7" style="text-align: right;"><strong>Total</strong></td>
                <td><strong>{{ number_format($invoice->items->sum('amount'), 2) }}</strong></td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
    <img src="{{ public_path('invoicefooter.png') }}" 
     style="width:100%; height:auto; display:block; margin:0; padding:0;">
    </div>
    <!-- <p class="signature">Authorized Signature: ____________________</p> -->


</body>
</html>
