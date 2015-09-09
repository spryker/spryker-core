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
     *
     * @return \Guzzle\Http\Message\Response
     */
    public function sendRequest(AbstractRequest $request)
    {
        return $this->client
            ->post(
                $this->gatewayUrl,
                $headers = [],
                $request->toXml()->saveXML()
            )
            ->send();
    }

}
