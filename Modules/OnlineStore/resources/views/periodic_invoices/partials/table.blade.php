{{-- ملف: resources/views/sales/periodic_invoices/partials/table.blade.php --}}

@if ($periodicInvoices->count() > 0)
    <div class="table-responsive">
        <table class="table table-hover table-striped">
            <thead>
                <tr>
                    <th style="width: 5%">
                        <input type="checkbox" class="form-check-input" id="select-all">
                    </th>
                    <th>رقم المعرف</th>
                    <th>الاسم</th>
                    <th>اسم العميل</th>
                    <th>التاريخ القادم</th>
                    <th>تم إنشاؤها</th>
                    <th>الإجمالي</th>
                    <th>كل</th>
                    <th style="width: 10%">الإجراءات</th>
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

    {{-- الترقيم --}}
    @include('sales::periodic_invoices.partials.pagination', ['periodicInvoices' => $periodicInvoices])
@else
    <div class="alert alert-info text-center" role="alert">
        <i class="fa fa-info-circle me-2"></i>
        <p class="mb-0">لا يوجد فواتير دورية تطابق معايير البحث</p>
    </div>
@endif