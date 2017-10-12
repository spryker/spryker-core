<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payolution\Business\Api\Adapter\Http;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use Spryker\Zed\Payolution\Business\Exception\ApiHttpRequestException;

class Guzzle extends AbstractHttpAdapter
{
    const DEFAULT_TIMEOUT = 45;

    /**
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * @param string $gatewayUrl
     * @param string $contentType
     */
    public function __construct($gatewayUrl, $contentType)
    {
        parent::__construct($gatewayUrl, $contentType);

        $this->client = new Client([
            'timeout' => self::DEFAULT_TIMEOUT,
        ]);
    }

    /**
     * @return \Psr\Http\Message\RequestInterface
     */
    protected function buildRequest()
    {
        $headers = [
            'Content-Type' => self::$requestContentTypes[$this->contentType],
        ];
        $request = new Request('POST', $this->gatewayUrl, $headers);

        return $request;
    }

    /**
     * @param string $user
     * @param string $password
     *
     * @return array
     */
    protected function authorizeRequest($user, $password)
    {
        return [
            'auth' => [$user, $password],
        ];
    }

    /**
     * @param \Psr\Http\Message\RequestInterface $request
     * @param array $options
     *
     * @throws \Spryker\Zed\Payolution\Business\Exception\ApiHttpRequestException
     *
     * @return string
     */
    protected function send($request, array $options = [])
    {
        try {
            $response = $this->client->send($request, $options);
        } catch (RequestException $requestException) {
            throw new ApiHttpRequestException($requestException->getMessage());
        }

        return $response->getBody();
    }
}
