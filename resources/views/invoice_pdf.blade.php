<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $invoice->id }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }

        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 4px; text-align: start; }
        .no-border { border: none !important; }

        /* Page margins: reserve space for footer */
        @page {
            margin: 50px 20px 160px 20px; /* bottom >= footer height */
        }

        /* Prevent rows from breaking */
        table {
            page-break-inside: auto;
        }
        tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }
        thead {
            display: table-header-group; /* repeat header on each page */
        }
        tfoot {
            display: table-footer-group;
        }

        /* Sections like warranty/signature */
        .warranty, .signature {
            page-break-inside: avoid;
            margin-top: 20px;
        }

        /* Footer image repeats on every page */
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 145px; /* must match image */
            text-align: center;
        }
        .footer img {
            width: 100%;
            height: auto;
            display: block;
        }
    </style>
</head>
<body>
@php
    $subtotal = $invoice->items->sum('amount');
    $grandTotal = $subtotal + $invoice->tax;
    $updatedBalance = $grandTotal + $invoice->previous_balance;
@endphp

<!-- Header -->
<table style="border:none;">
    <tr>
        <td style="width:40%; border:none;">
            <img src="{{ public_path('assets/images/user.png') }}" alt="Logo" style="height:100px;">
        </td>
        <td style="width:60%; text-align:right; border:none;">
            <h1 style="margin:0; font-size:22px;">AM TRADERS</h1>
            <p style="margin:0; font-size:14px; line-height:1.4;">
                66-Kusar Block Awan Town Multan Road, Lahore, Pakistan<br>
                0334-3538725 | 0341-0022521 
            </p>
            <p style="margin:0; font-size:14px; line-height:1.4;">amtraders725@gmail.com</p>
        </td>
    </tr>
</table>

<h3 style="text-align:center; background:#8ea9db; padding:5px; font-size:18px;">Customer Detail</h3>
<table style="border:2px solid #000000; border-collapse: collapse; width:100%;">
    <tr>
        <td class="no-border"><strong>Customer Name:</strong> {{ $invoice->user->name ?? '-' }}</td>
        <td class="no-border" style="text-align:right;"><strong>Invoice No:</strong> {{ $invoice->id }}</td>
    </tr>
    <tr>
        <td class="no-border"><strong>Address:</strong> {{ $invoice->user->address ?? '-' }}</td>
        <td class="no-border" style="text-align:right;"><strong>Date:</strong> {{ $invoice->created_at->format('d-M-Y') }}</td>
    </tr>
    <tr>
        <td class="no-border"><strong>Phone:</strong> {{ $invoice->user->contact ?? '-' }}</td>
        <td class="no-border" style="text-align:right;"><strong>Area:</strong> {{ $invoice->user->area ?? '-' }}</td>
    </tr>
    <tr>
        <td colspan="2" class="no-border"><strong>NTN:</strong> {{ $invoice->user->ntn_strn ?? '-' }}</td>
    </tr>
</table>

<h3 style="text-align:center; background:#8ea9db; padding:5px; font-size:18px;">Invoice Detail</h3>
<table>
    <thead>
        <tr>
            <th>S.No</th>
            <th>Item</th>
            <th>Batch Code</th>
            <th>Expiry</th>
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
            <td style="text-align:center;">{{ $index + 1 }}</td>
            <td>{{ $item->item_name }}</td>
            <td style="text-align:center;">{{ $item->batch_code ?? '-' }}</td>
            <td style="text-align:center;">
                {{ $item->expiry ? \Illuminate\Support\Carbon::parse($item->expiry)->format('d-M-Y') : '-' }}    
            </td>
            <td style="text-align:center;">{{ $item->pack_size ?? '-' }}</td>
            <td style="text-align:center;">{{ $item->sale_qty }}</td>
            <td style="text-align:center;">{{ $item->foc }}</td>
            <td style="text-align:center;">{{ number_format($item->sale_rate, 0) }}</td>
            <td style="text-align:center;">{{ number_format($item->amount, 0) }}</td>
        </tr>
        @endforeach
        <tr>
            <td colspan="7" class="no-border"></td>
            <td style="text-align:right;"><strong>Amount</strong></td>
            <td style="text-align:center;"><strong>{{ number_format($subtotal, 0) }}</strong></td>
        </tr>
        <tr>
            <td colspan="7" class="no-border"></td>
            <td style="text-align:right;"><strong>Tax</strong></td>
            <td style="text-align:center;"><strong>{{ number_format($invoice->tax, 0) }}</strong></td>
        </tr>
        <tr>
            <td colspan="7" class="no-border"></td>
            <td style="text-align:right;"><strong>Sub Total</strong></td>
            <td style="text-align:center;"><strong>{{ number_format($grandTotal, 0) }}</strong></td>
        </tr>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="2"><strong>Previous Balance</strong></td>
            <td style="text-align:center;"><strong>{{ number_format($invoice->previous_balance, 0) }}</strong></td>
            <td colspan="6" class="no-border"></td>
        </tr>
        <tr>
            <td colspan="2"><strong>Current Invoice</strong></td>
            <td style="text-align:center;"><strong>{{ number_format($grandTotal, 0) }}</strong></td>
            <td colspan="6" class="no-border"></td>
        </tr>
        <tr>
            <td colspan="2"><strong>Updated Balance</strong></td>
            <td style="text-align:center;"><strong>{{ number_format($updatedBalance, 0) }}</strong></td>
            <td colspan="6" class="no-border"></td>
        </tr>
    </tfoot>
</table>

<!-- Warranty / Signature -->
<div class="warranty">
</div>

<!-- Footer image -->
<div class="footer">
    <img src="{{ public_path('invoicefooter.png') }}">
</div>

</body>
</html>
