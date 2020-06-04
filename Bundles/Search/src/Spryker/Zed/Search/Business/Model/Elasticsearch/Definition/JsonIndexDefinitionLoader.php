<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Search\Business\Model\Elasticsearch\Definition;

use Generated\Shared\Transfer\ElasticsearchIndexDefinitionTransfer;
use Generated\Shared\Transfer\IndexDefinitionFileTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Search\SearchConstants;
use Spryker\Zed\Search\Business\Definition\IndexDefinitionFinderInterface;
use Spryker\Zed\Search\Dependency\Facade\SearchToStoreFacadeInterface;

class JsonIndexDefinitionLoader implements IndexDefinitionLoaderInterface
{
    public const FILE_EXTENSION = '.json';

    /**
     * @var \Spryker\Zed\Search\Business\Model\Elasticsearch\Definition\IndexDefinitionMergerInterface
     */
    protected $definitionMerger;

    /**
     * @var \Spryker\Zed\Search\Dependency\Facade\SearchToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @var \Spryker\Zed\Search\Business\Definition\IndexDefinitionFinderInterface
     */
    protected $definitionFinder;

    /**
     * @param \Spryker\Zed\Search\Business\Definition\IndexDefinitionFinderInterface $definitionFinder
     * @param \Spryker\Zed\Search\Business\Model\Elasticsearch\Definition\IndexDefinitionMergerInterface $definitionMerger
     * @param \Spryker\Zed\Search\Dependency\Facade\SearchToStoreFacadeInterface $storeFacade
     */
    public function __construct(
        IndexDefinitionFinderInterface $definitionFinder,
        IndexDefinitionMergerInterface $definitionMerger,
        SearchToStoreFacadeInterface $storeFacade
    ) {
        $this->definitionFinder = $definitionFinder;
        $this->definitionMerger = $definitionMerger;
        $this->storeFacade = $storeFacade;
    }

    /**
     * @return \Generated\Shared\Transfer\ElasticsearchIndexDefinitionTransfer[]
     */
    public function loadIndexDefinitions()
    {
        $indexDefinitions = [];
        $currentStorePrefix = $this->getStorePrefix($this->storeFacade->getCurrentStore());
        $indexDefinitionFileTransfers = $this->definitionFinder->getSortedIndexDefinitionFileTransfers();

        foreach ($indexDefinitionFileTransfers as $indexDefinitionFileTransfer) {
            if (!$this->isIndexDefinitionFileValidForCurrentStore($indexDefinitionFileTransfer, $currentStorePrefix)) {
                continue;
            }

            $fileIndexName = $this->getIndexName($indexDefinitionFileTransfer, $currentStorePrefix);
            $indexDefinitions = $this->addDefinition($indexDefinitions, $indexDefinitionFileTransfer->getContent(), $fileIndexName);
        }

        return $this->createIndexDefinitions($indexDefinitions);
    }

    /**
     * @param \Generated\Shared\Transfer\IndexDefinitionFileTransfer $indexDefinitionFileTransfer
     * @param string $currentStorePrefix
     *
     * @return bool
     */
    protected function isIndexDefinitionFileValidForCurrentStore(
        IndexDefinitionFileTransfer $indexDefinitionFileTransfer,
        string $currentStorePrefix
    ): bool {
        $indexDefinitionFileStorePrefix = $indexDefinitionFileTransfer->getStorePrefix();

        return !$indexDefinitionFileStorePrefix || $indexDefinitionFileStorePrefix === $currentStorePrefix;
    }

    /**
     * @param array $indexDefinitions
     * @param array $definitionData
     * @param string $indexName
     *
     * @return array
     */
    protected function addDefinition(array $indexDefinitions, array $definitionData, string $indexName): array
    {
        if (isset($indexDefinitions[$indexName])) {
            $definitionData = $this
                ->definitionMerger
                ->merge($indexDefinitions[$indexName], $definitionData);
        }

        $indexDefinitions[$indexName] = $definitionData;

        return $indexDefinitions;
    }

    /**
     * @param array $indexDefinitions
     *
     * @return \Generated\Shared\Transfer\ElasticsearchIndexDefinitionTransfer[]
     */
    protected function createIndexDefinitions(array $indexDefinitions): array
    {
        foreach ($indexDefinitions as $indexName => $indexDefinition) {
            $indexDefinitions[$indexName] = $this->createIndexDefinition($indexName, $indexDefinition);
        }

        return $indexDefinitions;
    }

    /**
     * @param string $indexName
     * @param array $definitionData
     *
     * @return \Generated\Shared\Transfer\ElasticsearchIndexDefinitionTransfer
     */
    protected function createIndexDefinition(string $indexName, array $definitionData): ElasticsearchIndexDefinitionTransfer
    {
        return (new ElasticsearchIndexDefinitionTransfer())
            ->setIndexName($indexName)
            ->setSettings($definitionData['settings'] ?? [])
            ->setMappings($definitionData['mappings'] ?? []);
    }

    /**
     * @param \Generated\Shared\Transfer\IndexDefinitionFileTransfer $indexDefinitionFileTransfer
     * @param string $currentStorePrefix
     *
     * @return string
     */
    protected function getIndexName(IndexDefinitionFileTransfer $indexDefinitionFileTransfer, string $currentStorePrefix): string
    {
        $indexName = substr($indexDefinitionFileTransfer->getFileName(), 0, -strlen(self::FILE_EXTENSION));

        if (!$indexDefinitionFileTransfer->getStorePrefix()) {
            $indexName = $currentStorePrefix . $indexName;
        }

        $indexName .= $this->getIndexNameSuffix();

        return $indexName;
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return string
     */
    protected function getStorePrefix(StoreTransfer $storeTransfer): string
    {
        return mb_strtolower($storeTransfer->getName()) . '_';
    }

    /**
     * @return string
     */
    protected function getIndexNameSuffix(): string
    {
        return Config::get(SearchConstants::SEARCH_INDEX_NAME_SUFFIX, '');
    }
}
