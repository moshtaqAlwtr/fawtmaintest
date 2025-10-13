@extends('taskmanager::master')

@section('title')
    تفاصيل مساحة العمل
@stop

@section('css')
<style>
/* تنسيقات عامة لتفاصيل مساحة العمل */
.workspace-info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 25px;
}

.info-card {
    background: #f8f9fa;
    border-radius: 12px;
    padding: 20px;
    border-left: 4px solid #007bff;
    transition: all 0.3s ease;
}

.info-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.info-label {
    font-size: 0.85rem;
    color: #6c757d;
    margin-bottom: 8px;
    font-weight: 500;
}

.info-value {
    font-size: 1rem;
    color: #495057;
    font-weight: 600;
}

/* تنسيقات الحالة */
.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 8px 15px;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
}

.status-primary { background: #e3f2fd; color: #0c5460; }
.status-warning { background: #fff3cd; color: #856404; }
.status-success { background: #d4edda; color: #155724; }

/* تنسيقات دائرة التقدم */
.progress-circle-container {
    position: relative;
    width: 120px;
    height: 120px;
    margin: 0 auto;
}

.progress-circle {
    transform: rotate(-90deg);
}

.progress-text {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    text-align: center;
}

.progress-percentage {
    font-size: 1.8rem;
    font-weight: bold;
    color: #495057;
    line-height: 1;
}

.progress-label {
    font-size: 0.8rem;
    color: #6c757d;
    margin-top: 2px;
}

/* تنسيقات المستخدمين */
.user-avatar {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    border: 3px solid #007bff;
    object-fit: cover;
    margin-left: 10px;
}

.user-card {
    background: white;
    border-radius: 10px;
    padding: 15px;
    border: 1px solid #e9ecef;
    transition: all 0.3s ease;
}

.user-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.member-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(45deg, #007bff, #0056b3);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
}

/* تنسيقات المشاريع */
.project-card {
    background: white;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 15px;
    border: 1px solid #e9ecef;
    transition: all 0.3s ease;
    position: relative;
}

.project-card:hover {
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    transform: translateY(-1px);
}

.project-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
    padding-bottom: 8px;
    border-bottom: 1px solid #f1f3f4;
}

.project-progress {
    height: 8px;
    border-radius: 4px;
    background-color: #e9ecef;
    margin-top: 10px;
}

.project-progress .progress-bar {
    border-radius: 4px;
}

/* تنسيقات responsive */
@media (max-width: 768px) {
    .workspace-info-grid {
        grid-template-columns: 1fr;
    }
}

/* تنسيقات إضافية */
.no-content {
    text-align: center;
    padding: 40px 20px;
    color: #6c757d;
}

.no-content i {
    font-size: 3rem;
    margin-bottom: 15px;
    color: #adb5bd;
}

.loading-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.9);
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
    z-index: 10;
}

/* تنسيقات الإحصائيات */
.stats-info {
    border-radius: 12px;
    padding: 1.5rem;
    text-align: center;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
}

.stats-info-primary {
    background: linear-gradient(135deg, rgba(0, 123, 255, 0.1), rgba(0, 123, 255, 0.05));
    border: 1px solid rgba(0, 123, 255, 0.2);
}

.stats-info-success {
    background: linear-gradient(135deg, rgba(40, 167, 69, 0.1), rgba(40, 167, 69, 0.05));
    border: 1px solid rgba(40, 167, 69, 0.2);
}

.stats-info-warning {
    background: linear-gradient(135deg, rgba(255, 193, 7, 0.1), rgba(255, 193, 7, 0.05));
    border: 1px solid rgba(255, 193, 7, 0.2);
}

.icon-wrapper {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
}

.primary-icon {
    background: rgba(0, 123, 255, 0.1);
    color: #007bff;
}

.success-icon {
    background: rgba(40, 167, 69, 0.1);
    color: #28a745;
}

