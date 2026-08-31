<?php
declare(strict_types=1);

namespace App\Model\Enum;

enum CalendarDayCustomType: int
{
   case Sunday = 1;
   case Monday = 2;
   case Tuesday = 3;
   case Wednesday = 4;
   case Thursday = 5;
   case Friday = 6;
   case Saturday = 7;
   case Shortened = 8;
   case Holiday = 9;
}
