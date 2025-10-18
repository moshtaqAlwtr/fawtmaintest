{{-- ملف: resources/views/sales/invoices/partials/table.blade.php --}}

@if ($invoices->count() > 0)
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
                                <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1">المبلغ الإجمالي</th>
                                <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1">الحالة</th>
                                <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" style="width: 10%">خيارات</th>
                            </tr>
                        </thead>
            <tbody>
                @foreach ($invoices as $invoice)
                    @php
                        $returnedInvoice = \App\Models\Invoice::where('type', 'returned')
                            ->where('reference_number', $invoice->id)
                            ->first();

                        $payments = \App\Models\PaymentsProcess::where('invoice_id', $invoice->id)
                            ->where('type', 'client payments')
                            ->orderBy('created_at', 'desc')
                            ->get();

                        $currency = $account_setting->currency ?? 'SAR';
                        $currencySymbol = $currency == 'SAR' || empty($currency)
                            ? '<img src="' . asset('assets/images/Saudi_Riyal.svg') . '" alt="ريال سعودي" width="15" style="vertical-align: middle;">'
                            : $currency;

                        $net_due = $invoice->due_value - ($invoice->returned_payment ?? 0);
                    @endphp

                    <tr>
                       
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar me-2" style="background-color: #28a745">
                                    <span class="avatar-content">#</span>
                                </div>
                                <div>
                                    #{{ $invoice->id }}
                                    
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="client-info">
                                <strong>
                                    {{ $invoice->client ? ($invoice->client->trade_name ?: $invoice->client->first_name . ' ' . $invoice->client->last_name) : 'عميل غير معروف' }}
                                </strong>
                               

                            </div>
                        </td>
                        <td>
                            {{ $invoice->created_at ? $invoice->created_at->format($account_setting->time_formula ?? 'Y-m-d H:i') : '' }}
                            <br>
                            <small class="text-muted">أضيفت بواسطة: {{ $invoice->createdByUser->name ?? 'غير محدد' }}</small>
                            @if($invoice->employee)
                                <br><small class="text-muted">للمندوب: {{ $invoice->employee->first_name ?? 'غير محدد' }}</small>
                            @endif
                        </td>
                        <td>
                            {{ number_format($invoice->grand_total ?? $invoice->total, 2) }} {!! $currencySymbol !!}
                            @if ($invoice->due_value > 0)
                                <br>
                                <small class="text-danger">(المبلغ المستحق: {{ number_format($net_due, 2) }}) {!! $currencySymbol !!}</small>
                            @endif
                        </td>
                        <td>
                            {{-- Badge للحالة العامة --}}
                            @if ($returnedInvoice)
                                <span class="badge bg-danger">مرتجع</span>
                            
                            @endif



                            <br>

                            {{-- Badge لحالة الدفع --}}
                            @php
                                $statusMap = [
                                    1 => ['class' => 'success', 'text' => 'مدفوعة بالكامل'],
                                    2 => ['class' => 'info', 'text' => 'مدفوعة جزئياً'],
                                    3 => ['class' => 'danger', 'text' => 'غير مدفوعة'],
                                    4 => ['class' => 'secondary', 'text' => 'مستلمة'],
                                ];
                                $status = $statusMap[$invoice->payment_status] ?? ['class' => 'dark', 'text' => 'غير معروفة'];
                            @endphp

                            <span class="badge bg-{{ $status['class'] }} mt-1">{{ $status['text'] }}</span>
                        </td>
                        <td>
                            <div class="btn-group">
                                <div class="dropdown">
                                    <button class="btn bg-gradient-info fa fa-ellipsis-v mr-1 mb-1 btn-sm"
                                        type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a class="dropdown-item" href="{{ route('invoices.show', $invoice->id) }}">
                                            <i class="fa fa-eye me-2 text-primary"></i>عرض
                                        </a>
                                        <a class="dropdown-item" href="{{ route('invoices.edit', $invoice->id) }}">
                                            <i class="fa fa-edit me-2 text-success"></i>تعديل
                                        </a>
                                        <a class="dropdown-item" href="{{ route('invoices.generatePdf', $invoice->id) }}">
                                            <i class="fa fa-file-pdf me-2 text-danger"></i>PDF
                                        </a>
                                        <a class="dropdown-item" href="{{ route('invoices.generatePdf', $invoice->id) }}">
                                            <i class="fa fa-print me-2 text-dark"></i>طباعة
                                        </a>
                                        <a class="dropdown-item" href="{{ route('invoices.send', $invoice->id) }}">
                                            <i class="fa fa-envelope me-2 text-warning"></i>إرسال إلى العميل
                                        </a>
                                        <a class="dropdown-item" href="{{ route('paymentsClient.create', ['id' => $invoice->id]) }}">
                                            <i class="fa fa-credit-card me-2 text-info"></i>إضافة عملية دفع
                                        </a>
                                       <form id="delete-form-{{ $invoice->id }}" 
      action="{{ route('invoices.destroy', $invoice->id) }}" 
      method="POST" 
      style="display:none;">
    @csrf
    @method('DELETE')
</form>

<a class="dropdown-item text-danger" 
   href="{{ route('invoices.destroy', $invoice->id) }}" 
   onclick="event.preventDefault(); document.getElementById('delete-form-{{ $invoice->id }}').submit();">
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
            @include('sales::invoices.partials.pagination', ['invoices' => $invoices])
        </div>
    </div>
@else
    <div class="alert alert-info text-center" role="alert">
        <i class="fa fa-info-circle me-2"></i>
        <p class="mb-0">لا يوجد فواتير تطابق معايير البحث</p>
    </div>
@endif