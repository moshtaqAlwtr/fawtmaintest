@extends('master')

@section('content')
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <!-- Header Section -->
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">إدارة تبريرات الزيارات</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('dashboard_sales.index') }}">الرئيسية</a>
                                </li>
                                <li class="breadcrumb-item active">تبريرات الزيارات</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="content-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header border-bottom pb-1">
                            <h4 class="card-title mb-0">
                                <i class="fas fa-clipboard-check me-50"></i>
                                تبريرات الزيارات بانتظار الموافقة
                            </h4>
                            @if(!$justifications->isEmpty())
                                <span class="badge bg-warning ms-1">{{ $justifications->count() }}</span>
                            @endif
                        </div>

                        <div class="card-body">
                            @if($justifications->isEmpty())
                                <!-- Empty State -->
                                <div class="alert alert-info text-center py-3">
                                    <div class="mb-1">
                                        <i class="fas fa-check-circle" style="font-size: 3rem; opacity: 0.7;"></i>
                                    </div>
                                    <h4 class="alert-heading mb-75">ممتاز!</h4>
                                    <p class="mb-0">لا توجد تبريرات بانتظار الموافقة في الوقت الحالي.</p>
                                </div>
                            @else
                                <!-- Table -->
                                <div class="table-responsive">
                                    <table class="table table-hover table-bordered datatable">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="text-center" style="width: 15%;">
                                                    <i class="fas fa-user me-50"></i>الموظف
                                                </th>
                                                <th class="text-center" style="width: 15%;">
                                                    <i class="fas fa-building me-50"></i>العميل
                                                </th>
                                                <th class="text-center" style="width: 10%;">
                                                    <i class="fas fa-calendar-day me-50"></i>اليوم
                                                </th>
                                                <th class="text-center" style="width: 12%;">
                                                    <i class="fas fa-calendar-week me-50"></i>التاريخ
                                                </th>
                                                <th class="text-center" style="width: 20%;">
                                                    <i class="fas fa-comment-alt me-50"></i>التبرير
                                                </th>
                                                <th class="text-center" style="width: 13%;">
                                                    <i class="fas fa-clock me-50"></i>تاريخ التبرير
                                                </th>
                                                <th class="text-center" style="width: 15%;">
                                                    <i class="fas fa-cogs me-50"></i>الإجراءات
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($justifications as $visit)
                                                <tr>
                                                    <!-- Employee Info -->
                                                    <td>
                                                        <div class="d-flex flex-column">
                                                            <strong class="mb-25">{{ $visit->employee->name ?? 'غير محدد' }}</strong>
                                                            <small class="text-muted">
                                                                <i class="fas fa-map-marker-alt me-25"></i>
                                                                {{ $visit->employee->branch->name ?? 'غير محدد' }}
                                                            </small>
                                                        </div>
                                                    </td>

                                                    <!-- Client Info -->
                                                    <td>
                                                        <div class="d-flex flex-column">
                                                            <strong class="mb-25">{{ $visit->client->trade_name ?? 'غير محدد' }}</strong>
                                                            <small class="text-muted">
                                                                <i class="fas fa-hashtag me-25"></i>
                                                                {{ $visit->client->code ?? '---' }}
                                                            </small>
                                                        </div>
                                                    </td>

                                                    <!-- Day -->
                                                    <td class="text-center">
                                                        <span class="badge bg-light-primary">{{ $visit->day_of_week }}</span>
                                                    </td>

                                                    <!-- Date -->
                                                    <td class="text-center">
                                                        <div class="d-flex flex-column">
                                                            <small class="fw-bold">{{ $visit->year }}</small>
                                                            <small class="text-muted">الأسبوع {{ $visit->week_number }}</small>
                                                        </div>
                                                    </td>

                                                    <!-- Justification -->
                                                    <td>
                                                        <div class="text-wrap" style="max-width: 200px;">
                                                            {{ $visit->justification }}
                                                        </div>
                                                    </td>

                                                    <!-- Justification Date -->
                                                    <td class="text-center">
                                                        @if($visit->justification_date)
                                                            <small>{{ $visit->justification_date->format('Y-m-d') }}</small>
                                                            <br>
                                                            <small class="text-muted">{{ $visit->justification_date->format('H:i') }}</small>
                                                        @else
                                                            <span class="text-muted">---</span>
                                                        @endif
                                                    </td>

                                                    <!-- Actions -->
                                                    <td class="text-center">
                                                        <div class="btn-group" role="group" aria-label="إجراءات التبرير">
                                                            <!-- Approve -->
                                                            <form action="{{ route('admin.visit-justifications.approve', $visit->id) }}" 
                                                                  method="POST" 
                                                                  class="d-inline"
                                                                  onsubmit="return confirm('هل أنت متأكد من الموافقة على هذا التبرير؟');">
                                                                @csrf
                                                                <button type="submit" 
                                                                        class="btn btn-success btn-sm" 
                                                                        title="موافقة"
                                                                        data-bs-toggle="tooltip">
                                                                    <i class="fas fa-check"></i>
                                                                    <span class="d-none d-md-inline ms-25">موافقة</span>
                                                                </button>
                                                            </form>

                                                            <!-- Reject -->
                                                            <form action="{{ route('admin.visit-justifications.reject', $visit->id) }}" 
                                                                  method="POST" 
                                                                  class="d-inline"
                                                                  onsubmit="return confirm('هل أنت متأكد من رفض هذا التبرير؟');">
                                                                @csrf
                                                                <button type="submit" 
                                                                        class="btn btn-danger btn-sm" 
                                                                        title="رفض"
                                                                        data-bs-toggle="tooltip">
                                                                    <i class="fas fa-times"></i>
                                                                    <span class="d-none d-md-inline ms-25">رفض</span>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize DataTable
    $('.datatable').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/ar.json'
        },
        pageLength: 10,
        order: [[5, 'desc']] // ترتيب حسب تاريخ التبرير
    });

    // Initialize Tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endpush