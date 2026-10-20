<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Events\StoreMessageEvent;
use App\Models\Image;
use App\Models\Message;
use App\Models\User;
use App\Service\NotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProcessMessageJob implements ShouldQueue
{
    use Queueable;

    /**
     * @var array<string, mixed>
     */
    private array $data;

    private Message $message;

    /**
     * Create a new job instance.
     *
     * @param  array<string, mixed>  $data
     */
    public function __construct(array $data, Message $message)
    {
        $this->data = $data;
        $this->message = $message;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $ids = User::getCleanedUserId($this->data);

        $imgIds = getByPatternId($this->data, '/img_id=[\d]+/', '/img_id=/');

        broadcast(new StoreMessageEvent($this->message))->toOthers();

        Image::updateMessageId($imgIds, $this->message);

        Image::query()->cleanFromStorage($this->message->user_id);

        Image::query()->cleanFromTable($this->message->user_id);

        $this->message->answeredUsers()->attach($ids);

        $ids->each(function ($id): void {
            NotificationService::store($this->message, (int) $id, 'You have received a reply.');
        });
    }
}
