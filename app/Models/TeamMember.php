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
        'gender',
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

    public function getHasPhotoAttribute(): bool
    {
        return !empty($this->photo);
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

        $gender = strtolower($this->gender ?? 'male');
        if ($gender === 'female') {
            return self::getFemaleAvatarSvg();
        }

        return self::getMaleAvatarSvg();
    }

    public function getPhotoStyleAttribute()
    {
        $posX = ($this->photo_position_x ?? 50) - 50;
        $posY = ($this->photo_position_y ?? 50) - 50;
        $zoom = ($this->photo_zoom ?? 100) / 100;
        return "transform: translate({$posX}%, {$posY}%) scale({$zoom}); transform-origin: center center;";
    }

    public static function getMaleAvatarSvg(): string
    {
        $svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="100%" height="100%">
            <defs>
                <linearGradient id="maleGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" stop-color="#0f172a"/>
                    <stop offset="50%" stop-color="#1e293b"/>
                    <stop offset="100%" stop-color="#334155"/>
                </linearGradient>
                <linearGradient id="maleAccent" x1="0%" y1="0%" x2="0%" y2="100%">
                    <stop offset="0%" stop-color="#38bdf8"/>
                    <stop offset="100%" stop-color="#0284c7"/>
                </linearGradient>
            </defs>
            <rect width="512" height="512" fill="url(#maleGrad)"/>
            <circle cx="256" cy="256" r="210" fill="none" stroke="rgba(255,255,255,0.06)" stroke-width="2" stroke-dasharray="8 8"/>
            <path d="M 120 512 C 120 400, 180 340, 256 340 C 332 340, 392 400, 392 512 Z" fill="#475569"/>
            <path d="M 150 512 C 150 420, 190 365, 256 365 C 322 365, 362 420, 362 512 Z" fill="#64748b"/>
            <path d="M 215 365 L 256 440 L 297 365 Z" fill="#f8fafc"/>
            <path d="M 246 365 L 256 425 L 266 365 Z" fill="url(#maleAccent)"/>
            <rect x="226" y="275" width="60" height="95" rx="10" fill="#cbd5e1"/>
            <ellipse cx="256" cy="210" rx="72" ry="90" fill="#e2e8f0"/>
            <path d="M 175 190 C 175 110, 210 95, 256 95 C 302 95, 337 110, 337 190 C 337 150, 310 115, 256 115 C 202 115, 175 150, 175 190 Z" fill="#1e293b"/>
            <path d="M 175 185 C 180 120, 220 100, 265 100 C 315 100, 335 125, 337 185 C 320 135, 280 120, 250 125 C 220 130, 190 150, 175 185 Z" fill="#0f172a"/>
            <ellipse cx="182" cy="215" rx="12" ry="18" fill="#cbd5e1"/>
            <ellipse cx="330" cy="215" rx="12" ry="18" fill="#cbd5e1"/>
        </svg>';
        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }

    public static function getFemaleAvatarSvg(): string
    {
        $svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="100%" height="100%">
            <defs>
                <linearGradient id="femaleGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" stop-color="#0f172a"/>
                    <stop offset="50%" stop-color="#1e1b4b"/>
                    <stop offset="100%" stop-color="#312e81"/>
                </linearGradient>
                <linearGradient id="femaleAccent" x1="0%" y1="0%" x2="0%" y2="100%">
                    <stop offset="0%" stop-color="#c084fc"/>
                    <stop offset="100%" stop-color="#818cf8"/>
                </linearGradient>
            </defs>
            <rect width="512" height="512" fill="url(#femaleGrad)"/>
            <circle cx="256" cy="256" r="210" fill="none" stroke="rgba(255,255,255,0.06)" stroke-width="2" stroke-dasharray="8 8"/>
            <path d="M 155 200 C 155 380, 190 420, 210 512 L 302 512 C 322 420, 357 380, 357 200 Z" fill="#1e1b4b"/>
            <path d="M 130 512 C 130 410, 185 355, 256 355 C 327 355, 382 410, 382 512 Z" fill="#4338ca"/>
            <path d="M 160 512 C 160 425, 195 375, 256 375 C 317 375, 352 425, 352 512 Z" fill="#6366f1"/>
            <path d="M 215 375 L 256 435 L 297 375 Z" fill="#f8fafc"/>
            <path d="M 240 375 L 256 420 L 272 375 Z" fill="url(#femaleAccent)"/>
            <rect x="230" y="270" width="52" height="100" rx="8" fill="#e2e8f0"/>
            <ellipse cx="256" cy="210" rx="68" ry="85" fill="#f1f5f9"/>
            <path d="M 172 200 C 172 105, 205 90, 256 90 C 307 90, 340 105, 340 200 C 340 260, 335 300, 325 330 C 305 230, 280 130, 256 130 C 232 130, 207 230, 187 330 C 177 300, 172 260, 172 200 Z" fill="#0f172a"/>
            <path d="M 172 195 C 175 115, 210 95, 256 95 C 302 95, 337 115, 340 195 C 315 130, 275 115, 245 125 C 210 135, 185 160, 172 195 Z" fill="#311b92"/>
            <circle cx="180" cy="235" r="7" fill="url(#femaleAccent)"/>
            <circle cx="332" cy="235" r="7" fill="url(#femaleAccent)"/>
        </svg>';
        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }
}
