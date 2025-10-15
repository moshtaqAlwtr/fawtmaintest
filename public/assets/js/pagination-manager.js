/**
 * Pagination Manager - مدير الترقيم المحسّن
 * يدير عمليات التنقل بين الصفحات
 */

class PaginationManager {
    constructor() {
        this.currentPage = 1;
        this.lastPage = 1;
        this.perPage = 50;
        this.isLoading = false;
    }

    /**
     * تهيئة مدير الترقيم
     */
    init() {
        this.bindEvents();
        console.log('✅ Pagination Manager تم تهيئة');
    }

    /**
     * ربط الأحداث
     */
    bindEvents() {
        // معالج النقر على روابط الترقيم
        $(document).on('click', '.pagination-link', (e) => {
            e.preventDefault();

            if (this.isLoading) {
                console.log('⏳ جاري التحميل...');
                return;
            }

            const page = parseInt($(e.currentTarget).data('page'));
            console.log('🔢 النقر على الصفحة:', page);

            if (page && !isNaN(page) && page !== this.currentPage) {
                this.goToPage(page);
            }
        });
    }

    /**
     * الانتقال إلى صفحة محددة
     */
    goToPage(page) {
        console.log('📄 الانتقال للصفحة:', page);

        this.isLoading = true;
        this.currentPage = page;

        // الحصول على الفلاتر الحالية من النموذج
        const filters = this.getCurrentFilters();
        filters.page = page;

        console.log('🔍 الفلاتر المرسلة:', filters);

        // إظهار مؤشر التحميل
        this.showLoading();

        // إرسال طلب AJAX
        $.ajax({
            url: window.location.pathname,
            method: 'GET',
            data: filters,
            dataType: 'json',
            success: (response) => {
                console.log('✅ نجح تحميل الصفحة:', response);
                this.handleSuccess(response);
            },
            error: (xhr, status, error) => {
                console.error('❌ خطأ في تحميل الصفحة:', error);
                this.handleError(xhr);
            },
            complete: () => {
                this.isLoading = false;
                this.hideLoading();
            }
        });
    }

    /**
     * الحصول على الفلاتر الحالية
     */
    getCurrentFilters() {
        const filters = {};

        // جمع الفلاتر من النموذج
        $('#filterForm').find('input, select').each(function() {
            const name = $(this).attr('name');
            const value = $(this).val();

            if (name && value) {
                filters[name] = value;
            }
        });

        return filters;
    }

    /**
     * معالجة الاستجابة الناجحة
     */
    handleSuccess(response) {
        // تحديث البطاقات
        if (response.html) {
            $('.row.g-4').html(response.html);
        }

        // تحديث الترقيم
        if (response.pagination) {
            this.updatePaginationInfo(response.pagination);
            this.renderPaginationControls(response.pagination);
        }

        // تم إزالة التمرير للأعلى - الصفحة تبقى في نفس المكان

        // إعادة تهيئة الـ Charts
        if (typeof createCharts === 'function') {
            setTimeout(() => createCharts(), 200);
        }
    }

