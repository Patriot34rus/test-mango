<?php
declare(strict_types=1);

use App\Base\AppFactory;
use App\Exception\AppRuntimeException;

require __DIR__ . '/vendor/autoload.php';

try{
    $appFactory =  AppFactory::createByDefault();
    $request = $appFactory->createRequest();
    $app = $appFactory->createApp();
    $response = $app->run($request);

    if($response->isSuccess()){
        http_response_code(200);

        header('Content-Type: application/json');
        echo json_encode([
            'data' => $response->getResponse(),
        ]);

        exit;
    }else{
        throw new AppRuntimeException($response->getError());
    }
}catch(AppRuntimeException $e){
    http_response_code($e->getCode());

    header('Content-Type: application/json');
    echo json_encode([
        'status' => $e->getCode(),
        'error'  => $e->getMessage()
    ]);

    exit;
}catch (\Throwable $e){
    http_response_code($e->getCode());
    header('Content-Type: application/json');
    echo json_encode([
        'status' => $e->getCode(),
        'error'  => 'Internal Error'
    ]);

    exit;
}
