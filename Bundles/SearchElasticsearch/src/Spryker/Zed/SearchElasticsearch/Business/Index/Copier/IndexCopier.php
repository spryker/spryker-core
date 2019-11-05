<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SearchElasticsearch\Business\Index\Copier;

use Generated\Shared\Transfer\SearchContextTransfer;
use Spryker\Zed\SearchElasticsearch\Dependency\Guzzle\SearchElasticsearchToGuzzleClientInterface;
use Spryker\Zed\SearchElasticsearch\Dependency\Service\SearchElasticsearchToUtilEncodingServiceInterface;
use Spryker\Zed\SearchElasticsearch\SearchElasticsearchConfig;

class IndexCopier implements IndexCopierInterface
{
    protected const RESPONSE_STATUS_SUCCESS = 200;

    /**
     * @var \Spryker\Zed\SearchElasticsearch\Dependency\Guzzle\SearchElasticsearchToGuzzleClientInterface
     */
    protected $client;

    /**
     * @var \Spryker\Zed\SearchElasticsearch\SearchElasticsearchConfig
     */
    protected $config;

    /**
     * @var \Spryker\Zed\SearchElasticsearch\Dependency\Service\SearchElasticsearchToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Zed\SearchElasticsearch\Dependency\Guzzle\SearchElasticsearchToGuzzleClientInterface $client
     * @param \Spryker\Zed\SearchElasticsearch\SearchElasticsearchConfig $config
     * @param \Spryker\Zed\SearchElasticsearch\Dependency\Service\SearchElasticsearchToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(
        SearchElasticsearchToGuzzleClientInterface $client,
        SearchElasticsearchConfig $config,
        SearchElasticsearchToUtilEncodingServiceInterface $utilEncodingService
    ) {
        $this->client = $client;
        $this->config = $config;
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param \Generated\Shared\Transfer\SearchContextTransfer $sourceSearchContextTransfer
     * @param \Generated\Shared\Transfer\SearchContextTransfer $targetSearchContextTransfer
     *
     * @return bool
     */
    public function copyIndex(SearchContextTransfer $sourceSearchContextTransfer, SearchContextTransfer $targetSearchContextTransfer): bool
    {
        $responseStatusCode = $this->client->post($this->config->getReindexUrl(), [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'body' => $this->buildCopyCommandRequestBody($sourceSearchContextTransfer, $targetSearchContextTransfer),
        ]);

        return $responseStatusCode === static::RESPONSE_STATUS_SUCCESS;
    }

    /**
     * @param \Generated\Shared\Transfer\SearchContextTransfer $sourceSearchContextTransfer
     * @param \Generated\Shared\Transfer\SearchContextTransfer $targetSearchContextTransfer
     *
     * @return string
     */
    protected function buildCopyCommandRequestBody(SearchContextTransfer $sourceSearchContextTransfer, SearchContextTransfer $targetSearchContextTransfer): string
    {
        $sourceIndexName = $this->getIndexName($sourceSearchContextTransfer);
        $targetIndexName = $this->getIndexName($targetSearchContextTransfer);

        $command = [
            'source' => [
                'index' => $sourceIndexName,
            ],
            'dest' => [
                'index' => $targetIndexName,
            ],
        ];

        return $this->utilEncodingService->encodeJson($command);
    }

    /**
     * @param \Generated\Shared\Transfer\SearchContextTransfer $searchContextTransfer
     *
     * @return string
     */
    protected function getIndexName(SearchContextTransfer $searchContextTransfer): string
    {
        $this->assertIndexNameIsSet($searchContextTransfer);

        return $searchContextTransfer->getElasticsearchContext()->getIndexName();
    }

    /**
     * @param \Generated\Shared\Transfer\SearchContextTransfer $searchContextTransfer
     *
     * @return void
     */
    protected function assertIndexNameIsSet(SearchContextTransfer $searchContextTransfer): void
    {
        $searchContextTransfer->requireElasticsearchContext()->getElasticsearchContext()->requireIndexName();
    }
}
