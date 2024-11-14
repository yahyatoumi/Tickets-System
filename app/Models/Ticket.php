<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'status',
        'assigned_tech_id',
        "submitter_id"
    ];

    public function submitter()
    {
        return $this->belongsTo(User::class, 'submitter_id');
    }

    public function assignedTech()
    {
        return $this->belongsTo(User::class, 'assigned_tech_id');
    }

    public function uploadedFiles()
    {
        return $this->hasMany(UploadedFile::class);
    }


    //
}
