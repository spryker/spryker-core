<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfiguration\Http;

use Psr\Http\Message\ResponseInterface;

interface ProductConfigurationGuzzleHttpClientInterface
{
    /**
     * @param string $method
     * @param string $uri
     * @param array $options
     *
     * @throws \Spryker\Client\ProductConfiguration\Http\Exception\ProductConfigurationHttpRequestException
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function request(string $method, string $uri, array $options = []): ResponseInterface;
}
