<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $table = 'project';
    protected $primaryKey = 'id';

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    
    public function order(){
        return $this->belongsTo(Order::class, 'order_id');
    }

    
    public function task(){
        return $this->hasMany(Task::class, 'project_id');
    }
}
