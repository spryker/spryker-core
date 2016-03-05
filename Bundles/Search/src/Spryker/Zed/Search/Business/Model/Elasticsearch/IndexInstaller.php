<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Search\Business\Model\Elasticsearch;

use Elastica\Client;
use Elastica\Type\Mapping;

class IndexInstaller
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
     * @param \Spryker\Zed\Search\Business\Model\Elasticsearch\IndexDefinitionLoaderInterface $indexDefinitionLoader
     * @param \Elastica\Client $elasticaClient
     */
    public function __construct(IndexDefinitionLoaderInterface $indexDefinitionLoader, Client $elasticaClient)
    {
        $this->indexDefinitionLoader = $indexDefinitionLoader;
        $this->elasticaClient = $elasticaClient;
    }

    /**
     * @return void
     */
    public function install()
    {
        $indexDefinitions = $this->indexDefinitionLoader->loadIndexDefinitions();
        foreach ($indexDefinitions as $indexDefinition) {
            $index = $this->elasticaClient->getIndex($indexDefinition->getIndexName());
            if (!$index->exists()) {
                $index->create($indexDefinition->getSettings());
            }

            foreach ($indexDefinition->getMappingTypes() as $mappingType) {
                $type = $index->getType($mappingType[IndexDefinition::NAME]);

                if ($type->exists()) {
                    continue;
                }

                $mapping = new Mapping($type);
                $mapping->setProperties($mappingType[IndexDefinition::MAPPING]);
                $mapping->send();
            }
        }
    }

}
