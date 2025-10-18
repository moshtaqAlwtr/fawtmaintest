{{-- ملف: resources/views/sales/periodic_invoices/partials/pagination.blade.php - نسخة مبسطة --}}

@if ($periodicInvoices->hasPages())

    <div class="d-flex justify-content-between align-items-center mt-3 w-100">
        {{-- معلومات النتائج --}}
        <div class="pagination-info text-muted">
            عرض {{ $periodicInvoices->firstItem() }} إلى {{ $periodicInvoices->lastItem() }} من {{ $periodicInvoices->total() }} نتيجة
        </div>

        {{-- أزرار الترقيم --}}
        <nav aria-label="صفحات النتائج">
            <ul class="pagination pagination-sm mb-0">
                {{-- الصفحة الأولى --}}
                @if ($periodicInvoices->onFirstPage())
                    <li class="page-item disabled">
                        <span class="page-link"><i class="fa fa-angle-double-right"></i></span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $periodicInvoices->url(1) }}">
                            <i class="fa fa-angle-double-right"></i>
                        </a>
                    </li>
                @endif

                {{-- الصفحة السابقة --}}
                @if ($periodicInvoices->onFirstPage())
                    <li class="page-item disabled">
                        <span class="page-link"><i class="fa fa-angle-right"></i></span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $periodicInvoices->previousPageUrl() }}">
                            <i class="fa fa-angle-right"></i>
                        </a>
                    </li>
                @endif

                {{-- رقم الصفحة الحالية --}}
                <li class="page-item active">
                    <span class="page-link">{{ $periodicInvoices->currentPage() }}</span>
                </li>

                {{-- الصفحة التالية --}}
                @if ($periodicInvoices->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="{{ $periodicInvoices->nextPageUrl() }}">
                            <i class="fa fa-angle-left"></i>
                        </a>
                    </li>
                @else
                    <li class="page-item disabled">
                        <span class="page-link"><i class="fa fa-angle-left"></i></span>
                    </li>
                @endif

                {{-- الصفحة الأخيرة --}}
                @if ($periodicInvoices->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="{{ $periodicInvoices->url($periodicInvoices->lastPage()) }}">
                            <i class="fa fa-angle-double-left"></i>
                        </a>
                    </li>
                @else
                    <li class="page-item disabled">
                        <span class="page-link"><i class="fa fa-angle-double-left"></i></span>
                    </li>
                @endif
            </ul>
        </nav>
    </div>
@endif
