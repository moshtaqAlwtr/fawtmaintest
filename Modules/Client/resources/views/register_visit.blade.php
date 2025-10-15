@extends('sales::master')

@section('title')
    تسجيل زيارة عميل
@stop

@section('content')
    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-left mb-0">تسجيل زيارة عميل</h2>
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="">الرئيسية</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('clients.index') }}">العملاء</a></li>
                            <li class="breadcrumb-item active">تسجيل زيارة</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header bg-primary">
                    <h4 class="card-title text-white">تسجيل زيارة للعميل: {{ $client->trade_name }}</h4>
                </div>
                <div class="card-body">
                    {{-- عرض رسائل الخطأ --}}
                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <h5 class="alert-heading"><i class="fas fa-exclamation-triangle"></i> خطأ في البيانات</h5>
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    {{-- عرض رسالة الخطأ من الـ Exception --}}
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <h5 class="alert-heading"><i class="fas fa-exclamation-circle"></i> خطأ!</h5>
                            <p class="mb-0">{{ session('error') }}</p>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if(session('warning'))
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            {{ session('warning') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    {{-- رسالة تحذيرية للموقع --}}
                    <div class="alert alert-info" role="alert">
                        <i class="fas fa-info-circle"></i>
                        <strong>ملاحظة:</strong> يجب أن تكون ضمن نطاق 0.3 كيلومتر من موقع العميل لتسجيل الزيارة.
                    </div>

                    <div class="client-info mb-4">
                        <h5>معلومات العميل</h5>
                        <table class="table table-borderless">
                            <tr>
                                <th>الاسم التجاري:</th>
                                <td>{{ $client->trade_name }}</td>
                            </tr>
                            <tr>
                                <th>الكود:</th>
                                <td>{{ $client->code }}</td>
                            </tr>
                            <tr>
                                <th>رقم الهاتف:</th>
                                <td>{{ $client->phone ?? 'غير محدد' }}</td>
                            </tr>
                            <tr>
                                <th>العنوان:</th>
                                <td>{{ $client->address ?? 'غير محدد' }}</td>
                            </tr>
                        </table>
                    </div>

                    <form action="{{ route('clients.storeVisit', $client->id) }}" method="POST" id="visitForm">
                        @csrf

                        {{-- حقول الموقع المخفية --}}
                        <input type="hidden" id="current_latitude" name="current_latitude" value="">
                        <input type="hidden" id="current_longitude" name="current_longitude" value="">

                        {{-- عرض حالة الموقع --}}
                        <div class="alert alert-secondary" id="locationStatus">
                            <i class="fas fa-spinner fa-spin"></i> جاري تحديد موقعك الحالي...
                        </div>

                        <div class="form-group">
                            <label for="notes">ملاحظات الزيارة (اختياري):</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror"
                                      id="notes"
                                      name="notes"
                                      rows="4"
                                      placeholder="أضف أي ملاحظات حول الزيارة...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('clients.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> العودة للعملاء
                            </a>
                            <button type="submit" class="btn btn-primary" id="submitBtn" disabled>
                                <i class="fas fa-check"></i> تسجيل الزيارة وعرض التفاصيل
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#notes').focus();

            // الحصول على الموقع الحالي
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        // نجح الحصول على الموقع
                        $('#current_latitude').val(position.coords.latitude);
                        $('#current_longitude').val(position.coords.longitude);

                        $('#locationStatus')
                            .removeClass('alert-secondary')
                            .addClass('alert-success')
                            .html('<i class="fas fa-check-circle"></i> تم تحديد موقعك بنجاح! يمكنك الآن تسجيل الزيارة.');

                        // تفعيل زر الإرسال
                        $('#submitBtn').prop('disabled', false);
                    },
                    function(error) {
                        // فشل الحصول على الموقع
                        let errorMessage = '';
                        switch(error.code) {
                            case error.PERMISSION_DENIED:
                                errorMessage = 'تم رفض الوصول إلى الموقع. يرجى السماح بالوصول للموقع من إعدادات المتصفح.';
                                break;
                            case error.POSITION_UNAVAILABLE:
                                errorMessage = 'معلومات الموقع غير متاحة حالياً.';
                                break;
                            case error.TIMEOUT:
                                errorMessage = 'انتهت مهلة طلب الموقع.';
                                break;
                            default:
                                errorMessage = 'حدث خطأ غير معروف أثناء تحديد الموقع.';
                        }

                        $('#locationStatus')
                            .removeClass('alert-secondary')
                            .addClass('alert-danger')
                            .html('<i class="fas fa-exclamation-triangle"></i> ' + errorMessage);

                        $('#submitBtn').prop('disabled', true);
                    },
                    {
                        enableHighAccuracy: true,
                        timeout: 10000,
                        maximumAge: 0
                    }
                );
            } else {
                $('#locationStatus')
                    .removeClass('alert-secondary')
                    .addClass('alert-danger')
                    .html('<i class="fas fa-exclamation-triangle"></i> متصفحك لا يدعم خدمات الموقع.');

                $('#submitBtn').prop('disabled', true);
            }

            // إرسال الفورم باستخدام AJAX
            $('#visitForm').on('submit', function(e) {
                e.preventDefault();

                const lat = $('#current_latitude').val();
                const lng = $('#current_longitude').val();

                if (!lat || !lng) {
                    alert('يرجى الانتظار حتى يتم تحديد موقعك الحالي');
                    return false;
                }

                // تعطيل زر الإرسال لمنع الإرسال المتكرر
                $('#submitBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> جاري التسجيل...');

                // جمع بيانات الفورم
                const formData = $(this).serialize();
                const formUrl = $(this).attr('action');

                // إرسال الطلب باستخدام AJAX
                $.ajax({
                    url: formUrl,
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        // النقل إلى صفحة الـ index بدون reload
                        window.location.href = "{{ route('clients.index') }}";
                    },
                    error: function(xhr) {
                        // إعادة تفعيل الزر
                        $('#submitBtn').prop('disabled', false).html('<i class="fas fa-check"></i> تسجيل الزيارة وعرض التفاصيل');

                        // عرض رسائل الخطأ
                        if (xhr.status === 422) {
                            // أخطاء التحقق من الصحة
                            const errors = xhr.responseJSON.errors;
                            let errorMessage = '<ul class="mb-0">';
                            $.each(errors, function(key, value) {
                                errorMessage += '<li>' + value[0] + '</li>';
                            });
                            errorMessage += '</ul>';

                            // عرض رسالة الخطأ
                            showAlert('danger', 'خطأ في البيانات', errorMessage);
                        } else {
                            // أخطاء أخرى
                            const errorMsg = xhr.responseJSON?.message || 'حدث خطأ أثناء تسجيل الزيارة';
                            showAlert('danger', 'خطأ!', errorMsg);
                        }
                    }
                });
            });

            // دالة لعرض رسائل التنبيه
            function showAlert(type, title, message) {
                const alertHtml = `
                    <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                        <h5 class="alert-heading"><i class="fas fa-exclamation-circle"></i> ${title}</h5>
                        <div>${message}</div>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                `;

                // إضافة الرسالة في بداية card-body
                $('.card-body').prepend(alertHtml);

                // التمرير للأعلى لرؤية الرسالة
                $('html, body').animate({ scrollTop: 0 }, 300);
            }
        });
    </script>
@endsection
