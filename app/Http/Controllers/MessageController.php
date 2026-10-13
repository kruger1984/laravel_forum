<?php

namespace App\Http\Controllers;

use App\Events\StoreLikeEvent;
use App\Events\StoreMessageEvent;
use App\Http\Requests\Complaint\StoreRequest as ComplaintStoreRequest;
use App\Http\Requests\Message\StoreRequest;
use App\Http\Requests\Message\UpdateRequest;
use App\Http\Resources\Message\MessageResource;
use App\Models\Image;
use App\Models\Message;
use App\Models\Notification;
use App\Models\User;
use App\Service\NotificationService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $data            = $request->validated();
        $data['user_id'] = auth()->id();

        $ids = Str::of($data['content'])
                  ->matchAll('/@[\d]+/')
                  ->unique()
                  ->transform(function ($id) {
                      return Str::of($id)->replaceMatches('/@/', '')->value();
                  })
                  ->filter(function ($id) {
                      return User::where('id', $id)->exists();
                  });


        $imgIds = Str::of($data['content'])
                     ->matchAll('/img_id=[\d]+/')
                     ->unique()
                     ->transform(function ($item) {
                         return Str::of($item)->replaceMatches('/img_id=/', '')->value();
                     });

        $message = Message::create($data);

        broadcast(new StoreMessageEvent($message))->toOthers();

        Image::whereIn('id', $imgIds)->update(['message_id' => $message->id]);

        Image::where('user_id', auth()->id())
             ->whereNull('message_id')
             ->get()
             ->pluck('path')
             ->each(function ($path) {
                 Storage::disk('public')->delete($path);
             });

        Image::where('user_id', auth()->id())
             ->whereNull('message_id')->delete();

        $message->answeredUsers()->attach($ids);

        $ids->each(function ($id) use ($message) {
            NotificationService::store($message, $id, 'You have received a reply.');
        });

        $message->loadCount('likedUsers');

        return MessageResource::make($message)->resolve();
    }

    /**
     * Display the specified resource.
     */
    public function show(Message $message)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Message $message)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Message $message)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Message $message)
    {
        //
    }

    public function toggleLike(Message $message)
    {
        $res = $message->likedUsers()->toggle(auth()->id());
        if($res['attached']) {
            NotificationService::store($message, null, 'You have liked');
        }
        broadcast(new StoreLikeEvent($message))->toOthers();


    }

    public function storeComplaint(ComplaintStoreRequest $request, Message $message)
    {
        $data = $request->validated();
        $message->complaintedUsers()->attach(auth()->id(), $data);

        NotificationService::store($message, null, 'You have been complained about');

        return MessageResource::make($message)->resolve();
    }
}
