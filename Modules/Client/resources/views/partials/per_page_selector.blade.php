{{-- resources/views/client/partials/per_page_selector.blade.php --}}
<div class="per-page-selector">
    <div class="selector-container">
        <label for="perPageSelect" class="selector-label">
            <i class="fas fa-list-ul me-1"></i>
            عرض
        </label>

        <div class="select-wrapper">
            <select id="perPageSelect" class="form-select per-page-select">
                <option value="10" {{ request('perPage', 50) == 10 ? 'selected' : '' }}>10</option>
                <option value="25" {{ request('perPage', 50) == 25 ? 'selected' : '' }}>25</option>
                <option value="50" {{ request('perPage', 50) == 50 ? 'selected' : '' }}>50</option>
                <option value="100" {{ request('perPage', 50) == 100 ? 'selected' : '' }}>100</option>
            </select>
            <i class="fas fa-chevron-down select-icon"></i>
        </div>

        <span class="selector-text">عنصر في الصفحة</span>
    </div>

    <!-- عرض إجمالي النتائج -->
    @if (isset($clients) && $clients->total() > 0)
        <div class="total-count">
            <small class="text-muted">
                <i class="fas fa-database me-1"></i>
                {{ number_format($clients->total()) }} إجمالي
            </small>
        </div>
    @endif
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const perPageSelect = document.getElementById('perPageSelect');

        if (perPageSelect) {
            perPageSelect.addEventListener('change', function() {
                const selectedValue = this.value;

                // إنشاء URL جديد مع القيمة المحددة
                const url = new URL(window.location);
                url.searchParams.set('perPage', selectedValue);
                url.searchParams.delete('page'); // إعادة تعيين رقم الصفحة إلى 1

                // إضافة تأثير loading
                this.disabled = true;
                this.style.opacity = '0.7';

                // إعادة توجيه إلى URL الجديد
                window.location.href = url.toString();
            });

            // تأثير بصري عند التغيير
            perPageSelect.addEventListener('focus', function() {
                this.closest('.per-page-selector').style.transform = 'scale(1.02)';
                this.closest('.per-page-selector').style.boxShadow = '0 4px 12px rgba(0,0,0,0.15)';
            });

            perPageSelect.addEventListener('blur', function() {
                this.closest('.per-page-selector').style.transform = 'scale(1)';
                this.closest('.per-page-selector').style.boxShadow = 'none';
            });
        }
    });
</script>
