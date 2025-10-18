{{-- ملف: resources/views/sales/periodic_invoices/partials/table.blade.php --}}

@if ($periodicInvoices->count() > 0)

    <div class="table-responsive">
        <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap4">
            <div class="row align-items-center">
                <div class="col-12">
                    <div class="dataTables_length" id="DataTables_Table_0_length" style="text-align: left;">
                        <label style="margin-bottom: 0;">
                            عرض 
                            <select name="DataTables_Table_0_length" aria-controls="DataTables_Table_0" class="form-select form-select-sm d-inline-block w-auto" 
                                onchange="if(window.loadData) { window.loadData(1, parseInt(this.value)); }">
                                <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                                <option value="25" {{ request('per_page', 10) == 25 ? 'selected' : '' }}>25</option>
                                <option value="50" {{ request('per_page', 10) == 50 ? 'selected' : '' }}>50</option>
                                <option value="100" {{ request('per_page', 10) == 100 ? 'selected' : '' }}>100</option>
                            </select> 
                            سجل
                        </label>
                    </div>
                </div>
               
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <table class="table dataTable" id="DataTables_Table_0" role="grid" aria-describedby="DataTables_Table_0_info">
                        <thead>
                            <tr role="row">
                                <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" style="width: 5%">
                                    <input type="checkbox" class="form-check-input" id="select-all">
                                </th>
                                <th class="sorting_asc" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="ascending">رقم المعرف</th>
                                <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1">الاسم</th>
                                <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1">اسم العميل</th>
                                <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1">التاريخ القادم</th>
                                <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1">تم إنشاؤها</th>
                                <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1">الإجمالي</th>
                                <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1">الفترة</th>
                                <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" style="width: 10%">خيارات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($periodicInvoices as $preInvoice)
                                @php
                                    $currency = $account_setting->currency ?? 'SAR';
                                    $currencySymbol = $currency == 'SAR' || empty($currency) 
                                        ? '<img src="' . asset('assets/images/Saudi_Riyal.svg') . '" alt="ريال سعودي" width="15" style="vertical-align: middle;">' 
                                        : $currency;
                                @endphp
                                <tr>
                                    <td>
                                        <input type="checkbox" class="form-check-input" name="selected[]" value="{{ $preInvoice->id }}">
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar me-2" style="background-color: #28a745">
                                                <span class="avatar-content">#</span>
                                            </div>
                                            <div>
                                                #{{ $preInvoice->id }}
                                                <div class="text-muted small">اشتراك دوري</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <strong>{{ $preInvoice->details_subscription }}</strong>
                                    </td>
                                    <td>
                                        <div class="client-info">
                                            <strong>{{ optional($preInvoice->client)->trade_name ?? 'عميل نقدي' }}</strong>
                                            @if ($preInvoice->client && $preInvoice->client->tax_number)
                                                <div class="text-muted small">الرقم الضريبي: {{ $preInvoice->client->tax_number }}</div>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        {{ $preInvoice->first_invoice_date ? date('Y-m-d', strtotime($preInvoice->first_invoice_date)) : 'انتهى' }}
                                    </td>
                                    <td>
                                        {{ $preInvoice->created_at ? date('Y-m-d', strtotime($preInvoice->created_at)) : '-' }}
                                    </td>
                                    <td>
                                        {{ number_format($preInvoice->grand_total, 2) }} {!! $currencySymbol !!}
                                    </td>
                                    <td>
                                        <span class="badge bg-info">
                                            {{ $preInvoice->repeat_interval }}
                                            @if ($preInvoice->repeat_type == 1)
                                                يوم
                                            @elseif($preInvoice->repeat_type == 2)
                                                اسبوع
                                            @elseif($preInvoice->repeat_type == 3)
                                                شهري
                                            @else
                                                سنوي
                                            @endif
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <div class="dropdown">
                                                <button class="btn bg-gradient-info fa fa-ellipsis-v mr-1 mb-1 btn-sm"
                                                    type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a class="dropdown-item" href="{{ route('periodic_invoices.show', $preInvoice->id) }}">
                                                        <i class="fa fa-eye me-2 text-primary"></i>عرض
                                                    </a>
                                                    <a class="dropdown-item" href="{{ route('periodic_invoices.edit', $preInvoice->id) }}">
                                                        <i class="fa fa-edit me-2 text-success"></i>تعديل
                                                    </a>
                                                    <a class="dropdown-item text-danger delete-periodic" href="#" data-id="{{ $preInvoice->id }}">
                                                        <i class="fa fa-trash me-2"></i>حذف
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            
            {{-- الترقيم --}}
            @include('sales::periodic_invoices.partials.pagination', ['periodicInvoices' => $periodicInvoices])
        </div>
    </div>
@else
    <div class="alert alert-info text-center" role="alert">
        <i class="fa fa-info-circle me-2"></i>
        <p class="mb-0">لا يوجد فواتير دورية تطابق معايير البحث</p>
    </div>
@endif