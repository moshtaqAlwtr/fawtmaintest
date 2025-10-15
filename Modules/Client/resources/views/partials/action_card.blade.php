<div class="card shadow-sm border-0 rounded-3" id="actionCard">
    <div class="card-body p-3">
        <div class="d-flex flex-wrap justify-content-end align-items-center" style="gap: 10px;">
            <!-- زر الخريطة -->
            <button id="toggleMapButton" class="bg-white border d-flex align-items-center justify-content-center"
                style="width: 44px; height: 44px; border-radius: 6px; position: relative;" title="عرض الخريطة"
                data-tooltip="عرض الخريطة">
                <i class="fas fa-map-marked-alt text-primary"></i>
            </button>

            <!-- باقي الأزرار -->
            <label class="bg-white border d-flex align-items-center justify-content-center"
                style="width: 44px; height: 44px; cursor: pointer; border-radius: 6px;" title="تحميل ملف">
                <i class="fas fa-cloud-upload-alt text-primary"></i>
                <input type="file" name="file" class="d-none">
            </label>

            <button type="submit" class="bg-white border d-flex align-items-center justify-content-center"
                style="width: 44px; height: 44px; border-radius: 6px;" title="استيراد ك Excel">
                <i class="fas fa-database text-primary"></i>
            </button>

            <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#creditLimitModal"
                class="bg-white border d-flex align-items-center justify-content-center"
                style="width: 44px; height: 44px; border-radius: 6px;" title="حد ائتماني">
                <i class="fas fa-credit-card text-primary"></i>
            </a>

            <button id="exportExcelBtn" class="bg-white border d-flex align-items-center justify-content-center"
                style="width: 44px; height: 44px; border-radius: 6px;" title="تصدير ك Excel">
                <i class="fas fa-file-excel text-primary"></i>
            </button>

            <a href="{{ route('clients.create') }}" type="submit" class="btn btn-primary">
                <i class="fas fa-add me-1"></i>
                اضافة عميل            </a>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Export to Excel functionality
    document.getElementById('exportExcelBtn')?.addEventListener('click', function() {
        // Show loading indicator
        const originalIcon = this.innerHTML;
        this.innerHTML = '<i class="fas fa-spinner fa-spin text-primary"></i>';
        this.disabled = true;
        
        try {
            // Collect all client data from the cards
            const clients = [];
            document.querySelectorAll('.client-card').forEach(card => {
                try {
                    const clientId = card.getAttribute('data-client-id');
                    const tradeName = card.querySelector('.client-title')?.textContent?.trim() || '';
                    const code = card.querySelector('.client-code-badge')?.textContent?.trim() || '';
                    const firstName = card.querySelector('.contact-item:nth-child(1) span')?.textContent?.trim() || '';
                    const phone = card.querySelector('.contact-item:nth-child(2) span')?.textContent?.trim() || '';
                    const category = card.querySelector('.contact-item:nth-child(3) span')?.textContent?.trim() || '';
                    const branch = card.querySelector('.contact-item:nth-child(4) span')?.textContent?.trim() || '';
                    const createdAt = card.querySelector('.date-item:first-child .date-value')?.textContent?.trim() || '';
                    const lastInvoice = card.querySelectorAll('.date-item')[1]?.querySelector('.date-value')?.textContent?.trim() || '';
                    const lastPayment = card.querySelectorAll('.date-item')[2]?.querySelector('.date-value')?.textContent?.trim() || '';
                    
                    // Get status
                    const statusElement = card.querySelector('.status-indicator span');
                    const status = statusElement ? statusElement.textContent.trim() : '';
                    
                    // Get distance
                    const distanceElement = card.querySelector('.distance-item span');
                    const distance = distanceElement ? distanceElement.textContent.trim() : '';
                    
                    clients.push({
                        id: clientId,
                        code: code,
                        trade_name: tradeName,
                        first_name: firstName,
                        phone: phone,
                        category: category,
                        branch: branch,
                        status: status,
                        distance: distance,
                        created_at: createdAt,
                        last_invoice: lastInvoice,
                        last_payment: lastPayment
                    });
                } catch (e) {
                    console.warn('Failed to gather client data', e);
                }
            });

            if (clients.length === 0) {
                alert('لا توجد بيانات للتصدير');
                return;
            }

            // Create Excel workbook
            const worksheetData = [
                ['الكود', 'الاسم التجاري', 'اسم العميل', 'الهاتف', 'التصنيف', 'الفرع', 'الحالة', 'المسافة', 'تاريخ التسجيل', 'آخر فاتورة', 'آخر دفعة'],
                ...clients.map(client => [
                    client.code,
                    client.trade_name,
                    client.first_name,
                    client.phone,
                    client.category,
                    client.branch,
                    client.status,
                    client.distance,
                    client.created_at,
                    client.last_invoice,
                    client.last_payment
                ])
            ];

            const ws = XLSX.utils.aoa_to_sheet(worksheetData);
            
            // Set column widths
            ws['!cols'] = [
                {wch: 10}, // الكود
                {wch: 25}, // الاسم التجاري
                {wch: 20}, // اسم العميل
                {wch: 15}, // الهاتف
                {wch: 15}, // التصنيف
                {wch: 15}, // الفرع
                {wch: 15}, // الحالة
                {wch: 15}, // المسافة
                {wch: 15}, // تاريخ التسجيل
                {wch: 15}, // آخر فاتورة
                {wch: 15}  // آخر دفعة
            ];

            const wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, ws, 'العملاء');
            
            // Download the file
            const filename = 'العملاء_' + new Date().toISOString().slice(0, 10) + '.xlsx';
            XLSX.writeFile(wb, filename);
            
            // Show success message
            setTimeout(() => {
                alert('تم تصدير ' + clients.length + ' عميل إلى ملف Excel');
            }, 100);
        } catch (error) {
            console.error('Export error:', error);
            alert('حدث خطأ أثناء التصدير: ' + error.message);
        } finally {
            // Restore button
            this.innerHTML = originalIcon;
            this.disabled = false;
        }
    });
});
</script>