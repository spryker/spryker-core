<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SearchElasticsearch\Business\Index;

use Elastica\Client;
use Elastica\Index as ElasticaIndex;
use Elastica\Request;
use Generated\Shared\Transfer\SearchContextTransfer;
use Spryker\Shared\SearchElasticsearch\Index\IndexNameResolverInterface;
use Spryker\Zed\SearchElasticsearch\SearchElasticsearchConfig;

class Index implements IndexInterface
{
    /**
     * @var \Elastica\Client
     */
    protected $client;

    /**
     * @var \Spryker\Shared\SearchElasticsearch\Index\IndexNameResolverInterface
     */
    protected $indexNameResolver;

    /**
     * @var \Spryker\Zed\SearchElasticsearch\SearchElasticsearchConfig
     */
    protected $config;

    /**
     * @param \Elastica\Client $client
     * @param \Spryker\Shared\SearchElasticsearch\Index\IndexNameResolverInterface $indexNameResolver
     * @param \Spryker\Zed\SearchElasticsearch\SearchElasticsearchConfig $config
     */
    public function __construct(
        Client $client,
        IndexNameResolverInterface $indexNameResolver,
        SearchElasticsearchConfig $config
    ) {
        $this->client = $client;
        $this->indexNameResolver = $indexNameResolver;
        $this->config = $config;
    }

    /**
     * @param \Generated\Shared\Transfer\SearchContextTransfer $searchContextTransfer
     *
     * @return bool
     */
    public function openIndex(SearchContextTransfer $searchContextTransfer): bool
    {
        return $this->getIndex($searchContextTransfer)->open()->isOk();
    }

    /**
     * @return bool
     */
    public function openIndexes(): bool
    {
        return $this->getAllStoreIndexes()->open()->isOk();
    }

    /**
     * @param \Generated\Shared\Transfer\SearchContextTransfer|null $searchContextTransfer
     *
     * @return bool
     */
    public function closeIndex(?SearchContextTransfer $searchContextTransfer = null): bool
    {
        return $this->getIndex($searchContextTransfer)->close()->isOk();
    }

    /**
     * @return bool
     */
    public function closeIndexes(): bool
    {
        return $this->getAllStoreIndexes()->close()->isOk();
    }

    /**
     * @param \Generated\Shared\Transfer\SearchContextTransfer|null $searchContextTransfer
     *
     * @return bool
     */
    public function deleteIndex(?SearchContextTransfer $searchContextTransfer = null): bool
    {
        return $this->getIndex($searchContextTransfer)->delete()->isOk();
    }

    /**
     * @return bool
     */
    public function deleteIndexes(): bool
    {
        return $this->getAllStoreIndexes()->delete()->isOk();
    }

    /**
     * @param \Generated\Shared\Transfer\SearchContextTransfer $sourceSearchContextTransfer
     * @param \Generated\Shared\Transfer\SearchContextTransfer $targetSearchContextTransfer
     *
     * @return bool
     */
    public function copyIndex(SearchContextTransfer $sourceSearchContextTransfer, SearchContextTransfer $targetSearchContextTransfer): bool
    {
        return $this->client->request(
            $this->config->getReindexUrl(),
            Request::POST,
            $this->buildCopyCommandRequestData($sourceSearchContextTransfer, $targetSearchContextTransfer)
        )->isOk();
    }

    /**
     * @param \Generated\Shared\Transfer\SearchContextTransfer $sourceSearchContextTransfer
     * @param \Generated\Shared\Transfer\SearchContextTransfer $targetSearchContextTransfer
     *
     * @return array
     */
    protected function buildCopyCommandRequestData(SearchContextTransfer $sourceSearchContextTransfer, SearchContextTransfer $targetSearchContextTransfer): array
    {
        $sourceIndexName = $this->resolveIndexNameFromSearchContextTransfer($sourceSearchContextTransfer);
        $targetIndexName = $this->resolveIndexNameFromSearchContextTransfer($targetSearchContextTransfer);

        return [
            'source' => [
                'index' => $sourceIndexName,
            ],
            'dest' => [
                'index' => $targetIndexName,
            ],
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\SearchContextTransfer $searchContextTransfer
     *
     * @return \Elastica\Index
     */
    protected function getIndex(SearchContextTransfer $searchContextTransfer): ElasticaIndex
    {
        $indexName = $this->resolveIndexNameFromSearchContextTransfer($searchContextTransfer);

        return $this->client->getIndex($indexName);
    }

    /**
     * @return \Elastica\Index
     */
    protected function getAllStoreIndexes(): ElasticaIndex
    {
        return $this->client->getIndex($this->getAllIndexNamesFormattedString());
    }

    /**
     * @param \Generated\Shared\Transfer\SearchContextTransfer $searchContextTransfer
     *
     * @return string
     */
    protected function resolveIndexNameFromSearchContextTransfer(SearchContextTransfer $searchContextTransfer): string
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

    /**
     * @return string
     */
    protected function getAllIndexNamesFormattedString(): string
    {
        return implode(',', $this->getAllIndexNamesForCurrentStore());
    }

    /**
     * @return string[]
     */
    protected function getAllIndexNamesForCurrentStore(): array
    {
        $supportedSourceIdentifiers = $this->config->getSupportedSourceIdentifiers();

        return array_map(function (string $sourceIdentifier) {
            return $this->indexNameResolver->resolve($sourceIdentifier);
        }, $supportedSourceIdentifiers);
    }
}
