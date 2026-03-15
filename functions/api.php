<?php

    function apiRequest($endpoint, $method, $config, $data = null, $headers = []){
        $url = rtrim($config['api_url'],'/').'/'.ltrim($endpoint,'/');

        $ch = curl_init($url);

        $dafaultHeaders = [
            'Content-Type: application/json',
        ];
        
        $headers = array_merge($dafaultHeaders, $headers);
        curl_setopt_array($ch, 
        [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_TIMEOUT        => 30
        ]);

        if($data != null){
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }

        $response = curl_exec($ch);

        // cURL error
        if(curl_errno($ch)){
            $error = curl_error($ch);
            curl_close($ch);
            return [
                'success' => false,
                'error' => $error
            ];
        }

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        //deprecated u PHP 8.5 but I leave to show that connection to the API need to be closed
        //alternative is unset($ch);
        curl_close($ch);

        $decoded = json_decode($response, true);
        return [
            'success' => ($httpCode >= 200 && $httpCode < 300),
            'status' => $httpCode,
            'data' => $decoded ?? 'empty',
            'raw' => $response
        ];
    }
?>