<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Events\StoreMessageEvent;
use App\Models\Image;
use App\Models\User;
use App\Service\NotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProcessMessageJob implements ShouldQueue
{
    use Queueable;

    private $data;

    private $message;

    /**
     * Create a new job instance.
     */
    public function __construct($data, $message)
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

        Image::cleanFromStorage();

        Image::cleanFromTable();

        $this->message->answeredUsers()->attach($ids);

        $ids->each(function ($id): void {
            NotificationService::store($this->message, $id, 'You have received a reply.');
        });
    }
}
