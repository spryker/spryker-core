<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Search\Business\Model\Elasticsearch;

use Elastica\Client;
use Elastica\Index;
use Elastica\Type;
use Elastica\Type\Mapping;
use Spryker\Zed\Messenger\Business\Model\MessengerInterface;
use Spryker\Zed\Search\Business\Model\SearchInstallerInterface;

class IndexInstaller implements SearchInstallerInterface
{

    /**
     * @var \Spryker\Zed\Search\Business\Model\Elasticsearch\IndexDefinitionLoaderInterface
     */
    protected $indexDefinitionLoader;

    /**
     * @var \Elastica\Client
     */
    protected $elasticaClient;

    /**
     * @var \Spryker\Zed\Messenger\Business\Model\MessengerInterface
     */
    protected $messenger;

    /**
     * @param \Spryker\Zed\Search\Business\Model\Elasticsearch\IndexDefinitionLoaderInterface $indexDefinitionLoader
     * @param \Elastica\Client $elasticaClient
     * @param \Spryker\Zed\Messenger\Business\Model\MessengerInterface $messenger
     */
    public function __construct(IndexDefinitionLoaderInterface $indexDefinitionLoader, Client $elasticaClient, MessengerInterface $messenger)
    {
        $this->indexDefinitionLoader = $indexDefinitionLoader;
        $this->elasticaClient = $elasticaClient;
        $this->messenger = $messenger;
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
     * @param \Spryker\Zed\Search\Business\Model\Elasticsearch\IndexDefinition $indexDefinition
     *
     * @return void
     */
    protected function createIndex(IndexDefinition $indexDefinition)
    {
        $index = $this->elasticaClient->getIndex($indexDefinition->getIndexName());

        if (!$index->exists()) {
            $this->messenger->info(sprintf(
                'Creating elasticsearch index: "%s"',
                $indexDefinition->getIndexName()
            ));

            $settings = $indexDefinition->getSettings();
            $index->create($settings);
        }

        foreach ($indexDefinition->getMappings() as $mappingName => $mappingData) {
            $this->createMapping($index, $mappingName, $mappingData);
        }
    }

    /**
     * @param \Elastica\Index $index
     * @param string $mappingName
     * @param array $mappingData
     *
     * @return void
     */
    protected function createMapping(Index $index, $mappingName, array $mappingData)
    {
        $type = $index->getType($mappingName);
        if ($type->exists()) {
            return;
        }

        $this->messenger->info(sprintf(
            'Creating mapping "%s" for index: "%s"',
            $mappingName,
            $index->getName()
        ));

        $mapping = new Mapping($type);
        $mapping->setProperties($mappingData);
        $mapping->send();
    }

}
