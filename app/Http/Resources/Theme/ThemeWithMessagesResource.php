<?php

declare(strict_types=1);

namespace App\Http\Resources\Theme;

use App\Http\Resources\Message\MessageResource;
use App\Models\Message;
use App\Models\Theme;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Theme
 */
class ThemeWithMessagesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $msgIds = auth()->user()->likedMessages()->pluck('id');

        $this->messages->each(function (Message $message) use ($msgIds): void {
            $message->is_liked = $msgIds->contains($message->id);
        });

        $complaintedMsgIds = auth()->user()->complaintedMessages()->pluck('id');

        $this->messages->each(function (Message $message) use ($complaintedMsgIds): void {
            $message->is_not_solved_complaint = $complaintedMsgIds->contains($message->id);
        });

        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'branch_id' => $this->branch_id,
            'messages' => MessageResource::collection($this->messages->load('user'))->resolve(),
        ];
    }
}
