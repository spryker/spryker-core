<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Collector\Business\Exporter\Reader\Search;

use Elastica\Client;
use Spryker\Zed\Collector\Business\Exporter\Reader\ReaderInterface;

class ElasticsearchReader implements ReaderInterface
{

    const READER_NAME = 'elastic-search-reader';
    const META_ATTRIBUTE = '_meta';

    /**
     * @var \Elastica\Client
     */
    protected $client;

    /**
     * @var \Elastica\Index
     */
    protected $index;

    /**
     * @var string
     */
    protected $type;

    /**
     * @param \Elastica\Client $searchClient
     * @param string $indexName
     * @param string $type
     */
    public function __construct(Client $searchClient, $indexName, $type)
    {
        $this->client = $searchClient;
        $this->index = $this->client->getIndex($indexName);
        $this->type = $type;
    }

    /**
     * @param string $key
     * @param string $type
     *
     * @return \Elastica\Document
     */
    public function read($key, $type = '')
    {
        return $this->index->getType($this->type)->getDocument($key);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return self::READER_NAME;
    }

}
