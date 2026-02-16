<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use Illuminate\Http\Request;

/**
 * ArticleController — handles published article listing & detail.
 *
 * Supports:
 * - ?search=keyword → search by title
 * - ?limit=N        → limit results
 */
class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $query = Article::whereNotNull('published_at');

        // Search by title
        if ($search = $request->input('search')) {
            $query->where('title', 'like', "%{$search}%");
        }

        $limit = $request->input('limit', 20);
        $articles = $query->latest('published_at')->take($limit)->get();

        return ArticleResource::collection($articles);
    }

    public function show(Article $article)
    {
        return new ArticleResource($article);
    }
}
