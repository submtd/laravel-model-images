<?php

return [
    'allowedMimes' => 'jpeg,png,jpg,gif,svg',
    'maxFileSize' => 10240,
    'imagePath' => storage_path('app/public/images'),
    'imageFormat' => 'jpg', // jpg, pjpg, png, gif
    'optimizeImage' => true,
    'imageBackground' => '000000', // hex color code used for `fill` type resizes
    'keepOriginalImage' => false,
    'sizes' => [
        'thumbnail' => [
            'fit' => 'crop', // can be crop, stretch, fill, max, or contain
            'width' => 100,
            'height' => 100,
        ],
        '250x250' => [
            'fit' => 'crop',
            'width' => 250,
            'height' => 250,
        ],
        '400x400' => [
            'fit' => 'crop',
            'width' => 400,
            'height' => 400,
        ],
        '800x800' => [
            'fit' => 'contain',
            'width' => 800,
            'height' => 800,
        ]
    ],
];
