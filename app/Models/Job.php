<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Job extends Model
{
    protected $fillable = [
        'organization_id',
        'title',
        'description',
        'requirements',
        'location',
        'start_date',
        'end_date',
        'application_deadline',
        'status',
    ];

    
protected $casts = [
    'start_date' => 'date',
    'end_date' => 'date',
    'application_deadline' => 'date',
];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
  

    /**
     * Applications submitted for this job.
     */
    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }
    
}

