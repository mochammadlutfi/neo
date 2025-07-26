<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Kategori extends Model
{
    use HasFactory;
    use Sluggable;
    
    protected $table = 'kategori';
    protected $primaryKey = 'id';

    
    protected $fillable = [
        'id', 'nama', 'slug'
    ];

    public function training(){
        return $this->hasMany(Training::class, 'kategori_id');
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
