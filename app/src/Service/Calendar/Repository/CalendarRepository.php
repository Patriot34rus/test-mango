<?php
declare(strict_types=1);

namespace App\Service\Calendar\Repository;

use App\Base\Db\BaseRepository;
use App\Model\Calendar;
use App\Model\CalendarCollection;
use App\Model\Enum\CalendarDayCustomType;

class CalendarRepository extends BaseRepository
{
    private const TABLE_NAME = 'calendar';
    public const DATE_FORMATE = 'Y-m-d';

    public function getCalendarDays(int $year): CalendarCollection
    {
        $rows = $this->connection->select(
            'SELECT * FROM calendar WHERE YEAR(date) = :year',
            ['year' => $year]
        );

        return $this->createCalendarCollectionFromArray($rows);
    }

    public function addDay(
        \DateTimeInterface $date,
        CalendarDayCustomType $type,
        ?string $comment
    ): Calendar {
        $id = $this->connection->insert(
            'INSERT INTO '.self::TABLE_NAME.' (date, day, comment) VALUES (:date, :day, :comment)',
            [
                'date' => $date->format(self::DATE_FORMATE),
                'day' => $type->value,
                'comment' => $comment,
            ]
        );

        return new Calendar($date, $type, $comment, $id);
    }

    public function deleteDay(int $id): bool
    {
        return $this->connection->delete(
            'DELETE FROM '.self::TABLE_NAME.' WHERE id = :id',
            ['id' => $id]
        ) === 1;
    }

    public function updateDay(int $id, CalendarDayCustomType $type, ?string $comment): ?Calendar
    {
        $this->connection->update(
            'UPDATE '.self::TABLE_NAME.' SET day = :day, comment = :comment WHERE id = :id',
            [
                'id' => $id,
                'day' => $type->value,
                'comment' => $comment,
            ]
        );

        return $this->getById($id);
    }

    public function getByDate(\DateTimeInterface $date): ?Calendar
    {
        $rows = $this->connection->select(
            $this->selectCalendar().' WHERE date = :date LIMIT 1',
            ['date' => $date->format(self::DATE_FORMATE)]
        );

        return isset($rows[0]) ? $this->createCalendarDayFromArray($rows[0]) : null;
    }

    private function getById(int $id): ?Calendar
    {
        $rows = $this->connection->select(
            $this->selectCalendar().' WHERE id = :id LIMIT 1',
            ['id' => $id]
        );

        return isset($rows[0]) ? $this->createCalendarDayFromArray($rows[0]) : null;
    }

    private function selectCalendar(): string
    {
        return 'SELECT id, date, day AS type, comment FROM '.self::TABLE_NAME;
    }

    private function createCalendarDayFromArray(array $data): Calendar
    {
        return Calendar::fromArray($data);
    }

    private function createCalendarCollectionFromArray(array $data): CalendarCollection
    {
        $calendarCollection = new CalendarCollection();
        foreach ($data as $day) {
            $calendarCollection->add($this->createCalendarDayFromArray($day));
        }

        return $calendarCollection;
    }
}
