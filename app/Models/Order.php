<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'order';
    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id', 'paket_id', 'nomor', 'durasi', 'harga', 'tgl', 'status', 'total', 'tgl_selesai', 'tgl_tempo'
    ];

    protected $appends = [
        'status_pembayaran'
    ];
    
    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function project(){
        return $this->hasMany(Project::class, 'order_id');
    }

    public function payment(){
        return $this->hasMany(Pembayaran::class, 'order_id');
    }
    
    public function paket(){
        return $this->belongsTo(Paket::class, 'paket_id');
    }

    public function getStatusPembayaranAttribute()
    {
        $totalPembayaran = $this->payment()->where('status', 'terima')->sum('jumlah');
        
        if ($totalPembayaran == 0) {
            return 'Belum Bayar';
        } elseif ($totalPembayaran < $this->total) {
            return 'Down Payment';
        } else {
            return 'Lunas';
        }
    }
}
