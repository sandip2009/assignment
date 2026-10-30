<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany; // Make sure this is imported!

class Client extends Model
{
    //
    protected $fillable = ['company_name','slug','email'];

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'client_id');
    }

    public function invitations(): HasMany
    {
        return $this->hasMany(Invitation::class);
    }

    public function shortUrls(): HasMany
    {
        return $this->hasMany(ShortUrl::class, 'client_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
