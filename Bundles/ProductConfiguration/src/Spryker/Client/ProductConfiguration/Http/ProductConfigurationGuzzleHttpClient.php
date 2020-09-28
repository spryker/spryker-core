<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
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
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \Spryker\Client\ProductConfiguration\Http\Exception\ProductConfigurationHttpRequestException
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
