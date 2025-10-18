<!-- قالب بطاقة الموعد -->
<div class="booking-item {{ $booking['status'] }}">
    <div class="d-flex justify-content-between align-items-center">
        <div class="booking-time">
            <i class="fas fa-clock me-1"></i>
            {{ $booking['time'] }}
        </div>
        <span class="badge {{ $booking['status'] }}">{{ $booking['status_text'] }}</span>
    </div>
    <div class="booking-client mt-1">
        <i class="fas fa-user me-1"></i>
        {{ $booking['client'] }}
    </div>
    @if($booking['phone'])
    <div class="booking-phone mt-1 text-muted">
        <i class="fas fa-phone me-1"></i>
        {{ $booking['phone'] }}
    </div>
    @endif
</div>