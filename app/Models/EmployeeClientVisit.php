<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmployeeClientVisit extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'client_id',
        'justification',
        'day_of_week',
        'year',
        'status',
        'week_number',
        'justification_date',
        'justification_approved',
        'approved_by',
    ];

    protected $casts = [
        'justification_date' => 'datetime',
    ];

    // العلاقة مع الموظف
    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    // العلاقة مع العميل
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id')->with(['locations', 'status_client']);
    }

    // الموظف الذي اعتمد التبرير
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // نطاق للحالة (مثلاً عرض الزيارات النشطة فقط)
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // نطاق لعرض الزيارات غير النشطة
    public function scopeInactive($query)
    {
        return $query->where('status', 'unactive');
    }

    // نطاق لعرض الزيارات غير المكتملة التي تحتاج تبرير
    public function scopeNeedsJustification($query)
    {
        return $query->where('status', '!=', 'active')
            ->where(function ($q) {
                $q->whereNull('justification')
                  ->orWhere('justification', '=', '')
                  ->orWhere('justification_approved', 0)
                  ->orWhereNull('justification_approved');
            });
    }

    // هل التبرير معتمد؟
    public function isJustificationApproved()
    {
        return $this->justification_approved == 1;
    }
    
    // هل التبرير مرفوض؟
    public function isJustificationRejected()
    {
        return $this->justification_approved == 2;
    }
    
    // هل التبرير معلق (بانتظار الموافقة)؟
    public function isJustificationPending()
    {
        return $this->justification_approved == 0;
    }
}