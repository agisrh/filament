<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Division extends Model
{
    use SoftDeletes;

    protected $table = 'divisions';
    
    protected $fillable = [
        'code',
        'name',
        'status',
        'organization_id'
    ];

    protected $casts = [
        'status' => 'string'
    ];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
}
