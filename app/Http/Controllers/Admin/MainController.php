<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Message;
use App\Models\Theme;
use App\Models\User;
use Illuminate\Http\Request;

class MainController extends Controller
{
    public function index()
    {
        $stats = [
            [
                'title' => 'Branches',
                'count' => Branch::count(),
            ],
            [
                'title' => 'Themes',
                'count' => Theme::count(),
            ],
            [
                'title' => 'Messages',
                'count' => Message::count(),
            ],
            [
                'title' => 'Users',
                'count' => User::count(),
            ],
        ];
        return inertia('Admin/Main/Index', compact('stats'));
    }
}
