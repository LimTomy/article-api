<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $articles = Article::all();
        return response()->json([
            'success' => true,
            'articles' => $articles,

        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validateData = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
            'published' => 'boolean',
        ]);

        // enregistrement de l'article en base
        $article = Article::create($validateData);

        //confirmation de l'enregistrement de l'article au format json
        return response()->json([
            'success' => true,
            'message' => 'Article crée succès',
            'article' => $article,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Article $article)
    {
        //
        return response()->json([
            'success' => true,
            'article' => $article,

        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Article $article)
    {
        //
        $validateData = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
            'published' => 'boolean',
        ]);

        // enregistrement de l'article en base
        $article->update($validateData);

        //confirmation de l'enregistrement de l'article au format json
        return response()->json([
            'success' => true,
            'message' => 'Article modifié',
            'article' => $article
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article)
    {
        //
        $article->delete();

        return response()->json([
            'success' => true,
            'message' => 'Article supprimée',
            'article' => $article
        ]);
    }
}
