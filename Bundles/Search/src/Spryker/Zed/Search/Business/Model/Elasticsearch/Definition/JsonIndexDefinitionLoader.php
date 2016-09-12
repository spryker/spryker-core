<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Search\Business\Model\Elasticsearch\Definition;

use Generated\Shared\Transfer\ElasticsearchIndexDefinitionTransfer;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Library\Json;
use Spryker\Shared\Search\SearchConstants;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class JsonIndexDefinitionLoader implements IndexDefinitionLoaderInterface
{

    const FILE_EXTENSION = '.json';

    /**
     * @var array
     */
    protected $sourceDirectories;

    /**
     * @var \Spryker\Zed\Search\Business\Model\Elasticsearch\Definition\IndexDefinitionMergerInterface
     */
    protected $definitionMerger;

    /**
     * @var array
     */
    protected $storePrefixes;

    /**
     * @param array $sourceDirectories
     * @param \Spryker\Zed\Search\Business\Model\Elasticsearch\Definition\IndexDefinitionMergerInterface $definitionMerger
     * @param array $stores
     */
    public function __construct(array $sourceDirectories, IndexDefinitionMergerInterface $definitionMerger, array $stores)
    {
        $this->sourceDirectories = $sourceDirectories;
        $this->definitionMerger = $definitionMerger;
        $this->storePrefixes = $this->getStorePrefixes($stores);
    }

    /**
     * @return \Generated\Shared\Transfer\ElasticsearchIndexDefinitionTransfer[]
     */
    public function loadIndexDefinitions()
    {
        $indexDefinitions = [];

        $jsonFiles = $this->getJsonFiles();
        foreach ($jsonFiles as $jsonFile) {
            $definitionData = Json::decode($jsonFile->getContents(), true);
            $indexDefinitions = $this->getDefinitionByStores($jsonFile, $indexDefinitions, $definitionData);
        }

        return $this->createIndexDefinitions($indexDefinitions);
    }

    /**
     * @return \Symfony\Component\Finder\Finder|\Symfony\Component\Finder\SplFileInfo[]
     */
    protected function getJsonFiles()
    {
        $finder = new Finder();
        $finder->in($this->sourceDirectories)->name('*' . self::FILE_EXTENSION);

        return $finder;
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
        foreach ($this->storePrefixes as $storePrefix) {
            $indexName = $this->getIndexName($jsonFile, $storePrefix);

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
     * @param \Symfony\Component\Finder\SplFileInfo $jsonFile
     * @param string $storePrefix
     *
     * @return mixed
     */
    protected function getIndexName(SplFileInfo $jsonFile, $storePrefix)
    {
        $indexName = substr($jsonFile->getFilename(), 0, -strlen(self::FILE_EXTENSION));
        $storePrefix = mb_strtolower($storePrefix);
        $fileStorePrefix = mb_strtolower(substr($indexName, 0, strlen($storePrefix)));

        if ($this->isPrefixable($storePrefix, $fileStorePrefix)) {
            $indexName = $storePrefix . $indexName;
        }

        $indexName = $this->addSearchIndexNameSuffix($indexName);

        return $indexName;
    }

    /**
     * @param array $stores
     *
     * @return array
     */
    protected function getStorePrefixes(array $stores)
    {
        array_walk($stores, function(&$store) {
            $store = mb_strtolower($store) . '_';
        });

        return $stores;
    }

    /**
     * @param $storePrefix
     * @param $fileStorePrefix
     *
     * @return bool
     */
    protected function isPrefixable($storePrefix, $fileStorePrefix)
    {
        if ($fileStorePrefix === $storePrefix) {
            return false;
        }

        $otherPrefixes = $this->storePrefixes;
        $index = array_search($storePrefix, $this->storePrefixes);
        unset($otherPrefixes[$index]);

        if (in_array($fileStorePrefix, $otherPrefixes)) {
            return false;
        }

        return true;
    }

    /**
     * @param string $indexName
     *
     * @return string
     */
    protected function addSearchIndexNameSuffix($indexName)
    {
        $suffix = Config::get(SearchConstants::SEARCH_INDEX_NAME_SUFFIX, '');
        $indexName .= $suffix;

        return $indexName;
    }

}
