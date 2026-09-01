<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Receipt - {{ $sale->invoice_no }}</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 13px;
            color: #000;
            padding: 10px;
            margin: 0 auto;
            max-width: 320px;
        }

        .text-center {
            text-align: center;
        }

        .text-end {
            text-align: right;
        }

        .fw-bold {
            font-weight: bold;
        }

        .divider {
            border-top: 1px dashed #000;
            margin: 6px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 6px 0;
        }

        th,
        td {
            padding: 3px 0;
        }

        @media print {
            .no-print {
                display: none;
            }

            body {
                padding: 0;
            }
        }
    </style>
</head>

<body onload="window.print()">

    <div class="no-print" style="margin-bottom: 15px; text-align: center;">
        <button onclick="window.print()" style="padding: 6px 15px; font-weight: bold; cursor: pointer;">🖨️ Print
            Receipt</button>
        <button onclick="window.close()" style="padding: 6px 15px; margin-left: 5px; cursor: pointer;">Close</button>
    </div>

    <div class="text-center">
        <h3 style="margin: 0; text-transform: uppercase;">{{ $company->company_name ?? 'ROYEL BAKERY' }}</h3>
        <div style="font-size: 11px;">Fresh Bakery & Confectionery</div>
        <div class="divider"></div>
        <div style="font-size: 11px;">
            Invoice: <strong>{{ $sale->invoice_no }}</strong><br>
            Date: {{ $sale->created_at->format('d-M-Y h:i A') }}<br>
            Customer: {{ $sale->customer_name }} ({{ ucfirst($sale->customer_type) }})<br>
            @if ($sale->customer_phone)
                Phone: {{ $sale->customer_phone }}
            @endif
        </div>
    </div>

    <div class="divider"></div>

    <table>
        <thead>
            <tr style="border-bottom: 1px dashed #000;">
                <th style="text-align: left;">Item</th>
                <th style="text-align: center;">Qty</th>
                <th style="text-align: right;">Rate</th>
                <th style="text-align: right;">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sale->items as $item)
                <tr>
                    <td>{{ $item->product->product_name ?? 'Item' }}</td>
                    <td style="text-align: center;">{{ $item->quantity }}</td>
                    <td style="text-align: right;">{{ number_format($item->unit_price, 2) }}</td>
                    <td style="text-align: right;">{{ number_format($item->subtotal, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="divider"></div>

    <table>
        <tr>
            <td class="fw-bold">Grand Total:</td>
            <td class="text-end fw-bold">₹{{ number_format($sale->grand_total, 2) }}</td>
        </tr>
        <tr>
            <td>Paid Total:</td>
            <td class="text-end">₹{{ number_format($sale->paid_total, 2) }}</td>
        </tr>
        @if ($sale->due_amount > 0)
            <tr style="color: red;">
                <td class="fw-bold">Due Amount:</td>
                <td class="text-end fw-bold">₹{{ number_format($sale->due_amount, 2) }}</td>
            </tr>
        @endif
    </table>

    <div class="divider"></div>
    <div class="text-center" style="font-size: 11px; margin-top: 8px;">
        Thank You! Visit Again.<br>
        Software by Bakery ERP
    </div>

</body>

</html>