.warning-icon {
    background: rgba(255, 193, 7, 0.1);
    color: #ffc107;
}
</style>
@endsection

@section('content')
<div class="content-header row">
    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-left mb-0" id="workspace-title">تفاصيل مساحة العمل</h2>
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">الرئيسية</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('workspaces.index') }}">مساحات العمل</a></li>
                        <li class="breadcrumb-item active">تفاصيل</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="content-header-right text-md-right col-md-3 col-12 d-md-block d-none">
        <div class="form-group breadcrumb-right">
            <div class="dropdown">
                <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="feather icon-menu"></i> الإجراءات
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    @if(Auth::id() === $workspace->admin_id)
                    <li>
                        <a class="dropdown-item" href="javascript:void(0)" onclick="editWorkspace({{ $workspace->id }})">
                            <i class="feather icon-edit me-2"></i>تعديل البيانات
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    @endif
                    <li>
                        <a class="dropdown-item" href="javascript:void(0)" onclick="createNewProject()">
                            <i class="feather icon-plus me-2"></i>إضافة مشروع
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="javascript:void(0)" onclick="openInviteModal()">
                            <i class="feather icon-user-plus me-2"></i>دعوة عضو
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item" href="javascript:void(0)" onclick="window.print()">
                            <i class="feather icon-printer me-2"></i>طباعة
                        </a>
                    </li>
                    @if(Auth::id() === $workspace->admin_id)
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item text-danger" href="javascript:void(0)" onclick="deleteWorkspace({{ $workspace->id }})">
                            <i class="feather icon-trash-2 me-2"></i>حذف
                        </a>
                    </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="content-body">
    <!-- تفاصيل مساحة العمل -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h4 class="card-title mb-4" id="main-workspace-title">
                        <i class="feather icon-briefcase text-primary me-2"></i>
                        {{ $workspace->title }}
                    </h4>

                    @if($workspace->description)
                        <div class="mb-4">
                            <h6 class="text-muted mb-2">
                                <i class="feather icon-align-left me-1"></i>
                                الوصف
                            </h6>
                            <p class="text-secondary">{{ $workspace->description }}</p>
                        </div>
                    @endif

                    <div class="workspace-info-grid" id="workspace-info-container">
                        <!-- Loading placeholder -->
                        <div class="text-center py-3" id="workspace-loading">
                            <div class="spinner-border text-primary" role="status">

                            </div>
                        </div>
                    </div>

                    <!-- الأعضاء -->
                    <div id="members-section" style="display: none;">
                        <h6 class="text-muted mb-3">
                            <i class="feather icon-users me-1"></i>
                            المالك والمسؤولين
                        </h6>
                        <div class="user-card mb-3">
                            <div class="d-flex align-items-center">
                                <div class="member-avatar me-3">
                                    {{ substr($workspace->admin->name ?? 'غ', 0, 1) }}
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0">{{ $workspace->admin->name ?? 'غير محدد' }}</h6>
                                    <small class="text-muted">{{ $workspace->admin->email ?? '' }}</small>
                                </div>
                                <span class="badge bg-danger">المالك</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- قسم المشاريع -->
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <h5 class="mb-0">
                            <i class="feather icon-folder me-2"></i>
                            المشاريع
                            <span class="badge bg-primary ms-2" id="projectsCount">0</span>
                        </h5>
                        <div class="d-flex gap-2 mt-2 mt-md-0">
                            <select class="form-control form-control-sm" id="projectStatusFilter" style="width: auto;">
                                <option value="">جميع الحالات</option>
                                <option value="pending">في الانتظار</option>
                                <option value="in_progress">قيد التنفيذ</option>
                                <option value="completed">مكتمل</option>
                                <option value="on_hold">متوقف</option>
                            </select>
                            <button class="btn btn-sm btn-success" onclick="createNewProject()">
                                <i class="feather icon-plus me-1"></i>مشروع جديد
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div id="projectsList">
                        <div class="text-center py-4">
                            <div class="spinner-border text-primary" role="status">

                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <!-- قسم الأعضاء -->
            <div class="card border-0 shadow-sm mt-4" id="members-card" style="display: none;">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="feather icon-users me-2"></i>
                            الأعضاء المشاركين
                            <span class="badge bg-success ms-2" id="members-count">0</span>
                        </h5>
                        <button class="btn btn-sm btn-info" onclick="openInviteModal()">
                            <i class="feather icon-user-plus me-1"></i>دعوة عضو
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row" id="members-list">
                        <!-- سيتم ملؤها عبر JavaScript -->
                    </div>
                </div>
            </div>
        </div>

        <!-- الشريط الجانبي -->
        <div class="col-lg-4">
            <!-- نسبة الإنجاز -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body text-center">
                    <h6 class="card-title mb-4">
                        <i class="feather icon-trending-up text-success me-1"></i>
                        معدل الإنجاز العام
                    </h6>

                    <div class="progress-circle-container mb-3" id="progress-circle">
                        <!-- سيتم ملؤها عبر JavaScript -->
                    </div>

                    <div class="text-muted mt-3">
                        <small>آخر تحديث: <span id="last-updated">{{ $workspace->updated_at->diffForHumans() }}</span></small>
                    </div>
                </div>
            </div>

            <!-- الإحصائيات -->
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="card-title mb-3">
                        <i class="feather icon-bar-chart-2 text-info me-1"></i>
                        إحصائيات مساحة العمل
                    </h6>

                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <div class="stats-info stats-info-primary p-3">
                                <div class="icon-wrapper primary-icon">
                                    <i class="feather icon-folder"></i>
                                </div>
                                <h4 id="totalProjects">0</h4>
                                <small class="text-muted">إجمالي المشاريع</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="stats-info stats-info-success p-3">
                                <div class="icon-wrapper success-icon">
                                    <i class="feather icon-check-circle"></i>
                                </div>
                                <h4 id="completedProjects">0</h4>
                                <small class="text-muted">مشاريع مكتملة</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stats-info stats-info-warning p-3">
                                <div class="icon-wrapper warning-icon">
                                    <i class="feather icon-clock"></i>
                                </div>
                                <h4 id="activeProjects">0</h4>
                                <small class="text-muted">مشاريع نشطة</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stats-info p-3" style="background: linear-gradient(135deg, rgba(23, 162, 184, 0.1), rgba(23, 162, 184, 0.05)); border: 1px solid rgba(23, 162, 184, 0.2);">
                                <div class="icon-wrapper" style="background: rgba(23, 162, 184, 0.1); color: #17a2b8;">
                                    <i class="feather icon-users"></i>
                                </div>
                                <h4 id="totalMembers">0</h4>
                                <small class="text-muted">إجمالي الأعضاء</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- معلومات إضافية -->
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-body">
                    <h6 class="card-title mb-3">
                        <i class="feather icon-info text-primary me-1"></i>
                        معلومات إضافية
                    </h6>

                    <div id="workspace-extra-info">
                        <!-- سيتم ملؤها عبر JavaScript -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal دعوة عضو -->
