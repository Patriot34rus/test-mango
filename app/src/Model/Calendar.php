<?php
declare(strict_types=1);

namespace App\Model;

use App\Exception\ErrorAppRuntimeException;
use App\Model\Enum\CalendarDayCustomType;

class Calendar implements \JsonSerializable
{
    private ?int $id = null;
    private \DateTimeInterface $date;
    private ?CalendarDayCustomType $type = null;
    private ?string $comment = null;
    public function __construct(
        \DateTimeInterface $date,
        ?CalendarDayCustomType $type = null,
        ?string $comment = null,
        ?int $id = null
    ){
        $this->date = $date;
        $this->type = $type;
        $this->comment = $comment;
        $this->id = $id;
    }

    public function getId(): ?int{
        return $this->id;
    }

    public function setId(int $id): self{
        $this->id = $id;
        return $this;
    }

    public function getDate(): \DateTimeInterface
    {
        return $this->date;
    }

    public function getType(): ?CalendarDayCustomType
    {
        return $this->type;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'date' => $this->date->format('Y-m-d'),
            'type' => $this->type?->value,
            'description' => $this->comment,
        ];
    }

    public static function fromArray(array $data): self
    {
        if (!isset($data['date']) || !is_string($data['date'])) {
            throw new \RuntimeException('Calendar date is required.');
        }

        $id = isset($data['id']) ? (int) $data['id'] : null;
        $date = new \DateTimeImmutable($data['date']);
        $type = self::convertTypeIntToCalendarDayCustomTypeEnum($data['day'] ?? null);
        $description = isset($data['comment']) ? (string) $data['comment'] : null;

        return new self($date, $type, $description, $id);
    }

    private static function convertTypeIntToCalendarDayCustomTypeEnum(mixed $value): ?CalendarDayCustomType
    {
        if ($value === null || $value === '') {
            return null;
        }

        if ($value instanceof CalendarDayCustomType) {
            return $value;
        }

        if (is_int($value)) {
            $type = CalendarDayCustomType::tryFrom($value);

            if ($type !== null) {
                return $type;
            }
        }

        throw new \RuntimeException('Unexpected calendar day type.');
    }
}
