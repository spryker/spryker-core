<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SearchElasticsearch\Business\Index;

use Elastica\Client;
use Elastica\Exception\ResponseException;
use Elastica\Index as ElasticaIndex;
use Elastica\Request;
use Generated\Shared\Transfer\ElasticsearchSearchContextTransfer;
use Generated\Shared\Transfer\SearchContextTransfer;
use Spryker\Shared\ErrorHandler\ErrorLogger;
use Spryker\Zed\SearchElasticsearch\Business\SourceIdentifier\SourceIdentifierInterface;
use Spryker\Zed\SearchElasticsearch\SearchElasticsearchConfig;

class Index implements IndexInterface
{
    /**
     * @var \Elastica\Client
     */
    protected $elasticaClient;

    /**
     * @var \Spryker\Zed\SearchElasticsearch\Business\SourceIdentifier\SourceIdentifierInterface
     */
    protected $sourceIdentifier;

    /**
     * @var \Spryker\Zed\SearchElasticsearch\SearchElasticsearchConfig
     */
    protected $config;

    /**
     * @param \Elastica\Client $elasticaClient
     * @param \Spryker\Zed\SearchElasticsearch\Business\SourceIdentifier\SourceIdentifierInterface $sourceIdentifier
     * @param \Spryker\Zed\SearchElasticsearch\SearchElasticsearchConfig $config
     */
    public function __construct(
        Client $elasticaClient,
        SourceIdentifierInterface $sourceIdentifier,
        SearchElasticsearchConfig $config
    ) {
        $this->elasticaClient = $elasticaClient;
        $this->sourceIdentifier = $sourceIdentifier;
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
        $allIndexes = $this->getAllIndexes();

        if ($allIndexes) {
            return $allIndexes->open()->isOk();
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\SearchContextTransfer $searchContextTransfer
     *
     * @return bool
     */
    public function closeIndex(SearchContextTransfer $searchContextTransfer): bool
    {
        return $this->getIndex($searchContextTransfer)->close()->isOk();
    }

    /**
     * @return bool
     */
    public function closeIndexes(): bool
    {
        $allIndexes = $this->getAllIndexes();

        if ($allIndexes) {
            return $allIndexes->close()->isOk();
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\SearchContextTransfer $searchContextTransfer
     *
     * @return bool
     */
    public function deleteIndex(SearchContextTransfer $searchContextTransfer): bool
    {
        return $this->getIndex($searchContextTransfer)->delete()->isOk();
    }

    /**
     * @return bool
     */
    public function deleteIndexes(): bool
    {
        $allIndexes = $this->getAllIndexes();

        if ($allIndexes) {
            return $allIndexes->delete()->isOk();
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\SearchContextTransfer $sourceSearchContextTransfer
     * @param \Generated\Shared\Transfer\SearchContextTransfer $targetSearchContextTransfer
     *
     * @return bool
     */
    public function copyIndex(SearchContextTransfer $sourceSearchContextTransfer, SearchContextTransfer $targetSearchContextTransfer): bool
    {
        return $this->elasticaClient->request(
            $this->config->getReindexUrl(),
            Request::POST,
            $this->buildCopyCommandRequestData($sourceSearchContextTransfer, $targetSearchContextTransfer)
        )->isOk();
    }

    /**
     * @param \Generated\Shared\Transfer\ElasticsearchSearchContextTransfer $elasticsearchSearchContextTransfer
     *
     * @return int
     */
    public function getDocumentsTotalCount(ElasticsearchSearchContextTransfer $elasticsearchSearchContextTransfer): int
    {
        $indexName = $elasticsearchSearchContextTransfer->requireIndexName()->getIndexName();

        try {
            return $this->elasticaClient->getIndex($indexName)->count();
        } catch (ResponseException $e) {
            ErrorLogger::getInstance()->log($e);

            return 0;
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ElasticsearchSearchContextTransfer $elasticsearchSearchContextTransfer
     *
     * @return array
     */
    public function getIndexMetaData(ElasticsearchSearchContextTransfer $elasticsearchSearchContextTransfer): array
    {
        $metaData = [];
        $indexName = $elasticsearchSearchContextTransfer->requireIndexName()->getIndexName();

        try {
            $index = $this->elasticaClient->getIndex($indexName);
            $mapping = $index->getMapping()[0] ?? null;
            $metaData = $mapping['_meta'] ?? [];
        } catch (ResponseException $e) {
            // legal catch, if no mapping found (fresh installation etc) we still want to show empty meta data
            ErrorLogger::getInstance()->log($e);
        }

        return $metaData;
    }

    /**
     * @return string[]
     */
    public function getIndexNames(): array
    {
        return $this->getAvailableIndexNames();
    }

    /**
     * @param \Generated\Shared\Transfer\SearchContextTransfer $sourceSearchContextTransfer
     * @param \Generated\Shared\Transfer\SearchContextTransfer $targetSearchContextTransfer
     *
     * @return array
     */
    protected function buildCopyCommandRequestData(
        SearchContextTransfer $sourceSearchContextTransfer,
        SearchContextTransfer $targetSearchContextTransfer
    ): array {
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

        return $this->elasticaClient->getIndex($indexName);
    }

    /**
     * @return \Elastica\Index|null
     */
    protected function getAllIndexes(): ?ElasticaIndex
    {
        $availableIndexNamesFormattedString = $this->getAvailableIndexNamesFormattedString();

        if (!$availableIndexNamesFormattedString) {
            return null;
        }

        return $this->elasticaClient->getIndex($availableIndexNamesFormattedString);
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
    protected function getAvailableIndexNamesFormattedString(): string
    {
        return implode(',', $this->getAvailableIndexNames());
    }

    /**
     * @return string[]
     */
    protected function getAvailableIndexNames(): array
    {
        $supportedSourceIdentifiers = $this->config->getSupportedSourceIdentifiers();

        $supportedIndexNames = array_map(function (string $sourceIdentifier) {
            return $this->sourceIdentifier->translateToIndexName($sourceIdentifier);
        }, $supportedSourceIdentifiers);

        return array_intersect($supportedIndexNames, $this->elasticaClient->getCluster()->getIndexNames());
    }
}
