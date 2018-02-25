<?php
/**
 * Created by PhpStorm.
 * User: Speed
 * Date: 03/02/2018
 * Time: 01:31
 */

namespace App\Service;

use Symfony\Component\BrowserKit\Client;

/**
 * Class APIService
 * @package App\Service
 */
class APIService {

    /**
     * @return \Psr\Http\Message\StreamInterface
     */
    public function callPoloniexApi() {
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', 'https://poloniex.com/public?command=returnTicker');
        if($response->getStatusCode() == 200) {
            return $response->getBody();
        }
    }
}