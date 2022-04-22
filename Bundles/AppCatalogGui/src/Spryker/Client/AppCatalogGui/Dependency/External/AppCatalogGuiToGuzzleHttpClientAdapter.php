<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\AppCatalogGui\Dependency\External;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Spryker\Client\AppCatalogGui\Exception\ExternalHttpRequestException;

class AppCatalogGuiToGuzzleHttpClientAdapter implements AppCatalogGuiToHttpClientAdapterInterface
{
    /**
     * @var \GuzzleHttp\ClientInterface
     */
    protected $httpClient;

    /**
     * @param \GuzzleHttp\ClientInterface $httpClient
     */
    public function __construct(ClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @param string $method
     * @param string $uri
     * @param array<mixed> $options
     *
     * @throws \Spryker\Client\AppCatalogGui\Exception\ExternalHttpRequestException
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function request(string $method, string $uri, array $options = []): ResponseInterface
    {
        try {
            return $this->httpClient->request($method, $uri, $options);
        } catch (GuzzleException $exception) {
            $externalHttpRequestException = new ExternalHttpRequestException(
                $exception->getMessage(),
                $exception->getCode(),
                $exception,
            );

            if ($exception instanceof RequestException && $exception->getResponse() instanceof Response) {
                $externalHttpRequestException->setResponseBody(
                    $exception->getResponse()->getBody()->getContents(),
                );
            }

            throw $externalHttpRequestException;
        }
    }
}
