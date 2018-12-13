<?php

function route_class()
{
    return str_replace('.', '-', Route::currentRouteName());
}

function get_model(){
    $name = Route::currentRouteName();
    return explode('.',$name)[0];
}

function make_excerpt($value, $length = 200)
{
    $excerpt = trim(preg_replace('/\r\n|\r|\n+/', ' ', strip_tags($value)));
    return str_limit($excerpt, $length);
}
function storage_url($avatar){
    if (preg_match('/^(http)|(HTTP)$/i',$avatar)){
        return $avatar;
    }else{
        return '/storage/'.$avatar;
    }
}