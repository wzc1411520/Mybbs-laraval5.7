<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    //获取分类信息
    public function index()
    {
       return CategoryResource::collection(Category::all());
    }
}
