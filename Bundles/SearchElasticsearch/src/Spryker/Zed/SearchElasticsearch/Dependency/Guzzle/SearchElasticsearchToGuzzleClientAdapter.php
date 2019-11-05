<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SearchElasticsearch\Dependency\Guzzle;

use GuzzleHttp\Client;

class SearchElasticsearchToGuzzleClientAdapter implements SearchElasticsearchToGuzzleClientInterface
{
    /**
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * @param \GuzzleHttp\Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param string $uri
     * @param array $options
     *
     * @return int Response status code.
     */
    public function post(string $uri, array $options = []): int
    {
        $response = $this->client->post($uri, $options);

        return $response->getStatusCode();
    }
}
