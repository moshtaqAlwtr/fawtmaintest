{{-- resources/views/client/partials/search_card.blade.php --}}

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center p-2">
        <div class="d-flex gap-2">
            <span class="hide-button-text">بحث وتصفية</span>
        </div>
        <div class="d-flex align-items-center gap-2">
            <button class="btn btn-outline-secondary btn-sm" onclick="toggleSearchFields(this)">
                <i class="fa fa-times"></i>
                <span class="hide-button-text">اخفاء</span>
            </button>
            <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="collapse"
                data-bs-target="#advancedSearchForm" onclick="toggleSearchText(this)">
                <i class="fa fa-filter"></i>
                <span class="button-text">متقدم</span>
            </button>

        </div>
    </div>

    <div class="card-body">
        <form class="form" id="searchForm">
            @csrf
            <div class="row g-3">
                <!-- الحقول الأساسية -->
                <div class="col-md-4">
<label for="" class=""> اختر العميل</label>
                    <select name="client" class="form-control select2">
                        <option value="">اختر العميل</option>
                        @foreach ($allClients as $client)
                            <option value="{{ $client->id }}" {{ request('client') == $client->id ? 'selected' : '' }}>
                                {{ $client->trade_name }} ({{ $client->code }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label for="" class=""> المجموعة</label>
                    <select name="region" class="form-control select2">
                        <option value="">المجموعة</option>
                        @foreach ($Region_groups as $Region_group)
                            <option value="{{ $Region_group->id }}" {{ request('region') == $Region_group->id ? 'selected' : '' }}>
                                {{ $Region_group->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label for="" class=""> الحالة</label>
                    <select name="status" class="form-control select2">
                        <option value="">الحالة</option>
                        @foreach ($statuses as $status)
                            <option value="{{ $status->id }}" {{ request('status') == $status->id ? 'selected' : '' }}>
                                {{ $status->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- البحث المتقدم -->
            <div class="collapse" id="advancedSearchForm">
                <div class="row g-3 mt-2">
                    <div class="col-md-4">
                        <label for="" class=""> اختر الفترة</label>
                        <select name="last_activity_period" class="form-control">
                            <option value="">آخر  الفترة</option>
                            <option value="today" {{ request('last_activity_period') == 'today' ? 'selected' : '' }}>اليوم (0-1 يوم)</option>
                            <option value="week" {{ request('last_activity_period') == 'week' ? 'selected' : '' }}>منذ أسبوع (1-7 أيام)</option>
                            <option value="two_weeks" {{ request('last_activity_period') == 'two_weeks' ? 'selected' : '' }}>منذ أسبوعين (7-14 يوم)</option>
                            <option value="month" {{ request('last_activity_period') == 'month' ? 'selected' : '' }}>منذ شهر (14-30 يوم)</option>
                            <option value="three_months" {{ request('last_activity_period') == 'three_months' ? 'selected' : '' }}>منذ 3 أشهر (30-90 يوم)</option>
                            <option value="six_months" {{ request('last_activity_period') == 'six_months' ? 'selected' : '' }}>منذ 6 أشهر (90-180 يوم)</option>
                            <option value="year" {{ request('last_activity_period') == 'year' ? 'selected' : '' }}>منذ سنة (180-365 يوم)</option>
                            <option value="more_than_year" {{ request('last_activity_period') == 'more_than_year' ? 'selected' : '' }}>أكثر من سنة (أكثر من 365 يوم)</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="" class=""> التصنيف</label>
                        <select name="categories" class="form-control">
                            <option value="">التصنيف</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ request('categories') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="" class=""> التاريخ من </label>
                        <input type="date" name="date_from" class="form-control" placeholder="التاريخ من" value="{{ request('date_from') }}">
                    </div>

                    <div class="col-md-4">
                        <label for="" class=""> التاريخ إلى </label>
                        <input type="date" name="date_to" class="form-control" placeholder="التاريخ إلى" value="{{ request('date_to') }}">
                    </div>

                    <div class="col-md-4">
                        <label for="" class=""> الموظف</label>
                        <select name="employee" class="form-control">
                            <option value="">الموظف</option>
                            @foreach ($employees as $employee)
                                <option value="{{ $employee->id }}" {{ request('employee') == $employee->id ? 'selected' : '' }}>
                                    {{ $employee->full_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-actions mt-2">
                <button type="submit" class="btn btn-primary">بحث</button>
                <button type="button" id="resetSearch" class="btn btn-outline-warning">إلغاء</button>
            </div>
        </form>
    </div>
</div>
