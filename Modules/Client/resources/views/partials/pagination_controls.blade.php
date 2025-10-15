{{-- resources/views/client/partials/pagination_controls.blade.php --}}
@if ($clients->hasPages())
    <div class="d-flex justify-content-between align-items-center mt-3 w-100">
        {{-- معلومات الترقيم --}}
        <div class="pagination-info text-muted">
            عرض {{ $clients->firstItem() }} إلى {{ $clients->lastItem() }} من {{ $clients->total() }} نتيجة
        </div>

        {{-- أزرار التنقل --}}
        <nav aria-label="صفحات النتائج">
            <ul class="pagination pagination-sm mb-0">
                {{-- الصفحة الأولى --}}
                @if ($clients->onFirstPage())
                    <li class="page-item disabled">
                        <span class="page-link"><i class="fa fa-angle-double-right"></i></span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link pagination-link" href="#" data-page="1" aria-label="الأول">
                            <i class="fa fa-angle-double-right"></i>
                        </a>
                    </li>
                @endif

                {{-- الصفحة السابقة --}}
                @if ($clients->onFirstPage())
                    <li class="page-item disabled">
                        <span class="page-link"><i class="fa fa-angle-right"></i></span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link pagination-link" href="#"
                           data-page="{{ $clients->currentPage() - 1 }}"
                           aria-label="السابق">
                            <i class="fa fa-angle-right"></i>
                        </a>
                    </li>
                @endif

                {{-- رقم الصفحة الحالية --}}
                <li class="page-item active">
                    <span class="page-link">{{ $clients->currentPage() }}</span>
                </li>

                {{-- الصفحة التالية --}}
                @if ($clients->hasMorePages())
                    <li class="page-item">
                        <a class="page-link pagination-link" href="#"
                           data-page="{{ $clients->currentPage() + 1 }}"
                           aria-label="التالي">
                            <i class="fa fa-angle-left"></i>
                        </a>
                    </li>
                @else
                    <li class="page-item disabled">
                        <span class="page-link"><i class="fa fa-angle-left"></i></span>
                    </li>
                @endif

                {{-- الصفحة الأخيرة --}}
                @if ($clients->hasMorePages())
                    <li class="page-item">
                        <a class="page-link pagination-link" href="#"
                           data-page="{{ $clients->lastPage() }}"
                           aria-label="الأخير">
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


<script>
    document.addEventListener('DOMContentLoaded', function() {
        // ربط أحداث النقر على أزرار الترقيم
        document.querySelectorAll('.pagination-link').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const page = parseInt(this.getAttribute('data-page'));

                if (page && !isNaN(page)) {
                    // استخدام PaginationManager إذا كان متاحاً
                    if (window.paginationManager) {
                        window.paginationManager.goToPage(page);
                    } else {
                        console.log('الانتقال للصفحة:', page);
                    }
                }
            });
        });
    });
</script>
