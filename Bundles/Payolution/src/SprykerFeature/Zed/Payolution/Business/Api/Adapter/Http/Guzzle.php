<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payolution\Business\Api\Adapter\Http;

use Guzzle\Http\Client;
use Guzzle\Http\Exception\RequestException;
use SprykerFeature\Zed\Payolution\Business\Api\Adapter\AdapterInterface;
use SprykerFeature\Zed\Payolution\Business\Exception\ApiHttpRequestException;

class Guzzle implements AdapterInterface
{

    const DEFAULT_TIMEOUT = 45;

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var string
     */
    private $gatewayUrl;

    /**
     * @param string $gatewayUrl
     */
    public function __construct($gatewayUrl)
    {
        $this->gatewayUrl = $gatewayUrl;
        $this->client = new Client([
            'timeout' => self::DEFAULT_TIMEOUT,
        ]);
    }

    /**
     * @param array $data
     *
     * @throws ApiHttpRequestException
     *
     * @return array
     */
    public function sendArrayDataRequest(array $data)
    {
        $guzzleRequest = $this->client->post(
          $this->gatewayUrl,
            $headers = ['Content-Type' => 'application/x-www-form-urlencoded;charset=UTF-8'],
            $data
        );

        try {
            $response = $guzzleRequest->send();
        } catch (RequestException $requestException) {
            throw new ApiHttpRequestException($requestException->getMessage());
        }

        parse_str($response->getBody($asString = true), $out);

        return $out;
    }

}
