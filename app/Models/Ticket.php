<?php

namespace App\Models;

use App\Events\TicketCreatedEvent;
use App\Events\TicketUpdateEvent;
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

    public function broadcastCreationToAdminsAndOwner()
    {
        // Get all admins excluding the ticket submitter
        $admins = User::where('role', 'admin')
            ->where('id', '!=', $this->submitter_id)
            ->get();
    
        // Fire the event for each admin
        foreach ($admins as $admin) {
            event(new TicketCreatedEvent($this, $admin));
        }
    
        // Fire the event for the submitter
        if ($this->submitter) {
            event(new TicketCreatedEvent($this, $this->submitter));
        }
    }

    public function broadcastUpdateToAdminsAndOwner(User $updater,array $updatedFields)
    {
        // Get all admins excluding the ticket submitter
        $admins = User::where('role', 'admin')
            ->where('id', '!=', $this->submitter_id)
            ->get();
    
        // Fire the event for each admin
        foreach ($admins as $admin) {
            event(new TicketUpdateEvent($this, $admin, $updater, $updatedFields));
        }
    
        // Fire the event for the submitter
        if ($this->submitter) {
            event(new TicketUpdateEvent($this, $this->submitter, $updater, $updatedFields));
        }
    }
}
