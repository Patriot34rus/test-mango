<?php
declare(strict_types=1);

namespace App\Model;

class CalendarCollection
{
    private  array $days = [];
    public function add(Calendar $createCalendarDayFromArray)
    {
        $this->days[] = $createCalendarDayFromArray;
    }

    public function toArray(): array{
        return $this->days;
    }

    public static function fromArray(array $data): CalendarCollection{
        $calendarCollection = new CalendarCollection();
        foreach($data as $day){
            $calendarCollection[] = Calendar::fromArray($day);
        }

        return $calendarCollection;
    }
}