<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    
    protected $table = 'task';
    protected $primaryKey = 'id';

    protected $fillable = [
        'project_id', 'nama', 'link_brief', 'tgl_tempo', 'tgl_upload', 
        'status', 'status_upload', 'file', 'catatan',
        'total_view', 'total_likes', 'total_comments', 'total_share', 'bukti'
    ];

    protected $casts = [
        'tgl_tempo' => 'datetime',
        'tgl_upload' => 'datetime',
    ];
    
    public function project(){
        return $this->belongsTo(Project::class, 'project_id');
    }
}
