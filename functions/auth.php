<?php 
    require_once __DIR__ . '/../logs/logger/logger.php';

    function authenticate($config) {
        // This should validate credentials and return user data or false
    
        $endpoint = '/user/auth/login'; //dohvatiti iz dokumentacije

        $data = [
            'token' => $config['api_token'],
            'username' => $config['api_user'],
            'password' => $config['api_pass'],
            'remember' => 0
        ];

        logMessage(
            'INFO',
            'API Authentication Attempt',
            [
                'endpoint' => $endpoint,
                'username' => $config['api_user'],

            ],
        );

        $response = apiRequest($endpoint, 'POST', $config, $data);

        if(!$response){
            logMessage(
                'ERROR',
                'API Authentication Failed',
                [
                    'endpoint' => $endpoint,
                    'error' => $response['data']['message'] ?? 'Unknown error',
                ],
            );
            throw new Exception('API Authentication Failed');
        }
        // Extract pkey from response

        $pkey = $response['data']['pkey'];
        $_SESSION['api_pkey'] = $pkey;
        
        logMessage(
            'INFO',
            'API Authentication Successful',
            [
                'endpoint' => $endpoint,
                'status'   => $response['status'],
                'message'  => $response['data']['message'] ?? 'Authentication successful',
            ],
        );

        return $pkey;
    }

    function logoutFromAPI(){
        unset($_SESSION['api_token']);
        unset($_SESSION['api_user']);

        return [
            'status' => 'success',
            'message' => 'User logged out successfully.'
        ];
    }

    function isLoggerIn(){
        return isset($_SESSION['api_token']) && !empty($_SESSION['api_token']);
    }


    function getValidpKey($config){
        if(isset($_SESSION['api_pkey']) && !empty($_SESSION['api_pkey'])){
            
            if(isPkeyValid($_SESSION['api_pkey'], $config)){
                return $_SESSION['api_pkey'];
            }
            else{
                logMessage(
                    'WARRNING',
                    'Pkey expired, re-authenticating',  []
                );
            }
        }
        $newPkey = authenticate($config);

        if($newPkey){
            $_SESSION['api_pkey'] = $newPkey;
            return $newPkey;
        }
        return false;
    }

    function isPkeyValid($pkey, $config){
        //Check if pkey is valid by making a test request
        $testResponse = apiRequest('/test', 'GET', $config, [], $pkey);
        return $testResponse['status'] === 'success';
    }
?>