<div class="modal fade" id="inviteMemberModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">
                    <i class="feather icon-user-plus me-2"></i>
                    دعوة عضو جديد
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="inviteMemberForm">
                <div class="modal-body">
                    <div class="alert alert-danger d-none" id="inviteFormErrors"></div>

                    <!-- طرق الدعوة -->
                    <ul class="nav nav-tabs mb-3" id="inviteMethodTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="email-tab" data-bs-toggle="tab" data-bs-target="#email-method" type="button" role="tab">
                                <i class="feather icon-mail me-1"></i>بالبريد الإلكتروني
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="users-tab" data-bs-toggle="tab" data-bs-target="#users-method" type="button" role="tab" onclick="loadAvailableUsers()">
                                <i class="feather icon-user-friends me-1"></i>اختيار من المستخدمين
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content" id="inviteMethodContent">
                        <!-- الدعوة بالبريد الإلكتروني -->
                        <div class="tab-pane fade show active" id="email-method" role="tabpanel">
                            <div class="form-group mb-3">
                                <label for="member_email" class="form-label">
                                    <i class="feather icon-mail me-1"></i>
                                    البريد الإلكتروني <span class="text-danger">*</span>
                                </label>
                                <input type="email" class="form-control" id="member_email" placeholder="أدخل البريد الإلكتروني" required>
                                <small class="form-text text-muted">
                                    سيتم البحث عن المستخدم بهذا البريد الإلكتروني وإرسال دعوة له
                                </small>
                            </div>
                        </div>

                        <!-- اختيار من المستخدمين المتاحين -->
                        <div class="tab-pane fade" id="users-method" role="tabpanel">
                            <div class="mb-3">
                                <label class="form-label">المستخدمين المتاحين للدعوة</label>
                                <div class="input-group mb-2">
                                    <input type="text" class="form-control" id="userSearchInput" placeholder="ابحث عن مستخدم...">
                                    <button class="btn btn-outline-secondary" type="button" onclick="loadAvailableUsers()">
                                        <i class="feather icon-refresh-cw"></i>
                                    </button>
                                </div>

                                <div id="availableUsersList" style="max-height: 300px; overflow-y: auto;">
                                    <!-- سيتم تحميل المستخدمين هنا -->
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info mb-0">
                        <i class="feather icon-info me-2"></i>
                        بعد قبول الدعوة، سيتمكن العضو من المشاركة في مشاريع هذه المساحة
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="feather icon-x me-1"></i>
                        إلغاء
                    </button>
                    <button type="submit" class="btn btn-info">
                        <i class="feather icon-send me-1"></i>
                        إرسال الدعوة
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    const workspaceId = {{ $workspace->id }};
    const userId = {{ auth()->id() ?? 'null' }};

    // تحميل البيانات الأولية
    loadWorkspaceDetails();
    loadProjects();
    loadMembers();

    // فلترة المشاريع
    $('#projectStatusFilter').change(function() {
        loadProjects();
    });

    // تحميل تفاصيل مساحة العمل
    function loadWorkspaceDetails() {
        $.ajax({
            url: `/workspaces/api/${workspaceId}/stats`,
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    renderWorkspaceStats(response.data);
                }
            },
            error: function(xhr) {
                console.error('خطأ في تحميل الإحصائيات:', xhr);
                showDefaultStats();
            }
        });
    }

    // عرض إحصائيات مساحة العمل
    function renderWorkspaceStats(data) {
        $('#workspace-loading').hide();

        // تحديث الإحصائيات
        $('#totalProjects').text(data.projects.total);
        $('#activeProjects').text(data.projects.active);
        $('#completedProjects').text(data.projects.completed);
        $('#totalMembers').text(data.members.total);
        $('#projectsCount').text(data.projects.total);

        // تحديث دائرة التقدم
        const completionRate = data.projects.completion_rate || 0;
        updateProgressCircle(completionRate);

        // عرض معلومات مساحة العمل
        const infoHtml = `
            <div class="info-card">
                <div class="info-label">
                    <i class="feather icon-user me-1"></i>
                    المالك
                </div>
                <div class="info-value">{{ $workspace->admin->name ?? 'غير محدد' }}</div>
            </div>

            <div class="info-card">
                <div class="info-label">
                    <i class="feather icon-layers me-1"></i>
                    النوع
                </div>
                <div class="info-value">
                    <span class="status-badge status-{{ $workspace->is_primary ? 'warning' : 'primary' }}">
                        <i class="feather icon-{{ $workspace->is_primary ? 'star' : 'folder' }}"></i>
                        {{ $workspace->is_primary ? 'مساحة رئيسية' : 'مساحة فرعية' }}
                    </span>
                </div>
            </div>

            <div class="info-card">
                <div class="info-label">
                    <i class="feather icon-calendar me-1"></i>
                    تاريخ الإنشاء
                </div>
                <div class="info-value">{{ $workspace->created_at->format('Y/m/d') }}</div>
            </div>

            <div class="info-card">
                <div class="info-label">
                    <i class="feather icon-activity me-1"></i>
                    آخر تحديث
                </div>
                <div class="info-value">{{ $workspace->updated_at->diffForHumans() }}</div>
            </div>
        `;

        $('#workspace-info-container').html(infoHtml);

        // معلومات إضافية
        const extraInfoHtml = `
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted">منشئ المساحة:</span>
                <strong>{{ $workspace->admin->name ?? 'غير محدد' }}</strong>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted">معدل الإنجاز:</span>
                <strong class="text-success">${completionRate}%</strong>
            </div>
            <div class="d-flex justify-content-between align-items-center">
                <span class="text-muted">المشاريع النشطة:</span>
                <strong>${data.projects.active}</strong>
            </div>
        `;

        $('#workspace-extra-info').html(extraInfoHtml);
        $('#last-updated').text(formatDateTime('{{ $workspace->updated_at }}'));
    }

    // عرض قيم افتراضية عند الخطأ
    function showDefaultStats() {
        $('#totalProjects, #activeProjects, #completedProjects, #totalMembers').text('--');
        updateProgressCircle(0);
    }

    // تحميل المشاريع
    function loadProjects() {
        const status = $('#projectStatusFilter').val();

        $.ajax({
            url: `/workspaces/api/${workspaceId}/projects`,
            method: 'GET',
            data: { status: status },
            success: function(response) {
                if (response.success) {
                    renderProjects(response.data);
                }
            },
            error: function(xhr) {
                console.error('خطأ في تحميل المشاريع:', xhr);
                showProjectsError();
            }
        });
    }

    // عرض المشاريع
    function renderProjects(projects) {
        if (!projects || projects.length === 0) {
            $('#projectsList').html(`
                <div class="no-content">
                    <i class="feather icon-folder"></i>
                    <h5 class="text-muted">لا توجد مشاريع</h5>
                    <p class="text-muted">لم يتم إنشاء أي مشاريع في هذه المساحة بعد</p>
                    <button class="btn btn-primary" onclick="createNewProject()">
                        <i class="feather icon-plus me-1"></i>إنشاء مشروع جديد
                    </button>
                </div>
            `);
            return;
        }

        let html = '';
        projects.forEach(project => {
            const statusClass = getStatusClass(project.status);
            const statusText = getStatusText(project.status);
            const progress = project.completion_percentage || 0;

            html += `
                <div class="project-card">
                    <div class="project-header">
                        <div>
                            <h6 class="mb-1">
                                <a href="/projects/${project.id}" class="text-decoration-none">
                                    ${project.title}
                                </a>
                            </h6>
                            <small class="text-muted">${project.description || 'لا يوجد وصف'}</small>
                        </div>
                        <span class="badge ${statusClass}">${statusText}</span>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <i class="feather icon-users text-muted me-2"></i>
                            <small class="text-muted">${project.members_count || 0} عضو</small>
                        </div>
                        <small class="text-muted">${progress}% مكتمل</small>
                    </div>

                    <div class="project-progress">
                        <div class="progress-bar bg-${getProgressColor(progress)}"
                             style="width: ${progress}%"></div>
                    </div>
                </div>
            `;
        });

        $('#projectsList').html(html);
    }

    // تحميل الأعضاء
    function loadMembers() {
        $.ajax({
            url: `/workspaces/api/${workspaceId}/members`,
            method: 'GET',
            success: function(response) {
                if (response.success && response.data.length > 0) {
                    renderMembers(response.data);
                }
            },
            error: function(xhr) {
                console.error('خطأ في تحميل الأعضاء:', xhr);
            }
        });
    }

    // عرض الأعضاء
    function renderMembers(members) {
        if (!members || members.length === 0) {
            return;
        }

        let html = '';
        members.forEach(member => {
            html += `
                <div class="col-md-6 mb-3">
                    <div class="user-card">
                        <div class="d-flex align-items-center">
                            <div class="member-avatar me-3">
                                ${member.name.charAt(0).toUpperCase()}
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0">${member.name}</h6>
                                <small class="text-muted">${member.email}</small>
                            </div>
                            <div class="text-end">
                                <small class="text-muted d-block">المشاريع</small>
                                <span class="badge bg-primary">${member.projects_count || 0}</span>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });

        $('#members-list').html(html);
        $('#members-count').text(members.length);
        $('#members-card').show();
    }

    // تحديث دائرة التقدم
    function updateProgressCircle(percentage) {
        const color = getProgressColor(percentage);
        const circumference = 2 * Math.PI * 50;
        const offset = circumference - (percentage / 100) * circumference;

        const circleHtml = `
            <svg width="120" height="120" class="progress-circle">
                <circle cx="60" cy="60" r="50" fill="none"
                        stroke="#e9ecef" stroke-width="8"/>
                <circle cx="60" cy="60" r="50" fill="none"
                        stroke="${color}" stroke-width="8"
                        stroke-dasharray="${circumference}"
                        stroke-dashoffset="${offset}"
                        stroke-linecap="round"/>
            </svg>
            <div class="progress-text">
                <div class="progress-percentage">${percentage}%</div>
                <div class="progress-label">مكتمل</div>
            </div>
        `;

        $('#progress-circle').html(circleHtml);
    }

    // نموذج دعوة عضو
    $('#inviteMemberForm').on('submit', function(e) {
        e.preventDefault();

        let email = '';

        // تحديد البريد الإلكتروني حسب الطريقة المختارة
        if ($('#email-tab').hasClass('active')) {
            email = $('#member_email').val().trim();
        } else if ($('#users-tab').hasClass('active') && window.selectedUser) {
            email = window.selectedUser.email;
        }

        if (!email) {
            showToast('error', 'الرجاء إدخال البريد الإلكتروني أو اختيار مستخدم');
            return;
        }

        // إظهار loading
        Swal.fire({
            title: 'جاري الإرسال...',
            text: 'يرجى الانتظار',
            allowOutsideClick: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        $.ajax({
            url: `/workspaces/${workspaceId}/invite`,
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                email: email
            },
            success: function(response) {
                Swal.close();
                if (response.success) {
                    $('#inviteMemberModal').modal('hide');
                    showToast('success', response.message);
                    $('#member_email').val('');
                    window.selectedUser = null;
                    loadMembers();
                }
            },
            error: function(xhr) {
                Swal.close();
                let errorMsg = 'حدث خطأ أثناء إرسال الدعوة';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }
                showToast('error', errorMsg);
            }
        });
    });

    // تحميل المستخدمين المتاحين
    window.loadAvailableUsers = function() {
        $('#availableUsersList').html(`
            <div class="text-center py-3">
                <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                <div class="text-muted mt-2">جاري تحميل المستخدمين...</div>
            </div>
        `);

        $.ajax({
            url: `/workspaces/${workspaceId}/available-users`,
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    window.availableUsers = response.data;
                    displayAvailableUsers(response.data);
                } else {
                    showUsersError('حدث خطأ أثناء تحميل المستخدمين');
                }
            },
            error: function(xhr) {
                console.error('خطأ في تحميل المستخدمين:', xhr);
                showUsersError('حدث خطأ أثناء تحميل المستخدمين');
            }
        });
    };

    // عرض المستخدمين المتاحين
    function displayAvailableUsers(users) {
        if (!users || users.length === 0) {
            $('#availableUsersList').html(`
                <div class="text-center py-4 text-muted">
                    <i class="feather icon-users" style="font-size: 2rem;"></i>
                    <div class="mt-2">لا يوجد مستخدمين متاحين للدعوة</div>
                    <small>جميع المستخدمين إما أعضاء بالفعل أو لديهم دعوات معلقة</small>
                </div>
            `);
            return;
        }

        let html = '<div class="list-group">';
        users.forEach(user => {
            const isSelected = window.selectedUser && window.selectedUser.id === user.id;
            html += `
                <div class="list-group-item list-group-item-action ${isSelected ? 'active' : ''}"
                     style="cursor: pointer;"
                     onclick="selectUser(${user.id}, '${user.name}', '${user.email}')">
                    <div class="d-flex align-items-center">
                        <div class="member-avatar me-3" style="width: 35px; height: 35px; font-size: 14px;">
                            ${user.name.charAt(0).toUpperCase()}
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-bold">${user.name}</div>
                            <small class="text-muted">${user.email}</small>
                        </div>
                        ${isSelected ? '<i class="feather icon-check text-white"></i>' : ''}
                    </div>
                </div>
            `;
        });
        html += '</div>';

        $('#availableUsersList').html(html);
    }

    // اختيار مستخدم
    window.selectUser = function(id, name, email) {
        window.selectedUser = { id, name, email };
        displayAvailableUsers(window.availableUsers);
        $('#member_email').val(email);
    };

    // البحث في المستخدمين
    $('#userSearchInput').on('input', function() {
        const searchTerm = $(this).val().toLowerCase().trim();

        if (!window.availableUsers) return;

        if (searchTerm === '') {
            displayAvailableUsers(window.availableUsers);
            return;
        }

        const filtered = window.availableUsers.filter(user =>
            user.name.toLowerCase().includes(searchTerm) ||
            user.email.toLowerCase().includes(searchTerm)
        );

        displayAvailableUsers(filtered);
    });

    function showUsersError(message) {
        $('#availableUsersList').html(`
            <div class="text-center py-4 text-danger">
                <i class="feather icon-alert-circle" style="font-size: 2rem;"></i>
                <div class="mt-2">${message}</div>
                <button class="btn btn-outline-primary btn-sm mt-2" onclick="loadAvailableUsers()">
                    <i class="feather icon-refresh-cw me-1"></i>إعادة المحاولة
                </button>
            </div>
        `);
    }

    // دوال مساعدة
    function getStatusClass(status) {
        const classes = {
            'pending': 'bg-warning',
            'in_progress': 'bg-primary',
            'completed': 'bg-success',
            'on_hold': 'bg-danger'
        };
        return classes[status] || 'bg-secondary';
    }

    function getStatusText(status) {
        const texts = {
            'pending': 'في الانتظار',
            'in_progress': 'قيد التنفيذ',
            'completed': 'مكتمل',
            'on_hold': 'متوقف'
        };
        return texts[status] || 'غير محدد';
    }

    function getProgressColor(percentage) {
        if (percentage >= 70) return 'success';
        if (percentage >= 30) return 'warning';
        return 'danger';
    }

    function formatDateTime(dateString) {
        if (!dateString) return 'غير محدد';
        try {
            const date = new Date(dateString);
            return date.toLocaleDateString('ar-SA', {
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            });
        } catch (e) {
            return dateString;
        }
    }

    function showProjectsError() {
        $('#projectsList').html(`
            <div class="alert alert-warning text-center">
                <i class="feather icon-alert-triangle me-2"></i>
                حدث خطأ أثناء تحميل المشاريع
            </div>
        `);
    }

    function showToast(type, message) {
        const icons = {
            'success': 'success',
            'error': 'error',
            'warning': 'warning',
            'info': 'info'
        };

        Swal.fire({
            icon: icons[type] || 'info',
            title: message,
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: type === 'error' ? 4000 : 2500,
            timerProgressBar: true
        });
    }

    // دوال عامة للمساحة
    window.createNewProject = function() {
        window.location.href = `/projects/create?workspace_id=${workspaceId}`;
    };

    window.editWorkspace = function(id) {
        window.location.href = `/workspaces/${id}/edit`;
    };

    window.deleteWorkspace = function(id) {
        Swal.fire({
            title: 'هل أنت متأكد؟',
            text: "سيتم حذف مساحة العمل وجميع مشاريعها نهائياً!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'نعم، احذف',
            cancelButtonText: 'إلغاء'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/workspaces/${id}`,
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'تم الحذف!',
                                text: response.message,
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.href = '{{ route("workspaces.index") }}';
                            });
                        }
                    },
                    error: function(xhr) {
                        console.error('خطأ في حذف المساحة:', xhr);
                        showToast('error', 'حدث خطأ أثناء حذف مساحة العمل');
                    }
                });
            }
        });
    };

    window.openInviteModal = function() {
        $('#inviteMemberModal').modal('show');
    };
});
</script>

