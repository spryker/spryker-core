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
use Spryker\Zed\Search\Business\Exception\MissingIndexStateException;
use Spryker\Zed\Search\Business\Model\Elasticsearch\Definition\IndexDefinitionLoaderInterface;
use Spryker\Zed\Search\Business\Model\SearchInstallerInterface;
use Spryker\Zed\Search\SearchConfig;

/**
 * @deprecated Use `\Spryker\Zed\SearchElasticsearch\Business\Installer\Index\Install\IndexInstaller` instead.
 */
class IndexInstaller implements SearchInstallerInterface
{
    protected const SETTING_PATH_DELIMITER = '.';

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
     * @var \Spryker\Zed\Search\SearchConfig
     */
    protected $searchConfig;

    /**
     * @param \Spryker\Zed\Search\Business\Model\Elasticsearch\Definition\IndexDefinitionLoaderInterface $indexDefinitionLoader
     * @param \Elastica\Client $elasticaClient
     * @param \Psr\Log\LoggerInterface $messenger
     * @param \Spryker\Zed\Search\SearchConfig $searchConfig
     */
    public function __construct(
        IndexDefinitionLoaderInterface $indexDefinitionLoader,
        Client $elasticaClient,
        LoggerInterface $messenger,
        SearchConfig $searchConfig
    ) {
        $this->indexDefinitionLoader = $indexDefinitionLoader;
        $this->elasticaClient = $elasticaClient;
        $this->messenger = $messenger;
        $this->searchConfig = $searchConfig;
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
            $this->updateIndexSettings($indexDefinitionTransfer, $index);
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
    }

    /**
     * @param \Generated\Shared\Transfer\ElasticsearchIndexDefinitionTransfer $indexDefinitionTransfer
     * @param \Elastica\Index $index
     *
     * @return void
     */
    protected function updateIndexSettings(
        ElasticsearchIndexDefinitionTransfer $indexDefinitionTransfer,
        Index $index
    ): void {
        $settings = $indexDefinitionTransfer->getSettings();
        $indexState = $this->getIndexState($index);

        if (!$settings) {
            return;
        }

        $settings = $this->filterSettingsByIndexState($indexState, $settings);
        $settings = $this->removeBlacklistedSettings($settings);

        if ($this->isSettingsForUpdateExists($settings)) {
            $index->setSettings($settings);
        }
    }

    /**
     * @param string[] $settings
     *
     * @return bool
     */
    protected function isSettingsForUpdateExists(array $settings): bool
    {
        $settings = array_filter($settings, function ($setting) {
            return !empty($setting);
        });

        return !empty($settings);
    }

    /**
     * @param string $indexState
     * @param string[] $settings
     *
     * @return string[]
     */
    protected function filterSettingsByIndexState(string $indexState, array $settings): array
    {
        $notUpdatableIndexSettings = [];

        if ($indexState === SearchConfig::INDEX_OPEN_STATE) {
            $notUpdatableIndexSettings = $this->searchConfig->getStaticIndexSettings();
            $this->messenger->info('Index is open, updating dynamic settings.');
        }

        if ($indexState === SearchConfig::INDEX_CLOSE_STATE) {
            $notUpdatableIndexSettings = $this->searchConfig->getDynamicIndexSettings();
            $this->messenger->info('Index is closed, updating static settings.');
        }

        foreach ($notUpdatableIndexSettings as $notUpdatableIndexSettingPath) {
            $settings = $this->removeSettingPath($settings, $notUpdatableIndexSettingPath);
        }

        return $settings;
    }

    /**
     * @param \Elastica\Index $index
     *
     * @throws \Spryker\Zed\Search\Business\Exception\MissingIndexStateException
     *
     * @return string
     */
    protected function getIndexState(Index $index): string
    {
        $clusterState = $index->getClient()->getCluster()->getState();

        if (isset($clusterState['metadata']['indices'][$index->getName()]['state'])) {
            return $clusterState['metadata']['indices'][$index->getName()]['state'];
        }

        throw new MissingIndexStateException('Can not determine index state.');
    }

    /**
     * @param array $settings
     *
     * @return array
     */
    protected function removeBlacklistedSettings(array $settings): array
    {
        $blacklistSettingsForIndexUpdate = $this->searchConfig->getBlacklistSettingsForIndexUpdate();

        foreach ($blacklistSettingsForIndexUpdate as $blacklistedSettingPath) {
            $settings = $this->removeSettingPath($settings, $blacklistedSettingPath);
        }

        return $settings;
    }

    /**
     * @param array $settings
     * @param string $removeSettingPath
     *
     * @return array
     */
    protected function removeSettingPath(array $settings, string $removeSettingPath): array
    {
        $settingsElement = &$settings;
        $settingPathArray = explode(static::SETTING_PATH_DELIMITER, $removeSettingPath);
        $lastPathNumber = $this->getLastPathNumber($settingPathArray);

        foreach ($settingPathArray as $pathNumber => $settingElementKey) {
            if (!isset($settingsElement[$settingElementKey])) {
                return $settings;
            }

            if ($pathNumber === $lastPathNumber) {
                unset($settingsElement[$settingElementKey]);

                return $settings;
            }
            $settingsElement = &$settingsElement[$settingElementKey];
        }

        return $settings;
    }

    /**
     * @param array $settingPathArray
     *
     * @return int
     */
    protected function getLastPathNumber(array $settingPathArray): int
    {
        end($settingPathArray);

        return key($settingPathArray);
    }
}
