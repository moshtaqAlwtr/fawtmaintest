<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class CashierDevice extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'cashier_devices';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'device_name',
        'store_id',
        'main_category_id',
        'device_status',
        'device_image',
        'description',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'device_status' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * الحالات المتاحة للجهاز
     */
    public const STATUS_ACTIVE = 'active';
    public const STATUS_INACTIVE = 'inactive';
    public const STATUS_MAINTENANCE = 'maintenance';
    public const STATUS_DAMAGED = 'damaged';

    /**
     * الحصول على جميع الحالات المتاحة
     */
    public static function getStatusOptions(): array
    {
        return [
            self::STATUS_ACTIVE => 'نشط',
            self::STATUS_INACTIVE => 'غير نشط',
            self::STATUS_MAINTENANCE => 'تحت الصيانة',
            self::STATUS_DAMAGED => 'معطل',
        ];
    }

    /**
     * الحصول على نص الحالة باللغة العربية
     */
    public function getStatusTextAttribute(): string
    {
        return self::getStatusOptions()[$this->device_status] ?? $this->device_status;
    }

    /**
     * الحصول على رابط الصورة الكامل
     */
    public function getImageUrlAttribute(): ?string
    {
        if ($this->device_image) {
            return Storage::url($this->device_image);
        }
        return null;
    }

    /**
     * فحص ما إذا كان الجهاز نشطاً
     */
    public function isActive(): bool
    {
        return $this->device_status === self::STATUS_ACTIVE;
    }

    /**
     * فحص ما إذا كان الجهاز تحت الصيانة
     */
    public function isUnderMaintenance(): bool
    {
        return $this->device_status === self::STATUS_MAINTENANCE;
    }

    /**
     * العلاقة مع جدول المخازن
     */
    public function store(): BelongsTo
    {
        return $this->belongsTo(StoreHouse::class, 'store_id');
    }

    /**
     * العلاقة مع جدول المستودعات
     */
    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'main_warehouse_id');
    }

    /**
     * العلاقة مع جدول التصنيفات
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'main_category_id');
    }

    /**
     * العلاقة مع المستخدم المنشئ
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * العلاقة مع المستخدم المحدث
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * نطاق البحث بالاسم
     */
    public function scopeSearchByName($query, $name)
    {
        if ($name) {
            return $query->where('device_name', 'like', '%' . $name . '%');
        }
        return $query;
    }

    /**
     * نطاق التصفية حسب الحالة
     */
    public function scopeByStatus($query, $status)
    {
        if ($status) {
            return $query->where('device_status', $status);
        }
        return $query;
    }

    /**
     * نطاق التصفية حسب المخزن
     */
    public function scopeByStore($query, $storeId)
    {
        if ($storeId) {
            return $query->where('store_id', $storeId);
        }
        return $query;
    }

    /**
     * نطاق التصفية حسب المستودع
     */
    public function scopeByWarehouse($query, $warehouseId)
    {
        if ($warehouseId) {
            return $query->where('main_warehouse_id', $warehouseId);
        }
        return $query;
    }

    /**
     * نطاق التصفية حسب التصنيف
     */
    public function scopeByCategory($query, $categoryId)
    {
        if ($categoryId) {
            return $query->where('main_category_id', $categoryId);
        }
        return $query;
    }

    /**
     * نطاق للأجهزة النشطة فقط
     */
    public function scopeActive($query)
    {
        return $query->where('device_status', self::STATUS_ACTIVE);
    }

    /**
     * حفظ الجهاز مع تسجيل المستخدم الحالي
     */
    public function save(array $options = [])
    {
        // تسجيل معرف المستخدم الحالي عند الإنشاء أو التحديث
        if (auth()->check()) {
            if (!$this->exists) {
                $this->created_by = auth()->id();
            }
            $this->updated_by = auth()->id();
        }

        return parent::save($options);
    }

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        // حذف الصورة عند حذف السجل
        static::deleting(function ($device) {
            if ($device->device_image) {
                Storage::delete($device->device_image);
            }
        });
    }
}
