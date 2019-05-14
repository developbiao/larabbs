<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Topic;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    public function show(Category $category)
    {
        // read category assoc topic and paginate 20
        $topics = Topic::where('category_id', $category->id)->paginate(20);
        // render variable to category template
        return view('topics.index', compact('topics', 'category'));
    }
}
