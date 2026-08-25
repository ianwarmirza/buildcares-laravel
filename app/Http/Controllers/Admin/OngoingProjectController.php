<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OngoingProject;
use Illuminate\Http\Request;

class OngoingProjectController extends Controller
{
    public function index()
    {
        $projects = OngoingProject::orderBy('sort_order')->orderByDesc('id')->paginate(15);
        return view('admin.ongoing-projects.index', compact('projects'));
    }

    public function create()
    {
        return view('admin.ongoing-projects.form', ['project' => new OngoingProject()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'site_address' => 'required|string|max:255',
            'proposal'     => 'required|string|max:255',
            'status'       => 'nullable|string|max:100',
            'sort_order'   => 'nullable|integer',
            'is_active'    => 'boolean',
        ]);

        $data['status'] = $data['status'] ?? 'In Progress';
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['is_active'] = $request->has('is_active');

        OngoingProject::create($data);

        return redirect()->route('admin.ongoing-projects.index')->with('success', 'Ongoing project added successfully!');
    }

    public function edit(OngoingProject $ongoingProject)
    {
        return view('admin.ongoing-projects.form', ['project' => $ongoingProject]);
    }

    public function update(Request $request, OngoingProject $ongoingProject)
    {
        $data = $request->validate([
            'site_address' => 'required|string|max:255',
            'proposal'     => 'required|string|max:255',
            'status'       => 'nullable|string|max:100',
            'sort_order'   => 'nullable|integer',
            'is_active'    => 'boolean',
        ]);

        $data['status'] = $data['status'] ?? 'In Progress';
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['is_active'] = $request->has('is_active');

        $ongoingProject->update($data);

        return redirect()->route('admin.ongoing-projects.index')->with('success', 'Ongoing project updated successfully!');
    }

    public function destroy(OngoingProject $ongoingProject)
    {
        $ongoingProject->delete();
        return redirect()->route('admin.ongoing-projects.index')->with('success', 'Ongoing project deleted.');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->input('ids', []);
        if (!empty($ids)) {
            OngoingProject::whereIn('id', $ids)->delete();
        }
        return redirect()->route('admin.ongoing-projects.index')->with('success', count($ids) . ' projects deleted.');
    }
}
