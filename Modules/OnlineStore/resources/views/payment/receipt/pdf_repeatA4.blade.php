<!DOCTYPE html>
<html dir="rtl" lang="ar">

<head>
    <meta charset="UTF-8">
    <title>إيصال استلام #{{ $receipt->id }}</title>
    <style>
        body {
            font-family: 'Arial', 'Tahoma', sans-serif;
            direction: rtl;
            margin: 0;
            padding: 20px;
            background: #f5f5f5;
            font-size: 16px;
            line-height: 1.6;
        }

        .receipt-container {
            width: 210mm;
            min-height: 297mm;
            margin: 0 auto;
            background: white;
            padding: 40px 50px;
            border: 2px solid #333;
            position: relative;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        .receipt-header {
            text-align: center;
            margin-bottom: 40px;
            padding-bottom: 25px;
            border-bottom: 3px dashed #333;
        }

        .receipt-title {
            font-size: 42px;
            font-weight: bold;
            margin: 15px 0;
            color: #333;
        }

        .company-name {
            font-size: 28px;
            font-weight: bold;
            margin: 10px 0;
            color: #555;
        }

        .company-address {
            font-size: 20px;
            color: #666;
            margin: 8px 0;
        }

        .receipt-info {
            margin: 40px 0;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            font-size: 22px;
            padding: 15px 20px;
            border-bottom: 2px dotted #ccc;
        }

        .info-label {
            font-weight: bold;
            color: #333;
            min-width: 150px;
        }

        .info-value {
            font-weight: 600;
            color: #000;
            text-align: left;
        }

        .amount-row {
            background: #f8f9fa;
            padding: 25px 20px;
            margin: 30px 0;
            border: 3px solid #007bff;
            border-radius: 12px;
        }

        .amount-row .info-label {
            color: #007bff;
            font-size: 28px;
        }

        .amount-row .info-value {
            color: #007bff;
            font-size: 36px;
            font-weight: bold;
        }

        .payment-details {
            margin: 40px 0;
            padding: 25px;
            background: #f1f3f4;
            border-radius: 12px;
            text-align: center;
        }

        .payment-details div {
            font-size: 20px;
            margin: 12px 0;
            font-weight: 500;
        }

        .signature-area {
            margin-top: 80px;
            text-align: center;
            font-size: 20px;
        }

        .signature-line {
            border-top: 3px solid #333;
            width: 300px;
            margin: 40px auto;
            padding-top: 15px;
            font-size: 22px;
            font-weight: bold;
        }

        .thank-you {
            margin-top: 40px;
            font-size: 26px;
            font-weight: bold;
            color: #28a745;
        }

        .question {
            margin-top: 15px;
            font-size: 20px;
            color: #666;
        }

        .stamp {
            position: absolute;
            bottom: 80px;
            left: 80px;
            opacity: 0.8;
        }

        .stamp-content {
            border: 4px solid #28a745;
            color: #28a745;
            padding: 15px 30px;
            transform: rotate(-15deg);
            display: inline-block;
            font-weight: bold;
            font-size: 28px;
            border-radius: 8px;
            background: rgba(40, 167, 69, 0.1);
        }

        .receipt-number {
            font-size: 26px;
            font-weight: bold;
            color: #dc3545;
        }

        .receipt-date {
            font-size: 24px;
            font-weight: 600;
        }

        /* أزرار التحكم */
        .print-controls {
            text-align: center;
            margin: 30px 0;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
        }

        .print-controls button {
            background: #007bff;
            color: white;
            border: none;
            padding: 15px 35px;
            border-radius: 8px;
            font-size: 18px;
            cursor: pointer;
            margin: 0 15px;
            font-family: inherit;
            transition: all 0.3s ease;
        }

        .print-controls button:hover {
            background: #0056b3;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }

        .print-controls button.secondary {
            background: #6c757d;
        }

        .print-controls button.secondary:hover {
            background: #545b62;
        }

        /* تحسينات للطباعة */
        @media print {
            body {
                background: white;
                padding: 0;
                -webkit-print-color-adjust: exact;
                color-adjust: exact;
            }

            .receipt-container {
                border: 2px solid #000;
                box-shadow: none;
                margin: 0;
                padding: 30px 40px;
            }

            .print-controls {
                display: none;
            }

            .stamp-content {
                border: 3px solid #000;
                color: #000;
            }
        }
    </style>
</head>

<body>
    <div class="receipt-container">
        <div class="receipt-header">
            <div class="receipt-title">دفعة لفاتورة {{ $receipt->invoice->id??'غير محدد' }}</div>
            <div class="company-name">{{ $receipt->branch->name ?? 'مؤسسة أعمال خاصة للنجارة' }}</div>
            <div class="company-address">{{ $receipt->branch->address ?? 'الرياض' }}</div>
        </div>

        <div class="receipt-info">
            <div class="info-row">
                <span class="info-label">رقم:</span>
                <span class="info-value receipt-number">{{ str_pad($receipt->id, 6, '0', STR_PAD_LEFT) }}</span>
            </div>

            <div class="info-row">
                <span class="info-label">تاريخ:</span>
                <span class="info-value receipt-date">{{ $receipt->payment_date->format('d/m/Y') }}</span>
            </div>

            <div class="info-row">
                <span class="info-label">من:</span>
                <span class="info-value">
                    {{ $receipt->invoice->client->trade_name ?? 'غير محدد' }}
                </span>
            </div>

            <div class="info-row amount-row">
                <span class="info-label">المبلغ:</span>
                <span class="info-value">{{ number_format($receipt->amount, 2) }} ريال</span>
            </div>

            <div class="info-row">
                <span class="info-label">المستلم:</span>
                <span class="info-value">{{ $receipt->employee->name ?? 'غير محدد' }}</span>
            </div>

            <div class="info-row">
                <span class="info-label">الخزينة:</span>
                <span class="info-value">{{ $receipt->treasury->name ?? 'الخزينة الرئيسية' }}</span>
            </div>
        </div>

        <div class="payment-details">
            <div><strong>طريقة الدفع:</strong> {{ $receipt->payment_type->name ?? 'غير محدد' }}</div>
            <div><strong>رقم المرجع:</strong> {{ $receipt->reference_number ?? 'غير محدد' }}</div>
        </div>

        <div class="signature-area">
            <div>......</div>
            <div class="signature-line">التوقيع: {{ $receipt->invoice->employee->full_name ?? 'غير محدد' }}</div>
            <div class="thank-you">شكراً لتعاملكم معنا</div>
            <div class="question">لديك سؤال؟ اتصل بنا</div>
        </div>

        @if ($receipt->payment_status == 1)
            <div class="stamp">
                <div class="stamp-content">مدفوع</div>
            </div>
        @endif
    </div>

    <!-- أزرار التحكم -->
    <div class="print-controls">
        <button onclick="printReceipt()">طباعة الإيصال</button>
        <button class="secondary" onclick="window.close()">إغلاق النافذة</button>
    </div>

    <script>
        // الطباعة التلقائية عند تحميل الصفحة
        window.addEventListener('load', function() {
            setTimeout(function() {
                window.print();
            }, 500);
        });

        // دالة للطباعة اليدوية
        function printReceipt() {
            window.print();
        }

        // الطباعة بالضغط على Ctrl+P
        document.addEventListener('keydown', function(event) {
            if (event.ctrlKey && event.key === 'p') {
                event.preventDefault();
                printReceipt();
            }
        });

        // إخفاء أزرار التحكم أثناء الطباعة
        window.addEventListener('beforeprint', function() {
            document.querySelector('.print-controls').style.display = 'none';
        });

        window.addEventListener('afterprint', function() {
            document.querySelector('.print-controls').style.display = 'block';
        });
    </script>
</body>

</html>