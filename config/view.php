<?php

return [

    'paths' => [
        resource_path('views'),
    ],

    // Vercel hanya menyediakan filesystem sementara di /tmp.
    'compiled' => env(
        'VIEW_COMPILED_PATH',
        env('VERCEL') ? '/tmp/vif-compiled-views' : storage_path('framework/views')
    ),

];