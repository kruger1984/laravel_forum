<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Message;
use App\Models\Theme;
use App\Models\User;
use Inertia\Response;

class MainController extends Controller
{
    public function index(): Response
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
