<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Collector\Business\Internal;

use Elastica\Client;
use SprykerFeature\Zed\Installer\Business\Model\AbstractInstaller;

class InstallElasticsearch extends AbstractInstaller
{

    /**
     * @var Client
     */
    private $client;

    /**
     * @var
     */
    private $indexName;

    /**
     * @param Client $client
     * @param string $indexName
     */
    public function __construct(Client $client, $indexName)
    {
        $this->client = $client;
        $this->indexName = $indexName;
    }

    /**
     */
    public function install()
    {
        $this->createIndex();
    }

    /**
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
