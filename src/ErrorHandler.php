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
        return null;
    }

    public function getFile(){
        if($this->error() && isset($this->api->getResponse()->file)){
            return $this->api->getResponse()->file;
        }
        return null;
    }

    public function getLine(){
        if($this->error() && isset($this->api->getResponse()->line)){
            return $this->api->getResponse()->line;
        }
        return null;
    }

    public function getServerIp(){
        if($this->error() && isset($this->api->getResponse()->server_ip)){
            return $this->api->getResponse()->server_ip;
        }
        return null;
    }

    public function getServerName(){
        if($this->error() && isset($this->api->getResponse()->server_name)){
            return $this->api->getResponse()->server_name;
        }
        return null;
    }

    public function getServerScript(){
        if($this->error() && isset($this->api->getResponse()->server_script)){
            return $this->api->getResponse()->server_script;
        }
        return null;
    }

    public function getArgs(){
        if($this->error() && isset($this->api->getResponse()->args)){
            return $this->api->getResponse()->args;
        }
        return null;
    }

    public function debug(){
        if($this->error()){
            $response = [
                'data' => [
                    'url'               => $this->api->getUrl(),
                    'method'            => $this->api->getMethod(),
                    'query'             => $this->api->getQuery(),
                    'header'            => $this->api->getHeader(),
                    'error'             => $this->error(),
                    'message'           => $this->getMessage(),
                    'file'              => $this->getFile(),
                    'line'              => $this->getLine(),
                    'args'              => $this->getArgs(),
                    'server_ip'         => $this->getServerIp(),
                    'server_name'       => $this->getServerName(),
                    'server_script'     => $this->getServerScript(),
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
            'info'              => $e->getMessage(),
            'file'              => $e->getFile(),
            'line'              => $e->getLine(),
            'server_ip'         => isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : null,
            'server_name'       => isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : null,
            'server_script'     => isset($_SERVER['SCRIPT_FILENAME']) ? $_SERVER['SCRIPT_FILENAME'] : null,
            'error'             => true,
        ];
        $response = json_encode($response, JSON_FORCE_OBJECT|JSON_PRETTY_PRINT);
        $response = json_decode($response, false);

        return $response;
    }
}