<?php

namespace App\Base\Controller;

class Response
{
    private mixed $response;
    private bool $success = true;
    private ?string $error = null;
    public function success($data = null){
        $this->success = true;
        $this->response = $data;
        return $this;
    }

    public function error($error){
        $this->error = $error;
        $this->success = false;
        return $this;
    }

    public function getResponse(){
        return $this->response;
    }

    public function isSuccess(){
        return $this->success;
    }
    public function getError(){
        return $this->error;
    }
}