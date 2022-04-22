<?php

return [
    'app_path' => __DIR__ . '/app/Command',
    'debug' => true,
    'devto_username' => getenv('DEVTO_USERNAME') ?: 'erikaheidi',
    'data_path' => getenv('APP_DATA_DIR') ?: __DIR__ . '/devto'
];