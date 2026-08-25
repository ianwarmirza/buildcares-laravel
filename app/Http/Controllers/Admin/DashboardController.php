<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\PortfolioItem;
use App\Models\ContactMessage;
use App\Models\Service;
use App\Models\TeamMember;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'portfolio_count' => PortfolioItem::count(),
            'messages_count'  => ContactMessage::count(),
            'unread_messages' => ContactMessage::where('is_read', false)->count(),
            'services_count'  => Service::count(),
            'team_count'      => TeamMember::count(),
        ];
        $recentMessages = ContactMessage::latest()->limit(5)->get();

        return view('admin.dashboard', compact('stats', 'recentMessages'));
    }
}
