<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Events\StoreLikeEvent;
use App\Http\Requests\Complaint\StoreRequest as ComplaintStoreRequest;
use App\Http\Requests\Message\StoreRequest;
use App\Http\Resources\Message\MessageResource;
use App\Jobs\ProcessMessageJob;
use App\Models\Message;
use App\Service\NotificationService;

class MessageController extends Controller
{
    /**
     * @return array<string, mixed>
     */
    public function store(StoreRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();

        $message = Message::create($data);

        ProcessMessageJob::dispatchSync($data, $message);

        $message->loadCount('likedUsers');

        return MessageResource::make($message)->resolve();
    }

    public function toggleLike(Message $message): void
    {
        $res = $message->likedUsers()->toggle(auth()->id());
        if ($res['attached']) {
            NotificationService::store($message, 'You have liked');
        }
        broadcast(new StoreLikeEvent($message))->toOthers();

    }

    /**
     * @return array<string, mixed>
     */
    public function storeComplaint(ComplaintStoreRequest $request, Message $message): array
    {
        $data = $request->validated();
        $message->complaintedUsers()->attach(auth()->id(), $data);

        NotificationService::store($message, 'You have been complained about');

        $message->is_not_solved_complaint = true;

        return MessageResource::make($message)->resolve();
    }
}
