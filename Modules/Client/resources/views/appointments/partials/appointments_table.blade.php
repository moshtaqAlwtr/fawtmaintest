@if (@isset($appointments) && !@empty($appointments) && count($appointments) > 0)
    <div class="card-content">
        <div class="card-body card-dashboard">

            <div class="table">
                <table class="table zero-configuration">
                    <thead>
                        <tr>
                            <th class="min-mobile">اسم العميل</th>
                            <th class="min-tablet">حالة العميل</th>
                            <th class="min-tablet">رقم الهاتف</th>
                            <th class="min-mobile">التاريخ</th>
                            <th class="min-tablet">الوقت</th>
                            <th class="min-desktop">المدة</th>
                            <th class="min-tablet">الموظف</th>
                            <th class="min-mobile">الحالة</th>
                            <th style="width: 120px">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($appointments as $info)
                            <tr>
                                <td class="min-mobile">{{ $info->client->trade_name }}</td>
                                <td class="min-tablet">
                                    @if ($info->client->status_client)
                                        <span
                                            style="background-color: {{ $info->client->status_client->color }}; color: #fff; padding: 2px 8px; font-size: 12px; border-radius: 4px;">
                                            {{ $info->client->status_client->name }}
                                        </span>
                                    @else
                                        <span
                                            style="background-color: #6c757d; color: #fff; padding: 2px 8px; font-size: 12px; border-radius: 4px;">
                                            غير محدد
                                        </span>
                                    @endif
                                </td>
                                <td class="min-tablet">{{ $info->client->phone }}</td>
                                <td class="min-mobile">
                                    {{ \Carbon\Carbon::parse($info->appointment_date)->format('Y-m-d') }}</td>
                                <td class="min-tablet">{{ $info->time }}</td>
                                <td class="min-desktop">{{ $info->duration ?? 'غير محدد' }}</td>
                                <td class="min-tablet">{{ $info->createdBy ? $info->createdBy->name : 'غير محدد' }}</td>
                                <td class="min-mobile">
                                    <span
                                        class="badge {{ $info->status == 1 ? 'bg-warning' : ($info->status == 2 ? 'bg-success' : ($info->status == 3 ? 'bg-danger' : 'bg-info')) }}">
                                        {{ $info->status == 1 ? 'قيد الانتظار' : ($info->status == 2 ? 'مكتمل' : ($info->status == 3 ? 'ملغي' : 'معاد جدولته')) }}
                                    </span>
                                </td>
                                <!-- تصحيح بنية الدروب داون -->
                                <td style="width: 120px">
                                    <div class="dropdown">
                                        <button class="btn bg-gradient-info fa fa-ellipsis-v mr-1 mb-1 btn-sm"
                                            type="button" id="dropdownMenu{{ $info->id }}"
                                            data-bs-toggle="dropdown">
                                        </button>

                                        <ul class="dropdown-menu dropdown-menu-end shadow-lg"
                                            aria-labelledby="dropdownMenu{{ $info->id }}">
                                            <li>
                                                <a class="dropdown-item"
                                                    href="{{ route('appointments.edit', $info->id) }}">
                                                    <i class="fa fa-edit me-2 text-success"></i>تعديل
                                                </a>
                                            </li>

                                            <li>
                                                <a class="dropdown-item status-action"
                                                    href="{{ route('update-status', ['id' => $info->id, 'status' => 1]) }}">
                                                    <i class="fa fa-clock me-2 text-warning"></i>قيد الانتظار
                                                </a>
                                            </li>

                                            <li>
                                                <a class="dropdown-item status-action"
                                                    href="{{ route('update-status', ['id' => $info->id, 'status' => 2]) }}">
                                                    <i class="fa fa-check me-2 text-success"></i>مكتمل
                                                </a>
                                            </li>

                                            <li>
                                                <a class="dropdown-item status-action"
                                                    href="{{ route('update-status', ['id' => $info->id, 'status' => 3]) }}">
                                                    <i class="fa fa-times me-2 text-danger"></i>ملغي
                                                </a>
                                            </li>

                                            <li>
                                                <a class="dropdown-item status-action"
                                                    href="{{ route('update-status', ['id' => $info->id, 'status' => 4]) }}">
                                                    <i class="fa fa-redo me-2 text-info"></i>معاد جدولته
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>


                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- ✅ تصميم الباجينيشن الجديد --}}
            @if ($appointments instanceof \Illuminate\Pagination\LengthAwarePaginator && $appointments->hasPages())
                <div class="d-flex justify-content-between align-items-center mt-3 w-100">
                    <div class="pagination-info text-muted">
                        عرض {{ $appointments->firstItem() }} إلى {{ $appointments->lastItem() }} من
                        {{ $appointments->total() }} نتيجة
                    </div>
                    <nav aria-label="صفحات النتائج">
                        <ul class="pagination pagination-sm mb-0">
                            {{-- الصفحة الأولى --}}
                            @if ($appointments->onFirstPage())
                                <li class="page-item disabled"><span class="page-link"><i
                                            class="fa fa-angle-double-right"></i></span></li>
                            @else
                                <li class="page-item"><a class="page-link" href="{{ $appointments->url(1) }}"><i
                                            class="fa fa-angle-double-right"></i></a></li>
                            @endif

                            {{-- السابق --}}
                            @if ($appointments->onFirstPage())
                                <li class="page-item disabled"><span class="page-link"><i
                                            class="fa fa-angle-right"></i></span></li>
                            @else
                                <li class="page-item"><a class="page-link"
                                        href="{{ $appointments->previousPageUrl() }}"><i
                                            class="fa fa-angle-right"></i></a></li>
                            @endif

                            {{-- الحالية --}}
                            <li class="page-item active"><span
                                    class="page-link">{{ $appointments->currentPage() }}</span></li>

                            {{-- التالي --}}
                            @if ($appointments->hasMorePages())
                                <li class="page-item"><a class="page-link" href="{{ $appointments->nextPageUrl() }}"><i
                                            class="fa fa-angle-left"></i></a></li>
                            @else
                                <li class="page-item disabled"><span class="page-link"><i
                                            class="fa fa-angle-left"></i></span></li>
                            @endif

                            {{-- الأخيرة --}}
                            @if ($appointments->hasMorePages())
                                <li class="page-item"><a class="page-link"
                                        href="{{ $appointments->url($appointments->lastPage()) }}"><i
                                            class="fa fa-angle-double-left"></i></a></li>
                            @else
                                <li class="page-item disabled"><span class="page-link"><i
                                            class="fa fa-angle-double-left"></i></span></li>
                            @endif
                        </ul>
                    </nav>
                </div>
            @endif
        </div>
    </div>
@else
    <div class="alert alert-info text-center">
        <p class="mb-0">لا توجد مواعيد مسجلة حالياً</p>
    </div>
@endif

<style>
    .dropdown-menu {
        position: absolute !important;
        z-index: 1000 !important;
        min-width: 10rem !important;
        background-color: #fff !important;
        border-radius: 0.25rem !important;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.175) !important;
    }

    .dropdown-item:hover {
        background-color: #f8f9fa !important;
    }

    @media (max-width: 768px) {

        .min-tablet,
        .min-desktop {
            display: none;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('.zero-configuration').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.21/i18n/Arabic.json"
            },
            "order": [
                [3, "desc"]
            ],
            "responsive": true,
            "paging": false // ✅ إيقاف الباجينيشن الداخلي
        });
    });
</script>