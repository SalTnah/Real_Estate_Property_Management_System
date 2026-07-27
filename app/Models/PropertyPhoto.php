<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PropertyPhoto extends Model
{
    protected $fillable = [
        'property_id', 'photo_url', 'is_primary', 'sort_order',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    protected function url(): Attribute
    {
        return Attribute::make(
            get: fn () => str_starts_with($this->photo_url, 'http')
                ? $this->photo_url
                : Storage::url($this->photo_url),
        );
    }
}