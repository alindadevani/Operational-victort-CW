<?php

return [

    'default' => env('FILESYSTEM_DISK', env('VERCEL') ? 'vercel_tmp' : 'local'),

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app/private'),
            'serve' => true,
            'throw' => false,
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
            'throw' => false,
        ],

        // Disk sementara untuk proses serverless, bukan penyimpanan permanen.
        'vercel_tmp' => [
            'driver' => 'local',
            'root' => env('VERCEL_STORAGE_PATH', '/tmp/vif-storage'),
            'throw' => false,
        ],

    ],

    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],

];
