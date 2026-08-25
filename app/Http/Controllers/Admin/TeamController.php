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
            $validated['photo'] = $request->file('photo')->store('team', 'public');
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
            if ($team->photo && !str_starts_with($team->photo, 'http') && Storage::disk('public')->exists($team->photo)) {
                Storage::disk('public')->delete($team->photo);
            }
            $validated['photo'] = $request->file('photo')->store('team', 'public');
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
}
