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
                        <h2 class="content-header-title float-start mb-0">تعديل التبرير</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="{{route('dashboard_sales.index')}}">الرئيسية</a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="{{ route('employee.visit-justifications.index') }}">تبريراتي</a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="{{ route('employee.visit-justifications.show', $justification->id) }}">تفاصيل التبرير</a>
                                </li>
                                <li class="breadcrumb-item active">تعديل التبرير</li>
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
                                <i class="fas fa-edit me-50"></i>
                                تعديل تبرير الزيارة
                            </h4>
                        </div>

                        <div class="card-body">
                            <form action="{{ route('employee.visit-justifications.update', $justification->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <!-- Client and Visit Info Cards -->
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

                                <!-- Justification Input -->
                                <div class="row mt-2">
                                    <div class="col-12">
                                        <div class="card bg-light border-0">
                                            <div class="card-body">
                                                <div class="form-group mb-0">
                                                    <label for="justification" class="form-label fw-bold">
                                                        <i class="fas fa-comment-alt text-warning me-50"></i>
                                                        التبرير 
                                                        <span class="text-danger">*</span>
                                                    </label>
                                                    <textarea
                                                        name="justification"
                                                        id="justification"
                                                        class="form-control @error('justification') is-invalid @enderror"
                                                        rows="6"
                                                        placeholder="يرجى تقديم تبرير واضح ومفصل لعدم إتمام الزيارة..."
                                                        required>{{ old('justification', $justification->justification) }}</textarea>
                                                    @error('justification')
                                                        <div class="invalid-feedback">
                                                            <i class="fas fa-exclamation-circle me-25"></i>
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                    <div class="form-text mt-2">
                                                        <i class="fas fa-info-circle me-25"></i>
                                                        يرجى كتابة تبرير واضح ومفصل يوضح سبب عدم إتمام الزيارة. 
                                                        أمثلة: "العميل غير موجود في موقعه"، "العميل رفض الزيارة"، "المحل مغلق لظروف طارئة"، إلخ.
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Help Alert -->
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <div class="alert alert-info d-flex" role="alert">
                                            <div class="me-2">
                                                <i class="fas fa-lightbulb" style="font-size: 1.5rem;"></i>
                                            </div>
                                            <div>
                                                <h5 class="alert-heading mb-1">
                                                    <i class="fas fa-info-circle me-50"></i>نصائح لكتابة تبرير جيد
                                                </h5>
                                                <ul class="mb-0 ps-3">
                                                    <li>اكتب تبريراً واضحاً ومحدداً</li>
                                                    <li>اذكر السبب الحقيقي لعدم إتمام الزيارة</li>
                                                    <li>تجنب العبارات المبهمة أو العامة</li>
                                                    <li>كن صادقاً ودقيقاً في وصف الموقف</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                                    <a href="{{ route('employee.visit-justifications.show', $justification->id) }}" 
                                       class="btn btn-secondary">
                                        <i class="fas fa-times me-50"></i>
                                        إلغاء
                                    </a>
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="fas fa-save me-50"></i>
                                        تحديث التبرير
                                    </button>
                                </div>
                            </form>
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
    // Form validation
    $('form').on('submit', function(e) {
        const justification = $('#justification').val().trim();
        
        if (justification === '') {
            e.preventDefault();
            $('#justification').addClass('is-invalid');
            alert('يرجى كتابة التبرير قبل الحفظ');
            return false;
        }

        if (justification.length < 10) {
            e.preventDefault();
            $('#justification').addClass('is-invalid');
            alert('التبرير قصير جداً. يرجى كتابة تبرير أكثر تفصيلاً (10 أحرف على الأقل)');
            return false;
        }

        return confirm('هل أنت متأكد من تحديث التبرير؟');
    });

    // Remove invalid class on input
    $('#justification').on('input', function() {
        if ($(this).val().trim() !== '') {
            $(this).removeClass('is-invalid');
        }
    });

    // Character counter
    const maxLength = 500;
    $('#justification').after('<small class="form-text text-muted" id="charCount"></small>');
    
    function updateCharCount() {
        const length = $('#justification').val().length;
        $('#charCount').html(`<i class="fas fa-keyboard me-25"></i>عدد الأحرف: ${length}`);
    }
    
    $('#justification').on('input', updateCharCount);
    updateCharCount();
});
</script>
@endsection