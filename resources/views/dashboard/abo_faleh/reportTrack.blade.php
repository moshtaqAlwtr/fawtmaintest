@extends('master')

@section('title')
تتبع المبيعات
@stop

@section('css')
<script src="https://unpkg.com/lucide@latest"></script>

<style>
* {
  box-sizing: border-box;
  margin: 0;
  padding: 0;
}

body {
  font-family: 'Cairo', sans-serif;
  min-height: 100vh;
  padding: 20px;
}

.main-container {
  max-width: 1600px;
  margin: 0 auto;
}

.modern-header {
  background: white;
  border-radius: 24px;
  padding: 40px;
  margin-bottom: 30px;
  box-shadow: 0 20px 60px rgba(0,0,0,0.15);
  position: relative;
  overflow: hidden;
}

.modern-header::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 4px;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.header-content {
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-wrap: wrap;
  gap: 20px;
}

.header-title h1 {
  font-size: 32px;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  margin-bottom: 8px;
  font-weight: 800;
  display: flex;
  align-items: center;
  gap: 12px;
}

.header-title .subtitle {
  color: #64748b;
  font-size: 16px;
  font-weight: 600;
}

.modern-btn {
  padding: 14px 32px;
  border: none;
  border-radius: 12px;
  font-family: inherit;
  font-size: 15px;
  font-weight: 700;
  cursor: pointer;
  transition: all 0.3s ease;
  display: inline-flex;
  align-items: center;
  gap: 10px;
  box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.btn-print {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
}

.btn-print:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
}

.btn-filter {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  width: 100%;
}

.btn-filter:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
}

.filter-section {
  background: white;
  border-radius: 20px;
  padding: 30px;
  margin-bottom: 30px;
  box-shadow: 0 10px 40px rgba(0,0,0,0.1);
}

.filter-section h3 {
  font-size: 20px;
  color: #1e293b;
  margin-bottom: 24px;
  font-weight: 700;
  display: flex;
  align-items: center;
  gap: 12px;
}

.filter-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
  gap: 20px;
  align-items: end;
}

.form-field {
  position: relative;
}

.form-field label {
  font-weight: 600;
  color: #475569;
  font-size: 14px;
  margin-bottom: 8px;
  display: block;
}

.form-field select,
.form-field input {
  width: 100%;
  padding: 14px 16px;
  border: 2px solid #e2e8f0;
  border-radius: 10px;
  font-family: inherit;
  font-size: 14px;
  transition: all 0.3s;
  background: white;
}

.form-field select:focus,
.form-field input:focus {
  outline: none;
  border-color: #667eea;
  box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
}

.employee-dropdown {
  position: absolute;
  top: 100%;
  left: 0;
  right: 0;
  background: white;
  border: 2px solid #e2e8f0;
  border-radius: 10px;
  max-height: 250px;
  overflow-y: auto;
  z-index: 100;
  margin-top: 5px;
  box-shadow: 0 10px 30px rgba(0,0,0,0.15);
  display: none;
}

.employee-dropdown.active {
  display: block;
}

.employee-item {
  padding: 12px 16px;
  cursor: pointer;
  transition: all 0.2s;
  font-size: 14px;
  color: #334155;
  border-bottom: 1px solid #f1f5f9;
}

.employee-item:last-child {
  border-bottom: none;
}

.employee-item:hover {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
}

.employee-item.hidden {
  display: none;
}

.stats-container {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
  gap: 20px;
  margin-bottom: 30px;
}

.stat-box {
  background: white;
  padding: 28px;
  border-radius: 18px;
  box-shadow: 0 10px 30px rgba(0,0,0,0.08);
  transition: all 0.3s ease;
  position: relative;
  overflow: hidden;
  border-top: 4px solid var(--stat-color);
}

.stat-box:hover {
  transform: translateY(-5px);
  box-shadow: 0 15px 45px rgba(0,0,0,0.15);
}

