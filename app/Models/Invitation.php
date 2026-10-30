<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany; // Make sure this is imported!
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invitation extends Model
{
    //
    protected $fillable = [
        'client_id',
        'invited_by',
        'name',
        'email',
        'role',
        'token_hash',
        'expires_at',
        'accepted_at',
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
            'accepted_at' => 'datetime',
        ];
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function inviter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'invited_by');
    }
}
