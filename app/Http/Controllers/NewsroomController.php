<?php

namespace App\Http\Controllers;

use App\Models\Article;

class NewsroomController extends Controller
{
    public function index()
    {
        // CHALLENGE 3: Retrieve only assignments due tomorrow (one query line).
        $articles = Article::whereDate('deadline', today()->addDay())->orderBy('deadline')->get();

        return view('challenges.newsroom', compact('articles'));
    }
}
