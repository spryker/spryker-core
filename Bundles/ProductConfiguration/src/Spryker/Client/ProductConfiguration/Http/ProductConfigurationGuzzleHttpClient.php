<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfiguration\Http;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;
use Spryker\Client\ProductConfiguration\Http\Exception\ProductConfigurationHttpRequestException;

class ProductConfigurationGuzzleHttpClient implements ProductConfigurationGuzzleHttpClientInterface
{
    /**
     * @var \GuzzleHttp\ClientInterface
     */
    protected $guzzleHttpClient;

    /**
     * @param \GuzzleHttp\ClientInterface $guzzleHttpClient
     */
    public function __construct(ClientInterface $guzzleHttpClient)
    {
        $this->guzzleHttpClient = $guzzleHttpClient;
    }

    /**
     * @param string $method
     * @param string $uri
     * @param array $options
     *
     * @throws \Spryker\Client\ProductConfiguration\Http\Exception\ProductConfigurationHttpRequestException
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function request(string $method, string $uri, array $options = []): ResponseInterface
    {
        try {
            return $this->guzzleHttpClient->request($method, $uri, $options);
        } catch (GuzzleException $exception) {
            throw new ProductConfigurationHttpRequestException(
                $exception->getMessage(),
                $exception->getCode(),
                $exception
            );
        }
    }
}
