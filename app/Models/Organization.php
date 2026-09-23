<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;



class Organization extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'phone',
        'email',
        'logo',
        'bio',
        'website',
        'address',
        'city',
        'country',
        'status',
        'approved_at',
        'approved_by',
    ];

    public function jobs(): HasMany
    {
        return $this->hasMany(Job::class);
    }
     public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * User who approved the organization.
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

}