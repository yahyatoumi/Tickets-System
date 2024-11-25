<?php

namespace App\Models;

use App\Events\CommentEvent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;

class Comment extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'post_id', 'parent_id', 'body', 'ticket_id'];

    /**
     * The belongs to Relationship
     *
     * @var array
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    /**
     * The has Many Relationship
     *
     * @var array
     */
    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }

    public function broadcastCommentToAdminsAndOwner(User $whoCommented)
    {
        $ticket = Ticket::find($this->ticket_id);
        $excludeIds = [$whoCommented->id];

        // Add submitter to excluded IDs if they're not the commenter
        $ticketSubmitter = User::find($ticket->submitter_id);
        if ($ticket->submitter_id !== $whoCommented->id) {
            $excludeIds[] = $ticket->submitter_id;
            event(new CommentEvent($this, $ticketSubmitter, $ticket, $whoCommented));
        }

        $assignedTech = User::find($ticket->assigned_tech_id);
        // Notify assigned tech if exists and not the commenter
        if ($ticket->assigned_tech_id && $ticket->assigned_tech_id !== $whoCommented->id) {
            if ($assignedTech) {
                event(new CommentEvent($this, $assignedTech, $ticket, $whoCommented));
                $excludeIds[] = $ticket->assigned_tech_id;
            }
        }

        // Notify admins
        $admins = User::where('role', 'admin')
            ->whereNotIn('id', $excludeIds)
            ->get();

        foreach ($admins as $admin) {
            event(new CommentEvent($this, $admin, $ticket, $whoCommented));
        }
    }
}
