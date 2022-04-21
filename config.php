<?php

return [
    'app_path' => __DIR__ . '/app/Command',
    'debug' => true,
    'devto_username' => getenv('DEVTO_USERNAME') ?: 'erikaheidi',
    'data_path' => __DIR__ . '/data'
];