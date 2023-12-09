<?php

class Demo
{
    private $url_api;
    private $config;
    private $apikey;
    private $redirect_url;
    
    public function __construct ($apikey, $redirect_url)
    {
        global $config;
        $this->config = $config;
        $this->url_api = $this->config->base_url . '/api/';
        $this->apikey = $apikey;
        $this->redirect_url = $redirect_url;
    }

    public function create ()
    {
        $url = $this->url_api . 'create';
        $data = [
            'apiKey' => $this->apikey,
            'redirect_url' => $this->redirect_url
        ];
        
        $headers = [
            'Content-Type: application/json'
        ];
        
        $ch = curl_init($url);
        
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        
        $response = curl_exec($ch);
        
        if (curl_errno($ch)) {
            echo 'Error en la solicitud cURL: ' . curl_error($ch);
        }
        
        curl_close($ch);
        
        return json_decode($response);
    }

    public function info ($uuid)
    {
        $url = $this->url_api . 'info';
        
        $data = [
            'apiKey' => $this->apikey,
            'uuid' => $uuid
        ];
        
        $headers = [
            'Content-Type: application/json'
        ];
        
        $ch = curl_init($url);
        
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        
        $response = curl_exec($ch);
        
        if (curl_errno($ch)) {
            echo 'Error en la solicitud cURL: ' . curl_error($ch);
        }
        
        curl_close($ch);
        return $response;
        
    }
}