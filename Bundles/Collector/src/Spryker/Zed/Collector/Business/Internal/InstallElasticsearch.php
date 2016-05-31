<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Collector\Business\Internal;

use Elastica\Client;
use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Shared\Config\Config;
use Spryker\Zed\Installer\Business\Model\AbstractInstaller;

class InstallElasticsearch extends AbstractInstaller
{

    /**
     * @var \Elastica\Client
     */
    private $client;

    /**
     * @var string
     */
    private $indexName;

    /**
     * @param \Elastica\Client $client
     * @param string $indexName
     */
    public function __construct(Client $client, $indexName)
    {
        $this->client = $client;
        $this->indexName = $indexName;
    }

    /**
     * @return void
     */
    public function install()
    {
        $this->createIndex();
    }

    /**
     * @return void
     */
    protected function createIndex()
    {
        $index = $this->client->getIndex($this->indexName);

        if (!$index->exists()) {
            $numberOfShards = 1;
            $numberOfReplicas = 1;

            if (Config::hasValue(ApplicationConstants::ELASTICA_NUMBER_OF_SHARDS)) {
                $numberOfShards = Config::get(ApplicationConstants::ELASTICA_NUMBER_OF_SHARDS);
            }

            if (Config::hasValue(ApplicationConstants::ELASTICA_NUMBER_OF_REPLICAS)) {
                $numberOfReplicas = Config::get(ApplicationConstants::ELASTICA_NUMBER_OF_REPLICAS);
            }

            $index->create([
                'number_of_shards' => $numberOfShards,
                'number_of_replicas' => $numberOfReplicas,
            ]);
        }
    }

}
