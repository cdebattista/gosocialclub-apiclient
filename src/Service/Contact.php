<?php

namespace GoSocialClub\Service;

Class Contact {

    protected $client;

    public function __construct(\GoSocialClub\ApiClient $client){
        $this->client = $client;
    }

    public function store($query = []){
        $url = "/api";
        $response = $this->client->call("post", $url, $query);
        return $response;
    }

}