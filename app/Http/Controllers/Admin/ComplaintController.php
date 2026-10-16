<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Complaint\ComplaintResource;
use App\Models\Complaint;
use App\Service\NotificationService;
use Inertia\Response;
use Inertia\ResponseFactory;

class ComplaintController extends Controller
{
    public function index(): Response
    {
        $complaints = Complaint::all();
        $complaints = ComplaintResource::collection($complaints)->resolve();

        return inertia('Admin/Complaint/Index', compact('complaints'));
    }

    /**
     * @param  Complaint  $complaint
     *
     * @return array<string, mixed>
     */
    public function update(Complaint $complaint): array
    {
        $complaint->update([
            'is_solved' => ! $complaint->is_solved,
        ]);

        $title = $complaint->is_solved ? 'Your complaint is still under review' : 'Your complaint has been reviewed';

        if ($complaint->message instanceof \App\Models\Message) {
            NotificationService::store($complaint->message, $complaint->user_id, $title);
        }

        return ComplaintResource::make($complaint)->resolve();
    }
}
