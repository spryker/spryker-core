<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payone\Business\Api\Adapter\Http;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use Spryker\Zed\Payone\Business\Exception\TimeoutException;

class Guzzle extends AbstractHttpAdapter
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

    /**
     * @param array $params
     *
     * @throws \Spryker\Zed\Payone\Business\Exception\TimeoutException
     *
     * @return array
     */
    protected function performRequest(array $params)
    {
        $urlArray = $this->generateUrlArray($params);

        $urlHost = $urlArray['host'];
        $urlPath = isset($urlArray['path']) ? $urlArray['path'] : '';
        $urlScheme = $urlArray['scheme'];

        $url = $urlScheme . '://' . $urlHost . $urlPath;

        try {
            $response = $this->client->post($url, ['form_params' => $params]);
        } catch (ConnectException $e) {
            throw new TimeoutException('Timeout - Payone Communication: ' . $e->getMessage());
        }

        $result = (string)$response->getBody();
        $result = explode("\n", $result);

        return $result;
    }

}
