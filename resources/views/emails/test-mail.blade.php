@if(isset($details['type']) && $details['type'] == 'project_invite')
    <h1>دعوة لمشروع جديد</h1>
    <p>مرحباً،</p>
    <p>تمت دعوتك من قبل {{ $details['inviter_name'] }} للانضمام إلى مشروع {{ $details['project_title'] }} في مساحة العمل {{ $details['workspace_title'] }} كـ {{ $details['role'] }}.</p>
    
    @if(isset($details['invite_message']) && !empty($details['invite_message']))
        <p>رسالة من {{ $details['inviter_name'] }}:</p>
        <blockquote>{{ $details['invite_message'] }}</blockquote>
    @endif

    <p>بيانات الدخول المؤقتة:</p>
    <ul>
        <li>البريد الإلكتروني: {{ $details['email'] }}</li>
        <li>كلمة المرور: {{ $details['password'] }}</li>
    </ul>

    <p>الرجاء النقر على الرابط التالي لقبول الدعوة:</p>
    <a href="{{ $details['accept_url'] }}" style="display: inline-block; padding: 10px 20px; background-color: #4CAF50; color: white; text-decoration: none; border-radius: 5px;">قبول الدعوة</a>

    <p>تنتهي صلاحية هذه الدعوة في: {{ $details['expires_at'] }}</p>

@elseif(isset($details['type']) && $details['type'] == 'project_notification')
    <h1>إشعار انضمام لمشروع</h1>
    <p>مرحباً {{ $details['name'] }}،</p>
    <p>{{ $details['message'] }}</p>

@else
    <h1>إشعار من النظام</h1>
    <p>مرحباً {{ $details['name'] }}،</p>
    <p>{{ $details['message'] ?? 'لديك إشعار جديد من النظام' }}</p>
@endif

<p style="margin-top: 20px;">مع تحيات فريق العمل</p>