<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class notifications extends Model
{
    protected $table = 'notifications';
    protected $fillable = [
        'user_id',
        'receiver_id',
        'title',
        'description',
        'message',
        'read',
        'type',
        'data'
    ];

    protected $casts = [
        'data' => 'array',
        'read' => 'boolean'
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function receiver(){
        return $this->belongsTo(User::class, 'receiver_id');
    }
}