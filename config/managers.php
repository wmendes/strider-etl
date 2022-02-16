<?php

return [
    'extraction' => [
        'default_driver' => env('MANAGER_EXTRACTION_DEFAULT'),
        'drivers' => [
            'stream' => [
                'slug' => 'stream',
                'files' => [
                    'users' => 'users.csv',
                    'movies' => 'movies.csv',
                    'streams' => 'streams.csv',
                    'authors' => 'authors.json',
                    'books' => 'books.json',
                    'reviews' => 'reviews.json'
                ]
            ],
        ],
    ],
];