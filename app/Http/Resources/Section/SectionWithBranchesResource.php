<?php

declare(strict_types=1);

namespace App\Http\Resources\Section;

use App\Http\Resources\Branch\BranchResource;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Section
 */
class SectionWithBranchesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'branches' => BranchResource::collection($this->parentBranches)->resolve(),
        ];
    }
}
