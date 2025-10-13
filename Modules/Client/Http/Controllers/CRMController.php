<?php

namespace Modules\Client\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\ClientRelation;
use App\Models\Invoice;
use Illuminate\Http\Request;
use App\Models\Region_groub;
use Illuminate\Support\Facades\Log;

class CRMController extends Controller
{
    public function mang_client(Request $request)
    {
        $clientGroups = Region_groub::all();

        // جلب الفواتير مع علاقاتها - مرتبة من الأحدث إلى الأقدم
        $invoices = Invoice::with([
            'client',
            'employee',
            'payments',
            'treasury',
            'items.product'
        ])
        ->orderBy('created_at', 'desc')
        ->get();

        // جلب جميع الملاحظات - مرتبة من الأحدث إلى الأقدم
        $notes = ClientRelation::with(['employee', 'location'])
            ->orderBy('created_at', 'desc')
            ->get();

        // جلب العملاء مع علاقاتهم
        $clients = Client::with([
            'account',
            'invoices' => function($query) {
                $query->with(['employee', 'payments', 'treasury', 'items.product'])
                      ->orderBy('created_at', 'desc');
            },
            'appointmentNotes' => function($query) {
                $query->with('employee')->orderBy('date', 'desc');
            },
            'clientRelations' => function ($query) {
                $query->with(['employee', 'location'])
                      ->orderBy('created_at', 'desc');
            },
        ])
        ->get()
        ->map(function ($client) {
            // جلب الرصيد من جدول الحسابات
            $balance = 0;
            if ($client->relationLoaded('account') && $client->account) {
                $balance = $client->account->balance ?? 0;
            } elseif ($client->relationLoaded('accounts') && $client->accounts->isNotEmpty()) {
                $balance = $client->accounts->first()->balance ?? 0;
            } elseif (isset($client->balance)) {
                $balance = $client->balance;
            }

            return [
                'id' => $client->id,
                'name' => $client->full_name,
                'phone' => $client->phone,
                'balance' => $balance,
                'invoices' => $client->invoices->map(function ($invoice) {
                    // حساب معلومات إضافية للفاتورة
                    $totalPayments = $invoice->payments->sum('amount');
                    $remainingAmount = $invoice->grand_total - $totalPayments;
                    $isPaid = $invoice->is_paid;
                    $paymentStatus = $invoice->payment_status;
                    
                    // تحديد نص الحالة
                    $statusText = 'غير محدد';
                    if ($isPaid) {
                        $statusText = 'مدفوعة بالكامل';
                    } elseif ($totalPayments > 0) {
                        $statusText = 'مدفوعة جزئياً';
                    } else {
                        $statusText = 'غير مدفوعة';
                    }
                    
                    // تحديد لون الحالة
                    $statusClass = 'secondary';
                    if ($isPaid) {
                        $statusClass = 'success';
                    } elseif ($totalPayments > 0) {
                        $statusClass = 'warning';
                    } else {
                        $statusClass = 'danger';
                    }
                    
                    // معالجة عناصر الفاتورة
                    $items = $invoice->items->map(function ($item) {
                        return [
                            'id' => $item->id,
                            'product_name' => $item->product ? $item->product->name : 'غير محدد',
                            'description' => $item->description,
                            'quantity' => $item->quantity,
                            'unit_price' => $item->unit_price,
                            'discount' => $item->discount,
                            'tax_1' => $item->tax_1,
                            'tax_2' => $item->tax_2,
                            'total' => $item->total,
                        ];
                    });

                    return [
                        'id' => $invoice->id,
                        'number' => $invoice->code,
                        'date' => $invoice->invoice_date ? $invoice->invoice_date->format('Y-m-d') : null,
                        'issue_date' => $invoice->issue_date ? $invoice->issue_date->format('Y-m-d') : null,
                        'amount' => $invoice->grand_total,
                        'subtotal' => $invoice->subtotal,
                        'tax_total' => $invoice->tax_total,
                        'discount_amount' => $invoice->discount_amount,
                        'status' => $paymentStatus,
                        'status_text' => $statusText,
                        'status_class' => $statusClass,
                        'remaining' => $remainingAmount,
                        'paymentMethod' => $invoice->payment_method,
                        'employee' => $invoice->employee ? $invoice->employee->name : 'غير محدد',
                        'treasury' => $invoice->treasury ? $invoice->treasury->name : 'غير محدد',
                        'currency' => $invoice->currency,
                        'notes' => $invoice->notes,
                        'is_paid' => $isPaid,
                        'payment_terms' => $invoice->payment_terms,
                        'reference_number' => $invoice->reference_number,
                        'type' => $invoice->type,
                        'items_count' => $invoice->items->count(),
                        'items' => $items,
                        'total_payments' => $totalPayments,
                        'created_at' => $invoice->created_at ? $invoice->created_at->format('Y-m-d H:i') : null,
                        'updated_at' => $invoice->updated_at ? $invoice->updated_at->format('Y-m-d H:i') : null,
                        'shipping_cost' => $invoice->shipping_cost,
                        'adjustment_label' => $invoice->adjustment_label,
                        'adjustment_value' => $invoice->adjustment_value,
                    ];
                }),
                'appointmentNotes' => $client->appointmentNotes->map(function ($note) {
                    return [
                        'id' => $note->id,
                        'date' => $note->date,
                        'employee' => $note->employee->name ?? 'غير محدد',
                        'content' => $note->description,
                        'status' => $note->status,
                    ];
                }),
                'clientRelations' => $client->clientRelations->map(function ($relation) {
                    // معالجة نوع الموقع
                    $siteTypeText = $this->getSiteTypeText($relation->site_type);

                    // معالجة المرفقات
                    $attachmentsArray = $this->processAttachments($relation->attachments);

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
                        'attachments' => $relation->attachments,
                        'attachments_array' => $attachmentsArray,
                        'created_at' => $relation->created_at,
                        'updated_at' => $relation->updated_at,
                    ];
                }),
            ];
        });

        Log::info('CRM Controller Data', [
            'clients_count' => count($clients),
            'first_client_relations_count' => count($clients->first()['clientRelations'] ?? []),
        ]);

        return view('client::relestion_mang_client', [
            'clients' => $clients,
            'invoices' => $invoices,
            'notes' => $notes,
            'clientGroups' => $clientGroups
        ]);
    }

    /**
     * الحصول على نص نوع الموقع بالعربية
     */
    private function getSiteTypeText($siteType)
    {
        $types = [
            'independent_booth' => 'بسطة مستقلة',
            'grocery' => 'بقالة',
            'supplies' => 'تموينات',
            'markets' => 'أسواق',
            'station' => 'محطة',
        ];

        return $types[$siteType] ?? $siteType;
    }

    /**
     * معالجة المرفقات
     */
    private function processAttachments($attachments)
    {
        $attachmentsArray = [];

        if ($attachments) {
            if (is_string($attachments)) {
                $decoded = json_decode($attachments, true);
                if (is_array($decoded)) {
                    $attachmentsArray = $decoded;
                } else {
                    $attachmentsArray = [$attachments];
                }
            } elseif (is_array($attachments)) {
                $attachmentsArray = $attachments;
            }
        }

        return $attachmentsArray;
    }
}