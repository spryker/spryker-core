<?php

namespace SprykerFeature\Zed\Payolution\Business\Api\Adapter\Http;

use Bundles\Payolution\src\SprykerFeature\Zed\Payolution\Business\Api\Request\AbstractRequest;
use GuzzleHttp\Client;
use SprykerFeature\Zed\Payolution\Business\Api\Adapter\AdapterInterface;


class Guzzle implements AdapterInterface
{

    protected $client;

    /**
     * @param string $paymentGatewayUrl
     */
    public function __construct($paymentGatewayUrl)
    {
        parent::__construct($paymentGatewayUrl);

        $this->client = new Client([
            'timeout' => $this->getTimeout(),
        ]);
    }

    public function sendRequest(AbstractRequest $request)
    {
        $url = 'https://test.ctpe.net/frontend/payment.prc';

        $this->client->request('POST', $url, $request->toArray());

    }

}
