<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\UpdateRequest;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Image;
use Intervention\Image\ImageManager;

class UserController extends Controller
{
    public function personal()
    {
        $user = UserResource::make(auth()->user())->resolve();

        return inertia('User/Personal', compact('user'));
    }

    /**
     * @throws InvalidArgumentException
     */
    public function update(UpdateRequest $request)
    {
        $data = $request->validated();

        $path = Storage::disk('public')->put('avatars', $data['avatar']);

        $absolutePath = Storage::disk('public')->path($path);

        $manager = ImageManager::usingDriver(Driver::class);
        $manager->decode($absolutePath)
                ->cover(200, 200)
                ->save($absolutePath);

        if(auth()->user()->avatar) {
            Storage::disk('public')->delete(auth()->user()->avatar);
        }

        auth()->user()->update([
            'avatar' => $path
        ]);

        return UserResource::make(auth()->user())->resolve();
    }
}
