<?php
declare(strict_types=1);

namespace App\Controller;

use App\Base\Controller\AController;
use App\Base\Controller\IController;
use App\Base\Controller\Request;
use App\Base\Controller\Response;
use App\Exception\ErrorAppRuntimeException;
use App\Model\Enum\CalendarDayCustomType;
use App\Service\CalendarService;

class CalendarController extends AController
{
    public function __construct(
        private readonly CalendarService $calendarService
    )
    {

    }
    public function list(Request $request): Response{
        $year = (int) $request->getParameter('year');
        $calendarCollection = $this->calendarService->getCalendarDays($year);

        return $this->response()->success($calendarCollection->toArray());
    }

    public function addCustom(Request $request): Response{
        $date = $this->createDate($request->getParameter('date'));
        $comment = (string) $request->getParameter('comment');
        $type = $this->createType((int)$request->getParameter('type'));

        $result = $this->calendarService->addCalendarDay($date, $type, $comment);

        return $this->response()->success($result);
    }

    public function editCustom(Request $request): Response{
        $date = $this->createDate($request->getParameter('date'));
        $comment = (string) $request->getParameter('comment');
        $type = $this->createType((int)$request->getParameter('type'));

        $result = $this->calendarService->updateCalendarDay($date, $type, $comment);

        return $this->response()->success($result);
    }

    public function removeCustom(Request $request): Response{
        $date = $this->createDate($request->getParameter('date'));

        $result = $this->calendarService->deleteCalendarDay($date);

        return $this->response()->success($result);
    }

    // проверки ниже должны быть в валидаторе
    private function createDate(mixed $value): \DateTimeImmutable
    {
        if (!is_string($value)) {
            throw new ErrorAppRuntimeException('Date must be specified in Y-m-d format.', 400);
        }

        $date = \DateTimeImmutable::createFromFormat('Y-m-d', $value);
        $errors = \DateTimeImmutable::getLastErrors();

        if ($date === false || ($errors !== false && ($errors['warning_count'] > 0 || $errors['error_count'] > 0))) {
            throw new ErrorAppRuntimeException('Date must be specified in Y-m-d format.', 400);
        }

        return $date;
    }

    private function createType(mixed $value): CalendarDayCustomType
    {
        if (!is_int($value)) {
            throw new ErrorAppRuntimeException('Calendar day type must be an integer.', 400);
        }

        $type = CalendarDayCustomType::tryFrom((int) $value);

        if ($type === null) {
            throw new ErrorAppRuntimeException('Unknown calendar day type.', 400);
        }

        return $type;
    }
}
