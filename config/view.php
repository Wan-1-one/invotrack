<?php

return [

    /*
    |--------------------------------------------------------------------------
    | View Storage Paths
    |--------------------------------------------------------------------------
    |
    | Most templating systems load templates from disk from a folder stored in
    | the application. Here you may specify a list of paths that should be
    | checked for your views. Of course, the usual Laravel path is already
    | registered for you: resources/views.
    |
    */

    'paths' => [
        resource_path('views'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Compiled View Cache
    |--------------------------------------------------------------------------
    |
    | This option determines if view compilation will be cached on disk. By
    | default Laravel will cache the compiled views to improve performance
    | and reduce the need to re-compile them on each request. If you need
    | to clear the view cache you may use the "view:clear" command.
    |
    */

    'compiled' => env(
        'VIEW_COMPILED_PATH',
        realpath(storage_path('framework/views'))
    ),

];