<style>
/* تأثيرات إضافية */
.project-card {
    animation: fadeInUp 0.3s ease-in-out;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.user-card {
    animation: fadeIn 0.3s ease-in-out;
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

.info-card {
    animation: slideIn 0.4s ease-in-out;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateX(20px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

/* تحسين الـ scrollbar */
#projectsList::-webkit-scrollbar {
    width: 8px;
}

#projectsList::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

#projectsList::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 10px;
}

#projectsList::-webkit-scrollbar-thumb:hover {
    background: #555;
}

/* تحسين الأزرار */
.btn {
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

.btn:active {
    transform: translateY(0);
}

/* تحسين البطاقات */
.card {
    transition: all 0.3s ease;
}

.card:hover {
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

/* تحسين الـ badges */
.badge {
    transition: all 0.2s ease;
}

.badge:hover {
    transform: scale(1.05);
}

/* Loading spinner مخصص */
.spinner-border {
    animation: spinner-border 0.75s linear infinite;
}

@keyframes spinner-border {
    to {
        transform: rotate(360deg);
    }
}

/* تحسين Modal */
.modal-content {
    border: none;
    border-radius: 15px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.2);
}

.modal-header {
    border-radius: 15px 15px 0 0;
}

/* تحسين Forms */
.form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.25);
}

