<?php
declare(strict_types=1);

namespace App\Base\Controller;

use App\Base\Db\IDbConnection;
use App\Base\ServiceFactory;
use App\Controller\CalendarController;
use App\Exception\AppRuntimeException;
use App\Exception\ErrorAppRuntimeException;
use App\Service\CalendarService;
use \RuntimeException;

class ControllerFactory
{
    public function __construct(private readonly ServiceFactory $serviceFactory)
    {

    }
    public function createController(string $controller): IController{
        try{
            return match($controller){
                CalendarController::class => $this->createCalendarController(),
                default => throw new AppRuntimeException(sprintf("Controller %s does not exist.", $controller), 404)
            };
        }catch(\Throwable $exception){
             throw new ErrorAppRuntimeException('Controller error: '.$exception->getMessage(), 500, $exception);
        }
    }

    public function createCalendarController(): CalendarController{
        return new CalendarController(
            $this->serviceFactory->createCalendarService(),
        );
    }
}