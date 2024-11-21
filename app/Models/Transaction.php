<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',         // Tambahkan user_id
        'address',
        'payment_method',
        'total_price',
        'discount',
        'shipping_fee',
        'final_price',
    ];

    // Definisikan relasi ke tabel transaction_details
    public function details()
    {
        return $this->hasMany(TransactionDetail::class, 'transaction_id', 'id');
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    // public function details()
    // {
    //     return $this->hasMany(TransactionDetail::class, 'transaction_id', 'id');
    // }
}