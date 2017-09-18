<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Model\Elasticsearch\Reader;

use Elastica\Client;

class Reader implements ReaderInterface
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
     * @param string $typeName
     * @param string $indexName
     *
     * @return \Elastica\Document
     */
    public function read($key, $type = '', $typeName = '', $indexName = '')
    {
        if (!empty($typeName)) {
            $this->type = $typeName;
        }

        if (!empty($indexName)) {
            $this->index = $this->client->getIndex($indexName);
        }

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
