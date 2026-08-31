<?php
use App\Controller\CalendarController;

return [
    'v1/calendar/:year/list' => ['get', CalendarController::class, 'list'],
    'v1/calendar/:year/day/custom/add' => ['put', CalendarController::class, 'addCustom'],
    'v1/calendar/:year/day/custom/edit' => ['patch', CalendarController::class, 'editCustom'],
    'v1/calendar/:year/day/custom/remove' => ['delete', CalendarController::class, 'removeCustom'],
];
