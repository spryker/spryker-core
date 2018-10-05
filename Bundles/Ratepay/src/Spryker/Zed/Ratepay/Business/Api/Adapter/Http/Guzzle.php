<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Api\Adapter\Http;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use Spryker\Zed\Ratepay\Business\Api\Constants;
use Spryker\Zed\Ratepay\Business\Exception\ApiHttpRequestException;

class Guzzle extends AbstractHttpAdapter
{
    public const DEFAULT_TIMEOUT = 45;

    /**
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * @param string $gatewayUrl
     */
    public function __construct($gatewayUrl)
    {
        parent::__construct($gatewayUrl);

        $this->client = new Client([
            'timeout' => self::DEFAULT_TIMEOUT,
        ]);
    }

    /**
     * @param string $data
     *
     * @return \Psr\Http\Message\RequestInterface
     */
    protected function buildRequest($data)
    {
        $headers = [
            'Content-Type' => Constants::REQUEST_HEADER_CONTENT_TYPE,
        ];
        $request = new Request('POST', $this->gatewayUrl, $headers, $data);

        return $request;
    }

    /**
     * @param \Psr\Http\Message\RequestInterface $request
     *
     * @throws \Spryker\Zed\Ratepay\Business\Exception\ApiHttpRequestException
     *
     * @return string
     */
    protected function send($request)
    {
        try {
            $response = $this->client->send($request);
        } catch (RequestException $requestException) {
            throw new ApiHttpRequestException($requestException->getMessage());
        }

        return $response->getBody();
    }
}
