<?php
return [
    'api_url'        => env('API_URL', 'You must configure API_URL env variable !'),
    'default_rating' => env('DEFAULT_RATING', 1500),
    'min_rating'     => env('MIN_RATING', 300),
    'k_repartition'  => json_decode(env('K_REPARTITION', '{"2400": 20, "2000": 30, "1000": 50, "0": 80}'), true),
];
