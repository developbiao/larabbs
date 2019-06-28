<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Link;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    public function show(Category $category, Request $request, Topic $topic, User $user, Link $link)
    {
        // read category assoc topic and paginate 20
        $topics = $topic->withOrder($request->order)
            ->where('category_id', $category->id)
            ->paginate(20);
        // active users
        $active_users = $user->getActiveUsers();

        // links
        $links = $link->getAllCached();

        // render variable to category template
        return view('topics.index', compact('topics', 'category', 'active_users', 'links'));
    }
}
