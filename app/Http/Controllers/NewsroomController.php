<?php

namespace App\Http\Controllers;

use App\Models\Article;

class NewsroomController extends Controller
{
    public function index()
    {
        // TODO CHALLENGE 3: Retrieve only assignments due tomorrow (one query line).
        $articles = Article::orderBy('deadline')->get();

        return view('challenges.newsroom', compact('articles'));
    }
}
