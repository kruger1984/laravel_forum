<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Section\StoreRequest;
use App\Http\Requests\Section\UpdateRequest;
use App\Http\Resources\Branch\BranchResource;
use App\Http\Resources\Section\SectionResource;
use App\Http\Resources\Section\SectionWithBranchesResource;
use App\Models\Branch;
use App\Models\Section;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class SectionController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $sections = Section::query()->with('parentBranches')->get();

        $sections = SectionWithBranchesResource::collection($sections)->resolve();

        return inertia('Section/Index', compact('sections'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        $this->authorize('create', Section::class);

        return inertia('Section/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request): RedirectResponse
    {
        $this->authorize('create', Section::class);
        $data = $request->validated();

        Section::query()->firstOrCreate($data);

        return Redirect::route('sections.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Section $section): Response
    {
        $this->authorize('update', $section);

        $section = SectionResource::make($section)->resolve();

        return Inertia::render('Section/Edit', compact('section'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Section $section): RedirectResponse
    {
        $this->authorize('update', $section);
        $data = $request->validated();
        $section->update($data);

        return Redirect::route('sections.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Section $section): RedirectResponse
    {
        $this->authorize('delete', $section);
        $section->delete();

        return redirect()->back();
    }

    /**
     * @return array<string, mixed>
     */
    public function branchIndex(Section $section): array
    {
        return BranchResource::collection($section->branches)->resolve();
    }

    /**
     * @return array<string, mixed>
     */
    public function branchIndexExcept(Section $section, Branch $branch): array
    {
        $branches = $section->branches()->where('id', '!=', $branch->id)->get();

        return BranchResource::collection($branches)->resolve();
    }
}
