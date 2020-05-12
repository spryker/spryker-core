<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Search\Business\Model\Elasticsearch\Definition;

use Generated\Shared\Transfer\ElasticsearchIndexDefinitionTransfer;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Search\SearchConstants;
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
     * @var string[]
     */
    protected $currentStorePrefixes;

    /**
     * @var string[]
     */
    protected $availableStorePrefixes;

    /**
     * @var \Spryker\Zed\Search\Dependency\Service\SearchToUtilEncodingInterface
     */
    protected $utilEncodingService;

    /**
     * @param array $sourceDirectories
     * @param \Spryker\Zed\Search\Business\Model\Elasticsearch\Definition\IndexDefinitionMergerInterface $definitionMerger
     * @param \Spryker\Zed\Search\Dependency\Service\SearchToUtilEncodingInterface $utilEncodingService
     * @param string[] $currentStores
     * @param string[] $availableStores
     */
    public function __construct(
        array $sourceDirectories,
        IndexDefinitionMergerInterface $definitionMerger,
        SearchToUtilEncodingInterface $utilEncodingService,
        array $currentStores,
        array $availableStores
    ) {
        $this->sourceDirectories = $sourceDirectories;
        $this->definitionMerger = $definitionMerger;
        $this->utilEncodingService = $utilEncodingService;
        $this->currentStorePrefixes = $this->getStorePrefixes($currentStores);
        $this->availableStorePrefixes = $this->getStorePrefixes($availableStores);
    }

    /**
     * @return \Generated\Shared\Transfer\ElasticsearchIndexDefinitionTransfer[]
     */
    public function loadIndexDefinitions()
    {
        $indexDefinitions = [];

        $jsonFiles = $this->getSortedJsonFiles();

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

        return !$jsonFileStorePrefix || in_array($jsonFileStorePrefix, $this->currentStorePrefixes);
    }

    /**
     * @return \Symfony\Component\Finder\SplFileInfo[]
     */
    protected function getSortedJsonFiles(): array
    {
        $finder = (new Finder())
            ->in($this->sourceDirectories)
            ->name('*' . self::FILE_EXTENSION);

        $result = [];
        foreach ($finder as $file) {
            $result[] = $file;
        }

        usort(
            $result,
            function (SplFileInfo $firstJsonFile, SplFileInfo $secondJsonFile): bool {
                $firstJsonFileStorePrefix = $this->getFileStorePrefix($firstJsonFile->getFilename());
                $secondJsonFileStorePrefix = $this->getFileStorePrefix($secondJsonFile->getFilename());

                return (int)((bool)$firstJsonFileStorePrefix > (bool)$secondJsonFileStorePrefix);
            }
        );

        return $result;
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
        foreach ($this->currentStorePrefixes as $storePrefix) {
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

        if (!$this->getFileStorePrefix($indexName)) {
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
        foreach ($this->availableStorePrefixes as $availableStorePrefix) {
            if (strpos($fileName, $availableStorePrefix) === 0) {
                return $availableStorePrefix;
            }
        }

        return null;
    }

    /**
     * @param array $stores
     *
     * @return array
     */
    protected function getStorePrefixes(array $stores)
    {
        array_walk($stores, function (&$store) {
            $store = mb_strtolower($store) . '_';
        });

        return $stores;
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
}
