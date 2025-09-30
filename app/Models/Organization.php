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
        'brand',
        'slug',
        'logo',
        'phone',
        'email',
        'website',
        'address',
        'province_code',
        'city_code',
        'district_code',
        'village_code',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'is_active' => true,
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
     * Get the province for the organization.
     */
    public function province(): BelongsTo
    {
        return $this->belongsTo(\Aliziodev\IndonesiaRegions\Models\IndonesiaRegion::class, 'province_code', 'code');
    }

    /**
     * Get the city for the organization.
     */
    public function city(): BelongsTo
    {
        return $this->belongsTo(\Aliziodev\IndonesiaRegions\Models\IndonesiaRegion::class, 'city_code', 'code');
    }

    /**
     * Get the district for the organization.
     */
    public function district(): BelongsTo
    {
        return $this->belongsTo(\Aliziodev\IndonesiaRegions\Models\IndonesiaRegion::class, 'district_code', 'code');
    }

    /**
     * Get the village for the organization.
     */
    public function village(): BelongsTo
    {
        return $this->belongsTo(\Aliziodev\IndonesiaRegions\Models\IndonesiaRegion::class, 'village_code', 'code');
    }
}
