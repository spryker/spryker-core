<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Synchronization\Dependency\Client;

class SynchronizationToSearchBridge implements SynchronizationToSearchInterface
{

    /**
     * @var \Spryker\Client\Search\SearchClientInterface
     */
    protected $searchClient;

    /**
     * @param \Spryker\Client\Search\SearchClientInterface $searchClient
     */
    public function __construct($searchClient)
    {
        $this->searchClient = $searchClient;
    }

    /**
     * @param string $key
     * @param string $type
     * @param string $typeName
     * @param string $indexName
     *
     * @return mixed
     */
    public function read($key, $type = '', $typeName = '', $indexName = '')
    {
        return $this->searchClient->read($key, $type);
    }

    /**
     * @param array $dataSet
     * @param string $typeName
     * @param string $indexName
     *
     * @return bool
     */
    public function write(array $dataSet, $typeName = '', $indexName = '')
    {
        $this->searchClient->write($dataSet, $typeName, $indexName);
    }

    /**
     * @param array $dataSet
     * @param string $typeName
     * @param string $indexName
     *
     * @return bool
     */
    public function delete(array $dataSet, $typeName = '', $indexName = '')
    {
        $this->searchClient->delete($dataSet, $typeName, $indexName);
    }

}
