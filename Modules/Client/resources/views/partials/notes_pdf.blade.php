
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: 'aealarabiya', sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }

        .header h1 {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
        }

        .client-info {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            padding: 10px;
            margin-bottom: 15px;
        }

        .client-info h2 {
            margin: 0 0 10px 0;
            font-size: 14px;
            font-weight: bold;
        }

        .info-row {
            margin-bottom: 5px;
        }

        .info-label {
            font-weight: bold;
            display: inline-block;
            width: 100px;
        }

        .note-card {
            border: 1px solid #e0e0e0;
            margin-bottom: 15px;
            page-break-inside: avoid;
        }

        .note-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #e0e0e0;
            padding: 10px;
        }

        .note-body {
            padding: 10px;
        }

        .note-footer {
            background-color: #f8f9fa;
            border-top: 1px solid #e0e0e0;
            padding: 8px 10px;
            font-size: 10px;
        }

        .detail-label {
            font-weight: bold;
            display: inline-block;
            width: 120px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table td {
            padding: 5px;
            vertical-align: top;
        }

        .text-muted {
            color: #6c757d;
        }

        h3 {
            margin: 0 0 5px 0;
            font-size: 13px;
        }

        h4 {
            margin: 5px 0;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <!-- رأس التقرير -->
    <div class="header">
        <h1>ملاحظات العميل</h1>
        <div style="font-size: 11px; color: #666; margin-top: 5px;">
            تاريخ الطباعة: {{ now()->format('d/m/Y H:i') }}
        </div>
    </div>

    <!-- معلومات العميل -->
    <div class="client-info">
        <h2>معلومات العميل</h2>
        <table>
            <tr>
                <td width="50%">
                    <span class="info-label">الاسم التجاري:</span>
                    {{ $client->trade_name ?? 'غير محدد' }}
                </td>
                <td width="50%">
                    <span class="info-label">كود العميل:</span>
                    #{{ $client->code ?? 'غير محدد' }}
                </td>
            </tr>
            <tr>
                <td>
                    <span class="info-label">رقم الهاتف:</span>
                    {{ $client->phone ?? 'غير محدد' }}
                </td>
                <td>
                    <span class="info-label">البريد الإلكتروني:</span>
                    {{ $client->email ?? 'غير محدد' }}
                </td>
            </tr>
        </table>
    </div>

    <!-- عنوان الملاحظات -->
    <h2 style="font-size: 14px; margin-bottom: 10px;">
        سجل الملاحظات ({{ $ClientRelations->count() }} ملاحظة)
    </h2>

    <!-- قائمة الملاحظات -->
    @if($ClientRelations->count() > 0)
        @foreach($ClientRelations as $index => $note)
            <div class="note-card">
                <!-- رأس الملاحظة -->
                <div class="note-header">
                    <table>
                        <tr>
                            <td width="70%">
                                <h3>{{ $note->employee->name ?? 'غير معروف' }}</h3>
                                <div style="font-size: 10px; color: #666;">
                                    {{ $note->process ?? 'بدون تصنيف' }}
                                </div>
                            </td>
                            <td width="30%" style="text-align: left;">
                                <div style="font-size: 10px; color: #666;">
                                    {{ $note->created_at->format('d/m/Y H:i') }}
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>

                <!-- محتوى الملاحظة -->
                <div class="note-body">
                    <h4>الوصف:</h4>
                    <p style="margin: 5px 0;">{{ $note->description ?? 'لا يوجد وصف' }}</p>

                    <!-- معلومات إضافية -->
                    @if($note->deposit_count || $note->site_type || $note->competitor_documents)
                        <div style="background-color: #f8f9fa; padding: 8px; margin-top: 10px;">
                            <h4>معلومات إضافية:</h4>
                            <table style="margin-top: 5px;">
                                @if($note->deposit_count)
                                    <tr>
                                        <td width="50%">
                                            <span class="detail-label">عدد العهدة:</span>
                                            {{ $note->deposit_count }}
                                        </td>
                                    </tr>
                                @endif

                                @if($note->site_type)
                                    <tr>
                                        <td>
                                            <span class="detail-label">نوع الموقع:</span>
                                            @switch($note->site_type)
                                                @case('independent_booth') بسطة مستقلة @break
                                                @case('grocery') بقالة @break
                                                @case('supplies') تموينات @break
                                                @case('markets') أسواق @break
                                                @case('station') محطة @break
                                                @default {{ $note->site_type }}
                                            @endswitch
                                        </td>
                                    </tr>
                                @endif

                                @if($note->competitor_documents)
                                    <tr>
                                        <td>
                                            <span class="detail-label">مستندات المنافسين:</span>
                                            {{ $note->competitor_documents }}
                                        </td>
                                    </tr>
                                @endif
                            </table>
                        </div>
                    @endif

                    <!-- المرفقات -->
                    @php
                        $files = json_decode($note->attachments, true);
                    @endphp

                    @if(is_array($files) && count($files))
                        <div style="margin-top: 10px;">
                            <h4>المرفقات ({{ count($files) }}):</h4>
                            <ul style="margin: 5px 0; padding-right: 20px;">
                                @foreach($files as $file)
                                    <li style="font-size: 10px;">{{ $file }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>

                <!-- تذييل الملاحظة -->
                <div class="note-footer">
                    <table>
                        <tr>
                            <td width="50%">
                                تاريخ الإنشاء: {{ $note->created_at->format('d/m/Y') }}
                            </td>
                            <td width="50%" style="text-align: left;">
                                ID: {{ $note->id }}
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- فاصل صفحات بعد كل 3 ملاحظات -->
            @if(($index + 1) % 3 == 0 && !$loop->last)
                <div style="page-break-after: always;"></div>
            @endif
        @endforeach
    @else
        <div style="text-align: center; padding: 30px;">
            <h3 style="color: #999;">لا توجد ملاحظات</h3>
            <p style="color: #999;">لم يتم إضافة أي ملاحظات لهذا العميل بعد</p>
        </div>
    @endif
</body>
</html>