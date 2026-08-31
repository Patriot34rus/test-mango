<?php
return [
    'db' => [
        'host' => getenv('DATABASE_HOST'),
        'user' => getenv('DATABASE_USER'),
        'pass' => getenv('DATABASE_PASSWORD'),
        'db' => getenv('DATABASE_DB'),
        'charset' => 'utf8',
    ]
];