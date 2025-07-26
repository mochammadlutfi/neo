<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;
    
    protected $table = 'pembayaran';
    protected $primaryKey = 'id';

    
    protected $fillable = [
        'id', 'nama',
    ];

    
    public function order(){
        return $this->belongsTo(Order::class, 'order_id');
    }

}
