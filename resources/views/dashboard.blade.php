@extends('main-master')
@php($hideSidebar = true)

@section('content')
<style>
/* إخفاء السايدبار */
.main-menu,
.vertical-layout .main-menu {
    display: none !important;
}

/* إزالة أي مساحة جانبية */
.app-content,
.content-wrapper,
.content-area,
.main-content,
.content-body {
    margin: 0 !important;
    padding: 0 !important;
    width: 100% !important;
    max-width: 100% !important;
}

html body .content.app-content {
    margin-left: 0 !important;
}

/* الحاوية الرئيسية */
.dashboard-wrapper {
    width: 100%;
    box-sizing: border-box;
    padding: 2rem 3rem;
    margin-top: 90px;
}

/* كارد الهوية */
.system-card {
    border: 2px solid #7367F0;
    border-radius: 18px;
    background: linear-gradient(145deg, #ffffff, #f9f9ff);
    padding: 2rem;
    text-align: center;
    margin-bottom: 2.5rem;
    box-shadow: 0 5px 18px rgba(115, 103, 240, 0.08);
}

.system-card h1 {
    font-size: 1.8rem;
    font-weight: 800;
    color: #4B4B4B;
    margin-bottom: 0.5rem;
    letter-spacing: -0.5px;
}

.system-card .highlight {
    color: #7367F0;
}

.system-card p {
    font-size: 1rem;
    color: #666;
    margin: 0;
}

/* شبكة الكروت */
.dashboard-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1.5rem;
    align-items: stretch;
}

/* تصميم الكرت */
.card {
    border-radius: 18px !important;
    transition: all 0.3s ease;
    cursor: pointer;
    background: #fff;
    text-align: start;
    height: 160px;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 1.5rem;
    box-sizing: border-box;
    border: 1px solid #f0f0f0;
}

.card-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 100%;
}

.card:hover {
    transform: translateY(-6px);
    box-shadow: 0 8px 22px rgba(0, 0, 0, 0.1);
    border-color: #ddd;
}

.card h4 {
    font-weight: 700;
    font-size: 1.05rem;
    margin-bottom: 0.3rem;
    color: #333;
    line-height: 1.3;
}

.card p {
    font-size: 0.9rem;
    color: #777;
    margin: 0;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.card-link {
    text-decoration: none !important;
    color: inherit !important;
    display: block;
}

body {
    overflow-x: hidden !important;
}
</style>

<div class="dashboard-wrapper">

    {{-- كارد الهوية --}}
    <div class="system-card">
        <h1>نظام <span class="highlight">فوتره سمارت</span></h1>
        <p>نظام ERP متكامل لإدارة أعمالك — المبيعات، الموارد، المخزون، المالية والمزيد.</p>
    </div>

    <div class="dashboard-grid">

        {{-- إدارة المبيعات والعملاء --}}
        <a href="{{ route('sales.department.dashboard') }}" class="card-link">
            <div class="card">
                <div class="card-content">
                    <div>
                        <h4>إدارة المبيعات والعملاء</h4>
                        <p>مبيعات - فواتير - عملاء</p>
                    </div>
                    <div class="avatar bg-rgba-primary p-50 m-0">
                        <div class="avatar-content">
                            <i class="feather icon-shopping-cart text-primary font-medium-5"></i>
                        </div>
                    </div>
                </div>
            </div>
        </a>

        {{-- المتجر الإلكتروني --}}
        <a href="{{ url('/store') }}" class="card-link">
            <div class="card">
                <div class="card-content">
                    <div>
                        <h4>المتجر الإلكتروني</h4>
                        <p>طلبات أونلاين - الدفع - العملاء</p>
                    </div>
                    <div class="avatar bg-rgba-success p-50 m-0">
                        <div class="avatar-content">
                            <i class="feather icon-globe text-success font-medium-5"></i>
                        </div>
                    </div>
                </div>
            </div>
        </a>

        {{-- إدارة الوحدات والإيجارات --}}
        <a href="{{ url('/rents') }}" class="card-link">
            <div class="card">
                <div class="card-content">
                    <div>
                        <h4>إدارة الوحدات والإيجارات</h4>
                        <p>عقود - دفعات - مستأجرين</p>
                    </div>
                    <div class="avatar bg-rgba-warning p-50 m-0">
                        <div class="avatar-content">
                            <i class="feather icon-home text-warning font-medium-5"></i>
                        </div>
                    </div>
                </div>
            </div>
        </a>

        {{-- إدارة المخزون والمشتريات --}}
        <a href="{{route('stock.department.dashboard')}}" class="card-link">
            <div class="card">
                <div class="card-content">
                    <div>
                        <h4>إدارة المخزون والمشتريات</h4>
                        <p>مخزون - توريد - فواتير شراء</p>
                    </div>
                    <div class="avatar bg-rgba-danger p-50 m-0">
                        <div class="avatar-content">
                            <i class="feather icon-package text-danger font-medium-5"></i>
                        </div>
                    </div>
                </div>
            </div>
        </a>

        {{-- المالية والحسابات العامة --}}
        <a href="{{ route('account.department.dashboard') }}" class="card-link">
            <div class="card">
                <div class="card-content">
                    <div>
                        <h4>المالية والحسابات العامة</h4>
                        <p>قيود - حسابات - ميزانية</p>
                    </div>
                    <div class="avatar bg-rgba-info p-50 m-0">
                        <div class="avatar-content">
                            <i class="feather icon-dollar-sign text-info font-medium-5"></i>
                        </div>
                    </div>
                </div>
            </div>
        </a>

        {{-- إدارة المهام --}}
        <a href="{{ route('task.department.dashboard') }}" class="card-link">
            <div class="card">
                <div class="card-content">
                    <div>
                        <h4>إدارة المهام</h4>
                        <p>المتابعة - الإنجاز - التقارير</p>
                    </div>
                    <div class="avatar bg-rgba-secondary p-50 m-0">
                        <div class="avatar-content">
                            <i class="feather icon-check-square text-secondary font-medium-5"></i>
                        </div>
                    </div>
                </div>
            </div>
        </a>

        {{-- إدارة الموارد البشرية --}}
        <a href="{{  route('hr.department.dashboard') }} " class="card-link">
            <div class="card">
                <div class="card-content">
                    <div>
                        <h4>إدارة الموارد البشرية</h4>
                        <p>موظفين - رواتب - حضور وانصراف</p>
                    </div>
                    <div class="avatar bg-rgba-success p-50 m-0">
                        <div class="avatar-content">
                            <i class="feather icon-users text-success font-medium-5"></i>
                        </div>
                    </div>
                </div>
            </div>
        </a>

        {{-- الإعدادات العامة --}}
        <a href="{{ route('settings.department.dashboard') }}" class="card-link">
            <div class="card">
                <div class="card-content">
                    <div>
                        <h4>الإعدادات العامة</h4>
                        <p>المستخدمين - الصلاحيات - النظام</p>
                    </div>
                    <div class="avatar bg-rgba-dark p-50 m-0">
                        <div class="avatar-content">
                            <i class="feather icon-settings text-dark font-medium-5"></i>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>
@endsection
