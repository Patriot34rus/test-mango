<?php
declare(strict_types=1);

namespace App\Base;

use App\Base\Db\IDbConnection;
use App\Service\Calendar\Repository\CalendarRepository;
use App\Service\CalendarService;

class ServiceFactory
{
    public function __construct(private readonly IDbConnection $dbConnection){}

    public function createCalendarService(): CalendarService{
        return new CalendarService($this->createCalendarRepository());
    }

    private function createCalendarRepository(): CalendarRepository{
        return new CalendarRepository($this->dbConnection);
    }
}