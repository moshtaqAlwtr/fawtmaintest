 <div class="card shadow-sm border-0 rounded-3" id="actionCard">
    <div class="card-body p-3">
        <div class="d-flex flex-wrap justify-content-end align-items-center" style="gap: 10px;">
            <!-- زر الخريطة -->
            <button id="toggleMapButton" class="bg-white border d-flex align-items-center justify-content-center"
                style="width: 44px; height: 44px; border-radius: 6px; position: relative;" title="عرض الخريطة">
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

            <!-- زر تصدير PDF -->
            <a href="{{ route('clients.export-pdf') }}" class="bg-white border d-flex align-items-center justify-content-center"
                style="width: 44px; height: 44px; border-radius: 6px;" title="تصدير ك PDF">
                <i class="fas fa-file-pdf text-primary"></i>
            </a>

            <!-- زر تصدير Excel -->
            <button id="exportExcelBtn" class="bg-white border d-flex align-items-center justify-content-center"
                style="width: 44px; height: 44px; border-radius: 6px;" title="تصدير ك Excel">
                <i class="fas fa-file-excel text-primary"></i>
            </button>

            <a href="{{ route('clients.create') }}" type="submit" class="btn btn-primary">
                <i class="fas fa-add me-1"></i>
                اضافة عميل
            </a>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.31/jspdf.plugin.autotable.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {

    // 🔹 دالة لجلب جميع العملاء من السيرفر
    async function fetchAllClients() {
        try {
            const response = await fetch("{{ route('clients.export.all') }}");
            if (!response.ok) throw new Error("فشل في تحميل بيانات العملاء");
            const data = await response.json();
            console.log('✅ تم جلب البيانات:', data); // للتأكد من البيانات
            return data;
        } catch (error) {
            console.error('❌ خطأ في جلب البيانات:', error);
            throw error;
        }
    }

    // ============================
    // 📗 تصدير كـ Excel
    // ============================
    document.getElementById('exportExcelBtn')?.addEventListener('click', async function() {
        const btn = this;
        const originalIcon = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin text-success"></i>';
        btn.disabled = true;

        try {
            const clients = await fetchAllClients();
            console.log('عدد العملاء:', clients.length); // للتأكد

            if (!clients || clients.length === 0) {
                alert('⚠️ لا توجد بيانات عملاء للتصدير');
                return;
            }

            const worksheetData = [
                [
                    'الكود', 'الاسم التجاري', 'اسم العميل', 'الهاتف',
                    'التصنيف', 'الفرع', 'الحالة', 'المسافة',
                    'تاريخ التسجيل', 'آخر فاتورة', 'آخر دفعة',
                    'الرصيد', 'حد الائتمان', 'فترة الائتمان', 'العنوان'
                ],
                ...clients.map(c => [
                    c.code || '-',
                    c.trade_name || '-',
                    c.first_name || '-',
                    c.phone || '-',
                    c.category || '-',
                    c.branch || '-',
                    c.status || '-',
                    c.distance || '-',
                    c.created_at || '-',
                    c.last_invoice || '-',
                    c.last_payment || '-',
                    c.balance || '-',
                    c.credit_limit || '-',
                    c.credit_period || '-',
                    c.address || '-'
                ])
            ];

            const ws = XLSX.utils.aoa_to_sheet(worksheetData);
            ws['!cols'] = Array(15).fill({ wch: 15 });
            const wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, ws, 'العملاء');
            const filename = 'العملاء_' + new Date().toISOString().slice(0, 10) + '.xlsx';
            XLSX.writeFile(wb, filename);

            alert('✅ تم تصدير ' + clients.length + ' عميل إلى ملف Excel بنجاح');
        } catch (err) {
            console.error('❌ خطأ في التصدير:', err);
            alert('❌ حدث خطأ أثناء تصدير Excel: ' + err.message);
        } finally {
            btn.innerHTML = originalIcon;
            btn.disabled = false;
        }
    });

    // ============================
    // 📕 تصدير كـ PDF
    // ============================
    document.getElementById('exportPdfBtn')?.addEventListener('click', async function() {
        const btn = this;
        const originalIcon = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin text-danger"></i>';
        btn.disabled = true;

        try {
            const clients = await fetchAllClients();
            console.log('عدد العملاء:', clients.length); // للتأكد

            if (!clients || clients.length === 0) {
                alert('⚠️ لا توجد بيانات عملاء للتصدير');
                return;
            }

            const { jsPDF } = window.jspdf;
            const doc = new jsPDF('landscape', 'mm', 'a4');

            doc.setFontSize(16);
            doc.text('قائمة العملاء', doc.internal.pageSize.width / 2, 15, { align: 'center' });

            const today = new Date().toLocaleDateString('ar-SA');
            doc.setFontSize(10);
            doc.text('تاريخ التصدير: ' + today, doc.internal.pageSize.width - 15, 15, { align: 'right' });

            const tableData = clients.map(c => [
                c.code || '-',
                c.trade_name || '-',
                c.first_name || '-',
                c.phone || '-',
                c.category || '-',
                c.branch || '-',
                c.status || '-',
                c.distance || '-',
                c.created_at || '-',
                c.last_invoice || '-',
                c.last_payment || '-',
                c.balance || '-',
                c.credit_limit || '-',
                c.credit_period || '-',
                c.address || '-'
            ]);

            doc.autoTable({
                head: [[
                    'الكود', 'الاسم التجاري', 'اسم العميل', 'الهاتف',
                    'التصنيف', 'الفرع', 'الحالة', 'المسافة',
                    'تاريخ التسجيل', 'آخر فاتورة', 'آخر دفعة',
                    'الرصيد', 'حد الائتمان', 'فترة الائتمان', 'العنوان'
                ]],
                body: tableData,
                startY: 25,
                styles: {
                    fontSize: 7,
                    halign: 'center',
                    cellPadding: 1.5
                },
                headStyles: {
                    fillColor: [41, 128, 185],
                    textColor: 255,
                    fontStyle: 'bold',
                    fontSize: 8
                },
                alternateRowStyles: {
                    fillColor: [245, 245, 245]
                },
                margin: { top: 25, right: 8, bottom: 15, left: 8 },
                didDrawPage: function() {
                    doc.setFontSize(8);
                    doc.text(
                        'صفحة ' + doc.internal.getNumberOfPages(),
                        doc.internal.pageSize.width / 2,
                        doc.internal.pageSize.height - 5,
                        { align: 'center' }
                    );
                }
            });

            const filename = 'العملاء_' + new Date().toISOString().slice(0, 10) + '.pdf';
            doc.save(filename);

            alert('✅ تم تصدير ' + clients.length + ' عميل إلى ملف PDF بنجاح');
        } catch (err) {
            console.error('❌ خطأ في التصدير:', err);
            alert('❌ حدث خطأ أثناء تصدير PDF: ' + err.message);
        } finally {
            btn.innerHTML = originalIcon;
            btn.disabled = false;
        }
    });
});
</script>