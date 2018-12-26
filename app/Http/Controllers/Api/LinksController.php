<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\LinksResource;
use App\Models\Link;
use Illuminate\Http\Request;

class LinksController extends Controller
{
    //获取推荐资源
    public function index(Link $link)
    {
        $links = $link->getAllCached();
        return LinksResource::collection($links);
    }
}
