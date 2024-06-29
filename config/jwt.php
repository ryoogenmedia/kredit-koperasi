<?php

return [
    'secret' => env('JWT_SECRET', 'ryoogen-secret-key-kredit-koperasi'),
    'ttl' => env('JWT_TTL', 60), // dalam menit
];
