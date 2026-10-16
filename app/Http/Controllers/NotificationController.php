<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Notification\UpdateCollectionRequest;
use App\Models\Notification;
use Symfony\Component\HttpFoundation\Response;

class NotificationController extends Controller
{
    public function updateCollection(UpdateCollectionRequest $request): Response
    {
        $data = $request->validated();

        Notification::whereIn('id', $data['ids'])->update([
            'is_read' => true,
        ]);

        return response()->json(['count' => auth()->user()->notifications()->count()]);

    }
}
