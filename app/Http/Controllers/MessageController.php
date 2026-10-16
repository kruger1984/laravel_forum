<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Events\StoreLikeEvent;
use App\Http\Requests\Complaint\StoreRequest as ComplaintStoreRequest;
use App\Http\Requests\Message\StoreRequest;
use App\Http\Requests\Message\UpdateRequest;
use App\Http\Resources\Message\MessageResource;
use App\Jobs\ProcessMessageJob;
use App\Models\Message;
use App\Service\NotificationService;

class MessageController extends Controller
{
    /**
     * @param  StoreRequest  $request
     *
     * @return array<string, mixed>
     */
    public function store(StoreRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();

        $message = Message::create($data);

        ProcessMessageJob::dispatchSync($data, $message)->afterCommit();

        $message->loadCount('likedUsers');

        return MessageResource::make($message)->resolve();
    }

    public function toggleLike(Message $message): void
    {
        $res = $message->likedUsers()->toggle(auth()->id());
        if ($res['attached']) {
            NotificationService::store($message, null, 'You have liked');
        }
        broadcast(new StoreLikeEvent($message))->toOthers();

    }

    /**
     * @param  ComplaintStoreRequest  $request
     * @param  Message  $message
     *
     * @return array<string, mixed>
     */
    public function storeComplaint(ComplaintStoreRequest $request, Message $message): array
    {
        $data = $request->validated();
        $message->complaintedUsers()->attach(auth()->id(), $data);

        NotificationService::store($message, null, 'You have been complained about');

        $message->is_not_solved_complaint = true;

        return MessageResource::make($message)->resolve();
    }
}
