/**
 * إدارة جلسات نقطة البيع
 */
class PosSessionManager {
    constructor() {
        this.currentSession = null;
        this.sessionCheckInterval = null;
        this.init();
    }

    init() {
        this.checkActiveSession();
        this.bindEvents();
        this.startSessionMonitoring();
    }

    /**
     * فحص الجلسة النشطة
     */
    async checkActiveSession() {
        try {
            const response = await fetch('/pos/check-session', {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json'
                }
            });

            const data = await response.json();
            
            if (data.has_active_session) {
                this.currentSession = data.session;
                this.updateSessionDisplay();
                return true;
            } else {
                this.handleNoActiveSession();
                return false;
            }
        } catch (error) {
            console.error('خطأ في فحص الجلسة:', error);
            return false;
        }
    }

    /**
     * تحديث عرض معلومات الجلسة
     */
    updateSessionDisplay() {
        if (!this.currentSession) return;

        // تحديث معلومات الجلسة في الواجهة
        const sessionInfo = document.getElementById('session-info');
        if (sessionInfo) {
            sessionInfo.innerHTML = `
                <div class="session-status active">
                    <i class="fas fa-circle text-success"></i>
                    <span>جلسة نشطة: ${this.currentSession.session_number}</span>
                    <small class="text-muted">بدأت: ${this.currentSession.started_at}</small>
                </div>
            `;
        }

        // إظهار أزرار نقطة البيع
        const posControls = document.getElementById('pos-controls');
        if (posControls) {
            posControls.style.display = 'block';
        }
    }

    /**
     * التعامل مع عدم وجود جلسة نشطة
     */
    handleNoActiveSession() {
        this.currentSession = null;

        // إخفاء أزرار نقطة البيع
        const posControls = document.getElementById('pos-controls');
        if (posControls) {
            posControls.style.display = 'none';
        }

        // عرض رسالة عدم وجود جلسة
        const sessionInfo = document.getElementById('session-info');
        if (sessionInfo) {
            sessionInfo.innerHTML = `
                <div class="session-status inactive">
                    <i class="fas fa-exclamation-circle text-warning"></i>
                    <span>لا توجد جلسة نشطة</span>
                    <a href="/pos/sessions" class="btn btn-sm btn-primary ms-2">بدء جلسة</a>
                </div>
            `;
        }

        // إذا كنا في صفحة نقطة البيع، توجه لصفحة الجلسات
        if (window.location.pathname.includes('/pos/') && !window.location.pathname.includes('/pos/sessions')) {
            this.showSessionWarning();
        }
    }

    /**
     * عرض تحذير عدم وجود جلسة
     */
    showSessionWarning() {
        Swal.fire({
            icon: 'warning',
            title: 'لا توجد جلسة نشطة',
            text: 'يجب بدء جلسة عمل قبل استخدام نقطة البيع',
            confirmButtonText: 'بدء جلسة',
            cancelButtonText: 'إلغاء',
            showCancelButton: true
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '/pos/sessions';
            }
        });
    }

    /**
     * ربط الأحداث
     */
    bindEvents() {
        // مراقبة أخطاء AJAX للجلسات
        $(document).ajaxError((event, xhr, settings, error) => {
            if (xhr.responseJSON && xhr.responseJSON.error_type === 'no_active_session') {
                this.handleNoActiveSession();
                this.showSessionWarning();
            }
        });

        // زر إحصائيات الجلسة
        $(document).on('click', '#session-stats-btn', () => {
            this.showSessionStats();
        });

        // زر إغلاق الجلسة
        $(document).on('click', '#close-session-btn', () => {
            this.confirmCloseSession();
        });
    }

    /**
     * بدء مراقبة الجلسة
     */
    startSessionMonitoring() {
        // فحص الجلسة كل 5 دقائق
        this.sessionCheckInterval = setInterval(() => {
            this.checkActiveSession();
        }, 5 * 60 * 1000);

        // تحديث إحصائيات الجلسة كل دقيقة
        setInterval(() => {
            if (this.currentSession) {
                this.updateSessionStats();
            }
        }, 60 * 1000);
    }

    /**
     * تحديث إحصائيات الجلسة
     */
    async updateSessionStats() {
        try {
            const response = await fetch('/pos/stats/session');
            const data = await response.json();

            if (data.success) {
                this.displaySessionStats(data.stats);
            }
        } catch (error) {
            console.error('خطأ في تحديث إحصائيات الجلسة:', error);
        }
    }

    /**
     * عرض إحصائيات الجلسة
     */
    displaySessionStats(stats) {
        const statsContainer = document.getElementById('session-stats');
        if (statsContainer && stats) {
            statsContainer.innerHTML = `
                <div class="row">
                    <div class="col-md-3">
                        <div class="stat-card">
                            <h5>${stats.total_sales}</h5>
                            <small>إجمالي المبيعات</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card">
                            <h5>${stats.total_transactions}</h5>
                            <small>عدد المعاملات</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card">
                            <h5>${stats.total_cash}</h5>
                            <small>المبلغ النقدي</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card">
                            <h5>${stats.expected_balance}</h5>
                            <small>الرصيد المتوقع</small>
                        </div>
                    </div>
                </div>
            `;
        }
    }

    /**
     * عرض إحصائيات الجلسة في نافذة منبثقة
     */
    async showSessionStats() {
        try {
            const response = await fetch('/pos/stats/session');
            const data = await response.json();

            if (data.success) {
                const stats = data.stats;
                Swal.fire({
                    title: `إحصائيات الجلسة: ${stats.session_number}`,
                    html: `
                        <div class="session-stats-modal">
                            <div class="row">
                                <div class="col-6">
                                    <div class="stat-item">
                                        <strong>بداية الجلسة:</strong> ${stats.started_at}
                                    </div>
                                    <div class="stat-item">
                                        <strong>الرصيد الافتتاحي:</strong> ${stats.opening_balance} ر.س
                                    </div>
                                    <div class="stat-item">
                                        <strong>إجمالي المبيعات:</strong> ${stats.total_sales} ر.س
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="stat-item">
                                        <strong>عدد المعاملات:</strong> ${stats.total_transactions}
                                    </div>
                                    <div class="stat-item">
                                        <strong>المبلغ النقدي:</strong> ${stats.total_cash} ر.س
                                    </div>
                                    <div class="stat-item">
                                        <strong>مبلغ البطاقات:</strong> ${stats.total_card} ر.س
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="stat-item text-center">
                                <strong>الرصيد المتوقع: ${stats.expected_balance} ر.س</strong>
                            </div>
                        </div>
                    `,
                    width: 600,
                    confirmButtonText: 'حسناً'
                });
            }
        } catch (error) {
            console.error('خطأ في عرض إحصائيات الجلسة:', error);
        }
    }

    /**
     * تأكيد إغلاق الجلسة
     */
    confirmCloseSession() {
        if (!this.currentSession) {
            return;
        }

        Swal.fire({
            title: 'إغلاق الجلسة',
            text: 'هل أنت متأكد من إغلاق الجلسة الحالية؟',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'نعم، إغلاق',
            cancelButtonText: 'إلغاء',
            confirmButtonColor: '#d33'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `/pos/sessions/${this.currentSession.id}/close-form`;
            }
        });
    }

    /**
     * إيقاف مراقبة الجلسة
     */
    stopMonitoring() {
        if (this.sessionCheckInterval) {
            clearInterval(this.sessionCheckInterval);
        }
    }
}

