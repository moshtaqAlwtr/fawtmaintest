{{-- ملف: resources/views/sales/qoution/partials/table.blade.php --}}

@if ($quotes->count() > 0)
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
                                <th class="sorting_asc" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="ascending">رقم عرض السعر</th>
                                <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1">العميل</th>
                                <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1">التاريخ</th>
                                <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1">المبلغ الإجمالي</th>
                                <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1">الحالة</th>
                                <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" style="width: 10%">خيارات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($quotes as $quote)
                                @php
                                    $currency = $account_setting->currency ?? 'SAR';
                                    $currencySymbol = $currency == 'SAR' || empty($currency)
                                        ? '<img src="' . asset('assets/images/Saudi_Riyal.svg') . '" alt="ريال سعودي" width="15" style="vertical-align: middle;">'
                                        : $currency;

                                    $statusClass = $quote->status == 1 ? 'success' : 'info';
                                    $statusText = $quote->status == 1 ? 'مفتوح' : 'مغلق';
                                @endphp

                                <tr>
                                   
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar me-2" style="background-color: #007bff">
                                                <span class="avatar-content">#</span>
                                            </div>
                                            <div>
                                                #{{ $quote->id }}
                                                <div class="text-muted small">عرض سعر</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="client-info">
                                            <strong>
                                                {{ $quote->client ? ($quote->client->trade_name ?: $quote->client->first_name . ' ' . $quote->client->last_name) : 'عميل غير معروف' }}
                                            </strong>
                                            @if ($quote->client && $quote->client->tax_number)
                                                <div class="text-muted small">الرقم الضريبي: {{ $quote->client->tax_number }}</div>
                                            @endif
                                            @if ($quote->client && $quote->client->full_address)
                                                <div class="text-muted small">{{ $quote->client->full_address }}</div>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        {{ $quote->created_at ? $quote->created_at->format($account_setting->time_formula ?? 'Y-m-d H:i') : '' }}
                                        <br>
                                        <small class="text-muted">أضيفت بواسطة: {{ $quote->creator->name ?? 'غير محدد' }}</small>
                                    </td>
                                    <td>
                                        {{ number_format($quote->grand_total ?? $quote->total, 2) }} {!! $currencySymbol !!}
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $statusClass }}">
                                            <i class="fas fa-circle me-1"></i> {{ $statusText }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <div class="dropdown">
                                                <button class="btn bg-gradient-info fa fa-ellipsis-v mr-1 mb-1 btn-sm"
                                                    type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a class="dropdown-item" href="{{ route('questions.show', $quote->id) }}">
                                                        <i class="fa fa-eye me-2 text-primary"></i>عرض
                                                    </a>
                                                    <a class="dropdown-item" href="{{ route('questions.edit', $quote->id) }}">
                                                        <i class="fa fa-edit me-2 text-success"></i>تعديل
                                                    </a>
                                                    <a class="dropdown-item" href="{{ route('questions.pdf', $quote->id) }}">
                                                        <i class="fa fa-file-pdf me-2 text-danger"></i>PDF
                                                    </a>
                                                    <a class="dropdown-item" href="{{ route('questions.pdf', $quote->id) }}">
                                                        <i class="fa fa-print me-2 text-dark"></i>طباعة
                                                    </a>
                                                    <a class="dropdown-item" href="{{ route('questions.email', $quote->id) }}">
                                                        <i class="fa fa-envelope me-2 text-warning"></i>إرسال إلى العميل
                                                    </a>
                                                    <form id="delete-form-{{ $quote->id }}" 
                                                        action="{{ route('questions.destroy', $quote->id) }}" 
                                                        method="POST" 
                                                        style="display:none;">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>

                                                    <a class="dropdown-item text-danger" 
                                                        href="{{ route('questions.destroy', $quote->id) }}" 
                                                        onclick="event.preventDefault(); document.getElementById('delete-form-{{ $quote->id }}').submit();">
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
            @include('sales::qoution.partials.pagination', ['quotes' => $quotes])
        </div>
    </div>
@else
    <div class="alert alert-info text-center" role="alert">
        <i class="fa fa-info-circle me-2"></i>
        <p class="mb-0">لا يوجد عروض أسعار تطابق معايير البحث</p>
    </div>
@endif