/* تحسين Progress Bar */
.project-progress .progress-bar {
    transition: width 0.6s ease;
}

/* Responsive improvements */
@media (max-width: 768px) {
    .content-header-right .btn-group {
        display: flex;
        flex-direction: column;
        width: 100%;
    }

    .content-header-right .btn {
        width: 100%;
        margin-bottom: 5px;
    }

    .project-card {
        margin-bottom: 10px;
    }

    .workspace-info-grid {
        grid-template-columns: 1fr;
    }
}

/* Print styles */
@media print {
    .btn-group,
    .modal,
    .breadcrumb,
    #projectStatusFilter {
        display: none !important;
    }

    .card {
        page-break-inside: avoid;
    }
}

/* Dark mode support (اختياري) */
@media (prefers-color-scheme: dark) {


    .text-muted {
        color: #adb5bd !important;
    }
}

/* تحسين النصوص */
.text-truncate-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* تحسين الأيقونات */
.feather {
    width: 18px;
    height: 18px;
    vertical-align: middle;
}

/* Tooltip improvements */
.tooltip {
    font-size: 0.875rem;
}

.tooltip-inner {
    max-width: 200px;
    padding: 8px 12px;
    border-radius: 8px;
}

/* تحسين Alert */
.alert {
    border-radius: 10px;
    border: none;
}

.alert i {
    font-size: 1.2rem;
    vertical-align: middle;
}
</style>
@endsection