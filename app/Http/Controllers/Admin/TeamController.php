<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TeamMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TeamController extends Controller
{
    public function index()
    {
        $teamMembers = TeamMember::orderBy('sort_order')->orderBy('id')->paginate(20);
        return view('admin.team.index', compact('teamMembers'));
    }

    public function create()
    {
        return view('admin.team.form');
    }

    public function store(Request $request)
    {
        $validated = $this->validateRequest($request);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $this->processAndEncodeImage($request->file('photo'));

            // Secondary disk backup
            $path = $request->file('photo')->store('team', 'public');
            @mkdir(public_path('storage/team'), 0777, true);
            @copy(storage_path('app/public/' . $path), public_path('storage/' . $path));
        }

        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['sort_order'] = (int) $request->input('sort_order', 0);
        $validated['photo_position_x'] = (int) $request->input('photo_position_x', 50);
        $validated['photo_position_y'] = (int) $request->input('photo_position_y', 50);
        $validated['photo_zoom'] = (int) $request->input('photo_zoom', 100);

        TeamMember::create($validated);

        return redirect()->route('admin.team.index')->with('success', 'Team member added successfully.');
    }

    public function edit(TeamMember $team)
    {
        return view('admin.team.form', ['teamMember' => $team]);
    }

    public function update(Request $request, TeamMember $team)
    {
        $validated = $this->validateRequest($request, $team);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $this->processAndEncodeImage($request->file('photo'));

            // Secondary disk backup
            $path = $request->file('photo')->store('team', 'public');
            @mkdir(public_path('storage/team'), 0777, true);
            @copy(storage_path('app/public/' . $path), public_path('storage/' . $path));
        }

        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['sort_order'] = (int) $request->input('sort_order', 0);
        $validated['photo_position_x'] = (int) $request->input('photo_position_x', 50);
        $validated['photo_position_y'] = (int) $request->input('photo_position_y', 50);
        $validated['photo_zoom'] = (int) $request->input('photo_zoom', 100);

        $team->update($validated);

        return redirect()->route('admin.team.index')->with('success', 'Team member updated successfully.');
    }

    public function destroy(TeamMember $team)
    {
        if ($team->photo && !str_starts_with($team->photo, 'http') && Storage::disk('public')->exists($team->photo)) {
            Storage::disk('public')->delete($team->photo);
        }
        $team->delete();

        return redirect()->route('admin.team.index')->with('success', 'Team member deleted successfully.');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->input('ids', []);
        if (is_string($ids)) {
            $ids = array_filter(explode(',', $ids));
        }

        if (empty($ids)) {
            return redirect()->route('admin.team.index')->with('error', 'No team members selected for deletion.');
        }

        $members = TeamMember::whereIn('id', $ids)->get();
        foreach ($members as $member) {
            if ($member->photo && !str_starts_with($member->photo, 'http') && Storage::disk('public')->exists($member->photo)) {
                Storage::disk('public')->delete($member->photo);
            }
            $member->delete();
        }

        return redirect()->route('admin.team.index')->with('success', count($members) . ' team members deleted successfully.');
    }

    private function validateRequest(Request $request, ?TeamMember $team = null): array
    {
        return $request->validate([
            'name'             => 'required|string|max:255',
            'role'             => 'required|string|max:255',
            'gender'           => 'required|in:male,female',
            'bio'              => 'nullable|string|max:2000',
            'photo'            => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:10240',
            'photo_position_x' => 'nullable|integer|between:0,100',
            'photo_position_y' => 'nullable|integer|between:0,100',
            'photo_zoom'       => 'nullable|integer|between:100,250',
            'email'            => 'nullable|email|max:255',
            'phone'            => 'nullable|string|max:50',
            'linkedin'         => 'nullable|url|max:255',
            'sort_order'       => 'nullable|integer|min:0',
        ]);
    }

    private function processAndEncodeImage(\Illuminate\Http\UploadedFile $file): string
    {
        $realPath = $file->getRealPath();

        // If GD extension is loaded, resize & compress to lightweight WebP / JPEG (max 600px width/height, ~35KB)
        if (extension_loaded('gd') && function_exists('imagecreatefromstring')) {
            $image = @imagecreatefromstring(file_get_contents($realPath));
            if ($image !== false) {
                $width = imagesx($image);
                $height = imagesy($image);
                $maxDim = 600;

                if ($width > $maxDim || $height > $maxDim) {
                    if ($width >= $height) {
                        $newWidth = $maxDim;
                        $newHeight = (int) round(($height / $width) * $maxDim);
                    } else {
                        $newHeight = $maxDim;
                        $newWidth = (int) round(($width / $height) * $maxDim);
                    }
                    $resized = imagecreatetruecolor($newWidth, $newHeight);
                    imagealphablending($resized, false);
                    imagesavealpha($resized, true);
                    imagecopyresampled($resized, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
                    imagedestroy($image);
                    $image = $resized;
                }

                ob_start();
                if (function_exists('imagewebp')) {
                    imagewebp($image, null, 80);
                    $mime = 'image/webp';
                } else {
                    imagejpeg($image, null, 82);
                    $mime = 'image/jpeg';
                }
                $imageData = ob_get_clean();
                imagedestroy($image);

                if (!empty($imageData)) {
                    return 'data:' . $mime . ';base64,' . base64_encode($imageData);
                }
            }
        }

        // Fallback to raw base64
        $mime = $file->getMimeType();
        $contents = file_get_contents($realPath);
        return 'data:' . $mime . ';base64,' . base64_encode($contents);
    }
}
