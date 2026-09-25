<?php

return [
    'exports' => [
        'chunk_size' => 1000,
        'pre_calculate_formulas' => false,
        'strict_null_comparison' => false,
        'csv' => [
            'delimiter' => ',',
            'enclosure' => '"',
            'line_ending' => PHP_EOL,
            'use_bom' => false,
            'include_separator_line' => false,
            'excel_compatibility' => false,
        ],
        'properties' => [
            'creator' => 'VIF Operational System',
            'lastModifiedBy' => 'VIF Operational System',
            'title' => 'History Export',
            'description' => '',
            'subject' => '',
            'keywords' => '',
            'category' => '',
            'manager' => '',
            'company' => 'PT Victory International Futures',
        ],
    ],

    'imports' => [
        'read_only' => true,
        'ignore_empty' => false,
        'heading_row' => [
            'formatter' => 'slug',
        ],
        'csv' => [
            'delimiter' => null,
            'enclosure' => '"',
            'escape_character' => '\\',
            'contiguous' => false,
            'input_encoding' => 'guess',
        ],
    ],

    'extension_detector' => [
        'xlsx' => \Maatwebsite\Excel\Excel::XLSX,
        'xlsm' => \Maatwebsite\Excel\Excel::XLSX,
        'xltx' => \Maatwebsite\Excel\Excel::XLSX,
        'xltm' => \Maatwebsite\Excel\Excel::XLSX,
        'xls' => \Maatwebsite\Excel\Excel::XLS,
        'xlt' => \Maatwebsite\Excel\Excel::XLS,
        'ods' => \Maatwebsite\Excel\Excel::ODS,
        'ots' => \Maatwebsite\Excel\Excel::ODS,
        'slk' => \Maatwebsite\Excel\Excel::SLK,
        'xml' => \Maatwebsite\Excel\Excel::XML,
        'gnumeric' => \Maatwebsite\Excel\Excel::GNUMERIC,
        'htm' => \Maatwebsite\Excel\Excel::HTML,
        'html' => \Maatwebsite\Excel\Excel::HTML,
        'csv' => \Maatwebsite\Excel\Excel::CSV,
        'tsv' => \Maatwebsite\Excel\Excel::TSV,
        'pdf' => \Maatwebsite\Excel\Excel::DOMPDF,
    ],

    'value_binder' => [
        'default' => \Maatwebsite\Excel\DefaultValueBinder::class,
    ],

    'cache' => [
        'driver' => 'memory',
    ],

    'transactions' => [
        'handler' => 'db',
    ],

    'temporary_files' => [
        'local_path' => storage_path('framework/cache/laravel-excel'),
        'remote_disk' => null,
        'remote_prefix' => null,
        'force_resync_remote' => null,
    ],
];