/**
 * مدير نقطة البيع مع دعم الجلسات
 */
class SessionAwarePosManager extends PosManager {
    constructor() {
        super();
        this.sessionManager = new PosSessionManager();
    }

    /**
     * التحقق من الجلسة قبل إجراء أي عملية
     */
    async checkSessionBeforeAction() {
        const hasActiveSession = await this.sessionManager.checkActiveSession();
        if (!hasActiveSession) {
            this.sessionManager.showSessionWarning();
            return false;
        }
        return true;
    }

    /**
     * حفظ الفاتورة مع التحقق من الجلسة
     */
    async saveInvoice(invoiceData) {
        if (!(await this.checkSessionBeforeAction())) {
            return false;
        }

        return super.saveInvoice(invoiceData);
    }

    /**
     * البحث مع التحقق من الجلسة
     */
    async performSearch(query, type = 'all') {
        if (!(await this.checkSessionBeforeAction())) {
            return false;
        }

        return super.performSearch(query, type);
    }

    /**
     * إضافة منتج للسلة مع التحقق من الجلسة
     */
    async addProductToCart(product) {
        if (!(await this.checkSessionBeforeAction())) {
            return false;
        }

        return super.addProductToCart(product);
    }
}

// تهيئة مدير نقطة البيع مع دعم الجلسات عند تحميل الصفحة
$(document).ready(function() {
    if (typeof window.posManager === 'undefined') {
        window.posManager = new SessionAwarePosManager();
    }
    
    // تحديث إحصائيات الجلسة عند بداية التحميل
    if (window.posManager.sessionManager.currentSession) {
        window.posManager.sessionManager.updateSessionStats();
    }
});

// تنظيف الموارد عند مغادرة الصفحة
$(window).on('beforeunload', function() {
    if (window.posManager && window.posManager.sessionManager) {
        window.posManager.sessionManager.stopMonitoring();
    }
});