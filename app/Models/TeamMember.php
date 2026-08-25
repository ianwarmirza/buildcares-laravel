<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class TeamMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'role',
        'bio',
        'photo',
        'photo_position_x',
        'photo_position_y',
        'photo_zoom',
        'email',
        'phone',
        'linkedin',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'photo_position_x' => 'integer',
        'photo_position_y' => 'integer',
        'photo_zoom' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getPhotoUrlAttribute()
    {
        if ($this->photo) {
            if (str_starts_with($this->photo, 'http://') || str_starts_with($this->photo, 'https://')) {
                return $this->photo;
            }
            $path = ltrim($this->photo, '/');
            if (str_starts_with($path, 'storage/')) {
                $path = substr($path, 8);
            }
            return asset('storage/' . $path);
        }

        // Return a clean default avatar placeholder if photo not provided
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name ?? 'User') . '&background=0F172A&color=ffffff&size=512';
    }

    public function getPhotoStyleAttribute()
    {
        $posX = $this->photo_position_x ?? 50;
        $posY = $this->photo_position_y ?? 50;
        $zoom = ($this->photo_zoom ?? 100) / 100;
        return "object-position: {$posX}% {$posY}%; transform: scale({$zoom});";
    }
}