    /**
     * معالجة الأخطاء
     */
    handleError(xhr) {
        let errorMessage = 'حدث خطأ أثناء تحميل الصفحة';

        if (xhr.responseJSON && xhr.responseJSON.message) {
            errorMessage = xhr.responseJSON.message;
        }

        // عرض رسالة الخطأ
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'error',
                title: 'خطأ',
                text: errorMessage,
                confirmButtonText: 'حسناً'
            });
        } else {
            alert(errorMessage);
        }
    }

    /**
     * عرض مؤشر التحميل
     */
    showLoading() {
        // إظهار overlay على البطاقات
        if ($('.row.g-4').length) {
            $('.row.g-4').css('opacity', '0.5');
        }

        // تعطيل أزرار الترقيم
        $('.pagination-link').addClass('disabled').css('pointer-events', 'none');
    }

    /**
     * إخفاء مؤشر التحميل
     */
    hideLoading() {
        if ($('.row.g-4').length) {
            $('.row.g-4').css('opacity', '1');
        }

        $('.pagination-link').removeClass('disabled').css('pointer-events', 'auto');
    }

    /**
     * رسم عناصر التحكم بالترقيم
     */
    renderPaginationControls(paginationData) {
        const container = $('.pagination').parent().parent();
        if (!container.length) return;

        const html = this.generatePaginationHTML(paginationData);
        container.html(html);
    }

    /**
     * توليد HTML للترقيم
     */
    generatePaginationHTML(data) {
        const onFirstPage = data.current_page === 1;
        const hasMorePages = data.current_page < data.last_page;

        return `
            <div class="d-flex justify-content-between align-items-center mt-3 w-100">
                <div class="pagination-info text-muted">
                    عرض ${data.from || 0} إلى ${data.to || 0} من ${data.total || 0} نتيجة
                </div>
                <nav aria-label="صفحات النتائج">
                    <ul class="pagination pagination-sm mb-0">
                        <li class="page-item ${onFirstPage ? 'disabled' : ''}">
                            ${onFirstPage ?
                                '<span class="page-link"><i class="fa fa-angle-double-right"></i></span>' :
                                `<a class="page-link pagination-link" href="#" data-page="1"><i class="fa fa-angle-double-right"></i></a>`
                            }
                        </li>
                        <li class="page-item ${onFirstPage ? 'disabled' : ''}">
                            ${onFirstPage ?
                                '<span class="page-link"><i class="fa fa-angle-right"></i></span>' :
                                `<a class="page-link pagination-link" href="#" data-page="${data.current_page - 1}"><i class="fa fa-angle-right"></i></a>`
                            }
                        </li>
                        <li class="page-item active">
                            <span class="page-link">${data.current_page}</span>
                        </li>
                        <li class="page-item ${hasMorePages ? '' : 'disabled'}">
                            ${hasMorePages ?
                                `<a class="page-link pagination-link" href="#" data-page="${data.current_page + 1}"><i class="fa fa-angle-left"></i></a>` :
                                '<span class="page-link"><i class="fa fa-angle-left"></i></span>'
                            }
                        </li>
                        <li class="page-item ${hasMorePages ? '' : 'disabled'}">
                            ${hasMorePages ?
                                `<a class="page-link pagination-link" href="#" data-page="${data.last_page}"><i class="fa fa-angle-double-left"></i></a>` :
                                '<span class="page-link"><i class="fa fa-angle-double-left"></i></span>'
                            }
                        </li>
                    </ul>
                </nav>
            </div>
        `;
    }

    /**
     * تحديث معلومات الترقيم
     */
    updatePaginationInfo(paginationData) {
        this.currentPage = paginationData.current_page || 1;
        this.lastPage = paginationData.last_page || 1;
        this.perPage = paginationData.per_page || 50;

        console.log('📊 معلومات الترقيم:', {
            current: this.currentPage,
            last: this.lastPage,
            perPage: this.perPage
        });
    }

    /**
     * الانتقال إلى الصفحة التالية
     */
    nextPage() {
        if (this.hasNextPage()) {
            this.goToPage(this.currentPage + 1);
        }
    }

    /**
     * الانتقال إلى الصفحة السابقة
     */
    previousPage() {
        if (this.hasPreviousPage()) {
            this.goToPage(this.currentPage - 1);
        }
    }

    /**
     * التحقق من إمكانية الانتقال للصفحة التالية
     */
    hasNextPage() {
        return this.currentPage < this.lastPage;
    }

    /**
     * التحقق من إمكانية الانتقال للصفحة السابقة
     */
    hasPreviousPage() {
        return this.currentPage > 1;
    }

    /**
     * تنظيف الموارد
     */
    cleanup() {
        this.currentPage = 1;
        this.lastPage = 1;
        this.perPage = 50;
        this.isLoading = false;
    }
}

// تصدير وتهيئة تلقائية
window.PaginationManager = PaginationManager;

// تهيئة تلقائية عند تحميل الصفحة
$(document).ready(function() {
    if (!window.paginationManager) {
        window.paginationManager = new PaginationManager();
        window.paginationManager.init();
    }
});