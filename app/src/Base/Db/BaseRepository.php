<?php

namespace App\Base\Db;

class BaseRepository
{
    public function __construct(protected readonly IDbConnection $connection)
    {
    }
}