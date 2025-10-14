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
                        <h2 class="content-header-title float-start mb-0">تبريراتي</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="{{route('dashboard_sales.index')}}">الرئيسية</a>
                                </li>
                                <li class="breadcrumb-item active">تبريراتي</li>
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
                                <i class="fas fa-list-alt me-50"></i>
                                جميع تبريراتي
                            </h4>
                            @if(!$justifications->isEmpty())
                                <span class="badge bg-primary ms-1">{{ $justifications->total() }}</span>
                            @endif
                        </div>

                        <div class="card-body">
                            @if($justifications->isEmpty())
                                <!-- Empty State -->
                                <div class="alert alert-info text-center py-3">
                                    <div class="mb-1">
                                        <i class="fas fa-clipboard-list" style="font-size: 3rem; opacity: 0.7;"></i>
                                    </div>
                                    <h4 class="alert-heading mb-75">ممتاز!</h4>
                                    <p class="mb-0">لا توجد تبريرات مقدمة في الوقت الحالي.</p>
                                </div>
                            @else
                                <!-- Table -->
                                <div class="table-responsive">
                                    <table class="table table-hover table-bordered datatable">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="text-center" style="width: 20%;">
                                                    <i class="fas fa-building me-50"></i>العميل
                                                </th>
                                                <th class="text-center" style="width: 10%;">
                                                    <i class="fas fa-calendar-day me-50"></i>اليوم
                                                </th>
                                                <th class="text-center" style="width: 15%;">
                                                    <i class="fas fa-calendar-week me-50"></i>التاريخ
                                                </th>
                                                <th class="text-center" style="width: 25%;">
                                                    <i class="fas fa-comment-alt me-50"></i>التبرير
                                                </th>
                                                <th class="text-center" style="width: 15%;">
                                                    <i class="fas fa-info-circle me-50"></i>الحالة
                                                </th>
                                                <th class="text-center" style="width: 15%;">
                                                    <i class="fas fa-cogs me-50"></i>الإجراءات
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($justifications as $justification)
                                                <tr>
                                                    <!-- Client Info -->
                                                    <td>
                                                        <div class="d-flex flex-column">
                                                            <strong class="mb-25">{{ $justification->client->trade_name ?? 'غير محدد' }}</strong>
                                                            <small class="text-muted">
                                                                <i class="fas fa-hashtag me-25"></i>
                                                                {{ $justification->client->code ?? '---' }}
                                                            </small>
                                                        </div>
                                                    </td>

                                                    <!-- Day -->
                                                    <td class="text-center">
                                                        <span class="badge bg-light-primary">{{ $justification->day_of_week }}</span>
                                                    </td>

                                                    <!-- Date -->
                                                    <td class="text-center">
                                                        <div class="d-flex flex-column">
                                                            <small class="fw-bold">{{ $justification->year }}</small>
                                                            <small class="text-muted">الأسبوع {{ $justification->week_number }}</small>
                                                        </div>
                                                    </td>

                                                    <!-- Justification -->
                                                    <td>
                                                        <div class="text-wrap" style="max-width: 250px;">
                                                            {{ Str::limit($justification->justification, 50) }}
                                                        </div>
                                                    </td>

                                                    <!-- Status -->
                                                    <td class="text-center">
                                                        @if($justification->isJustificationApproved())
                                                            <span class="badge bg-success">
                                                                <i class="fas fa-check-circle me-25"></i>
                                                                معتمد
                                                            </span>
                                                        @elseif($justification->isJustificationRejected())
                                                            <span class="badge bg-danger">
                                                                <i class="fas fa-times-circle me-25"></i>
                                                                مرفوض
                                                            </span>
                                                        @else
                                                            <span class="badge bg-warning">
                                                                <i class="fas fa-clock me-25"></i>
                                                                بانتظار الموافقة
                                                            </span>
                                                        @endif
                                                    </td>

                                                    <!-- Actions -->
                                                    <td class="text-center">
                                                        <div class="btn-group" role="group">
                                                            <a href="{{ route('employee.visit-justifications.show', $justification->id) }}"
                                                               class="btn btn-info btn-sm" 
                                                               title="عرض التفاصيل"
                                                               data-bs-toggle="tooltip">
                                                                <i class="fas fa-eye"></i>
                                                                <span class="d-none d-lg-inline ms-25">عرض</span>
                                                            </a>
                                                            @if($justification->isJustificationPending())
                                                                <a href="{{ route('employee.visit-justifications.edit', $justification->id) }}"
                                                                   class="btn btn-warning btn-sm" 
                                                                   title="تعديل"
                                                                   data-bs-toggle="tooltip">
                                                                    <i class="fas fa-edit"></i>
                                                                    <span class="d-none d-lg-inline ms-25">تعديل</span>
                                                                </a>
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Pagination -->
                                <div class="d-flex justify-content-center mt-4">
                                    {{ $justifications->links() }}
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

@section('scripts')
<script>
$(document).ready(function() {
    // Initialize DataTable
    $('.datatable').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/ar.json'
        },
        pageLength: 10,
        order: [[3, 'desc']]
    });

    // Initialize Tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endsection