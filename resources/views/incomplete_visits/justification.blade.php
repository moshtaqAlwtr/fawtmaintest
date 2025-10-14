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
                        <h2 class="content-header-title float-start mb-0">تبرير الزيارات غير المكتملة</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="{{route('dashboard_sales.index')}}">الرئيسية</a>
                                </li>
                                <li class="breadcrumb-item active">تبرير الزيارات</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="content-body">
            <!-- Alert Warning -->
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-warning d-flex align-items-center" role="alert">
                        <div class="me-2">
                            <i class="fas fa-exclamation-triangle" style="font-size: 2rem;"></i>
                        </div>
                        <div>
                            <h4 class="alert-heading mb-1">
                                <i class="fas fa-bell me-50"></i>تنبيه مهم!
                            </h4>
                            <p class="mb-0">
                                لديك <strong>{{ $incompleteVisits->count() }}</strong> زيارة غير مكتملة في خط السير. 
                                يرجى تقديم تبرير لكل زيارة حتى تتمكن من استخدام النظام بشكل طبيعي.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Card -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header border-bottom pb-1">
                            <h4 class="card-title mb-0">
                                <i class="fas fa-clipboard-list me-50"></i>
                                الزيارات غير المكتملة
                            </h4>
                            @if(!$incompleteVisits->isEmpty())
                                <span class="badge bg-danger ms-1">{{ $incompleteVisits->count() }}</span>
                            @endif
                        </div>

                        <div class="card-body">
                            @if($incompleteVisits->isEmpty())
                                <!-- Empty State -->
                                <div class="alert alert-success text-center py-3">
                                    <div class="mb-1">
                                        <i class="fas fa-check-circle" style="font-size: 3rem; opacity: 0.7;"></i>
                                    </div>
                                    <h4 class="alert-heading mb-75">ممتاز!</h4>
                                    <p class="mb-0">لا توجد زيارات غير مكتملة في الوقت الحالي.</p>
                                    <div class="mt-3">
                                        <a href="{{ route('dashboard_sales.index') }}" class="btn btn-primary">
                                            <i class="fas fa-home me-50"></i>العودة للرئيسية
                                        </a>
                                    </div>
                                </div>
                            @else
                                <form action="{{ route('incomplete.visits.submit') }}" method="POST">
                                    @csrf
                                    
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover">
                                            <thead class="table-light">
                                                <tr>
                                                    <th class="text-center" style="width: 20%;">
                                                        <i class="fas fa-building me-50"></i>العميل
                                                    </th>
                                                    <th class="text-center" style="width: 12%;">
                                                        <i class="fas fa-calendar-day me-50"></i>اليوم
                                                    </th>
                                                    <th class="text-center" style="width: 15%;">
                                                        <i class="fas fa-calendar-week me-50"></i>التاريخ
                                                    </th>
                                                    <th class="text-center" style="width: 53%;">
                                                        <i class="fas fa-comment-alt me-50"></i>التبرير
                                                        <span class="text-danger">*</span>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($incompleteVisits as $visit)
                                                    <tr>
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
                                                        <td class="text-center align-middle">
                                                            <span class="badge bg-light-primary">{{ $visit->day_of_week }}</span>
                                                        </td>

                                                        <!-- Date -->
                                                        <td class="text-center align-middle">
                                                            <div class="d-flex flex-column">
                                                                <small class="fw-bold">{{ $visit->year }}</small>
                                                                <small class="text-muted">الأسبوع {{ $visit->week_number }}</small>
                                                            </div>
                                                        </td>

                                                        <!-- Justification Input -->
                                                        <td>
                                                            <textarea
                                                                name="justifications[{{ $visit->id }}]"
                                                                class="form-control @error('justifications.' . $visit->id) is-invalid @enderror"
                                                                rows="3"
                                                                placeholder="يرجى تقديم تبرير واضح ومفصل لعدم إتمام الزيارة..."
                                                                required>{{ old('justifications.' . $visit->id) }}</textarea>
                                                            @error('justifications.' . $visit->id)
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror

                                                            <!-- Help Text -->
                                                            <div class="form-text mt-1">
                                                                <i class="fas fa-info-circle me-25"></i>
                                                                أمثلة على التبريرات: "العميل مغلق اليوم"، "العميل في إجازة"، "تم رفض الزيارة"، إلخ.
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- Actions -->
                                    <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                                        <a href="{{ route('dashboard_sales.index') }}" class="btn btn-secondary">
                                            <i class="fas fa-arrow-left me-50"></i>
                                            العودة للرئيسية
                                        </a>
                                        <button type="submit" class="btn btn-primary btn-lg">
                                            <i class="fas fa-paper-plane me-50"></i>
                                            تقديم جميع التبريرات
                                        </button>
                                    </div>
                                </form>

                                <!-- Info Alert -->
                                <div class="alert alert-info d-flex mt-4" role="alert">
                                    <div class="me-2">
                                        <i class="fas fa-info-circle" style="font-size: 1.5rem;"></i>
                                    </div>
                                    <div>
                                        <h5 class="alert-heading mb-1">
                                            <i class="fas fa-lightbulb me-50"></i>ملاحظة مهمة
                                        </h5>
                                        <ul class="mb-0 ps-3">
                                            <li>سيتم مراجعة تبريراتك من قبل الإدارة في أقرب وقت ممكن</li>
                                            <li>بعد الموافقة على التبريرات، ستتمكن من استخدام النظام بشكل طبيعي</li>
                                            <li>في حالة رفض أي تبرير، سيُطلب منك تعديله وإعادة إرساله</li>
                                            <li>يرجى التأكد من وضوح ودقة التبريرات المقدمة</li>
                                        </ul>
                                    </div>
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
    // Form Validation
    $('form').on('submit', function(e) {
        let isEmpty = false;
        $('textarea[name^="justifications"]').each(function() {
            if ($(this).val().trim() === '') {
                isEmpty = true;
                $(this).addClass('is-invalid');
            } else {
                $(this).removeClass('is-invalid');
            }
        });

        if (isEmpty) {
            e.preventDefault();
            alert('يرجى ملء جميع حقول التبريرات قبل الإرسال');
            return false;
        }

        return confirm('هل أنت متأكد من تقديم جميع التبريرات؟');
    });

    // Remove invalid class on input
    $('textarea[name^="justifications"]').on('input', function() {
        if ($(this).val().trim() !== '') {
            $(this).removeClass('is-invalid');
        }
    });
});
</script>
@endsection