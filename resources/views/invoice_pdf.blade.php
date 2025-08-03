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
</head>
<body>

    <h2 style="text-align: center;">AM TRADERS</h2>
    <p style="text-align: center;">66-Block B Model Town, Lahore, Pakistan<br>
    0345-5170181 | amtraders123@gmail.com</p>

    <h3 style="background-color: #eee; padding: 5px;">Customer Detail</h3>
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

    <h3 style="background-color: #eee; padding: 5px;">Invoice Detail</h3>
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

    <p class="signature">Authorized Signature: ____________________</p>

    <div class="footer">Thank you for your business!</div>

</body>
</html>
