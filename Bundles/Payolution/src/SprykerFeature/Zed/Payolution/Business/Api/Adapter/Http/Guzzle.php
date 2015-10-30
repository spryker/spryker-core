<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payolution\Business\Api\Adapter\Http;

use Guzzle\Http\Client as GuzzleClient;
use Guzzle\Http\Exception\RequestException;
use SprykerFeature\Zed\Payolution\Business\Api\Adapter\AdapterInterface;
use SprykerFeature\Zed\Payolution\Business\Exception\ApiHttpRequestException;

class Guzzle implements AdapterInterface
{

    /**
     * @var GuzzleClient
     */
    protected $client;

    /**
     * @var string
     */
    private $gatewayUrl;

    /**
     * @param string $gatewayUrl
     */
    public function __construct(GuzzleClient $client, $gatewayUrl)
    {
        $this->client = $client;
        $this->gatewayUrl = $gatewayUrl;
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
        $headers = ['Content-Type' => 'application/x-www-form-urlencoded;charset=UTF-8'];
        $guzzleRequest = $this->client->post(
          $this->gatewayUrl,
            $headers,
            $data
        );

        try {
            $response = $guzzleRequest->send();
        } catch (RequestException $requestException) {
            throw new ApiHttpRequestException($requestException->getMessage());
        }

        parse_str($response->getBody(true), $out);

        return $out;
    }

}
