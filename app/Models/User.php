<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;

    protected $fillable = [
        'nickname',
        'phone',
        'status',
        'telegram_id',
    ];

    public function messages()
    {
        return $this->hasMany(Message::class, 'telegram_id', 'telegram_id');
    }
}