<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\User\UpdateRequest;
use App\Http\Resources\User\UserResource;
use Illuminate\Support\Facades\Storage;
use Inertia\Response;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\ImageManager;
use RuntimeException;

class UserController extends Controller
{
    public function personal(): Response
    {
        $user = UserResource::make(auth()->user())->resolve();

        return inertia('User/Personal', compact('user'));
    }

    /**
     * @param  UpdateRequest  $request
     *
     * @return array<string, mixed>
     *
     * @throws RuntimeException|InvalidArgumentException
     */
    public function update(UpdateRequest $request): array
    {
        $data = $request->validated();

        $path = Storage::disk('public')->put('avatars', $data['avatar']);

        if (! is_string($path)) {
            throw new RuntimeException('Failed to store uploaded avatar file.');
        }

        $absolutePath = Storage::disk('public')->path($path);

        $manager = ImageManager::usingDriver(Driver::class);
        $manager->decode($absolutePath)
            ->cover(200, 200)
            ->save($absolutePath);

        if (auth()->user()->avatar) {
            Storage::disk('public')->delete(auth()->user()->avatar);
        }

        auth()->user()->update([
            'avatar' => $path,
        ]);

        return UserResource::make(auth()->user())->resolve();
    }
}
