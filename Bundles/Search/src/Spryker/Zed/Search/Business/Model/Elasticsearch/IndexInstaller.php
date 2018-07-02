<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Search\Business\Model\Elasticsearch;

use Elastica\Client;
use Elastica\Index;
use Elastica\Request;
use Elastica\Type\Mapping;
use Generated\Shared\Transfer\ElasticsearchIndexDefinitionTransfer;
use Psr\Log\LoggerInterface;
use Spryker\Zed\Search\Business\Model\Elasticsearch\Definition\IndexDefinitionLoaderInterface;
use Spryker\Zed\Search\Business\Model\SearchInstallerInterface;

class IndexInstaller implements SearchInstallerInterface
{
    protected const BLACKLIST_DELIMITER = '.';

    /**
     * @var \Spryker\Zed\Search\Business\Model\Elasticsearch\Definition\IndexDefinitionLoaderInterface
     */
    protected $indexDefinitionLoader;

    /**
     * @var \Elastica\Client
     */
    protected $elasticaClient;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $messenger;

    /**
     * @var string[]
     */
    protected $blacklistedSettings;

    /**
     * @param \Spryker\Zed\Search\Business\Model\Elasticsearch\Definition\IndexDefinitionLoaderInterface $indexDefinitionLoader
     * @param \Elastica\Client $elasticaClient
     * @param \Psr\Log\LoggerInterface $messenger
     * @param string[] $blacklistedSettings
     */
    public function __construct(
        IndexDefinitionLoaderInterface $indexDefinitionLoader,
        Client $elasticaClient,
        LoggerInterface $messenger,
        array $blacklistedSettings = []
    ) {
        $this->indexDefinitionLoader = $indexDefinitionLoader;
        $this->elasticaClient = $elasticaClient;
        $this->messenger = $messenger;
        $this->blacklistedSettings = $blacklistedSettings;
    }

    /**
     * @return void
     */
    public function install()
    {
        $indexDefinitions = $this->indexDefinitionLoader->loadIndexDefinitions();

        foreach ($indexDefinitions as $indexDefinition) {
            $this->createIndex($indexDefinition);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ElasticsearchIndexDefinitionTransfer $indexDefinitionTransfer
     *
     * @return void
     */
    protected function createIndex(ElasticsearchIndexDefinitionTransfer $indexDefinitionTransfer)
    {
        $index = $this->elasticaClient->getIndex($indexDefinitionTransfer->getIndexName());

        if (!$index->exists()) {
            $this->messenger->info(sprintf(
                'Creating elasticsearch index: "%s"',
                $indexDefinitionTransfer->getIndexName()
            ));

            $this->importMappingsToNewIndex($indexDefinitionTransfer, $index);
        } else {
            $this->importMappingsToExistingIndex($indexDefinitionTransfer, $index);
        }
    }

    /**
     * @param \Elastica\Index $index
     * @param string $mappingName
     * @param array $mappingData
     *
     * @return void
     */
    protected function sendMapping(Index $index, $mappingName, array $mappingData)
    {
        $this->messenger->info(sprintf(
            'Send mapping type "%s" (index: "%s")',
            $mappingName,
            $index->getName()
        ));

        $mapping = $this->createMappingByName($mappingData, $mappingName, $index);

        $mapping->send();
    }

    /**
     * @param \Generated\Shared\Transfer\ElasticsearchIndexDefinitionTransfer $indexDefinitionTransfer
     * @param \Elastica\Index $index
     *
     * @return array
     */
    protected function mergeMappings(ElasticsearchIndexDefinitionTransfer $indexDefinitionTransfer, Index $index)
    {
        $mappings = [];
        foreach ($indexDefinitionTransfer->getMappings() as $mappingName => $mappingData) {
            $mapping = $this->createMappingByName($mappingData, $mappingName, $index);
            $mappings = array_merge($mappings, $mapping->toArray());
        }
        return $mappings;
    }

    /**
     * @param array $mappingData
     * @param string $mappingName
     * @param \Elastica\Index $index
     *
     * @return \Elastica\Type\Mapping
     */
    protected function createMappingByName(array $mappingData, $mappingName, Index $index)
    {
        $type = $index->getType($mappingName);

        $mapping = new Mapping($type);
        foreach ($mappingData as $key => $value) {
            $mapping->setParam($key, $value);
        }
        return $mapping;
    }

    /**
     * @param \Generated\Shared\Transfer\ElasticsearchIndexDefinitionTransfer $indexDefinitionTransfer
     * @param \Elastica\Index $index
     *
     * @return void
     */
    protected function importMappingsToNewIndex(
        ElasticsearchIndexDefinitionTransfer $indexDefinitionTransfer,
        Index $index
    ) {

        $mappings = $this->mergeMappings($indexDefinitionTransfer, $index);

        $data = ['mappings' => $mappings];
        $settings = $indexDefinitionTransfer->getSettings();
        if ($settings) {
            $data['settings'] = $settings;
        }

        $this->messenger->info(sprintf(
            'Send all mappings. (index: "%s")',
            $index->getName()
        ));

        $index->request('', Request::PUT, $data);
    }

    /**
     * @param \Generated\Shared\Transfer\ElasticsearchIndexDefinitionTransfer $indexDefinitionTransfer
     * @param \Elastica\Index $index
     *
     * @return void
     */
    protected function importMappingsToExistingIndex(
        ElasticsearchIndexDefinitionTransfer $indexDefinitionTransfer,
        Index $index
    ) {
        foreach ($indexDefinitionTransfer->getMappings() as $mappingName => $mappingData) {
            $this->sendMapping($index, $mappingName, $mappingData);
        }

        $settings = $indexDefinitionTransfer->getSettings();
        if ($settings) {
            $settings = $this->removeBlacklistedSettings($settings);
            $index->setSettings($settings);
        }
    }

    /**
     * @param array $settings
     *
     * @return array
     */
    protected function removeBlacklistedSettings(array $settings): array
    {
        foreach ($this->blacklistedSettings as $settingPath) {
            $settings = $this->removeSetting($settings, $settingPath);
        }

        return $settings;
    }

    /**
     * @param array $settings
     * @param string $settingPath
     *
     * @return array
     */
    protected function removeSetting(array $settings, string $settingPath): array
    {
        $settingsElement = &$settings;
        $settingPathArray = explode(static::BLACKLIST_DELIMITER, $settingPath);

        foreach ($settingPathArray as $pathNumber => $step) {
            if (!isset($settingsElement[$step])) {
                break;
            }

            if ($pathNumber === count($settingPathArray) - 1) {
                unset($settingsElement[$step]);
                continue;
            }
            $settingsElement = &$settingsElement[$step];
        }

        return $settings;
    }
}
