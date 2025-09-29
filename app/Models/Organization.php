<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Organization extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'slug',
        'logo',
        'phone',
        'email',
        'website',
        'address',
        'region_code',
        'status',
    ];

    /**
     * Get the users for the organization.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * Get the divisions for the organization.
     */
    public function divisions(): HasMany
    {
        return $this->hasMany(Division::class);
    }

    /**
     * Get the region for the organization.
     */
    public function region(): BelongsTo
    {
        return $this->belongsTo(\Aliziodev\IndonesiaRegions\Models\IndonesiaRegion::class, 'region_code', 'code');
    }
}
