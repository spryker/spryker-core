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
            $index->create(
                [
                    'number_of_shards' => 4,
                    'number_of_replicas' => 1,
                ]
            );
        }
    }

}
