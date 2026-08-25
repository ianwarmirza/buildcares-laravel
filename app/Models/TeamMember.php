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
        if (!empty($this->photo)) {
            if (str_starts_with($this->photo, 'data:image/') || str_starts_with($this->photo, 'http://') || str_starts_with($this->photo, 'https://')) {
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
        $file = public_path('images/avatars/male.svg');
        if (file_exists($file)) {
            return asset('images/avatars/male.svg');
        }
        $svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="100%" height="100%"><circle cx="256" cy="256" r="256" fill="#e5e7eb"/><path d="M 180 512 L 256 360 L 332 512 Z" fill="#ffffff"/><polygon points="190,360 256,430 220,380" fill="#e2e8f0"/><polygon points="322,360 256,430 292,380" fill="#cbd5e1"/><polygon points="244,375 268,375 274,470 256,510 238,470" fill="#dc2626"/><polygon points="242,365 270,365 266,380 246,380" fill="#b91c1c"/><path d="M 60 512 C 60 410, 130 350, 200 350 L 256 460 L 190 512 Z" fill="#18181b"/><path d="M 452 512 C 452 410, 382 350, 312 350 L 256 460 L 322 512 Z" fill="#18181b"/><path d="M 110 512 C 125 410, 175 350, 256 460 Z" fill="#27272a"/><path d="M 402 512 C 387 410, 337 350, 256 460 Z" fill="#27272a"/><path d="M 216 270 L 296 270 L 296 370 L 216 370 Z" fill="#f8d7be"/><path d="M 216 330 C 236 360, 276 360, 296 330 L 296 370 L 216 370 Z" fill="#ecc0a2"/><circle cx="178" cy="225" r="26" fill="#f8d7be"/><circle cx="334" cy="225" r="26" fill="#f8d7be"/><path d="M 184 190 C 184 290, 210 325, 256 325 C 302 325, 328 290, 328 190 C 328 110, 302 110, 256 110 C 210 110, 184 110, 184 190 Z" fill="#f8d7be"/><path d="M 166 190 C 166 90, 206 60, 256 60 C 306 60, 346 90, 346 190 C 346 180, 340 130, 326 120 C 310 110, 276 105, 246 125 C 220 140, 186 160, 174 195 Z" fill="#27272a"/><path d="M 166 185 C 168 120, 216 70, 270 70 C 320 70, 344 110, 346 180 C 326 115, 286 95, 240 115 C 205 130, 180 155, 166 185 Z" fill="#18181b"/><path d="M 178 180 L 186 180 L 184 220 L 176 215 Z" fill="#27272a"/><path d="M 334 180 L 326 180 L 328 220 L 336 215 Z" fill="#27272a"/></svg>';
        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }

    public static function getFemaleAvatarSvg(): string
    {
        $file = public_path('images/avatars/female.svg');
        if (file_exists($file)) {
            return asset('images/avatars/female.svg');
        }
        $svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="100%" height="100%"><circle cx="256" cy="256" r="256" fill="#e5e7eb"/><path d="M 140 210 C 130 310, 150 380, 185 410 C 200 420, 312 420, 327 410 C 362 380, 382 310, 372 210 C 370 120, 320 65, 256 65 C 192 65, 142 120, 140 210 Z" fill="#18181b"/><path d="M 200 512 L 256 360 L 312 512 Z" fill="#ffffff"/><path d="M 50 512 C 50 410, 120 350, 190 350 L 256 460 L 170 512 Z" fill="#18181b"/><path d="M 462 512 C 462 410, 392 350, 322 350 L 256 460 L 342 512 Z" fill="#18181b"/><path d="M 100 512 C 115 410, 165 350, 256 470 Z" fill="#27272a"/><path d="M 412 512 C 397 410, 347 350, 256 470 Z" fill="#27272a"/><path d="M 220 260 L 292 260 L 292 360 L 220 360 Z" fill="#f8d7be"/><path d="M 220 320 C 240 350, 272 350, 292 320 L 292 360 L 220 360 Z" fill="#ecc0a2"/><circle cx="182" cy="230" r="22" fill="#f8d7be"/><circle cx="330" cy="230" r="22" fill="#f8d7be"/><path d="M 188 190 C 188 280, 212 315, 256 315 C 300 315, 324 280, 324 190 C 324 120, 300 115, 256 115 C 212 115, 188 120, 188 190 Z" fill="#f8d7be"/><path d="M 148 210 C 148 300, 175 350, 200 360 C 210 320, 204 270, 194 220 C 210 160, 230 135, 275 140 C 310 145, 320 180, 318 220 C 308 270, 302 320, 312 360 C 337 350, 364 300, 364 210 C 364 110, 316 65, 256 65 C 196 65, 148 110, 148 210 Z" fill="#27272a"/><path d="M 152 205 C 152 285, 176 335, 198 345 C 205 310, 200 265, 192 215 C 210 155, 230 135, 272 140 C 305 145, 314 180, 312 215 C 304 265, 299 310, 306 345 C 328 335, 360 285, 360 205 C 360 115, 314 70, 256 70 C 198 70, 152 115, 152 205 Z" fill="#18181b"/></svg>';
        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }
}
