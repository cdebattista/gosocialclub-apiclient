<?php

namespace GoSocialClub;

Class ErrorHandler {

    protected $api;

    public function __construct($api)
    {
        $this->api = $api;
    }

    public function error(){
        if(isset($this->api->getResponse()->error) && $this->api->getResponse()->error){
            return true;
        }
        return false;
    }

    public function getMessage(){
        if($this->error()){
            return $this->api->getResponse()->info;
        }
        return false;
    }

    public function getArgs(){
        if($this->error() && isset($this->api->getResponse()->args)){
            return $this->api->getResponse()->args;
        }
        return false;
    }

    public function debug(){
        if($this->error()){
            $response = [
                'data' => [
                    'url'               => $this->api->getUrl(),
                    'method'            => $this->api->getMethod(),
                    'query'             => $this->api->getQuery(),
                    'header'            => $this->api->getHeader(),
                    'message'           => $this->getMessage(),
                    'args'              => $this->getArgs(),
                ]
            ];
            $response = json_encode($response, JSON_FORCE_OBJECT|JSON_PRETTY_PRINT);
            $response = json_decode($response, false);
            return $response;
        }
        return false;
    }

    public function statusCodeHandling($e){

        if($e->getResponse()) {
            $response = json_decode($e->getResponse()->getBody(true)->getContents());
            if(isset($response->error)) {
                return $response;
            }
        }
        //HERE API_URL CANNOT BE REACH
        $response = [
            'info'       => $e->getMessage(),
            'error'     => true
        ];
        $response = json_encode($response, JSON_FORCE_OBJECT|JSON_PRETTY_PRINT);
        $response = json_decode($response, false);

        return $response;
    }
}