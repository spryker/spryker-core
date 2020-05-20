<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Search\Business\Model\Elasticsearch\Definition;

use Generated\Shared\Transfer\ElasticsearchIndexDefinitionTransfer;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Search\SearchConstants;
use Spryker\Zed\Search\Dependency\Facade\SearchToStoreFacadeInterface;
use Spryker\Zed\Search\Dependency\Service\SearchToUtilEncodingInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class JsonIndexDefinitionLoader implements IndexDefinitionLoaderInterface
{
    public const FILE_EXTENSION = '.json';

    /**
     * @var array
     */
    protected $sourceDirectories;

    /**
     * @var \Spryker\Zed\Search\Business\Model\Elasticsearch\Definition\IndexDefinitionMergerInterface
     */
    protected $definitionMerger;

    /**
     * @var \Spryker\Zed\Search\Dependency\Service\SearchToUtilEncodingInterface
     */
    protected $utilEncodingService;

    /**
     * @var \Spryker\Zed\Search\Dependency\Facade\SearchToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @param array $sourceDirectories
     * @param \Spryker\Zed\Search\Business\Model\Elasticsearch\Definition\IndexDefinitionMergerInterface $definitionMerger
     * @param \Spryker\Zed\Search\Dependency\Service\SearchToUtilEncodingInterface $utilEncodingService
     * @param \Spryker\Zed\Search\Dependency\Facade\SearchToStoreFacadeInterface $storeFacade
     */
    public function __construct(
        array $sourceDirectories,
        IndexDefinitionMergerInterface $definitionMerger,
        SearchToUtilEncodingInterface $utilEncodingService,
        SearchToStoreFacadeInterface $storeFacade
    ) {
        $this->sourceDirectories = $sourceDirectories;
        $this->definitionMerger = $definitionMerger;
        $this->utilEncodingService = $utilEncodingService;
        $this->storeFacade = $storeFacade;
    }

    /**
     * @return \Generated\Shared\Transfer\ElasticsearchIndexDefinitionTransfer[]
     */
    public function loadIndexDefinitions()
    {
        $indexDefinitions = [];

        $jsonFiles = $this->getJsonFiles();
        usort($jsonFiles, [$this, 'sortJsonFilesByStorePrefix']);

        foreach ($jsonFiles as $jsonFile) {
            if (!$this->isFileValidForCurrentStores($jsonFile)) {
                continue;
            }

            $definitionData = $this->decodeJson($jsonFile->getContents());
            $indexDefinitions = $this->getDefinitionByStores($jsonFile, $indexDefinitions, $definitionData);
        }

        return $this->createIndexDefinitions($indexDefinitions);
    }

    /**
     * @param \Symfony\Component\Finder\SplFileInfo $jsonFile
     *
     * @return bool
     */
    protected function isFileValidForCurrentStores(SplFileInfo $jsonFile): bool
    {
        $jsonFileStorePrefix = $this->getFileStorePrefix($jsonFile->getFilename());

        return !$jsonFileStorePrefix || in_array($jsonFileStorePrefix, $this->getStorePrefixes($this->getCurrentStores()));
    }

    /**
     * @return \Symfony\Component\Finder\SplFileInfo[]
     */
    protected function getJsonFiles(): array
    {
        $finder = (new Finder())
            ->in($this->sourceDirectories)
            ->name('*' . self::FILE_EXTENSION);

        $jsonFiles = [];
        foreach ($finder as $file) {
            $jsonFiles[] = $file;
        }

        return $jsonFiles;
    }

    /**
     * @param \Symfony\Component\Finder\SplFileInfo $firstJsonFile
     * @param \Symfony\Component\Finder\SplFileInfo $secondJsonFile
     *
     * @return int
     */
    protected function sortJsonFilesByStorePrefix(SplFileInfo $firstJsonFile, SplFileInfo $secondJsonFile): int
    {
        $firstJsonFileStorePrefix = $this->getFileStorePrefix($firstJsonFile->getFilename());
        $secondJsonFileStorePrefix = $this->getFileStorePrefix($secondJsonFile->getFilename());

        return (int)((bool)$firstJsonFileStorePrefix > (bool)$secondJsonFileStorePrefix);
    }

    /**
     * @param \Symfony\Component\Finder\SplFileInfo $jsonFile
     * @param array $indexDefinitions
     * @param array $definitionData
     *
     * @return array
     */
    protected function getDefinitionByStores(SplFileInfo $jsonFile, array $indexDefinitions, array $definitionData)
    {
        foreach ($this->getStorePrefixes($this->getCurrentStores()) as $storePrefix) {
            $indexName = $this->getIndexName($jsonFile->getFilename(), $storePrefix);

            if (isset($indexDefinitions[$indexName])) {
                $definitionData = $this
                    ->definitionMerger
                    ->merge($indexDefinitions[$indexName], $definitionData);
            }

            $indexDefinitions[$indexName] = $definitionData;
        }

        return $indexDefinitions;
    }

    /**
     * @param array $indexDefinitions
     *
     * @return \Generated\Shared\Transfer\ElasticsearchIndexDefinitionTransfer[]
     */
    protected function createIndexDefinitions(array $indexDefinitions)
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
    protected function createIndexDefinition($indexName, array $definitionData)
    {
        $settings = isset($definitionData['settings']) ? $definitionData['settings'] : [];
        $mappings = isset($definitionData['mappings']) ? $definitionData['mappings'] : [];

        $indexDefinitionTransfer = new ElasticsearchIndexDefinitionTransfer();
        $indexDefinitionTransfer
            ->setIndexName($indexName)
            ->setSettings($settings)
            ->setMappings($mappings);

        return $indexDefinitionTransfer;
    }

    /**
     * @param string $fileName
     * @param string $storePrefix
     *
     * @return string
     */
    protected function getIndexName(string $fileName, $storePrefix)
    {
        $indexName = substr($fileName, 0, -strlen(self::FILE_EXTENSION));

        if (!$this->getFileStorePrefix($fileName)) {
            $indexName = $storePrefix . $indexName;
        }

        $indexName = $this->addSearchIndexNameSuffix($indexName);

        return $indexName;
    }

    /**
     * @param string $fileName
     *
     * @return string|null
     */
    protected function getFileStorePrefix(string $fileName): ?string
    {
        foreach ($this->getStorePrefixes($this->getAllStores()) as $storePrefix) {
            if (strpos($fileName, $storePrefix) === 0) {
                return $storePrefix;
            }
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer[] $storeTransfers
     *
     * @return string[]
     */
    protected function getStorePrefixes(array $storeTransfers): array
    {
        $storePrefixes = [];

        foreach ($storeTransfers as $storeTransfer) {
            $storePrefixes[] = mb_strtolower($storeTransfer->getName()) . '_';
        }

        return $storePrefixes;
    }

    /**
     * @param string $indexName
     *
     * @return string
     */
    protected function addSearchIndexNameSuffix($indexName)
    {
        $indexName .= $this->getIndexNameSuffix();

        return $indexName;
    }

    /**
     * @return string
     */
    protected function getIndexNameSuffix()
    {
        return Config::get(SearchConstants::SEARCH_INDEX_NAME_SUFFIX, '');
    }

    /**
     * @param string $jsonValue
     *
     * @return array
     */
    protected function decodeJson($jsonValue)
    {
        return $this->utilEncodingService
            ->decodeJson($jsonValue, true);
    }

    /**
     * @return \Generated\Shared\Transfer\StoreTransfer[]
     */
    protected function getCurrentStores(): array
    {
        return [$this->storeFacade->getCurrentStore()];
    }

    /**
     * @return \Generated\Shared\Transfer\StoreTransfer[]
     */
    protected function getAllStores(): array
    {
        return $this->storeFacade->getAllStores();
    }
}
