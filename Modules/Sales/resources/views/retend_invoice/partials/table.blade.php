{{-- ملف: resources/views/sales/retend_invoice/partials/table.blade.php --}}

@if ($return->count() > 0)
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
                               
                                <th class="sorting_asc" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="ascending">رقم الفاتورة</th>
                                <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1">العميل</th>
                                <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1">التاريخ</th>
                                <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1">المرجع</th>
                                <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1">المبلغ الإجمالي</th>
                                <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" style="width: 10%">خيارات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($return as $retur)
                                @php
                                    $currency = $account_setting->currency ?? 'SAR';
                                    $currencySymbol = $currency == 'SAR' || empty($currency)
                                        ? '<img src="' . asset('assets/images/Saudi_Riyal.svg') . '" alt="ريال سعودي" width="15" style="vertical-align: middle;">'
                                        : $currency;
                                @endphp

                                <tr>
                                   
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar me-2" style="background-color: #dc3545">
                                                <span class="avatar-content">#</span>
                                            </div>
                                            <div>
                                                #{{ $retur->id }}
                                               
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="client-info">
                                            <strong>
                                                {{ $retur->client ? ($retur->client->trade_name ?: $retur->client->first_name . ' ' . $retur->client->last_name) : 'عميل غير معروف' }}
                                            </strong>

                                        </div>
                                    </td>
                                    <td>
                                        {{ $retur->created_at ? $retur->created_at->format($account_setting->time_formula ?? 'Y-m-d H:i') : '' }}
                                        <br>
                                        <small class="text-muted">أضيفت بواسطة: {{ $retur->createdByUser->name ?? 'غير محدد' }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-warning">
                                            <i class="fas fa-undo-alt"></i> #{{ $retur->reference_number ?? '--' }}
                                        </span>
                                    </td>
                                    <td>
                                        {{ number_format($retur->grand_total ?? $retur->total, 2) }} {!! $currencySymbol !!}
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <div class="dropdown">
                                                <button class="btn bg-gradient-info fa fa-ellipsis-v mr-1 mb-1 btn-sm"
                                                    type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a class="dropdown-item" href="{{ route('ReturnIInvoices.show', $retur->id) }}">
                                                        <i class="fa fa-eye me-2 text-primary"></i>عرض
                                                    </a>
                                                    <a class="dropdown-item" href="{{ route('ReturnIInvoices.edit', $retur->id) }}">
                                                        <i class="fa fa-edit me-2 text-success"></i>تعديل
                                                    </a>
                                                    <a class="dropdown-item" href="{{ route('ReturnIInvoices.print', $retur->id) }}">
                                                        <i class="fa fa-print me-2 text-dark"></i>طباعة
                                                    </a>
                                                    <a class="dropdown-item" href="">
                                                        <i class="fa fa-envelope me-2 text-warning"></i>إرسال إلى العميل
                                                    </a>
                                                    
                                                    <form id="delete-form-{{ $retur->id }}" 
                                                        action="{{ route('ReturnIInvoices.destroy', $retur->id) }}" 
                                                        method="POST" 
                                                        style="display:none;">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>

                                                    <a class="dropdown-item text-danger" 
                                                        href="{{ route('ReturnIInvoices.destroy', $retur->id) }}" 
                                                        onclick="event.preventDefault(); document.getElementById('delete-form-{{ $retur->id }}').submit();">
                                                        <i class="fa fa-trash me-2"></i> حذف
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
            @include('sales::retend_invoice.partials.pagination', ['return' => $return])
        </div>
    </div>
@else
    <div class="alert alert-info text-center" role="alert">
        <i class="fa fa-info-circle me-2"></i>
        <p class="mb-0">لا يوجد فواتير مرتجعة تطابق معايير البحث</p>
    </div>
@endif