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
                        <h2 class="content-header-title float-start mb-0">تفاصيل التبرير</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="{{route('dashboard_sales.index')}}">الرئيسية</a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="{{ route('employee.visit-justifications.index') }}">تبريراتي</a>
                                </li>
                                <li class="breadcrumb-item active">تفاصيل التبرير</li>
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
                                <i class="fas fa-file-alt me-50"></i>
                                تفاصيل التبرير للزيارة
                            </h4>
                        </div>

                        <div class="card-body">
                            <!-- Client and Visit Info -->
                            <div class="row">
                                <!-- Client Information -->
                                <div class="col-md-6 mb-3">
                                    <div class="card bg-light border-0 h-100">
                                        <div class="card-body">
                                            <h5 class="card-title mb-3">
                                                <i class="fas fa-building text-primary me-50"></i>
                                                معلومات العميل
                                            </h5>
                                            <table class="table table-sm table-borderless mb-0">
                                                <tr>
                                                    <td class="fw-bold" style="width: 40%;">
                                                        <i class="fas fa-user me-50"></i>اسم العميل:
                                                    </td>
                                                    <td>{{ $justification->client->trade_name ?? 'غير محدد' }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-bold">
                                                        <i class="fas fa-hashtag me-50"></i>كود العميل:
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-primary">{{ $justification->client->code ?? '---' }}</span>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <!-- Visit Information -->
                                <div class="col-md-6 mb-3">
                                    <div class="card bg-light border-0 h-100">
                                        <div class="card-body">
                                            <h5 class="card-title mb-3">
                                                <i class="fas fa-calendar-check text-info me-50"></i>
                                                معلومات الزيارة
                                            </h5>
                                            <table class="table table-sm table-borderless mb-0">
                                                <tr>
                                                    <td class="fw-bold" style="width: 40%;">
                                                        <i class="fas fa-calendar-day me-50"></i>اليوم:
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-light-primary">{{ $justification->day_of_week }}</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-bold">
                                                        <i class="fas fa-calendar-week me-50"></i>التاريخ:
                                                    </td>
                                                    <td>{{ $justification->year }} - الأسبوع {{ $justification->week_number }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Justification Text -->
                            <div class="row mt-2">
                                <div class="col-12">
                                    <div class="card bg-light border-0">
                                        <div class="card-body">
                                            <h5 class="card-title mb-3">
                                                <i class="fas fa-comment-alt text-warning me-50"></i>
                                                نص التبرير
                                            </h5>
                                            <div class="alert alert-secondary mb-0">
                                                <p class="mb-0" style="white-space: pre-wrap;">{{ $justification->justification }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Status and Actions -->
                            <div class="row mt-2">
                                <!-- Status -->
                                <div class="col-md-8 mb-3">
                                    <div class="card border-0 h-100">
                                        <div class="card-body">
                                            <h5 class="card-title mb-3">
                                                <i class="fas fa-info-circle me-50"></i>
                                                حالة التبرير
                                            </h5>
                                            
                                            @if($justification->isJustificationApproved())
                                                <div class="alert alert-success d-flex align-items-start mb-0">
                                                    <div class="me-2" style="font-size: 2rem;">
                                                        <i class="fas fa-check-circle"></i>
                                                    </div>
                                                    <div>
                                                        <h5 class="alert-heading mb-1">
                                                            <i class="fas fa-thumbs-up me-50"></i>معتمد
                                                        </h5>
                                                        <p class="mb-1">
                                                            تمت الموافقة على التبرير من قبل: 
                                                            <strong>{{ $justification->approvedBy->name ?? 'مدير النظام' }}</strong>
                                                        </p>
                                                        <small class="text-muted">
                                                            <i class="fas fa-clock me-25"></i>
                                                            تاريخ الموافقة: {{ $justification->updated_at->format('Y-m-d H:i') }}
                                                        </small>
                                                    </div>
                                                </div>
                                            @elseif($justification->isJustificationRejected())
                                                <div class="alert alert-danger d-flex align-items-start mb-0">
                                                    <div class="me-2" style="font-size: 2rem;">
                                                        <i class="fas fa-times-circle"></i>
                                                    </div>
                                                    <div>
                                                        <h5 class="alert-heading mb-1">
                                                            <i class="fas fa-ban me-50"></i>مرفوض
                                                        </h5>
                                                        <p class="mb-1">
                                                            تم رفض التبرير من قبل: 
                                                            <strong>{{ $justification->approvedBy->name ?? 'مدير النظام' }}</strong>
                                                        </p>
                                                        <small class="text-muted">
                                                            <i class="fas fa-clock me-25"></i>
                                                            تاريخ الرفض: {{ $justification->updated_at->format('Y-m-d H:i') }}
                                                        </small>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="alert alert-warning d-flex align-items-start mb-0">
                                                    <div class="me-2" style="font-size: 2rem;">
                                                        <i class="fas fa-clock"></i>
                                                    </div>
                                                    <div>
                                                        <h5 class="alert-heading mb-1">
                                                            <i class="fas fa-hourglass-half me-50"></i>بانتظار الموافقة
                                                        </h5>
                                                        <p class="mb-1">التبرير قيد المراجعة من قبل الإدارة</p>
                                                        <small class="text-muted">
                                                            <i class="fas fa-clock me-25"></i>
                                                            تاريخ التقديم: {{ $justification->justification_date ? $justification->justification_date->format('Y-m-d H:i') : '---' }}
                                                        </small>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Actions Card -->
                                @if($justification->isJustificationPending())
                                <div class="col-md-4 mb-3">
                                    <div class="card bg-light border-0 h-100">
                                        <div class="card-body">
                                            <h5 class="card-title mb-3">
                                                <i class="fas fa-cogs text-primary me-50"></i>
                                                الإجراءات المتاحة
                                            </h5>
                                            <a href="{{ route('employee.visit-justifications.edit', $justification->id) }}"
                                               class="btn btn-warning w-100">
                                                <i class="fas fa-edit me-50"></i>
                                                تعديل التبرير
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>

                            <!-- Back Button -->
                            <div class="d-flex justify-content-start mt-4 pt-3 border-top">
                                <a href="{{ route('employee.visit-justifications.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-50"></i>
                                    العودة إلى التبريرات
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection