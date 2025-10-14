@extends('master')

@section('title')
تقرير المخازن
@stop

@section('css')
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

.nav-tabs {
  display: flex;
  gap: 10px;
  margin-bottom: 20px;
  border-bottom: 2px solid #e2e8f0;
  padding-bottom: 10px;
}

.nav-tab {
  padding: 10px 20px;
  border-radius: 8px;
  background: #f1f5f9;
  color: #475569;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s;
  text-decoration: none;
}

.nav-tab:hover {
  background: #e2e8f0;
}

.nav-tab.active {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
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

.quantity-badge {
  display: inline-block;
  padding: 6px 14px;
  border-radius: 10px;
  font-weight: 700;
  font-size: 14px;
}

.quantity-current { background: #dbeafe; color: #1e40af; }
.quantity-sold { background: #fee2e2; color: #991b1b; }
.quantity-remaining { background: #d1fae5; color: #065f46; }

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
  .filter-grid { grid-template-columns: 1fr; }
  table { font-size: 12px; }
  thead th, tbody td { padding: 10px 6px; }
}

@media print {
  body { background: white; padding: 0; }
  .filter-section, .header-actions, .modern-btn, .nav-tabs { display: none !important; }
  .modern-header, .data-table-wrapper { box-shadow: none; background: white; border: 1px solid #ddd; }
}
</style>
@endsection

@section('content')
<div class="content-header row">
    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-left mb-0">تقرير المخازن</h2>
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard_sales.index') }}">الرئيسية</a></li>
                        <li class="breadcrumb-item active">تقرير المخازن</li>
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
          <i class="fas fa-warehouse"></i>
          تقرير المخازن
        </h1>
        <p class="subtitle">تقرير بالكميات المتوفرة والمبيعة والمتبقية في المخازن</p>
      </div>
      <div class="header-actions">
        <button class="modern-btn btn-print" onclick="window.print()">
          <i class="fas fa-print"></i>
          طباعة
        </button>
      </div>
    </div>
    
    <!-- Navigation Tabs -->
    <div class="nav-tabs">
      <a href="{{ route('ABO_FALEH.reportTrac') }}" class="nav-tab">تقرير الموظف</a>
      <a href="{{ route('ABO_FALEH.storehouseReport') }}" class="nav-tab active">تقرير المخازن</a>
    </div>
  </div>

  <div class="filter-section">
    <h3>
      <i class="fas fa-filter"></i>
      تصفية البيانات
    </h3>
    <form method="GET" action="{{ route('ABO_FALEH.storehouseReport') }}" class="filter-grid">
      <div class="form-field">
        <label>اختر المستودع</label>
        <select name="storehouse_id" required>
          <option value="">اختر المستودع</option>
          @foreach ($storehouses as $storehouse)
            <option value="{{ $storehouse->id }}" {{ $selectedStorehouseId == $storehouse->id ? 'selected' : '' }}>
              {{ $storehouse->name }}
            </option>
          @endforeach
        </select>
      </div>

      <div class="form-field">
        <button type="submit" class="modern-btn btn-filter">
          <i class="fas fa-search"></i>
          عرض التقرير
        </button>
      </div>
    </form>
  </div>

  @if($selectedStorehouseId && !$productsWithQuantities->isEmpty())
    <div class="data-table-wrapper">
      <div class="table-title">
        <i class="fas fa-clipboard-list"></i>
        تفاصيل الكميات في المستودع: {{ $productsWithQuantities->first()['storehouse_name'] ?? '' }}
      </div>
      <div class="table-scroll">
        <table>
          <thead>
            <tr>
              <th>اسم المنتج</th>
              <th>الكمية الحالية</th>
              <th>الكمية المباعة</th>
              <th>الكمية المتبقية</th>
            </tr>
          </thead>
          <tbody>
            @php
              $totalCurrent = 0;
              $totalSold = 0;
              $totalRemaining = 0;
            @endphp
            
            @foreach ($productsWithQuantities as $product)
              @php
                $totalCurrent += $product['current_quantity'];
                $totalSold += $product['sold_quantity'];
                $totalRemaining += $product['remaining_quantity'];
              @endphp
              
              <tr>
                <td><strong>{{ $product['product_name'] }}</strong></td>
                <td>
                  <span class="quantity-badge quantity-current">
                    {{ number_format($product['current_quantity'], 2) }}
                  </span>
                </td>
                <td>
                  <span class="quantity-badge quantity-sold">
                    {{ number_format($product['sold_quantity'], 2) }}
                  </span>
                </td>
                <td>
                  <span class="quantity-badge quantity-remaining">
                    {{ number_format($product['remaining_quantity'], 2) }}
                  </span>
                </td>
              </tr>
            @endforeach

            <tr class="total-summary">
              <td>الإجمالي</td>
              <td>{{ number_format($totalCurrent, 2) }}</td>
              <td>{{ number_format($totalSold, 2) }}</td>
              <td>{{ number_format($totalRemaining, 2) }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  @elseif($selectedStorehouseId)
    <div class="empty-message">
      <i class="fas fa-box-open icon"></i>
      <h3>لا توجد منتجات في هذا المستودع</h3>
      <p>لا توجد منتجات متوفرة في المستودع المحدد</p>
    </div>
  @else
    <div class="empty-message">
      <i class="fas fa-warehouse icon"></i>
      <h3>اختر مستودع لعرض التقرير</h3>
      <p>يرجى اختيار مستودع من القائمة أعلاه لعرض تقرير الكميات</p>
    </div>
  @endif
</div>
@endsection