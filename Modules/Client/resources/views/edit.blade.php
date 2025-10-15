@extends('master')

@section('title')
    تعديل عميل
@stop

@section('content')
    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-left mb-0">تعديل عميل</h2>
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="">الرئيسيه</a></li>
                            <li class="breadcrumb-item active">تعديل</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="content-body">
        <form id="clientForm" action="{{ route('clients.update', $client->id) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- حقلين مخفيين لتخزين الإحداثيات -->
            <input type="hidden" name="latitude" id="latitude" value="{{ $location->latitude ?? '' }}">
            <input type="hidden" name="longitude" id="longitude" value="{{ $location->longitude ?? '' }}">

            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <div>
                            <label>الحقول التي عليها علامة <span style="color: red">*</span> الزامية</label>
                        </div>

                        <div>
                            <a href="{{ route('clients.index') }}" class="btn btn-outline-danger">
                                <i class="fa fa-ban"></i> الغاء
                            </a>
                            <button type="submit" class="btn btn-outline-primary" id="submitBtn">
                                <i class="fa fa-save"></i> حفظ التعديلات
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">بيانات العميل</h4>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                <div class="form-body">
                                    <div class="row">
                                        <!-- الاسم التجاري -->
                                        <div class="col-12 mb-3">
                                            <div class="form-group">
                                                <label for="trade_name">الاسم التجاري <span
                                                        class="text-danger">*</span></label>
                                                <div class="position-relative has-icon-left">
                                                    <input type="text" name="trade_name" id="trade_name"
                                                        class="form-control"
                                                        value="{{ old('trade_name', $client->trade_name) }}">
                                                    <div class="form-control-position">
                                                        <i class="feather icon-briefcase"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- الاسم الأول والأخير -->
                                        <div class="col-md-6 col-12 mb-3">
                                            <div class="form-group">
                                                <label for="first_name">الاسم الأول</label>
                                                <div class="position-relative has-icon-left">
                                                    <input type="text" name="first_name" id="first_name"
                                                        class="form-control"
                                                        value="{{ old('first_name', $client->first_name) }}">
                                                    <div class="form-control-position">
                                                        <i class="feather icon-user"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12 mb-3">
                                            <div class="form-group">
                                                <label for="last_name">الاسم الأخير</label>
                                                <div class="position-relative has-icon-left">
                                                    <input type="text" name="last_name" id="last_name"
                                                        class="form-control"
                                                        value="{{ old('last_name', $client->last_name) }}">
                                                    <div class="form-control-position">
                                                        <i class="feather icon-user"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- الهاتف والجوال -->
                                        <div class="col-md-6 col-12 mb-3">
                                            <div class="form-group">
                                                <label for="phone">الهاتف</label>
                                                <div class="position-relative has-icon-left">
                                                    <input type="text" name="phone" id="phone" class="form-control"
                                                        value="{{ old('phone', $client->phone) }}">
                                                    <div class="form-control-position">
                                                        <i class="feather icon-phone"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12 mb-3">
                                            <div class="form-group">
                                                <label for="mobile">جوال</label>
                                                <div class="position-relative has-icon-left">
                                                    <input type="text" name="mobile" id="mobile" class="form-control"
                                                        value="{{ old('mobile', $client->mobile) }}">
                                                    <div class="form-control-position">
                                                        <i class="feather icon-smartphone"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- عنوان الشارع -->
                                        <div class="col-md-6 col-12 mb-3">
                                            <div class="form-group">
                                                <label for="street1">عنوان الشارع 1</label>
                                                <div class="position-relative has-icon-left">
                                                    <input type="text" name="street1" id="street1"
                                                        class="form-control"
                                                        value="{{ old('street1', $client->street1) }}">
                                                    <div class="form-control-position">
                                                        <i class="feather icon-map-pin"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12 mb-3">
                                            <div class="form-group">
                                                <label for="street2">عنوان الشارع 2</label>
                                                <div class="position-relative has-icon-left">
                                                    <input type="text" name="street2" id="street2"
                                                        class="form-control"
                                                        value="{{ old('street2', $client->street2) }}">
                                                    <div class="form-control-position">
                                                        <i class="feather icon-map-pin"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- المدينة والمنطقة والرمز البريدي -->
                                        <div class="col-md-4 col-12 mb-3">
                                            <div class="form-group">
                                                <label for="city">المدينة</label>
                                                <div class="position-relative has-icon-left">
                                                    <input type="text" name="city" id="city"
                                                        class="form-control" value="{{ old('city', $client->city) }}">
                                                    <div class="form-control-position">
                                                        <i class="feather icon-map"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-12 mb-3">
                                            <div class="form-group">
                                                <label for="region">المنطقة</label>
                                                <div class="position-relative has-icon-left">
                                                    <input type="text" name="region" id="region"
                                                        class="form-control"
                                                        value="{{ old('region', $client->region) }}">
                                                    <div class="form-control-position">
                                                        <i class="feather icon-map"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-12 mb-3">
                                            <div class="form-group">
                                                <label for="postal_code">الرمز البريدي</label>
                                                <div class="position-relative has-icon-left">
                                                    <input type="text" name="postal_code" id="postal_code"
                                                        class="form-control"
                                                        value="{{ old('postal_code', $client->postal_code) }}">
                                                    <div class="form-control-position">
                                                        <i class="feather icon-mail"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- البلد -->
                                        <div class="col-12 mb-3">
                                            <div class="form-group">
                                                <label for="country">البلد</label>
                                                <select name="country" id="country" class="form-control">
                                                    <option value="SA"
                                                        {{ old('country', $client->country) == 'SA' ? 'selected' : '' }}>
                                                        المملكة العربية السعودية (SA)</option>
                                                </select>
                                            </div>
                                        </div>

                                        <!-- الرقم الضريبي والسجل التجاري -->
                                        <div class="col-md-6 col-12 mb-3">
                                            <div class="form-group">
                                                <label for="tax_number">الرقم الضريبي (اختياري)</label>
                                                <div class="position-relative has-icon-left">
                                                    <input type="text" name="tax_number" id="tax_number"
                                                        class="form-control"
                                                        value="{{ old('tax_number', $client->tax_number) }}">
                                                    <div class="form-control-position">
                                                        <i class="feather icon-file-text"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12 mb-3">
                                            <div class="form-group">
                                                <label for="commercial_registration">سجل تجاري (اختياري)</label>
                                                <div class="position-relative has-icon-left">
                                                    <input type="text" name="commercial_registration"
                                                        id="commercial_registration" class="form-control"
                                                        value="{{ old('commercial_registration', $client->commercial_registration) }}">
                                                    <div class="form-control-position">
                                                        <i class="feather icon-file"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- الحد الائتماني والمدة الائتمانية -->
                                        @foreach ($GeneralClientSettings as $GeneralClientSetting)
                                            @if ($GeneralClientSetting->is_active)
                                                @if ($GeneralClientSetting->key == 'credit_limit')
                                                    <div class="col-md-6 col-12 mb-3">
                                                        <div class="form-group">
                                                            <label for="credit_limit">الحد الائتماني</label>
                                                            <div class="position-relative has-icon-left">
                                                                <input type="number" name="credit_limit"
                                                                    id="credit_limit" class="form-control"
                                                                    value="{{ old('credit_limit', $client->credit_limit ?? 0) }}">
                                                                <div class="form-control-position">
                                                                    <span>SAR</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endif
                                        @endforeach

                                        @foreach ($GeneralClientSettings as $GeneralClientSetting)
                                            @if ($GeneralClientSetting->is_active)
                                                @if ($GeneralClientSetting->key == 'credit_duration')
                                                    <div class="col-md-6 col-12 mb-3">
                                                        <div class="form-group">
                                                            <label for="credit_period">المدة الائتمانية</label>
                                                            <div class="position-relative has-icon-left">
                                                                <input type="number" name="credit_period"
                                                                    id="credit_period" class="form-control"
                                                                    value="{{ old('credit_period', $client->credit_period ?? 0) }}">
                                                                <div class="form-control-position">
                                                                    <span>أيام</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endif
                                        @endforeach

                                        <!-- المجموعة ونوع الزيارة -->
                                        <div class="col-md-6 col-12 mb-3">
                                            <div class="form-group">
                                                <label for="region_id">المجموعة <span class="text-danger">*</span></label>
                                                <div class="position-relative has-icon-left">
                                                    <select class="form-control select2" id="region_id" name="region_id">
                                                        <option value="">اختر المجموعة</option>
                                                        @foreach ($Regions_groub as $Region_groub)
                                                            <option value="{{ $Region_groub->id }}"
                                                                {{ isset($client->Neighborhoodname->Region->id) && $Region_groub->id == $client->Neighborhoodname->Region->id ? 'selected' : '' }}>
                                                                {{ $Region_groub->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12 mb-3">
                                            <div class="form-group">
                                                <label for="visit_type">نوع الزيارة</label>
                                                <div class="position-relative has-icon-left">
                                                    <select class="form-control" id="visit_type" name="visit_type">
                                                        <option value="am"
                                                            {{ old('visit_type', $client->visit_type) == 'am' ? 'selected' : '' }}>
                                                            صباحية</option>
                                                        <option value="pm"
                                                            {{ old('visit_type', $client->visit_type) == 'pm' ? 'selected' : '' }}>
                                                            مسائية</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- زر إظهار الخريطة -->
                                        @foreach ($GeneralClientSettings as $GeneralClientSetting)
                                            @if ($GeneralClientSetting->is_active && $GeneralClientSetting->key == 'location')
                                                <div class="col-12 mb-3">
                                                    <button type="button" class="btn btn-outline-primary mb-2"
                                                        id="showMapBtn">
                                                        <i class="feather icon-map"></i> إظهار الخريطة
                                                    </button>
                                                    <button type="button" class="btn btn-outline-info mb-2 mr-1"
                                                        id="detectLocationBtn" style="display: none;">
                                                        <i class="feather icon-navigation"></i> تحديد موقعي
                                                    </button>

                                                    <div id="map-container" style="display: none;">
                                                        <div id="map" style="height: 400px; width: 100%;"></div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach

                                        <!-- قائمة الاتصال -->
                                        <div class="col-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h4 class="card-title">قائمة الاتصال</h4>
                                                </div>
                                                <div class="card-content">
                                                    <div class="card-body">
                                                        <div class="contact-fields-container" id="contactContainer">
                                                            @if ($client->contacts && $client->contacts->count() > 0)
                                                                @foreach ($client->contacts as $index => $contact)
                                                                    <div
                                                                        class="contact-fields-group mb-3 p-3 border rounded">
                                                                        <div class="row">
                                                                            <div class="col-md-6 mb-2">
                                                                                <label>الاسم الأول</label>
                                                                                <input type="text" class="form-control"
                                                                                    name="contacts[{{ $index }}][first_name]"
                                                                                    value="{{ $contact->first_name }}"
                                                                                    placeholder="الاسم الأول">
                                                                            </div>
                                                                            <div class="col-md-6 mb-2">
                                                                                <label>الاسم الأخير</label>
                                                                                <input type="text" class="form-control"
                                                                                    name="contacts[{{ $index }}][last_name]"
                                                                                    value="{{ $contact->last_name }}"
                                                                                    placeholder="الاسم الأخير">
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-md-6 mb-2">
                                                                                <label>البريد الإلكتروني</label>
                                                                                <input type="email" class="form-control"
                                                                                    name="contacts[{{ $index }}][email]"
                                                                                    value="{{ $contact->email }}"
                                                                                    placeholder="البريد الإلكتروني">
                                                                            </div>
                                                                            <div class="col-md-6 mb-2">
                                                                                <label>الهاتف</label>
                                                                                <input type="tel" class="form-control"
                                                                                    name="contacts[{{ $index }}][phone]"
                                                                                    value="{{ $contact->phone }}"
                                                                                    placeholder="الهاتف">
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-md-6 mb-2">
                                                                                <label>جوال</label>
                                                                                <input type="tel" class="form-control"
                                                                                    name="contacts[{{ $index }}][mobile]"
                                                                                    value="{{ $contact->mobile }}"
                                                                                    placeholder="جوال">
                                                                            </div>
                                                                            <div class="col-md-6 mb-2 text-right">
                                                                                <button type="button"
                                                                                    class="btn btn-danger mt-2"
                                                                                    onclick="removeContactFields(this)">
                                                                                    <i class="fa fa-trash"></i> حذف
                                                                                </button>
                                                                            </div>
                                                                        </div>
                                                                        <hr>
                                                                    </div>
                                                                @endforeach
                                                            @endif
                                                        </div>
                                                        <div class="text-right mt-1">
                                                            <button type="button"
                                                                class="btn btn-outline-success mr-1 mb-1"
                                                                onclick="addContactFields()">
                                                                <i class="feather icon-plus"></i> إضافة جهة اتصال
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">بيانات الحساب</h4>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                <div class="form-body">
                                    <div class="row">
                                        <!-- رقم الكود -->
                                        <div class="col-6 mb-3">
                                            <div class="form-group">
                                                <label for="code">رقم الكود <span class="text-danger">*</span></label>
                                                <div class="position-relative has-icon-left">
                                                    <input type="text" id="code" class="form-control"
                                                        name="code" value="{{ old('code', $client->code) }}" readonly>
                                                    <div class="form-control-position">
                                                        <i class="feather icon-hash"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- طريقة الفاتورة -->
                                        <div class="col-md-6 col-12 mb-3">
                                            <div class="form-group">
                                                <label for="printing_method">طريقة الفاتورة</label>
                                                <div class="position-relative has-icon-left">
                                                    <select class="form-control" id="printing_method"
                                                        name="printing_method">
                                                        <option value="1"
                                                            {{ old('printing_method', $client->printing_method) == 1 ? 'selected' : '' }}>
                                                            الطباعة</option>
                                                        <option value="2"
                                                            {{ old('printing_method', $client->printing_method) == 2 ? 'selected' : '' }}>
                                                            ارسل عبر البريد</option>
                                                    </select>
                                                    <div class="form-control-position">
                                                        <i class="feather icon-file-text"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- الرصيد الافتتاحي -->
                                        @foreach ($GeneralClientSettings as $GeneralClientSetting)
                                            @if ($GeneralClientSetting->is_active && $GeneralClientSetting->key == 'opening_balance')
                                                <div class="col-md-6 col-12 mb-3">
                                                    <div class="form-group">
                                                        <label for="opening_balance">الرصيد الافتتاحي</label>
                                                        <div class="position-relative has-icon-left">
                                                            <input type="number" id="opening_balance"
                                                                class="form-control" name="opening_balance"
                                                                value="{{ old('opening_balance', $client->opening_balance) }}">
                                                            <div class="form-control-position">
                                                                <i class="feather icon-dollar-sign"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach

                                        <!-- تاريخ الرصيد الاستحقاق -->
                                        <div class="col-md-6 col-12 mb-3">
                                            <div class="form-group">
                                                <label for="opening_balance_date">تاريخ الرصيد الاستحقاق</label>
                                                <div class="position-relative has-icon-left">
                                                    <input type="date" id="opening_balance_date" class="form-control"
                                                        name="opening_balance_date"
                                                        value="{{ old('opening_balance_date', $client->opening_balance_date) }}">
                                                    <div class="form-control-position">
                                                        <i class="feather icon-calendar"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- العملة -->
                                        <div class="col-md-12 col-12 mb-3">
                                            <div class="form-group">
                                                <label for="currency">العملة</label>
                                                <div class="position-relative has-icon-left">
                                                    <select class="form-control" id="currency" name="currency">
                                                        <option value="SAR"
                                                            {{ old('currency', $client->currency) == 'SAR' ? 'selected' : '' }}>
                                                            SAR</option>
                                                        <option value="USD"
                                                            {{ old('currency', $client->currency) == 'USD' ? 'selected' : '' }}>
                                                            USD</option>
                                                        <option value="EUR"
                                                            {{ old('currency', $client->currency) == 'EUR' ? 'selected' : '' }}>
                                                            EUR</option>
                                                    </select>
                                                    <div class="form-control-position">
                                                        <i class="feather icon-credit-card"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- البريد الإلكتروني -->
                                        <div class="col-md-12 col-12 mb-3">
                                            <div class="form-group">
                                                <label for="email">البريد الإلكتروني</label>
                                                <div class="position-relative has-icon-left">
                                                    <input type="email" id="email" class="form-control"
                                                        name="email" value="{{ old('email', $client->email) }}">
                                                    <div class="form-control-position">
                                                        <i class="feather icon-mail"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- التصنيف -->
                                        <div class="col-md-12 col-12 mb-3">
                                            <div class="form-group">
                                                <label for="category_id">التصنيف</label>
                                                <select class="form-control select2" name="category_id" id="category_id">
                                                    <option value="">اختر التصنيف</option>
                                                    @foreach ($categories as $category)
                                                        <option value="{{ $category->id }}"
                                                            {{ old('category_id', $client->category_id) == $category->id ? 'selected' : '' }}>
                                                            {{ $category->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <!-- الملاحظات -->
                                        <div class="col-md-12 col-12 mb-3">
                                            <label for="notes">الملاحظات</label>
                                            <textarea class="form-control" id="notes" name="notes" rows="5" style="resize: none;">{{ old('notes', $client->notes) }}</textarea>
                                        </div>

                                        <!-- المرفقات -->
                                        @foreach ($GeneralClientSettings as $GeneralClientSetting)
                                            @if ($GeneralClientSetting->is_active && $GeneralClientSetting->key == 'image')
                                                 <div class="col-md-12">
                        <label for="attachments" class="form-label">المرفقات</label>
                        <input id="attachments" type="file" name="attachments" class="form-control"
                            accept=".pdf,.jpg,.jpeg,.png">
                        <small class="text-muted">يمكنك رفع ملف PDF أو صورة (الحد الأقصى 2 ميجابايت)</small>
                    </div>
                                            @endif
                                        @endforeach

                                        <!-- نوع العميل -->

                                        <!-- الفرع -->
                                        <div class="col-md-12 col-12 mb-3">
                                            <div class="form-group">
                                                <label for="branch_id">الفرع <span class="text-danger">*</span></label>
                                                <select class="form-control select2" name="branch_id" id="branch_id" required>
                                                    <option value="">اختر الفرع</option>
                                                    @foreach ($branches as $branche)
                                                        <option value="{{ $branche->id }}"
                                                            @if (isset($client) && $client->branch_id == $branche->id) selected @endif>
                                                            {{ $branche->name ?? 'لا يوجد فروع' }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                                                                   @if (auth()->user()->role === 'manager')
                                            <div class="col-md-12 col-12 mb-3">
                                                <div class="form-group">
                                                    <label for="employee_client_id" class="form-label">الموظفين المسؤولين</label>
                                                    <select id="employee_select" class="form-control select2">
                                                        <option value="">اختر الموظف</option>
                                                        @foreach ($employees as $employee)
                                                            <option value="{{ $employee->id }}" data-name="{{ $employee->full_name }}">
                                                                {{ $employee->full_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>

                                                    {{-- الحقل الحقيقي الذي يتم إرساله --}}
                                                    <div id="selected_employees">
                                                        @foreach ($client->employees as $assigned)
                                                            <input type="hidden" name="employee_client_id[]" value="{{ $assigned->id }}">
                                                        @endforeach
                                                    </div>

                                                    {{-- عرض الموظفين المسؤولين الحاليين --}}
                                                    <ul id="employee_list" class="mt-2 list-group">
                                                        @foreach ($client->employees as $assigned)
                                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                {{ $assigned->full_name }}
                                                                <button type="button" class="btn btn-sm btn-danger remove-employee" data-id="{{ $assigned->id }}">حذف</button>
                                                            </li>
                                                        @endforeach
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
        </form>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script
        src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&libraries=places&callback=initGoogleMaps"
        async defer></script>

    <script>
        let map;
        let marker;
        let searchBox;
        let contactCounter = {{ $client->contacts ? $client->contacts->count() : 0 }};
        let selectedEmployeeIds = @json($client->employees ? $client->employees->pluck('id')->toArray() : []);
        let isGoogleMapsLoaded = false;
        let isMapInitialized = false;

        function initGoogleMaps() {
            isGoogleMapsLoaded = true;
            console.log('Google Maps تم تحميله بنجاح');
        }

        function addContactFields() {
            contactCounter++;
            const contactContainer = document.getElementById('contactContainer');
            const newContactGroup = document.createElement('div');
            newContactGroup.className = 'contact-fields-group mb-3 p-3 border rounded';
            newContactGroup.innerHTML = `
                <div class="row">
                    <div class="col-md-6 mb-2">
                        <label>الاسم الأول</label>
                        <input type="text" class="form-control" name="contacts[${contactCounter}][first_name]" placeholder="الاسم الأول">
                    </div>
                    <div class="col-md-6 mb-2">
                        <label>الاسم الأخير</label>
                        <input type="text" class="form-control" name="contacts[${contactCounter}][last_name]" placeholder="الاسم الأخير">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-2">
                        <label>البريد الإلكتروني</label>
                        <input type="email" class="form-control" name="contacts[${contactCounter}][email]" placeholder="البريد الإلكتروني">
                    </div>
                    <div class="col-md-6 mb-2">
                        <label>الهاتف</label>
                        <input type="tel" class="form-control" name="contacts[${contactCounter}][phone]" placeholder="الهاتف">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-2">
                        <label>جوال</label>
                        <input type="tel" class="form-control" name="contacts[${contactCounter}][mobile]" placeholder="جوال">
                    </div>
                    <div class="col-md-6 mb-2 text-right">
                        <button type="button" class="btn btn-danger mt-2" onclick="removeContactFields(this)">
                            <i class="fa fa-trash"></i> حذف
                        </button>
                    </div>
                </div>
                <hr>
            `;
            contactContainer.appendChild(newContactGroup);
        }

        function removeContactFields(button) {
            const contactGroup = button.closest('.contact-fields-group');
            contactGroup.remove();
        }

        function removeEmployee(button, employeeId) {
            const li = button.closest('li');
            li.remove();
            selectedEmployeeIds = selectedEmployeeIds.filter(id => id !== employeeId);
            updateHiddenInputs();
        }

        function initMap(lat = {{ $location->latitude ?? 24.7136 }}, lng = {{ $location->longitude ?? 46.6753 }}) {
            if (!isGoogleMapsLoaded) {
                console.log('انتظار تحميل Google Maps...');
                setTimeout(() => initMap(lat, lng), 100);
                return;
            }

            const mapContainer = document.getElementById('map-container');
            const mapElement = document.getElementById('map');

            if (!mapElement) {
                console.error('عنصر الخريطة غير موجود');
                return;
            }

            mapContainer.style.display = 'block';
            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;

            // Create map immediately without timeout
            map = new google.maps.Map(mapElement, {
                center: {
                    lat,
                    lng
                },
                zoom: 15,
                mapTypeControl: true,
                streetViewControl: true,
                fullscreenControl: true
            });

            marker = new google.maps.Marker({
                position: {
                    lat,
                    lng
                },
                map: map,
                draggable: true,
                title: 'موقع العميل',
                animation: google.maps.Animation.DROP
            });

            marker.addListener('dragend', function() {
                const newLat = marker.getPosition().lat();
                const newLng = marker.getPosition().lng();
                document.getElementById('latitude').value = newLat;
                document.getElementById('longitude').value = newLng;
                fetchAddressFromCoordinates(newLat, newLng);
            });

            map.addListener('click', function(event) {
                const newLat = event.latLng.lat();
                const newLng = event.latLng.lng();
                updateMapPosition(newLat, newLng);
            });

            isMapInitialized = true;
            console.log('تم تهيئة الخريطة بنجاح');
        }

        function updateMapPosition(lat, lng) {
            if (!map || !marker) return;
            map.setCenter({
                lat,
                lng
            });
            marker.setPosition({
                lat,
                lng
            });
            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;
            fetchAddressFromCoordinates(lat, lng);
        }

        function fetchAddressFromCoordinates(lat, lng) {
            if (!isGoogleMapsLoaded) return;

            const geocoder = new google.maps.Geocoder();
            const latLng = {
                lat,
                lng
            };

            geocoder.geocode({
                location: latLng
            }, (results, status) => {
                if (status === 'OK' && results[0]) {
                    const addressComponents = results[0].address_components;
                    const region = getAddressComponent(addressComponents, 'administrative_area_level_1');
                    const city = getAddressComponent(addressComponents, 'locality') || getAddressComponent(
                        addressComponents, 'administrative_area_level_2');
                    const postalCode = getAddressComponent(addressComponents, 'postal_code');
                    const street1 = getAddressComponent(addressComponents, 'route');
                    const street2 = getAddressComponent(addressComponents, 'neighborhood');

                    if (region) document.getElementById('region').value = region;
                    if (city) document.getElementById('city').value = city;
                    if (postalCode) document.getElementById('postal_code').value = postalCode;
                    if (street1) document.getElementById('street1').value = street1;
                    if (street2) document.getElementById('street2').value = street2;
                }
            });
        }

        function getAddressComponent(components, type) {
            const component = components.find(c => c.types.includes(type));
            return component ? component.long_name : '';
        }

        function requestLocationPermission() {
            if (!isGoogleMapsLoaded) {
                Swal.fire({
                    icon: 'info',
                    title: 'جاري التحميل...',
                    text: 'جاري تحميل الخريطة، يرجى الانتظار قليلاً',
                    timer: 1000, // Reduced timer
                    showConfirmButton: false
                });
                setTimeout(requestLocationPermission, 500); // Reduced timeout
                return;
            }

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        if (!isMapInitialized) {
                            initMap(position.coords.latitude, position.coords.longitude);
                        } else {
                            updateMapPosition(position.coords.latitude, position.coords.longitude);
                        }
                    },
                    (error) => {
                        console.warn('خطأ في تحديد الموقع:', error);
                        Swal.fire({
                            icon: 'info',
                            title: 'تحديد الموقع',
                            text: 'سيتم عرض الخريطة بموقع افتراضي (الرياض). يمكنك تحريك العلامة أو النقر على الخريطة لتحديد الموقع.',
                            confirmButtonText: 'موافق'
                        });

                        if (!isMapInitialized) {
                            initMap();
                        }
                    }, {
                        timeout: 10000, // Add timeout option
                        enableHighAccuracy: true // Enable high accuracy
                    }
                );
            } else {
                Swal.fire({
                    icon: 'info',
                    title: 'تحديد الموقع',
                    text: 'سيتم عرض الخريطة بموقع افتراضي. يمكنك النقر على الخريطة لتحديد الموقع.',
                    confirmButtonText: 'موافق'
                });

                if (!isMapInitialized) {
                    initMap();
                }
            }
        }

        // دالة تحديد الموقع الحالي للمستخدم
        function detectMyLocation() {
            if (!isGoogleMapsLoaded) {
                Swal.fire({
                    icon: 'info',
                    title: 'جاري التحميل...',
                    text: 'جاري تحميل الخريطة، يرجى الانتظار قليلاً',
                    timer: 1000, // Reduced timer
                    showConfirmButton: false
                });

                setTimeout(detectMyLocation, 500); // Reduced timeout
                return;
            }

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        // إذا لم تكن الخريطة مهيئة بعد، نهيئها أولاً
                        if (!isMapInitialized) {
                            initMap(position.coords.latitude, position.coords.longitude);
                        } else {
                            // إذا كانت الخريطة مهيئة، نحدث موقع العلامة
                            updateMapPosition(position.coords.latitude, position.coords.longitude);
                        }

                        Swal.fire({
                            icon: 'success',
                            title: 'تم تحديد الموقع',
                            text: 'تم تحديد موقعك الحالي على الخريطة',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    },
                    (error) => {
                        console.warn('خطأ في تحديد الموقع:', error);
                        let errorMessage = 'تعذر تحديد موقعك الحالي. ';

                        switch (error.code) {
                            case error.PERMISSION_DENIED:
                                errorMessage += 'تم رفض طلب تحديد الموقع.';
                                break;
                            case error.POSITION_UNAVAILABLE:
                                errorMessage += 'معلومات الموقع غير متاحة.';
                                break;
                            case error.TIMEOUT:
                                errorMessage += 'انتهت مدة طلب تحديد الموقع.';
                                break;
                            default:
                                errorMessage += 'حدث خطأ غير معروف.';
                                break;
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'خطأ في تحديد الموقع',
                            text: errorMessage,
                            confirmButtonText: 'موافق'
                        });
                    }, {
                        timeout: 10000, // Add timeout option
                        enableHighAccuracy: true // Enable high accuracy
                    }
                );
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'غير مدعوم',
                    text: 'متصفحك لا يدعم خاصية تحديد الموقع',
                    confirmButtonText: 'موافق'
                });
            }
        }

        function initEmployeeManagement() {
            const employeeSelect = document.getElementById('employee_select');
            const employeeList = document.getElementById('employee_list');
            const selectedEmployees = document.getElementById('selected_employees');

            if (employeeSelect) {
                employeeSelect.addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    const employeeId = selectedOption.value;
                    const employeeName = selectedOption.dataset.name;

                    if (employeeId && !selectedEmployeeIds.includes(parseInt(employeeId))) {
                        selectedEmployeeIds.push(parseInt(employeeId));

                        const li = document.createElement('li');
                        li.className = 'list-group-item d-flex justify-content-between align-items-center';
                        li.setAttribute('data-employee-id', employeeId);
                        li.textContent = employeeName;

                        const removeBtn = document.createElement('button');
                        removeBtn.textContent = 'حذف';
                        removeBtn.className = 'btn btn-sm btn-danger';
                        removeBtn.onclick = () => {
                            li.remove();
                            selectedEmployeeIds = selectedEmployeeIds.filter(id => id !== parseInt(employeeId));
                            updateHiddenInputs();
                        };

                        li.appendChild(removeBtn);
                        employeeList.appendChild(li);
                        updateHiddenInputs();
                    }

                    this.value = '';
                });
            }

            function updateHiddenInputs() {
                selectedEmployees.innerHTML = '';
                selectedEmployeeIds.forEach(id => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'employee_client_id[]';
                    input.value = id;
                    selectedEmployees.appendChild(input);
                });
            }

            updateHiddenInputs();
        }

        function handleFormSubmission() {
            const form = document.getElementById('clientForm');
            const submitBtn = document.getElementById('submitBtn');

            form.addEventListener('submit', function(e) {
                e.preventDefault();

                const tradeName = document.getElementById('trade_name').value.trim();
                const regionId = document.getElementById('region_id').value;
                const branchId = document.getElementById('branch_id').value;
                const latitude = document.getElementById('latitude').value;
                const longitude = document.getElementById('longitude').value;

                if (!tradeName || !regionId || !branchId) {
                    Swal.fire({
                        icon: 'error',
                        title: 'خطأ في البيانات',
                        text: 'يرجى ملء جميع الحقول المطلوبة',
                        confirmButtonText: 'موافق'
                    });
                    return;
                }

                if (!latitude || !longitude) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'تحديد الموقع مطلوب',
                        text: 'يرجى تحديد موقع العميل على الخريطة قبل الحفظ!',
                        confirmButtonText: 'إظهار الخريطة',
                        showCancelButton: true,
                        cancelButtonText: 'إلغاء'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            requestLocationPermission();
                        }
                    });
                    return;
                }

                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> جاري الحفظ...';

                const formData = new FormData(form);

                fetch(form.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'تم بنجاح!',
                                text: data.message,
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                if (data.redirect_url) {
                                    window.location.href = data.redirect_url;
                                }
                            });
                        } else {
                            let errorMessage = data.message || 'حدث خطأ أثناء حفظ البيانات';
                            if (data.errors) {
                                const errorList = Object.values(data.errors).flat();
                                errorMessage = errorList.join('\n');
                            }

                            Swal.fire({
                                icon: 'error',
                                title: 'خطأ في البيانات',
                                text: errorMessage,
                                confirmButtonText: 'موافق'
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'خطأ في الشبكة',
                            text: 'حدث خطأ أثناء إرسال البيانات. يرجى المحاولة مرة أخرى.',
                            confirmButtonText: 'موافق'
                        });
                    })
                    .finally(() => {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = '<i class="fa fa-save"></i> حفظ التعديلات';
                    });
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            const showMapBtn = document.getElementById('showMapBtn');
            const detectLocationBtn = document.getElementById('detectLocationBtn');

            if (showMapBtn) {
                showMapBtn.addEventListener('click', function() {
                    const originalText = this.innerHTML;
                    this.innerHTML = '<i class="fa fa-spinner fa-spin"></i> جاري تحميل الخريطة...';
                    this.disabled = true;

                    requestLocationPermission();

                    // إظهار زر تحديد الموقع بعد تهيئة الخريطة
                    setTimeout(() => {
                        this.innerHTML = originalText;
                        this.disabled = false;
                        if (detectLocationBtn) {
                            detectLocationBtn.style.display = 'inline-block';
                        }
                    }, 500); // Reduced timeout
                });
            }

            // إضافة مستمع الحدث لزر تحديد الموقع
            if (detectLocationBtn) {
                detectLocationBtn.addEventListener('click', function() {
                    const originalText = this.innerHTML;
                    this.innerHTML = '<i class="fa fa-spinner fa-spin"></i> جاري تحديد الموقع...';
                    this.disabled = true;

                    detectMyLocation();

                    setTimeout(() => {
                        this.innerHTML = originalText;
                        this.disabled = false;
                    }, 500); // Reduced timeout
                });
            }

            initEmployeeManagement();
            handleFormSubmission();

            const attachmentsInput = document.getElementById('attachments');
            if (attachmentsInput) {
                attachmentsInput.addEventListener('change', function() {
                    const fileName = this.files[0]?.name;
                    if (fileName) {
                        const uploadArea = document.querySelector('.upload-area');
                        uploadArea.innerHTML = `
                            <div class="d-flex align-items-center justify-content-center gap-2">
                                <i class="fas fa-check text-success"></i>
                                <span class="text-success">${fileName}</span>
                            </div>
                        `;
                    }
                });
            }
        });

        window.initGoogleMaps = initGoogleMaps;
    </script>
@endsection
