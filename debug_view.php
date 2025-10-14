<?php
// This is a simple debug script to check what data is being passed to the view
require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Get the CRM controller and check what data it's processing
use App\Models\Client;
use App\Models\ClientRelation;

// Try to get a client with relations like in the controller
$client = Client::with([
    'clientRelations' => function ($query) {
        $query->with(['employee', 'location'])
              ->orderBy('created_at', 'desc');
    },
])->first();

if (!$client) {
    echo "No client found\n";
    exit;
}

echo "Client: " . $client->trade_name . " (ID: " . $client->id . ")\n";
echo "Client Relations Count: " . $client->clientRelations->count() . "\n";

// Process the relations like in the controller
$clientRelations = $client->clientRelations->map(function ($relation) {
    // معالجة نوع الموقع لعرضه بالعربية
    $siteTypeText = '';
    switch ($relation->site_type) {
        case 'independent_booth':
            $siteTypeText = 'بسطة مستقلة';
            break;
        case 'grocery':
            $siteTypeText = 'بقالة';
            break;
        case 'supplies':
            $siteTypeText = 'تموينات';
            break;
        case 'markets':
            $siteTypeText = 'أسواق';
            break;
        case 'station':
            $siteTypeText = 'محطة';
            break;
        default:
            $siteTypeText = $relation->site_type;
    }

    // معالجة المرفقات
    $attachmentsArray = [];
    if ($relation->attachments) {
        // التحقق من نوع البيانات أولاً
        if (is_string($relation->attachments)) {
            // إذا كانت سلسلة نصية، نحاول تحليلها كـ JSON
            $decoded = json_decode($relation->attachments, true);
            if (is_array($decoded)) {
                $attachmentsArray = $decoded;
            } else {
                // إذا لم تنجح عملية التحليل، نتعامل معها كاسم ملف واحد
                $attachmentsArray = [$relation->attachments];
            }
        } elseif (is_array($relation->attachments)) {
            // إذا كانت مصفوفة بالفعل
            $attachmentsArray = $relation->attachments;
        }
    }

    return [
        'id' => $relation->id,
        'client_id' => $relation->client_id,
        'status' => $relation->status,
        'quotation_id' => $relation->quotation_id,
        'invoice_id' => $relation->invoice_id,
        'description' => $relation->description,
        'process' => $relation->process,
        'type' => $relation->type,
        'date' => $relation->date,
        'time' => $relation->time,
        'employee_id' => $relation->employee_id,
        'employee' => $relation->employee->name ?? 'غير محدد',
        'employee_email' => $relation->employee->email ?? null,
        'location_id' => $relation->location_id,
        'location' => $relation->location ? [
            'id' => $relation->location->id,
            'latitude' => $relation->location->latitude,
            'longitude' => $relation->location->longitude,
            'address' => $relation->location->address,
        ] : null,
        'deposit_count' => $relation->deposit_count,
        'employee_view_status' => $relation->employee_view_status,
        'site_type' => $relation->site_type,
        'site_type_text' => $siteTypeText,
        'competitor_documents' => $relation->competitor_documents,
        'additional_data' => $relation->additional_data,
        // إضافة المرفقات
        'attachments' => $relation->attachments,
        'attachments_array' => $attachmentsArray,
        'created_at' => $relation->created_at,
        'updated_at' => $relation->updated_at,
    ];
});

echo "Processed Relations Count: " . $clientRelations->count() . "\n";

// Show first relation details
if ($clientRelations->count() > 0) {
    $firstRelation = $clientRelations->first();
    echo "First Relation Details:\n";
    echo "  ID: " . $firstRelation['id'] . "\n";
    echo "  Description: " . $firstRelation['description'] . "\n";
    echo "  Process: " . $firstRelation['process'] . "\n";
    echo "  Employee: " . $firstRelation['employee'] . "\n";
    echo "  Deposit Count: " . $firstRelation['deposit_count'] . "\n";
    echo "  Attachments: " . json_encode($firstRelation['attachments']) . "\n";
    echo "  Attachments Array: " . json_encode($firstRelation['attachments_array']) . "\n";
}