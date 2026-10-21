<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Complaint\ComplaintResource;
use App\Models\Complaint;
use App\Models\Message;
use App\Service\NotificationService;
use Inertia\Response;

class ComplaintController extends Controller
{
    public function index(): Response
    {
        $complaints = Complaint::all();
        $complaints = ComplaintResource::collection($complaints)->resolve();

        return inertia('Admin/Complaint/Index', compact('complaints'));
    }

    /**
     * @return array<string, mixed>
     */
    public function update(Complaint $complaint): array
    {
        $complaint->update([
            'is_solved' => ! $complaint->is_solved,
        ]);

        $title = $complaint->is_solved ? 'Your complaint is still under review' : 'Your complaint has been reviewed';

        if ($complaint->message instanceof Message) {
            NotificationService::store($complaint->message, $title, $complaint->user_id);
        }

        return ComplaintResource::make($complaint)->resolve();
    }
}
