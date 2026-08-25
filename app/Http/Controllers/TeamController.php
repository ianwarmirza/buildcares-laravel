<?php

namespace App\Http\Controllers;

use App\Models\TeamMember;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function index()
    {
        $teamMembers = TeamMember::active()->orderBy('sort_order')->orderBy('id')->get();
        return view('team.index', compact('teamMembers'));
    }
}
