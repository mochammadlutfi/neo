<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paket extends Model
{
    use HasFactory;

    protected $table = 'paket';
    protected $primaryKey = 'id';

    
    protected $fillable = [
        'id', 'nama', 'deskripsi', '1bulan', '3bulan', 'tahun'
    ];

    protected $appends = [
        // 'sisa'
    ];

    public function user(){
        return $this->hasMany(UserTraining::class, 'training_id');
    }

    
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'nama'
            ]
        ];
    }
}
