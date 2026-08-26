<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Barcode - {{ $product->product_name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- JSBARCODE CDN -->
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
    <style>
        @media print {
            .no-print { display: none !important; }
            body { padding: 0; margin: 0; background: white !important; }
        }
        .barcode-sticker {
            width: 240px;
            border: 1px dashed #bbb;
            padding: 12px;
            border-radius: 6px;
            background: #fff;
            text-align: center;
        }
    </style>
</head>
<body class="bg-light p-4">

    <div class="no-print mb-4 text-center">
        <button onclick="window.print()" class="btn btn-primary btn-lg fw-semibold shadow-sm me-2">
            🖨️ Print Barcode Sticker
        </button>
        <button onclick="window.close()" class="btn btn-secondary btn-lg fw-semibold">
            Close
        </button>
    </div>

    <div class="d-flex justify-content-center align-items-center">
        <!-- BARCODE STICKER CARD -->
        <div class="barcode-sticker shadow-sm">
            <div class="fw-bold text-uppercase small text-truncate" style="font-size: 0.85rem;">
                {{ $product->product_name }}
            </div>
            
            <div class="my-2 d-flex justify-content-center">
                <!-- SVG Barcode Container -->
                <svg id="barcode"></svg>
            </div>

            <div class="fw-bold text-dark border-top pt-1 small">
                Price: ₹{{ number_format($product->flat_selling_price ?? $product->customer_price, 2) }}
            </div>
        </div>
    </div>

    <script>
        // Generate Barcode on Load
        JsBarcode("#barcode", "{{ $product->product_code }}", {
            format: "CODE128",
            width: 1.8,
            height: 45,
            displayValue: true,
            fontSize: 14,
            fontOptions: "bold"
        });
    </script>
</body>
</html>