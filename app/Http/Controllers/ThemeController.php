<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Theme\StoreRequest;
use App\Http\Requests\Theme\UpdateRequest;
use App\Http\Resources\Theme\ThemeResource;
use App\Http\Resources\Theme\ThemeWithMessagesResource;
use App\Models\Theme;
use Illuminate\Http\RedirectResponse;
use Inertia\Response;

class ThemeController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request): RedirectResponse
    {
        $data = $request->validated();
        Theme::create($data);

        return redirect()->route('branches.show', $data['branch_id']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Theme $theme): Response
    {
        $theme = ThemeWithMessagesResource::make($theme)->resolve();

        return inertia('Theme/Show', compact('theme'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Theme $theme): Response
    {
        $theme = ThemeResource::make($theme)->resolve();

        return inertia('Theme/Edit', compact('theme'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Theme $theme): RedirectResponse
    {
        $data = $request->validated();
        $theme->update($data);

        return redirect()->route('branches.show', $theme->branch_id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Theme $theme): RedirectResponse
    {
        $theme->delete();

        return redirect()->route('branches.show', $theme->branch_id);
    }
}
