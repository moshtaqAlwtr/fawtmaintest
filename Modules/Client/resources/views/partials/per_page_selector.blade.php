<div class="per-page-selector">
    <div class="row align-items-center">
        <div class="col-12">
            <div class="dataTables_length" id="DataTables_Table_0_length" style="text-align: left;">
                <label style="margin-bottom: 0;">
                    عرض
                    <select id="perPageSelect" name="DataTables_Table_0_length"
                        aria-controls="DataTables_Table_0"
                        class="form-select form-select-sm d-inline-block w-auto">
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
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const perPageSelect = document.getElementById('perPageSelect');

        if (perPageSelect) {
            perPageSelect.addEventListener('change', function() {
                const selectedValue = this.value;
                const url = new URL(window.location);
                url.searchParams.set('per_page', selectedValue);
                url.searchParams.delete('page'); // لإرجاع الصفحة للأولى

                this.disabled = true;
                this.style.opacity = '0.7';

                window.location.href = url.toString();
            });

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
