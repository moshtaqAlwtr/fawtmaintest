<?php

namespace Modules\Client\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GeneralClientSetting;

class DataStorageExampleController extends Controller
{
    /**
     * عرض نموذج لإدخال البيانات
     */
    public function create()
    {
        return view('client::data_storage_example.create');
    }

    /**
     * تخزين البيانات الجديدة
     */
    public function store(Request $request)
    {
        // التحقق من صحة البيانات
        $validatedData = $request->validate([
            'key' => 'required|string|unique:general_client_settings,key',
            'name' => 'required|string|max:255',
            'is_active' => 'boolean',
        ]);

        // تخزين البيانات في قاعدة البيانات
        $setting = new GeneralClientSetting();
        $setting->key = $validatedData['key'];
        $setting->name = $validatedData['name'];
        $setting->is_active = $validatedData['is_active'] ?? 0;
        $setting->save();

        // إعادة التوجيه مع رسالة نجاح
        return redirect()->back()->with('success', 'تم حفظ الإعداد بنجاح!');
    }

    /**
     * تحديث البيانات الموجودة
     */
    public function update(Request $request, $id)
    {
        // العثور على السجل
        $setting = GeneralClientSetting::findOrFail($id);

        // التحقق من صحة البيانات
        $validatedData = $request->validate([
            'key' => 'required|string|unique:general_client_settings,key,'.$id,
            'name' => 'required|string|max:255',
            'is_active' => 'boolean',
        ]);

        // تحديث البيانات
        $setting->key = $validatedData['key'];
        $setting->name = $validatedData['name'];
        $setting->is_active = $validatedData['is_active'] ?? 0;
        $setting->save();

        // إعادة التوجيه مع رسالة نجاح
        return redirect()->back()->with('success', 'تم تحديث الإعداد بنجاح!');
    }
}