<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Collector\Business\Internal;

use Elastica\Client;
use Spryker\Zed\Installer\Business\Model\AbstractInstaller;

class InstallElasticsearch extends AbstractInstaller
{

    /**
     * @var \Elastica\Client
     */
    protected $client;

    /**
     * @var string
     */
    protected $indexName;

    /**
     * @var int
     */
    protected $numberOfShards;

    /**
     * @var int
     */
    protected $numberOfReplicas;

    /**
     * @param \Elastica\Client $client
     * @param string $indexName
     * @param int $numberOfShards
     * @param int $numberOfReplicas
     */
    public function __construct(Client $client, $indexName, $numberOfShards = 1, $numberOfReplicas = 1)
    {
        $this->client = $client;
        $this->indexName = $indexName;
        $this->numberOfShards = $numberOfShards;
        $this->numberOfReplicas = $numberOfReplicas;
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
            $index->create([
                'number_of_shards' => $this->numberOfShards,
                'number_of_replicas' => $this->numberOfReplicas,
            ]);
        }
    }

}
