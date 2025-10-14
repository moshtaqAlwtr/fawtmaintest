@if ($payments->count() > 0)
    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="bg-light">
                <tr>
                    <th width="5%">
                        <input type="checkbox" class="form-check-input" id="selectAll">
                    </th>
                    <th width="20%">البيانات الأساسية</th>
                    <th width="15%">العميل</th>
                    <th width="15%">التاريخ والموظف</th>
                    <th width="15%" class="text-center">المبلغ</th>
                    <th width="15%" class="text-center">الحالة</th>
                    <th width="15%" class="text-end">الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($payments as $payment)
                    <tr>
                        <td>
                            <input type="checkbox" class="form-check-input payment-checkbox" value="{{ $payment->id }}">
                        </td>
                        <td style="white-space: normal; word-wrap: break-word;">
                            <div class="d-flex flex-column">
                                <strong>#{{ $payment->id }}</strong>
                                <small class="text-muted">
                                    @if ($payment->invoice)
                                        الفاتورة: #{{ $payment->invoice->code ?? '--' }}
                                    @endif
                                </small>
                                @if ($payment->notes)
                                    <small class="text-muted mt-1" style="white-space: normal;">
                                        <i class="fas fa-comment-alt"></i> {{ $payment->notes }}
                                    </small>
                                @endif
                            </div>
                        </td>
                        <td>
                            @if ($payment->invoice && $payment->invoice->client)
                                <div class="d-flex flex-column">
                                    <strong>{{ $payment->invoice->client->trade_name ?? '' }}</strong>
                                    <small class="text-muted">
                                        <i class="fas fa-phone"></i>
                                        {{ $payment->invoice->client->phone ?? '' }}
                                    </small>
                                </div>
                            @else
                                <span class="text-danger">لا يوجد عميل</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex flex-column">
                                <small><i class="fas fa-calendar"></i> {{ $payment->payment_date }}</small>
                                @if ($payment->employee)
                                    <small class="text-muted mt-1">
                                        <i class="fas fa-user"></i> {{ $payment->employee->name ?? '' }}
                                    </small>
                                @endif
                                <small class="text-muted mt-1">
                                    <i class="fas fa-clock"></i> {{ $payment->created_at->format('H:i') }}
                                </small>
                            </div>
                        </td>
                        <td class="text-center">
                            @php
                                $currency = $account_setting->currency ?? 'SAR';
                                $currencySymbol = $currency == 'SAR' || empty($currency)
                                    ? '<img src="' . asset('assets/images/Saudi_Riyal.svg') . '" alt="ريال سعودي" width="15" style="vertical-align: middle;">'
                                    : $currency;
                            @endphp
                            <h6 class="mb-0 font-weight-bold">
                                {{ number_format($payment->amount, 2) }} {!! $currencySymbol !!}
                            </h6>
                            <small class="text-muted">
                                {{ $payment->payment_method ?? 'غير محدد' }}
                            </small>
                        </td>
                        <td class="text-center">
                            @php
                                $statusClass = '';
                                $statusText = '';
                                $statusIcon = '';

                                if ($payment->payment_status == 2) {
                                    $statusClass = 'badge-warning';
                                    $statusText = 'غير مكتمل';
                                    $statusIcon = 'fa-clock';
                                } elseif ($payment->payment_status == 1) {
                                    $statusClass = 'badge-success';
                                    $statusText = 'مكتمل';
                                    $statusIcon = 'fa-check-circle';
                                } elseif ($payment->payment_status == 4) {
                                    $statusClass = 'badge-info';
                                    $statusText = 'تحت المراجعة';
                                    $statusIcon = 'fa-sync';
                                } elseif ($payment->payment_status == 5) {
                                    $statusClass = 'badge-danger';
                                    $statusText = 'فاشلة';
                                    $statusIcon = 'fa-times-circle';
                                } elseif ($payment->payment_status == 3) {
                                    $statusClass = 'badge-secondary';
                                    $statusText = 'مسودة';
                                    $statusIcon = 'fa-file-alt';
                                } else {
                                    $statusClass = 'badge-light';
                                    $statusText = 'غير معروف';
                                    $statusIcon = 'fa-question-circle';
                                }
                            @endphp
                            <span class="badge {{ $statusClass }} rounded-pill">
                                <i class="fas {{ $statusIcon }} me-1"></i>
                                {{ $statusText }}
                            </span>
                            @if ($payment->payment_status == 1)
                                <small class="d-block text-muted mt-1">
                                    <i class="fas fa-check-circle"></i> تم التأكيد
                                </small>
                            @endif
                        </td>
                        <td class="text-end">
                            <div class="btn-group">
                                <div class="dropdown">
                                    <button class="btn bg-gradient-info fa fa-ellipsis-v mr-1 mb-1 btn-sm"
                                        type="button" data-toggle="dropdown" aria-expanded="false">
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

    {{-- الترقيم --}}
    @include('sales::payment.partials.pagination', ['payments' => $payments])
@else
    <div class="alert alert-info text-center" role="alert">
        <i class="fa fa-info-circle me-2"></i>
        <p class="mb-0">لا توجد مدفوعات تطابق معايير البحث</p>
    </div>
@endif