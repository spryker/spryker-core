<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payolution\Business\Api\Adapter\Http;

use Guzzle\Http\Client;
use Guzzle\Http\Exception\RequestException;
use SprykerFeature\Zed\Payolution\Business\Api\Adapter\AdapterInterface;
use SprykerFeature\Zed\Payolution\Business\Api\Request\AbstractRequest;
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
     * @param AbstractRequest $request
     *
     * @throws ApiHttpRequestException
     * @return mixed
     */
    public function sendRequest(AbstractRequest $request)
    {
        /*$guzzleRequest = $this->client
            ->post(
                $this->gatewayUrl,
                $headers = null,
                [
                    'load' => $request->toXml()->saveXML()
                ]
            );

        $response = $guzzleRequest->send();

        echo $request->toXml()->saveXML();
        echo  "++++++++++++++++++++++++++++++";
        echo $response->getBody(true); exit;
        */

        $guzzleRequest = $this->client->post(
          $this->gatewayUrl,
            $headers = ['Content-Type' => 'application/x-www-form-urlencoded;charset=UTF-8'],
            $request->toArray()
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
