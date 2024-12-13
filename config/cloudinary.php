<?php

return [
    'cloud_url' => env('CLOUDINARY_URL'),
    'upload' => [
        'folder' => 'default-folder', // Default folder for uploads
        'quality' => 'auto',          // Adjust quality automatically
        'crop' => 'fill',             // Default crop mode
    ],
];
