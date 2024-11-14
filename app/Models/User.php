<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'role'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the identifier that will be stored in the JWT payload.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * Return a true if the user is admin
     *
     * @return boolean
     */
    public function isAdmin()
    {
        return $this->role === "admin";
    }

    /**
     * Return a true if the user is supervisor
     *
     * @return boolean
     */
    public function isSupervisor()
    {
        return $this->role === "supervisor";
    }

    /**
     * Return a true if the user is end_user
     *
     * @return boolean
     */
    public function isEndUser()
    {
        return $this->role === "end_user";
    }

    public function canChange(Ticket $ticket, string $field): bool
    {
        if ($field === "description" || $field === "title") {
            abort_unless($this->id === $ticket->submitter_id, 403, 'Unauthorized action.');
            return true;
        }

        if ($field === "status") {
            abort_unless($this->isAdmin() || $this->isSupervisor(), 403, 'Unauthorized action.');
            return $this->isAdmin() || $this->isSupervisor();
        }

        if ($field === "assigned_tech_id") {
            abort_unless($this->isAdmin(), 403, 'Unauthorized action.');
            return true;
        }

        return $this->isAdmin();
    }

    public function canSee(Ticket $ticket, string $field): bool
    {
        if ($field === "assigned_tech_id") {
            return !$this->isEndUser();
        }
        if ($field === "submitter") {
            return !$this->isEndUser();
        }

        return $this->isAdmin();
    }

    public function canDeleteTicket(Ticket $ticket)
    {
        abort_unless($this->isAdmin() || $this->id === $ticket->submitter_id, 403, 'Unauthorized action.');
        return true;
    }

    public function canEditTicket(Ticket $ticket)
    {
        $can_edit = $this->isAdmin() || $this->id === $ticket->submitter_id || $this->id === $ticket->assigned_tech_id;

        return $can_edit;
    }
}
