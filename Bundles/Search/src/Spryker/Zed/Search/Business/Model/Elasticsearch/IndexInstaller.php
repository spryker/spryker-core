<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Search\Business\Model\Elasticsearch;

use Elastica\Client;
use Elastica\Index;
use Elastica\Type\Mapping;
use Generated\Shared\Transfer\ElasticsearchIndexDefinitionTransfer;
use Spryker\Zed\Messenger\Business\Model\MessengerInterface;
use Spryker\Zed\Search\Business\Model\Elasticsearch\Definition\IndexDefinitionLoaderInterface;
use Spryker\Zed\Search\Business\Model\SearchInstallerInterface;

class IndexInstaller implements SearchInstallerInterface
{

    /**
     * @var \Spryker\Zed\Search\Business\Model\Elasticsearch\Definition\IndexDefinitionLoaderInterface
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
     * @param \Spryker\Zed\Search\Business\Model\Elasticsearch\Definition\IndexDefinitionLoaderInterface $indexDefinitionLoader
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

            $settings = $indexDefinitionTransfer->getSettings();
            $index->create($settings);
        }

        foreach ($indexDefinitionTransfer->getMappings() as $mappingName => $mappingData) {
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

        foreach ($mappingData as $key => $value) {
            $mapping->setParam($key, $value);
        }

        $mapping->send();
    }

}
