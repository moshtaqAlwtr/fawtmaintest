<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    .calendar-container {
        max-width: 1200px;
        margin: 0 auto;
        background: white;
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .calendar-header {
        background: linear-gradient(135deg, #dbe2e8, #ededed);
        color: rgb(0, 0, 0);
        padding: 30px;
        text-align: center;
        position: relative;
    }

    .calendar-title {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 20px;
    }

    .calendar-controls {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .nav-button {
        background: rgba(255, 255, 255, 0.2);
        border: none;
        color: white;
        padding: 12px 16px;
        border-radius: 50%;
        font-size: 1.2rem;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .nav-button:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: scale(1.1);
    }

    .month-year {
        font-size: 1.8rem;
        font-weight: 600;
    }

    .view-filters {
        display: flex;
        gap: 10px;
        justify-content: center;
        flex-wrap: wrap;
    }

    .filter-btn {
        background: rgba(255, 255, 255, 0.2);
        border: none;
        color: white;
        padding: 10px 20px;
        border-radius: 25px;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .filter-btn.active {
        background: rgba(255, 255, 255, 0.3);
        transform: scale(1.05);
    }

    .view-toggle {
        position: absolute;
        top: 30px;
        left: 30px;
        display: flex;
        gap: 10px;
    }

    .toggle-btn {
        background: rgba(255, 255, 255, 0.2);
        border: none;
        color: white;
        padding: 10px 15px;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .toggle-btn.active {
        background: rgba(255, 255, 255, 0.3);
    }

    .calendar-body {
        padding: 30px;
    }

    .calendar-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 1px;
        background: #e0e0e0;
        border-radius: 10px;
        overflow: hidden;
    }

    .day-header {
        background: linear-gradient(135deg, #3498db, #5dade2);
        color: white;
        padding: 20px 10px;
        text-align: center;
        font-weight: 600;
        font-size: 1.1rem;
    }

    .day-cell {
        background: white;
        min-height: 140px;
        padding: 15px 10px;
        position: relative;
        transition: all 0.3s ease;
        cursor: pointer;
        border: 2px solid transparent;
    }

    .day-cell:hover {
        background: #f8f9fa;
        border-color: #3498db;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .day-cell.today {
        background: linear-gradient(135deg, #e8f5e8, #f0f8f0);
        border-color: #27ae60;
        box-shadow: 0 0 0 2px #27ae60;
    }

    .day-cell.other-month {
        background: #f8f9fa;
        color: #bbb;
    }

    .day-number {
        font-size: 1.2rem;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 10px;
    }

    .day-cell.other-month .day-number {
        color: #ccc;
    }

    .bookings-list {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .booking-item {
        background: linear-gradient(135deg, #3498db, #5dade2);
        color: white;
        padding: 8px 10px;
        border-radius: 15px;
        font-size: 11px;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 5px;
        transition: all 0.3s ease;
        cursor: pointer;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .booking-item:hover {
        transform: scale(1.05);
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.2);
    }

    .booking-item.confirmed {
        background: linear-gradient(135deg, #27ae60, #2ecc71);
    }

    .booking-item.pending {
        background: linear-gradient(135deg, #f39c12, #f1c40f);
    }

    .booking-item.cancelled {
        background: linear-gradient(135deg, #e74c3c, #e67e22);
    }

    .booking-item.completed {
        background: linear-gradient(135deg, #8e44ad, #9b59b6);
    }

    .booking-icon {
        font-size: 10px;
    }

    .booking-time {
        font-size: 9px;
        opacity: 0.9;
        margin-top: 2px;
    }

    .more-bookings {
        background: #95a5a6;
        color: white;
        padding: 4px 8px;
        border-radius: 10px;
        font-size: 10px;
        text-align: center;
        margin-top: 5px;
        cursor: pointer;
    }

    .booking-details-modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 1000;
    }

    .modal-content {
        background: white;
        border-radius: 20px;
        padding: 30px;
        max-width: 500px;
        width: 90%;
        max-height: 80vh;
        overflow-y: auto;
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 2px solid #eee;
    }

    .modal-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #2c3e50;
    }

    .close-btn {
        background: none;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
        color: #95a5a6;
    }

    .modal-booking-item {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 10px;
        margin-bottom: 15px;
        border-right: 4px solid #3498db;
    }

    .modal-booking-item.confirmed {
        border-right-color: #27ae60;
    }

    .modal-booking-item.pending {
        border-right-color: #f39c12;
    }

    .modal-booking-item.cancelled {
        border-right-color: #e74c3c;
    }

    .modal-booking-item.completed {
        border-right-color: #8e44ad;
    }

    .legend {
        display: flex;
        justify-content: center;
        gap: 20px;
        margin-top: 20px;
        flex-wrap: wrap;
    }

    .legend-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 12px;
    }

    .legend-color {
        width: 15px;
        height: 15px;
        border-radius: 50%;
    }

    .legend-color.confirmed {
        background: #27ae60;
    }

    .legend-color.pending {
        background: #f39c12;
    }

    .legend-color.cancelled {
        background: #e74c3c;
    }

    .legend-color.completed {
        background: #8e44ad;
    }

    @media (max-width: 768px) {
        .calendar-container {
            margin: 10px;
            border-radius: 15px;
        }

        .calendar-header {
            padding: 20px;
        }

        .calendar-title {
            font-size: 1.8rem;
        }

        .calendar-body {
            padding: 15px;
        }

        .day-cell {
            min-height: 100px;
            padding: 10px 5px;
        }

        .day-number {
            font-size: 1rem;
        }

        .booking-item {
            font-size: 10px;
            padding: 6px 8px;
        }

        .view-toggle {
            position: static;
            justify-content: center;
            margin-top: 15px;
        }

        .legend {
            gap: 10px;
        }
    }
</style>

<!-- حاوية التقويم -->
<div class="calendar-container">
    <div class="calendar-header">
        <div class="view-toggle">
            <button class="toggle-btn active" onclick="toggleView('calendar')">
                <i class="fas fa-calendar-alt"></i>
            </button>
            <button class="toggle-btn" onclick="toggleView('list')">
                <i class="fas fa-list"></i>
            </button>
        </div>

        <h1 class="calendar-title">
            <i class="fas fa-calendar-check"></i>
            تقويم الحجوزات
        </h1>

        <div class="calendar-controls">
            <button class="nav-button" onclick="previousMonth()">
                <i class="fas fa-chevron-right"></i>
            </button>
            <div class="month-year" id="monthYear"></div>
            <button class="nav-button" onclick="nextMonth()">
                <i class="fas fa-chevron-left"></i>
            </button>
        </div>

        <div class="view-filters">
            <button class="filter-btn" onclick="filterBookings('all', event)">الكل</button>
            <button class="filter-btn" onclick="filterBookings('today', event)">اليوم</button>
            <button class="filter-btn" onclick="filterBookings('week', event)">الأسبوع</button>
            <button class="filter-btn" onclick="filterBookings('month', event)">الشهر</button>
        </div>
    </div>

    <div class="calendar-body">
        <div class="calendar-grid" id="calendarGrid"></div>

        <div class="legend">
            <div class="legend-item">
                <div class="legend-color confirmed"></div>
                <span>مؤكد</span>
            </div>
            <div class="legend-item">
                <div class="legend-color pending"></div>
                <span>تحت المراجعة</span>
            </div>
            <div class="legend-item">
                <div class="legend-color cancelled"></div>
                <span>ملغي</span>
            </div>
            <div class="legend-item">
                <div class="legend-color completed"></div>
                <span>مكتمل</span>
            </div>
        </div>
    </div>
</div>

<!-- النافذة المنبثقة لتفاصيل الحجوزات -->
<div class="booking-details-modal" id="bookingModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title" id="modalTitle">تفاصيل الحجوزات</h3>
            <button class="close-btn" onclick="closeModal()">&times;</button>
        </div>
        <div id="modalBookings"></div>
    </div>
</div>

<style>
/* أنماط CSS للتقويم */
.calendar-container {
    font-family: 'Tajawal', sans-serif;
    direction: rtl;
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
    margin: 20px auto;
    overflow: hidden;
    max-width: 1200px;
}

.calendar-header {
    padding: 15px 20px;
    background-color: #f8f9fa;
    border-bottom: 1px solid #eaeaea;
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.calendar-title {
    margin: 0;
    font-size: 1.5rem;
    color: #3498db;
    text-align: center;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
}

.calendar-controls {
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 10px 0;
}

.month-year {
    margin: 0 15px;
    font-size: 1.2rem;
    font-weight: bold;
    color: #2c3e50;
    min-width: 150px;
    text-align: center;
}

.nav-button {
    background-color: #3498db;
    color: white;
    border: none;
    border-radius: 50%;
    width: 35px;
    height: 35px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: background-color 0.3s;
}

.nav-button:hover {
    background-color: #2980b9;
}

.view-filters {
    display: flex;
    justify-content: center;
    gap: 10px;
    margin: 10px 0 5px 0;
}

.filter-btn {
    background-color: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 20px;
    padding: 5px 15px;
    cursor: pointer;
    transition: all 0.3s;
    font-size: 0.9rem;
}

.filter-btn:hover {
    background-color: #e9ecef;
}

.filter-btn.active {
    background-color: #3498db;
    color: white;
    font-weight: bold;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
}

.calendar-body {
    padding: 20px;
}

.calendar-grid {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 10px;
    margin-bottom: 20px;
}

.day-header {
    text-align: center;
    font-weight: bold;
    color: #2c3e50;
    padding: 10px 0;
    background-color: #f8f9fa;
    border-radius: 5px;
}

.day-cell {
    min-height: 120px;
    border: 1px solid #eaeaea;
    border-radius: 5px;
    padding: 10px;
    transition: all 0.3s;
    display: flex;
    flex-direction: column;
    overflow: hidden;
    background-color: white;
}

.day-cell:hover {
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    transform: translateY(-2px);
}

.day-cell.other-month {
    background-color: #f9f9f9;
    opacity: 0.6;
}

.day-cell.today {
    background-color: rgba(52, 152, 219, 0.1);
    box-shadow: 0 0 8px rgba(52, 152, 219, 0.6);
    border: 2px solid #3498db !important;
}

.highlight-today {
    animation: highlight-pulse 1.5s ease-in-out;
}

@keyframes highlight-pulse {
    0% { box-shadow: 0 0 5px 5px rgba(52, 152, 219, 0.2); }
    50% { box-shadow: 0 0 15px 5px rgba(52, 152, 219, 0.6); }
    100% { box-shadow: 0 0 5px 5px rgba(52, 152, 219, 0.2); }
}

.day-number {
    font-weight: bold;
    margin-bottom: 5px;
    color: #2c3e50;
    text-align: center;
}

.bookings-list {
    flex-grow: 1;
    overflow-y: auto;
    max-height: 90px;
    display: flex;
    flex-direction: column;
    gap: 5px;
    margin-top: 5px;
}

.booking-item {
    display: flex;
    align-items: center;
    gap: 5px;
    padding: 5px;
    border-radius: 4px;
    font-size: 0.8rem;
    background-color: #f8f9fa;
    cursor: pointer;
    transition: all 0.2s;
}

.booking-item:hover {
    background-color: #e9ecef;
}

.booking-icon {
    color: #3498db;
}

.booking-time {
    font-size: 0.75rem;
    color: #7f8c8d;
}

.more-bookings {
    text-align: center;
    color: #3498db;
    font-size: 0.75rem;
    margin-top: 2px;
}

.booking-item.confirmed {
    border-right: 3px solid #3498db;
}

.booking-item.pending {
    border-right: 3px solid #f39c12;
}

.booking-item.cancelled {
    border-right: 3px solid #e74c3c;
}

.booking-item.completed {
    border-right: 3px solid #2ecc71;
}

.view-toggle {
    display: flex;
    gap: 5px;
    position: absolute;
    right: 20px;
    top: 20px;
}

.toggle-btn {
    background-color: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 4px;
    width: 35px;
    height: 35px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s;
}

.toggle-btn.active {
    background-color: #3498db;
    color: white;
}

.legend {
    display: flex;
    justify-content: center;
    gap: 20px;
    margin-top: 20px;
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 0.85rem;
}

.legend-color {
    width: 15px;
    height: 15px;
    border-radius: 3px;
}

.legend-color.confirmed {
    background-color: #3498db;
}

.legend-color.pending {
    background-color: #f39c12;
}

.legend-color.cancelled {
    background-color: #e74c3c;
}

.legend-color.completed {
    background-color: #2ecc71;
}

/* أنماط النافذة المنبثقة */
.booking-details-modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 1000;
    justify-content: center;
    align-items: center;
}

.modal-content {
    background-color: white;
    width: 80%;
    max-width: 600px;
    border-radius: 10px;
    overflow: hidden;
    max-height: 80vh;
    display: flex;
    flex-direction: column;
}

.modal-header {
    background-color: #3498db;
    color: white;
    padding: 15px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-title {
    margin: 0;
}

.close-btn {
    background: none;
    border: none;
    color: white;
    font-size: 1.5rem;
    cursor: pointer;
}

#modalBookings {
    padding: 20px;
    overflow-y: auto;
    max-height: calc(80vh - 60px);
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.modal-booking-item {
    padding: 15px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    background-color: white;
}

.modal-booking-item.confirmed {
    border-right: 4px solid #3498db;
}

.modal-booking-item.pending {
    border-right: 4px solid #f39c12;
}

.modal-booking-item.cancelled {
    border-right: 4px solid #e74c3c;
}

.modal-booking-item.completed {
    border-right: 4px solid #2ecc71;
}

@media (max-width: 768px) {
    .calendar-grid {
        grid-template-columns: repeat(7, 1fr);
        gap: 5px;
    }

    .day-cell {
        min-height: 80px;
        padding: 5px;
    }

    .modal-content {
        width: 95%;
    }
}
</style>

<script>
    // التحقق من وجود بيانات الحجوزات، إذا كانت غير موجودة تهيئة كائن فارغ
    const bookingsData = typeof window.calendarBookings !== 'undefined' ? window.calendarBookings : (typeof calendarBookings !== 'undefined' ? calendarBookings : {});
    
    // Debug: Log the bookings data to console
    console.log('Calendar bookings data:', bookingsData);

    // المتغيرات العامة
    let currentDate = new Date();
    let currentFilter = 'all';

    // أسماء الأشهر والأيام بالعربية
    const monthNames = [
        'يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو',
        'يوليو', 'أغسطس', 'سبتمبر', 'أكتوبر', 'نوفمبر', 'ديسمبر'
    ];

    const dayNames = ['الأحد', 'الإثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة', 'السبت'];

    // توليد التقويم عند تحميل DOM
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Initializing calendar with bookings data:', bookingsData);
        initCalendar();
    });

    // دالة بدء التقويم
    function initCalendar() {
        generateCalendar();

        // تفعيل فلتر "اليوم" بشكل افتراضي
        const todayBtn = document.querySelector('.filter-btn[onclick="filterBookings(\'today\', event)"]');
        if (todayBtn) {
            // محاكاة النقر على زر "اليوم"
            const clickEvent = new Event('click');
            todayBtn.dispatchEvent(clickEvent);
        } else {
            // إذا لم يكن الزر موجودًا، فقط قم بتطبيق فلتر اليوم
            filterBookings('today');
        }
    }

    // دالة توليد التقويم
    function generateCalendar() {
        const calendarGrid = document.getElementById('calendarGrid');
        const monthYear = document.getElementById('monthYear');

        if (!calendarGrid || !monthYear) {
            console.error('لم يتم العثور على عناصر التقويم');
            return;
        }

        const year = currentDate.getFullYear();
        const month = currentDate.getMonth();

        monthYear.textContent = `${monthNames[month]} ${year}`;

        const firstDay = new Date(year, month, 1);
        const lastDay = new Date(year, month + 1, 0);
        const startDate = new Date(firstDay);
        startDate.setDate(startDate.getDate() - firstDay.getDay());

        calendarGrid.innerHTML = '';

        // إضافة أسماء أيام الأسبوع
        dayNames.forEach(day => {
            const dayHeader = document.createElement('div');
            dayHeader.className = 'day-header';
            dayHeader.textContent = day;
            calendarGrid.appendChild(dayHeader);
        });

        // إضافة خلايا التقويم
        const today = new Date();
        today.setHours(0, 0, 0, 0);

        let todayCellElement = null; // متغير لتخزين خلية اليوم الحالي

        for (let i = 0; i < 42; i++) {
            const cellDate = new Date(startDate);
            cellDate.setDate(startDate.getDate() + i);

            const dayCell = document.createElement('div');
            dayCell.className = 'day-cell';

            if (cellDate.getMonth() !== month) {
                dayCell.classList.add('other-month');
            }

            // تمييز اليوم الحالي
            if (cellDate.toDateString() === today.toDateString()) {
                dayCell.classList.add('today');
                todayCellElement = dayCell; // حفظ مرجع لخلية اليوم
            }

            const dayNumber = document.createElement('div');
            dayNumber.className = 'day-number';
            dayNumber.textContent = cellDate.getDate();
            dayCell.appendChild(dayNumber);

            const bookingsList = document.createElement('div');
            bookingsList.className = 'bookings-list';

            // Format the date as YYYY-MM-DD to match the bookingsData keys
            const dateKey = cellDate.toISOString().split('T')[0];
            const dayBookings = bookingsData[dateKey] || [];

            if (dayBookings.length > 0) {
                const displayBookings = dayBookings.slice(0, 3);
                displayBookings.forEach(booking => {
                    const bookingItem = document.createElement('div');
                    bookingItem.className = `booking-item ${booking.status || ''}`;

                    // الوصول الآمن إلى خصائص الحجز
                    const clientName = booking.client ? (typeof booking.client === 'object' ? booking.client.trade_name : booking.client) : 'عميل';
                    const time = booking.time || '';

                    bookingItem.innerHTML = `
                        <i class="fas fa-user booking-icon"></i>
                        <div>
                            <div>${clientName}</div>
                            <div class="booking-time">${time}</div>
                        </div>
                    `;
                    bookingsList.appendChild(bookingItem);
                });

                if (dayBookings.length > 3) {
                    const moreBookings = document.createElement('div');
                    moreBookings.className = 'more-bookings';
                    moreBookings.textContent = `+${dayBookings.length - 3} أخرى`;
                    bookingsList.appendChild(moreBookings);
                }

                dayCell.addEventListener('click', () => showBookingDetails(cellDate, dayBookings));
            }

            dayCell.appendChild(bookingsList);
            calendarGrid.appendChild(dayCell);
        }

        // بعد إنشاء التقويم، قم بالتمرير إلى اليوم الحالي إذا كان موجودًا في العرض الحالي
        if (todayCellElement && (currentFilter === 'all' || currentFilter === 'today')) {
            // تأخير قصير لضمان اكتمال العرض
            setTimeout(() => {
                todayCellElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
                // إضافة تأثير وميض للفت الانتباه إلى اليوم الحالي
                todayCellElement.classList.add('highlight-today');
                setTimeout(() => {
                    todayCellElement.classList.remove('highlight-today');
                }, 2000);
            }, 100);
        }
    }

    // دالة عرض تفاصيل الحجز
    function showBookingDetails(date, bookings) {
        const modal = document.getElementById('bookingModal');
        const modalTitle = document.getElementById('modalTitle');
        const modalBookings = document.getElementById('modalBookings');

        if (!modal || !modalTitle || !modalBookings) {
            console.error('لم يتم العثور على عناصر النافذة المنبثقة');
            return;
        }

        modalTitle.textContent = `حجوزات يوم ${date.toLocaleDateString('ar-SA')}`;

        modalBookings.innerHTML = '';
        bookings.forEach(booking => {
            const bookingDiv = document.createElement('div');
            bookingDiv.className = `modal-booking-item ${booking.status || ''}`;

            const statusText = {
                'confirmed': 'مؤكد',
                'pending': 'تحت المراجعة',
                'cancelled': 'ملغي',
                'completed': 'مكتمل'
            };

            // الوصول الآمن إلى خصائص الحجز
            const clientName = booking.client ? (typeof booking.client === 'object' ? booking.client.trade_name : booking.client) : 'عميل';
            const productName = booking.product ? (typeof booking.product === 'object' ? booking.product.name : booking.product) : '';
            const time = booking.time || '';
            const status = booking.status || '';
            const statusLabel = statusText[status] || status;

            bookingDiv.innerHTML = `
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <h4 style="margin: 0 0 5px 0; color: #2c3e50;">${clientName}</h4>
                        <p style="margin: 0; color: #7f8c8d;">
                            <i class="fas fa-concierge-bell"></i> ${productName}
                        </p>
                    </div>
                    <div style="text-align: left;">
                        <div style="font-weight: bold; margin-bottom: 5px;">
                            <i class="fas fa-clock"></i> ${time}
                        </div>
                        <span style="background: #3498db; color: white; padding: 4px 8px; border-radius: 10px; font-size: 11px;">
                            ${statusLabel}
                        </span>
                    </div>
                </div>
            `;

            modalBookings.appendChild(bookingDiv);
        });

        modal.style.display = 'flex';
    }

    // دالة إغلاق النافذة المنبثقة
    function closeModal() {
        const modal = document.getElementById('bookingModal');
        if (modal) {
            modal.style.display = 'none';
        }
    }

    // دالة الانتقال للشهر السابق
    function previousMonth() {
        currentDate.setMonth(currentDate.getMonth() - 1);
        generateCalendar();
    }

    // دالة الانتقال للشهر التالي
    function nextMonth() {
        currentDate.setMonth(currentDate.getMonth() + 1);
        generateCalendar();
    }

    // دالة فلترة الحجوزات
    function filterBookings(filter, event) {
        if (event) {
            event.preventDefault();
        }

        currentFilter = filter;

        // تحديث زر الفلتر النشط
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.classList.remove('active');
        });

        // العثور على الزر الذي تم النقر عليه وجعله نشطًا
        if (event && event.currentTarget) {
            event.currentTarget.classList.add('active');
        } else if (filter === 'today') {
            // إذا لم يكن هناك حدث، فقط تحديث زر اليوم
            const todayBtn = document.querySelector('.filter-btn[onclick*="today"]');
            if (todayBtn) todayBtn.classList.add('active');
        }

        // التصفية حسب الاختيار
        const today = new Date();

        if (filter === 'today') {
            // إظهار الشهر الحالي فقط والتمرير إلى اليوم الحالي
            currentDate = new Date(today.getFullYear(), today.getMonth(), 1);
            generateCalendar();
        } else if (filter === 'week') {
            // عرض الأسبوع الحالي
            currentDate = new Date(today.getFullYear(), today.getMonth(), 1);
            generateCalendar();
            // يمكن إضافة منطق خاص لتمييز أيام الأسبوع الحالي
        } else if (filter === 'month') {
            // عرض الشهر الحالي
            currentDate = new Date(today.getFullYear(), today.getMonth(), 1);
            generateCalendar();
        } else {
            // للفلتر "الكل" أو أي فلتر آخر، فقط أعد توليد التقويم
            generateCalendar();
        }
    }

    // دالة تبديل العرض بين التقويم والقائمة
    function toggleView(view) {
        document.querySelectorAll('.toggle-btn').forEach(btn => {
            btn.classList.remove('active');
        });

        // العثور على الزر الذي تم النقر عليه وجعله نشطًا
        event.currentTarget.classList.add('active');

        if (view === 'list') {
            // التبديل إلى عرض القائمة (يمكن تنفيذه حسب الحاجة)
            alert('عرض القائمة قيد التطوير');
        } else {
            // عرض التقويم
            generateCalendar();
        }
    }

    // إغلاق النافذة المنبثقة عند النقر خارجها
    window.onclick = function(event) {
        const modal = document.getElementById('bookingModal');
        if (modal && event.target === modal) {
            closeModal();
        }
    }

    // التمرير إلى اليوم الحالي عند التحميل الأولي
    window.addEventListener('load', function() {
        // تعيين التصفية إلى "اليوم" بشكل افتراضي
        setTimeout(() => {
            filterBookings('today');
        }, 300);
    });
</script>