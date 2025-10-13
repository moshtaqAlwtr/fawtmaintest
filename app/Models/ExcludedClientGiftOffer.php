<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExcludedClientGiftOffer extends Model
{
     protected $table = 'excluded_client_gift_offers'; // اسم الجدول

    protected $fillable = [
        'gift_offer_id',
        'client_id',
    ];

}
