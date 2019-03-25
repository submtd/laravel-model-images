<?php

return [
    'allowedMimes' => 'jpeg,png,jpg,gif,svg',
    'maxFileSize' => 10240,
    'imagePath' => 'images',
    'optimizeImage' => true,
    'imageBackground' => '000000', // hex color code used for `fill` type resizes
    'sizes' => [
        'original' => [
            'path' => 'original',
        ],
        'thumbnail' => [
            'path' => 'thumbnail',
            'fit' => 'crop', // can be crop, stretch, fill, max, or contain
            'width' => 100,
            'height' => 100,
        ],
        '250x250' => [
            'path' => '250x250',
            'fit' => 'crop',
            'width' => 250,
            'height' => 250,
        ],
        '400x400' => [
            'path' => '400x400',
            'fit' => 'crop',
            'width' => 400,
            'height' => 400,
        ],
    ],
];
