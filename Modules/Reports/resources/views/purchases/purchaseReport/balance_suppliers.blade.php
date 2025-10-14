@extends('master')

@section('title')
    تقرير رصيد الموردين
@stop

@section('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .card {
            background-color: #ffffff;
            border: none;
            border-radius: 10px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
        }

        .card-header {
            font-weight: bold;
            font-size: 20px;
            padding: 15px;
            border-radius: 10px 10px 0 0;
            text-align: center;
        }

        .form-control,
        .custom-select {
            border-radius: 5px;
        }

        .btn-primary {
            background: linear-gradient(45deg, #4CAF50, #2E7D32);
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            font-size: 16px;
        }

        .btn-primary:hover {
            background: linear-gradient(45deg, #66BB6A, #388E3C);
        }

        .btn-export,
        .btn-print {
            background: linear-gradient(45deg, #2196F3, #1976D2);
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            font-size: 16px;
            margin-right: 10px;
        }

        .btn-export:hover,
        .btn-print:hover {
            opacity: 0.9;
        }

        .form-check-label {
            font-weight: bold;
        }

        .table-container {
            margin-top: 20px;
        }

        .table thead th {
            background: linear-gradient(45deg, #1d8cf8, #d8e3e8);
            color: white;
            font-weight: bold;
            text-align: center;
        }

        .table tbody td {
            text-align: center;
            vertical-align: middle;
        }
    </style>
@endsection

@section('content')
    <div class="content-header row mb-3">
        <div class="content-header-left col-md-9 col-12 d-flex align-items-center">
            <div class="row breadcrumbs-top flex-grow-1">
                <div class="col-12 d-flex align-items-center">
                    <h2 class="content-header-title mb-0 me-3">تقارير رصيد الموردين </h2>
                    <div class="breadcrumb-wrapper">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="">الرئيسية</a></li>
                            <li class="breadcrumb-item active">عرض</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">رصيد الموردين</div>
        <div class="card-body">
            <form id="inventoryForm" method="GET" action="{{ route('StorHouseReport.inventorySheet') }}">
                <div class="row mb-3">
                    <!-- فلترة حسب المورد -->
                    <div class="col-md-4">
                        <label for="supplier_id" class="form-label">المورد</label>
                        <select id="supplier_id" name="supplier_id" class="custom-select">
                            <option value="">الكل</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                    {{ $supplier->full_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- فلترة حسب التاريخ من -->
                    <div class="col-md-4">
                        <label for="from_date" class="form-label">التاريخ من </label>
                        <input type="date" class="form-control" id="from_date" name="from_date" value="{{ request('from_date') }}">
                    </div>

                    <!-- فلترة حسب التاريخ إلى -->
                    <div class="col-md-4">
                        <label for="to_date" class="form-label">التاريخ إلى </label>
                        <input type="date" class="form-control" id="to_date" name="to_date" value="{{ request('to_date') }}">
                    </div>

                    <!-- فلترة حسب الفرع -->
                    <div class="col-md-4">
                        <label for="branch_id" class="form-label">الفرع</label>
                        <select id="branch_id" name="branch_id" class="custom-select">
                            <option value="">كل الفروع</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}" {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                                    {{ $branch->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- فلترة حسب التجميع -->
                    <div class="col-md-4">
                        <label for="group_by"> تجميع حسب</label>
                        <select name="group_by" class="form-control">
                            <option value="">اختر</option>
                            <option value="supplier" {{ request('group_by') == 'supplier' ? 'selected' : '' }}>المورد</option>
                            <option value="branch" {{ request('group_by') == 'branch' ? 'selected' : '' }}>الفرع</option>
                        </select>
                    </div>

                    <!-- فلترة حسب إخفاء الرصيد الصفري -->
                    <div class="col-md-4 d-flex align-items-center">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="hideZeroBalance" name="hideZeroBalance" {{ request('hideZeroBalance') ? 'checked' : '' }}>
                            <label class="form-check-label" for="hideZeroBalance">إخفاء الرصيد الصفري</label>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-primary">عرض التقرير</button>
                    <a href="{{ route('StorHouseReport.inventorySheet') }}" class="btn btn-secondary" id="resetFilter">إلغاء الفلترة</a>
                    <button type="button" class="btn btn-export" id="exportExcel">تصدير Excel</button>
                    <button type="button" class="btn btn-export" id="exportPDF">تصدير PDF</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Table Section -->
    <div class="table-container card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <p>تاريخ ووقت الطباعة: {{ now()->format('H:i d/m/Y') }}</p>
                <div>
                    <button class="btn btn-print"><i class="fas fa-print"></i> طباعة</button>
                    <button class="btn btn-export"><i class="fas fa-download"></i> خيارات التصدير</button>
                </div>
            </div>
            <table class="table table-bordered table-striped" id="suppliersTable">
                <thead>
                    <tr>
                        <th>الكود</th>
                        <th>رقم الحساب</th>
                        <th>فرع</th>
                        <th>رصيد قبل</th>
                        <th>إجمالي المشتريات</th>
                        <th>صافي المشتريات</th>
                        <th>إجمالي المدفوعات</th>
                        <th>التسويات</th>
                        <th>الرصيد</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($suppliers as $supplier)
                    <tr>
                        <td>{{ $supplier->number_suply }}</td>
                        <td>{{ $supplier->account_id }}</td>
                        <td>{{ $supplier->branch->name ?? 'N/A' }}</td>
                        <td>{{ number_format($supplier->opening_balance, 2) }}</td>
                        <td>{{ number_format($supplier->total_purchases, 2) }}</td>
                        <td>{{ number_format($supplier->total_purchases, 2) }}</td> <!-- صافي المشتريات (يمكن تعديله) -->
                        <td>{{ number_format($supplier->total_payments, 2) }}</td>
                        <td>{{ number_format($supplier->adjustments, 2) }}</td>
                        <td>{{ number_format($supplier->balance, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.24/jspdf.plugin.autotable.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const table = document.getElementById('suppliersTable');
            const resetFilterButton = document.getElementById('resetFilter');
            const exportExcelButton = document.getElementById('exportExcel');
            const exportPDFButton = document.getElementById('exportPDF');

            // Reset Filter
            resetFilterButton.addEventListener('click', function () {
                document.getElementById('supplier_id').value = '';
                document.getElementById('from_date').value = '';
                document.getElementById('to_date').value = '';
                document.getElementById('branch_id').value = '';
                document.getElementById('group_by').value = '';
                document.getElementById('hideZeroBalance').checked = false;
                document.querySelector('form').submit();
            });

            // Export to Excel
            exportExcelButton.addEventListener('click', function () {
                const wb = XLSX.utils.table_to_book(table, { sheet: "Suppliers Balance" });
                XLSX.writeFile(wb, 'suppliers_balance.xlsx');
            });

            // Export to PDF
            exportPDFButton.addEventListener('click', function () {
                const { jsPDF } = window.jspdf;
                const doc = new jsPDF();

                doc.autoTable({
                    html: '#suppliersTable',
                    theme: 'grid',
                    headStyles: { fillColor: [41, 128, 185], textColor: 255 },
                    bodyStyles: { textColor: 50 },
                });
                doc.save('suppliers_balance.pdf');
            });
        });
    </script>
@endsection
