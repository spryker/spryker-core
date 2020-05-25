<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Search\Business\Model\Elasticsearch\Copier;

use GuzzleHttp\Client;

/**
 * @deprecated Use {@link \Spryker\Zed\SearchElasticsearch\Business\Index\Copier\IndexCopier} instead.
 */
class IndexCopier implements IndexCopierInterface
{
    /**
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * @var string
     */
    protected $url;

    /**
     * @param \GuzzleHttp\Client $client
     * @param string $url
     */
    public function __construct(Client $client, $url)
    {
        $this->client = $client;
        $this->url = $url;
    }

    /**
     * @param string $source
     * @param string $target
     *
     * @return bool
     */
    public function copyIndex($source, $target)
    {
        $body = sprintf('{"source": {"index": "%s"}, "dest": {"index": "%s"}}', $source, $target);

        $response = $this->client->post($this->url, [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'body' => $body,
        ]);

        return ($response->getStatusCode() === 200);
    }
}
