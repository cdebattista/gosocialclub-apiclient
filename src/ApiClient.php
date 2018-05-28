<?php
namespace GoSocialClub;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

Class ApiClient {

    protected $client;
    protected $base_key;
    protected $api_url;
    protected $url;
    protected $method;
    protected $query;
    protected $header;
    protected $response;
    protected $error;
    protected $logger;
    protected $log_error;
    protected $path_log_error;
    protected $log_info;
    protected $path_log_info;

    public function __construct($options = []){
        $this->client           = new Client([
            'defaults' => [
                'verify' => false
            ]
        ]);
        $this->error            = new ErrorHandler($this);
        $this->logger           = new Logger($this);

        $this->base_key          = array_key_exists('base_key', $options) ? $options['base_key'] : null;
        $this->api_url          = array_key_exists('api_url', $options) ? $options['api_url'] : 'http://gosocialclub.com';
        $this->log_error        = array_key_exists('log_error', $options) ? $options['log_error'] : true;
        $this->path_log_error   = array_key_exists('path_log_error', $options) ? $options['path_log_error'] : null;
        $this->log_info         = array_key_exists('log_info', $options) ? $options['log_info'] : false;
        $this->path_log_info    = array_key_exists('path_log_info', $options) ? $options['path_log_info'] : null;
    }

    public function errorHandler(){
        return $this->error;
    }

    public function logger(){
        return $this->logger;
    }

    public function getUrl(){
        return $this->url;
    }

    public function getMethod(){
        return $this->method;
    }

    public function getQuery(){
        return $this->query;
    }

    public function getHeader(){
        return $this->header;
    }

    public function getResponse(){
        return $this->response;
    }

    public function setBaseKey($base_key){
        $this->base_key = $base_key;
    }

    public function setApiUrl($api_url){
        $this->api_url = $api_url;
    }

    public function getPathLogError(){
        return $this->path_log_error;
    }

    public function setPathLogError($path_log_error){
        $this->path_log_error = $path_log_error;
    }

    public function getPathLogInfo(){
        return $this->path_log_info;
    }

    public function setPathLogInfo($path_log_info){
        $this->path_log_info = $path_log_info;
    }

    public function call($method, $request, $query = []){
        try {
            $this->url      = $this->api_url . $request;
            $this->query    = array_merge(['base_key' => $this->base_key], $query);
            $this->method   = $method;
            $this->header   = [
                'Accept'            => 'application/x-www-form-urlencoded',
                'Content-Type'      => 'application/x-www-form-urlencoded'
            ];

            $this->response = $this->client->createRequest($this->method, $this->url, ['body' => $this->query, 'headers' => $this->header]);
            $this->response = $this->client->send($this->response);
            $this->response = json_decode($this->response->getBody()->getContents());
            if($this->log_info) {
                $this->logger->RequestInformation($this->response);
            }

            return $this->response;
        } catch (RequestException $e){
            $this->response = $this->error->StatusCodeHandling($e);
            if($this->log_error) {
                $this->logger->RequestException();
            }
            return $this->response;
        }

    }
}