.stat-box.green { --stat-color: #10b981; }
.stat-box.blue { --stat-color: #3b82f6; }
.stat-box.red { --stat-color: #ef4444; }
.stat-box.orange { --stat-color: #f59e0b; }
.stat-box.purple { --stat-color: #8b5cf6; }

.stat-icon {
  width: 40px;
  height: 40px;
  margin-bottom: 16px;
  color: var(--stat-color);
  stroke-width: 2.5;
}

.stat-label {
  font-size: 14px;
  color: #64748b;
  margin-bottom: 8px;
  font-weight: 600;
}

.stat-value {
  font-size: 28px;
  font-weight: 800;
  color: #1e293b;
}

.data-table-wrapper {
  background: white;
  border-radius: 20px;
  overflow: hidden;
  box-shadow: 0 10px 40px rgba(0,0,0,0.1);
  margin-bottom: 30px;
}

.table-title {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  padding: 24px 30px;
  font-size: 20px;
  font-weight: 700;
  display: flex;
  align-items: center;
  gap: 12px;
}

.table-scroll {
  overflow-x: auto;
  max-height: 600px;
}

table {
  width: 100%;
  border-collapse: collapse;
}

thead th {
  background: #f8fafc;
  color: #1e293b;
  padding: 16px 12px;
  text-align: center;
  font-size: 13px;
  font-weight: 700;
  border-bottom: 2px solid #e2e8f0;
  position: sticky;
  top: 0;
  white-space: nowrap;
  z-index: 10;
}

tbody td {
  padding: 16px 12px;
  text-align: center;
  border-bottom: 1px solid #f1f5f9;
  font-size: 14px;
  color: #334155;
  font-weight: 500;
}

tbody tr {
  transition: all 0.2s;
}

tbody tr:hover {
  background: #f8fafc;
}

.type-tag {
  display: inline-block;
  padding: 6px 16px;
  border-radius: 20px;
  font-size: 12px;
  font-weight: 700;
  white-space: nowrap;
}

.type-زيارة { background: #dbeafe; color: #1e40af; }
.type-فاتورة { background: #fee2e2; color: #991b1b; }
.type-فاتورة-مرتجعة { background: #fef3c7; color: #92400e; }
.type-مدفوع { background: #d1fae5; color: #065f46; }
.type-سند-قبض { background: #ccfbf1; color: #115e59; }
.type-سند-صرف { background: #fef3c7; color: #92400e; }
.type-زيارة-فاتورة,
.type-زيارة-مدفوع,
.type-زيارة-سند-قبض {
  background: linear-gradient(135deg, #dbeafe 0%, #d1fae5 100%);
  color: #1e40af;
}

.amount-badge {
  display: inline-block;
  padding: 6px 14px;
  border-radius: 10px;
  font-weight: 700;
  font-size: 14px;
}

.amount-positive { background: #d1fae5; color: #065f46; }
.amount-negative { background: #fee2e2; color: #991b1b; }
.amount-warning { background: #fef3c7; color: #92400e; }

.total-summary {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  font-weight: 800;
  font-size: 15px;
}

.total-summary td {
  border: none;
  padding: 20px 12px;
  color: white;
}

.pagination-container {
  display: flex;
  justify-content: space-between;
  align-items: center;
  background: white;
  padding: 20px 30px;
  border-radius: 16px;
  box-shadow: 0 4px 15px rgba(0,0,0,0.08);
  margin-bottom: 30px;
  flex-wrap: wrap;
  gap: 15px;
}

.pagination-left {
  display: flex;
  align-items: center;
  gap: 15px;
  flex-wrap: wrap;
}

.pagination-info {
  color: #64748b;
  font-size: 14px;
  font-weight: 600;
}

.per-page-selector {
  display: flex;
  align-items: center;
  gap: 10px;
}

.per-page-selector label {
  color: #475569;
  font-size: 14px;
  font-weight: 600;
}

.per-page-selector select {
  padding: 8px 12px;
  border: 2px solid #e2e8f0;
  border-radius: 8px;
  font-family: inherit;
  font-size: 14px;
  font-weight: 600;
  color: #475569;
  background: white;
  cursor: pointer;
  transition: all 0.3s;
}

.per-page-selector select:focus {
  outline: none;
  border-color: #667eea;
  box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.per-page-selector select:hover {
  border-color: #667eea;
}

.pagination-controls {
  display: flex;
  gap: 10px;
  align-items: center;
}

.pagination-btn {
  padding: 10px 16px;
  border: 2px solid #e2e8f0;
  background: white;
  border-radius: 10px;
  cursor: pointer;
  transition: all 0.3s;
  font-family: inherit;
  font-size: 14px;
  font-weight: 600;
  color: #475569;
  display: flex;
  align-items: center;
  gap: 6px;
}

.pagination-btn:hover:not(:disabled) {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  border-color: transparent;
}

.pagination-btn:disabled {
  opacity: 0.4;
  cursor: not-allowed;
}

.pagination-btn.active {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  border-color: transparent;
}

.page-numbers {
  display: flex;
  gap: 6px;
}

.empty-message {
  text-align: center;
  padding: 80px 20px;
  color: #64748b;
}

.empty-message .icon {
  width: 80px;
  height: 80px;
  margin: 0 auto 24px;
  opacity: 0.7;
  color: #cbd5e1;
}

.empty-message h3 {
  font-size: 24px;
  margin-bottom: 12px;
  color: #475569;
  font-weight: 700;
}

.empty-message p {
  font-size: 16px;
  opacity: 0.9;
}

@media(max-width: 768px) {
  .main-container { padding: 10px; }
  .modern-header { padding: 24px; }
  .header-title h1 { font-size: 24px; }
  .stats-container { grid-template-columns: repeat(2, 1fr); }
  .filter-grid { grid-template-columns: 1fr; }
  table { font-size: 12px; }
  thead th, tbody td { padding: 10px 6px; }
  .pagination-container { flex-direction: column; }
  .page-numbers { flex-wrap: wrap; justify-content: center; }
}

@media print {
  body { background: white; padding: 0; }
  .filter-section, .header-actions, .modern-btn, .pagination-container { display: none !important; }
  .modern-header, .data-table-wrapper, .stats-container { box-shadow: none; background: white; border: 1px solid #ddd; }
}
</style>
@endsection

@section('content')
<div class="content-header row">
    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-left mb-0">تقرير الموظف</h2>
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard_sales.index') }}">الرئيسية</a></li>
                        <li class="breadcrumb-item active">عرض</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="main-container">
  <div class="modern-header">
    <div class="header-content">
      <div class="header-title">
        <h1>
          <i data-lucide="chart-bar"></i>
          التقرير الموحد للموظف
        </h1>
        <p class="subtitle">{{ $user->name }}</p>
      </div>
      <div class="header-actions">
        <button class="modern-btn btn-print" onclick="window.print()">
          <i data-lucide="printer"></i>
          طباعة
        </button>
      </div>
    </div>
  </div>

  <div class="filter-section">
    <h3>
      <i data-lucide="filter"></i>
      تصفية البيانات
    </h3>
    <form method="GET" action="{{ route('ABO_FALEH.reportTrac') }}" class="filter-grid">
      <div class="form-field">
        <label>اختر الموظف</label>
        <input type="text" id="employeeSearch" placeholder="ابحث عن موظف..." autocomplete="off">
        <div class="employee-dropdown" id="employeeDropdown">
          <div class="employee-item" data-id="" data-name="المستخدم الحالي">
            المستخدم الحالي
          </div>
          @foreach ($allUsers as $u)
            <div class="employee-item" data-id="{{ $u->id }}" data-name="{{ $u->name }}">
              {{ $u->name }}
            </div>
          @endforeach
        </div>
        <input type="hidden" name="user_id" id="selectedUserId" value="{{ request('user_id') }}">
      </div>

      <div class="form-field">
        <label>من تاريخ</label>
        <input type="date" name="from_date" value="{{ request('from_date', $from->format('Y-m-d')) }}">
      </div>

      <div class="form-field">
        <label>إلى تاريخ</label>
        <input type="date" name="to_date" value="{{ request('to_date', $to->format('Y-m-d')) }}">
      </div>

      <div class="form-field">
        <button type="submit" class="modern-btn btn-filter">
          <i data-lucide="search"></i>
          عرض التقرير
        </button>
      </div>
    </form>
  </div>

  @if($all->isEmpty())
    <div class="empty-message">
      <i data-lucide="inbox" class="icon"></i>
      <h3>لا توجد بيانات للعرض</h3>
      <p>جرب تغيير التواريخ أو اختيار موظف آخر</p>
    </div>
  @else
    @php
      $total_receipt = $all->pluck('receipt')->filter(fn($v) => is_numeric($v))->sum();
      $total_payment = $all->pluck('payment')->filter(fn($v) => is_numeric($v))->sum();
      $total_invoice = $all->pluck('invoice')->filter(fn($v) => is_numeric($v))->sum();
      $total_expense = $all->pluck('expense')->filter(fn($v) => is_numeric($v))->sum();
      $total_credit = $all->pluck('credit_note')->filter(fn($v) => is_numeric($v))->sum();
      $total_minutes = 0;
    @endphp

    <div class="stats-container" id="statsContainer"></div>

    <div class="pagination-container">
      <div class="pagination-left">
        <div class="pagination-info">
          عرض <span id="currentStart">1</span> - <span id="currentEnd">50</span> من <span id="totalRecords">0</span> سجل
        </div>
        <div class="per-page-selector">
          <label>عرض:</label>
          <select id="perPageSelect">
            <option value="50">50</option>
            <option value="75">75</option>
            <option value="100">100</option>
            <option value="150">150</option>
            <option value="200">200</option>
          </select>
        </div>
      </div>
      <div class="pagination-controls">
        <button class="pagination-btn" id="firstPageBtn">
          <i data-lucide="chevrons-right"></i>
        </button>
        <button class="pagination-btn" id="prevPageBtn">
          <i data-lucide="chevron-right"></i>
        </button>
        <div class="page-numbers" id="pageNumbers"></div>
        <button class="pagination-btn" id="nextPageBtn">
          <i data-lucide="chevron-left"></i>
        </button>
        <button class="pagination-btn" id="lastPageBtn">
          <i data-lucide="chevrons-left"></i>
        </button>
      </div>
    </div>

    <div class="data-table-wrapper">
      <div class="table-title">
        <i data-lucide="clipboard-list"></i>
        تفاصيل العمليات
      </div>
      <div class="table-scroll">
        <table>
          <thead>
            <tr>
              <th>النوع</th>
              <th>المجموعة</th>
              <th>العميل</th>
              <th>الوصول</th>
              <th>الانصراف</th>
              <th>المدة</th>
              <th>التاريخ</th>
              <th>سند قبض</th>
              <th>المدفوع</th>
              <th>الفاتورة</th>
              <th>سند صرف</th>
              <th>مرتجع</th>
              <th>ملاحظات</th>
            </tr>
          </thead>
          <tbody id="tableBody">
            @foreach ($grouped as $rows)
              @php
                $row = $rows->first();
                $credit_note = $rows->pluck('credit_note')->filter(fn($v) => $v && $v !== '--')->first() ?? '--';
                $types = $rows->pluck('type')->unique()->implode(' + ');
                $arrival = $rows->pluck('arrival')->filter(fn($v) => $v && $v !== '--')->first() ?? '--';
                $departure = $rows->pluck('departure')->filter(fn($v) => $v && $v !== '--')->first() ?? '--';

                $duration = '--';
                $durationMinutes = 0;
                if ($arrival !== '--' && $departure !== '--') {
                    try {
                        $a = \Carbon\Carbon::parse($arrival);
                        $d = \Carbon\Carbon::parse($departure);
                        $durationMinutes = $d->diffInMinutes($a);
                        $total_minutes += $durationMinutes;
                        $duration = $durationMinutes . ' د';
                    } catch (\Exception $e) {
                        $duration = '--';
                    }
                }

                $receipt = $rows->pluck('receipt')->filter(fn($v) => $v && $v !== '--')->first() ?? '--';
                $payment = $rows->pluck('payment')->filter(fn($v) => $v && $v !== '--')->first() ?? '--';
                $invoice = $rows->pluck('invoice')->filter(fn($v) => $v && $v !== '--')->first() ?? '--';
                $expense = $rows->pluck('expense')->filter(fn($v) => $v && $v !== '--')->first() ?? '--';
                $repNote = $rows->pluck('description_note')->filter()->first() ?? '--';
                $typeClass = 'type-' . str_replace([' ', '/'], '-', $types);
              @endphp
              <tr class="data-row"
                  data-type="{{ $types }}"
                  data-group="{{ $row['group'] }}"
                  data-client="{{ $row['client'] }}"
                  data-arrival="{{ $arrival }}"
                  data-departure="{{ $departure }}"
                  data-duration="{{ $duration }}"
                  data-duration-minutes="{{ $durationMinutes }}"
                  data-date="{{ $row['date'] }}"
                  data-receipt="{{ $receipt }}"
                  data-payment="{{ $payment }}"
                  data-invoice="{{ $invoice }}"
                  data-expense="{{ $expense }}"
                  data-credit="{{ $credit_note }}"
                  data-note="{{ $repNote }}"
                  data-typeclass="{{ $typeClass }}"
                  style="display: none;">
                <td><span class="type-tag {{ $typeClass }}">{{ $types }}</span></td>
                <td>{{ $row['group'] }}</td>
                <td><strong>{{ $row['client'] }}</strong></td>
                <td>{{ $arrival }}</td>
                <td>{{ $departure }}</td>
                <td>{{ $duration }}</td>
                <td>{{ $row['date'] }}</td>
                <td>
                  @if($receipt !== '--')
                    <span class="amount-badge amount-positive">{{ $receipt }}</span>
                  @else
                    {{ $receipt }}
                  @endif
                </td>
                <td>
                  @if($payment !== '--')
                    <span class="amount-badge amount-positive">{{ $payment }}</span>
                  @else
                    {{ $payment }}
                  @endif
                </td>
                <td>
                  @if($invoice !== '--')
                    <span class="amount-badge amount-negative">{{ $invoice }}</span>
                  @else
                    {{ $invoice }}
                  @endif
                </td>
                <td>
                  @if($expense !== '--')
                    <span class="amount-badge amount-warning">{{ $expense }}</span>
                  @else
                    {{ $expense }}
                  @endif
                </td>
                <td>
                  @if($credit_note !== '--')
                    <span class="amount-badge amount-warning">{{ $credit_note }}</span>
                  @else
                    {{ $credit_note }}
                  @endif
                </td>
                <td style="max-width: 200px; text-align: right;">{{ $repNote }}</td>
              </tr>
            @endforeach

            <tr class="total-summary" id="totalRow">
              <td colspan="5">الإجمالي الكلي</td>
              <td id="totalMinutes">{{ $total_minutes }} د</td>
              <td>--</td>
              <td id="totalReceipt">{{ number_format($total_receipt, 2) }}</td>
              <td id="totalPayment">{{ number_format($total_payment, 2) }}</td>
              <td id="totalInvoice">{{ number_format($total_invoice, 2) }}</td>
              <td id="totalExpense">{{ number_format($total_expense, 2) }}</td>
              <td id="totalCredit">{{ number_format($total_credit, 2) }}</td>
              <td>--</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  @endif
</div>

<script>
lucide.createIcons();

class DynamicPagination {
  constructor() {
    this.allRows = Array.from(document.querySelectorAll('.data-row'));
    this.currentPage = 1;
    this.perPage = 50;
    this.totalPages = 1;
    this.statsData = {
      totalReceipt: {{ $total_receipt ?? 0 }},
      totalPayment: {{ $total_payment ?? 0 }},
      totalInvoice: {{ $total_invoice ?? 0 }},
      totalExpense: {{ $total_expense ?? 0 }},
      totalCredit: {{ $total_credit ?? 0 }}
    };
    this.init();
  }

  init() {
    document.getElementById('perPageSelect').addEventListener('change', (e) => {
      this.perPage = parseInt(e.target.value);
      this.currentPage = 1;
      this.render();
    });

    document.getElementById('firstPageBtn').addEventListener('click', () => this.goToPage(1));
    document.getElementById('prevPageBtn').addEventListener('click', () => this.goToPage(this.currentPage - 1));
    document.getElementById('nextPageBtn').addEventListener('click', () => this.goToPage(this.currentPage + 1));
    document.getElementById('lastPageBtn').addEventListener('click', () => this.goToPage(this.totalPages));

    this.updateStats();
    this.render();
  }

  goToPage(page) {
    if (page < 1 || page > this.totalPages) return;
    this.currentPage = page;
    this.render();
  }

  updateStats() {
    const statsContainer = document.getElementById('statsContainer');
    statsContainer.innerHTML = `
      <div class="stat-box green">
        <i data-lucide="wallet" class="stat-icon"></i>
        <div class="stat-label">إجمالي سندات القبض</div>
        <div class="stat-value">${this.formatNumber(this.statsData.totalReceipt)}</div>
      </div>
      <div class="stat-box blue">
        <i data-lucide="credit-card" class="stat-icon"></i>
        <div class="stat-label">إجمالي المدفوعات</div>
        <div class="stat-value">${this.formatNumber(this.statsData.totalPayment)}</div>
      </div>
      <div class="stat-box red">
        <i data-lucide="file-text" class="stat-icon"></i>
        <div class="stat-label">إجمالي الفواتير</div>
        <div class="stat-value">${this.formatNumber(this.statsData.totalInvoice)}</div>
      </div>
      <div class="stat-box orange">
        <i data-lucide="send" class="stat-icon"></i>
        <div class="stat-label">إجمالي سندات الصرف</div>
        <div class="stat-value">${this.formatNumber(this.statsData.totalExpense)}</div>
      </div>
      <div class="stat-box purple">
        <i data-lucide="rotate-ccw" class="stat-icon"></i>
        <div class="stat-label">إجمالي المرتجعات</div>
        <div class="stat-value">${this.formatNumber(this.statsData.totalCredit)}</div>
      </div>
    `;
    lucide.createIcons();
  }

  formatNumber(num) {
    return new Intl.NumberFormat('en-US', {
      minimumFractionDigits: 2,
      maximumFractionDigits: 2
    }).format(num);
  }

  render() {
    const totalItems = this.allRows.length;
    this.totalPages = Math.ceil(totalItems / this.perPage);

    this.allRows.forEach(row => row.style.display = 'none');

    const start = (this.currentPage - 1) * this.perPage;
    const end = start + this.perPage;
    const currentRows = this.allRows.slice(start, end);
    currentRows.forEach(row => row.style.display = '');

    this.calculatePageTotals(currentRows);

    document.getElementById('currentStart').textContent = totalItems > 0 ? start + 1 : 0;
    document.getElementById('currentEnd').textContent = Math.min(end, totalItems);
    document.getElementById('totalRecords').textContent = totalItems;

    this.updateButtons();
    this.renderPageNumbers();
    lucide.createIcons();

    window.scrollTo({ top: 0, behavior: 'smooth' });
  }

  calculatePageTotals(rows) {
    let totalMinutes = 0;
    let totalReceipt = 0;
    let totalPayment = 0;
    let totalInvoice = 0;
    let totalExpense = 0;
    let totalCredit = 0;

    rows.forEach(row => {
      const durationMinutes = parseInt(row.dataset.durationMinutes);
      if (!isNaN(durationMinutes)) totalMinutes += durationMinutes;

      const receipt = row.dataset.receipt;
      if (receipt && receipt !== '--') totalReceipt += this.parseAmount(receipt);

      const payment = row.dataset.payment;
      if (payment && payment !== '--') totalPayment += this.parseAmount(payment);

      const invoice = row.dataset.invoice;
      if (invoice && invoice !== '--') totalInvoice += this.parseAmount(invoice);

      const expense = row.dataset.expense;
      if (expense && expense !== '--') totalExpense += this.parseAmount(expense);

      const credit = row.dataset.credit;
      if (credit && credit !== '--') totalCredit += this.parseAmount(credit);
    });

    document.getElementById('totalMinutes').textContent = totalMinutes + ' د';
    document.getElementById('totalReceipt').textContent = this.formatNumber(totalReceipt);
    document.getElementById('totalPayment').textContent = this.formatNumber(totalPayment);
    document.getElementById('totalInvoice').textContent = this.formatNumber(totalInvoice);
    document.getElementById('totalExpense').textContent = this.formatNumber(totalExpense);
    document.getElementById('totalCredit').textContent = this.formatNumber(totalCredit);
  }

  parseAmount(amountStr) {
    const cleaned = amountStr.replace(/,/g, '').replace(/\s/g, '');
    const parsed = parseFloat(cleaned);
    return isNaN(parsed) ? 0 : parsed;
  }

  updateButtons() {
    document.getElementById('firstPageBtn').disabled = this.currentPage === 1;
    document.getElementById('prevPageBtn').disabled = this.currentPage === 1;
    document.getElementById('nextPageBtn').disabled = this.currentPage === this.totalPages;
    document.getElementById('lastPageBtn').disabled = this.currentPage === this.totalPages;
  }

  renderPageNumbers() {
    const container = document.getElementById('pageNumbers');
    container.innerHTML = '';

    const startPage = Math.max(1, this.currentPage - 2);
    const endPage = Math.min(this.totalPages, this.currentPage + 2);

    for (let i = startPage; i <= endPage; i++) {
      const btn = document.createElement('button');
      btn.className = 'pagination-btn' + (i === this.currentPage ? ' active' : '');
      btn.textContent = i;
      btn.addEventListener('click', () => this.goToPage(i));
      container.appendChild(btn);
    }
  }
}

document.addEventListener('DOMContentLoaded', function() {
  new DynamicPagination();

  const searchInput = document.getElementById('employeeSearch');
  const dropdown = document.getElementById('employeeDropdown');
  const hiddenInput = document.getElementById('selectedUserId');
  const employeeItems = document.querySelectorAll('.employee-item');
  const form = searchInput.closest('form');

  searchInput.addEventListener('focus', function() {
    dropdown.classList.add('active');
  });

  searchInput.addEventListener('input', function() {
    const searchTerm = this.value.trim().toLowerCase();
    employeeItems.forEach(item => {
      const employeeName = item.dataset.name.toLowerCase();
      if (employeeName.includes(searchTerm)) {
        item.classList.remove('hidden');
      } else {
        item.classList.add('hidden');
      }
    });
    dropdown.classList.add('active');
  });

  employeeItems.forEach(item => {
    item.addEventListener('click', function() {
      const employeeId = this.dataset.id;
      const employeeName = this.dataset.name;
      searchInput.value = employeeName;
      hiddenInput.value = employeeId;
      dropdown.classList.remove('active');
      form.submit();
    });
  });

  document.addEventListener('click', function(e) {
    if (!searchInput.contains(e.target) && !dropdown.contains(e.target)) {
      dropdown.classList.remove('active');
    }
  });

  const currentUserId = "{{ request('user_id') }}";
  if (currentUserId) {
    const selectedItem = document.querySelector(`[data-id="${currentUserId}"]`);
    if (selectedItem) {
      searchInput.value = selectedItem.dataset.name;
    }
  } else {
    searchInput.value = 'المستخدم الحالي';
  }

  setTimeout(() => {
    lucide.createIcons();
  }, 100);
});
</script>

@endsection
