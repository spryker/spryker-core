<?php

namespace SprykerFeature\Zed\Payone\Business\Api\Adapter\Http;

class Guzzle extends AbstractHttpAdapter
{

    protected $client;

    public function __construct()
    {
        $this->client = new GuzzleHttp\Client();
        $res = $client->get('https://api.github.com/user', ['auth' => ['user', 'pass']]);
        echo $res->getStatusCode();
    }

    /**
     * @throws \ErrorException
     *
     * @return array
     */
    protected function performRequest(array $params)
    {
        $response = $params;

        return $response;
    }

}
