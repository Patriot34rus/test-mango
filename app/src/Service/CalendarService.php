<?php
declare(strict_types=1);

namespace App\Service;

use App\Exception\ErrorAppRuntimeException;
use App\Model\Calendar;
use App\Model\CalendarCollection;
use App\Model\Enum\CalendarDayCustomType;
use App\Service\Calendar\Repository\CalendarRepository;

class CalendarService
{
    public function __construct(private readonly CalendarRepository $repository){}

    public function getCalendarDays(int $year): CalendarCollection{
        return $this->repository->getCalendarDays($year);
    }

    public function addCalendarDay(\DateTimeInterface $date, CalendarDayCustomType $type, string $comment):Calendar{
        $day = $this->repository->getByDate($date);

        if($day){
            throw new ErrorAppRuntimeException('Date already exists');
        }

        return $this->repository->addDay($date, $type, $comment);
    }

    public function updateCalendarDay(\DateTimeInterface $date, CalendarDayCustomType $type, string $comment): Calendar
    {
        $day = $this->repository->getByDate($date);

        if ($day === null || $day->getId() === null) {
            throw new ErrorAppRuntimeException('Calendar day was not found.');
        }

        $updatedDay = $this->repository->updateDay($day->getId(), $type, $comment);

        if ($updatedDay === null) {
            throw new ErrorAppRuntimeException('Calendar day was not found after update.');
        }

        return $updatedDay;
    }

    public function deleteCalendarDay(\DateTimeInterface $date): bool
    {
        $day = $this->repository->getByDate($date);

        if ($day === null || $day->getId() === null) {
            return false;
        }

        return $this->repository->deleteDay($day->getId());
    }
}
