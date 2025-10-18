@if ($payments->count() > 0)

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
                                <th class="sorting_asc" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="ascending" style="width: 5%">
                                    <input type="checkbox" class="form-check-input" id="selectAll">
                                </th>
                                <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1">البيانات الأساسية</th>
                                <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1">العميل</th>
                                <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1">التاريخ</th>
                                <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1">المبلغ</th>
                                <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1">الحالة</th>
                                <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" style="width: 10%">خيارات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($payments as $payment)
                                <tr>
                                    <td>
                                        <input type="checkbox" class="form-check-input payment-checkbox" value="{{ $payment->id }}">
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar me-2" style="background-color: #28a745">
                                                <span class="avatar-content">#</span>
                                            </div>
                                            <div>
                                                #{{ $payment->id }}
                                                <small class="text-muted d-block">
                                                    @if ($payment->invoice)
                                                        الفاتورة: #{{ $payment->invoice->code ?? '--' }}
                                                    @endif
                                                </small>
                                                @if ($payment->notes)
                                                    <small class="text-muted d-block">
                                                        <i class="fas fa-comment-alt"></i> {{ $payment->notes }}
                                                    </small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="client-info">
                                            @if ($payment->invoice && $payment->invoice->client)
                                                <strong>
                                                    {{ $payment->invoice->client->trade_name ?? '' }}
                                                </strong>
                                                <small class="text-muted d-block">
                                                    <i class="fas fa-phone"></i>
                                                    {{ $payment->invoice->client->phone ?? '' }}
                                                </small>
                                            @else
                                                <span class="text-danger">لا يوجد عميل</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        {{ $payment->payment_date }}
                                        <br>
                                        <small class="text-muted">
                                            @if ($payment->employee)
                                                <span>الموظف: {{ $payment->employee->name ?? '' }}</span>
                                            @endif
                                        </small>
                                        <small class="text-muted d-block">
                                            <i class="fas fa-clock"></i> {{ $payment->created_at->format('H:i') }}
                                        </small>
                                    </td>
                                    <td>
                                        @php
                                            $currency = $account_setting->currency ?? 'SAR';
                                            $currencySymbol = $currency == 'SAR' || empty($currency)
                                                ? '<img src="' . asset('assets/images/Saudi_Riyal.svg') . '" alt="ريال سعودي" width="15" style="vertical-align: middle;">'
                                                : $currency;
                                        @endphp
                                        {{ number_format($payment->amount, 2) }} {!! $currencySymbol !!}
                                        <br>
                                        <small class="text-muted">
                                            {{ $payment->payment_method ?? 'غير محدد' }}
                                        </small>
                                    </td>
                                    <td>
                                        @php
                                            $statusMap = [
                                                2 => ['class' => 'warning', 'text' => 'غير مكتمل'],
                                                1 => ['class' => 'success', 'text' => 'مكتمل'],
                                                4 => ['class' => 'info', 'text' => 'تحت المراجعة'],
                                                5 => ['class' => 'danger', 'text' => 'فاشلة'],
                                                3 => ['class' => 'secondary', 'text' => 'مسودة']
                                            ];
                                            $status = $statusMap[$payment->payment_status] ?? ['class' => 'dark', 'text' => 'غير معروفة'];
                                        @endphp

                                        <span class="badge bg-{{ $status['class'] }}">
                                            <i class="fa fa-circle me-1"></i> {{ $status['text'] }}
                                        </span>
                                        
                                        @if ($payment->payment_status == 1)
                                            <br>
                                            <small class="text-muted">
                                                <i class="fas fa-check-circle"></i> تم التأكيد
                                            </small>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <div class="dropdown">
                                                <button class="btn bg-gradient-info fa fa-ellipsis-v mr-1 mb-1 btn-sm"
                                                    type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a class="dropdown-item" href="{{ route('paymentsClient.show', $payment->id) }}">
                                                        <i class="fas fa-eye me-2 text-primary"></i>عرض التفاصيل
                                                    </a>
                                                    <a class="dropdown-item" href="{{ route('paymentsClient.edit', $payment->id) }}">
                                                        <i class="fas fa-edit me-2 text-success"></i>تعديل الدفع
                                                    </a>
                                                    <hr class="dropdown-divider">
                                                    <button type="button" class="dropdown-item text-danger {{ auth()->user()->role === 'employee' ? 'disabled-action' : '' }}"
                                                        onclick="{{ auth()->user()->role === 'employee' ? 'showPermissionError()' : 'confirmCancelPayment('.$payment->id.')' }}">
                                                        <i class="fa fa-times me-2"></i>إلغاء عملية الدفع
                                                    </button>
                                                    <a class="dropdown-item" href="{{ route('paymentsClient.rereceipt', ['id' => $payment->id]) }}?type=a4" target="_blank">
                                                        <i class="fas fa-file-pdf me-2 text-warning"></i>إيصال (A4)
                                                    </a>
                                                    <a class="dropdown-item" href="{{ route('paymentsClient.rereceipt', ['id' => $payment->id]) }}?type=thermal" target="_blank">
                                                        <i class="fas fa-receipt me-2 text-warning"></i>إيصال (حراري)
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
            @include('sales::payment.partials.pagination', ['payments' => $payments])
        </div>
    </div>
@else
    <div class="alert alert-info text-center" role="alert">
        <i class="fa fa-info-circle me-2"></i>
        <p class="mb-0">لا توجد مدفوعات تطابق معايير البحث</p>
    </div>
@endif