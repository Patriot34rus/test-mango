<?php
declare(strict_types=1);

namespace App\Base\Controller;

class AController implements IController
{
    protected function response(): Response{
        return new Response();
    }
}