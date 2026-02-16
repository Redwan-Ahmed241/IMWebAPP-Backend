<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'excerpt' => $this->excerpt,
            'body' => $this->body,
            'image_url' => $this->image_url,
            'author' => $this->author,
            'published_at' => $this->published_at?->toISOString(),
            'created_at' => $this->created_at,
        ];
    }
}
