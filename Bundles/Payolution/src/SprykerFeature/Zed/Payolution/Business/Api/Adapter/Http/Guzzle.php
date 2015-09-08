<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payolution\Business\Api\Adapter\Http;

use Guzzle\Http\Client;
use SprykerFeature\Zed\Payolution\Business\Api\Adapter\AdapterInterface;
use SprykerFeature\Zed\Payolution\Business\Api\Request\AbstractRequest;

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
     */
    public function sendRequest(AbstractRequest $request)
    {
        $response = $this->client->post($this->gatewayUrl, $headers = [], $request->toArray())->send();
        var_dump($response);exit;
    }

}
