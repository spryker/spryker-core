<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Model\Elasticsearch\Reader;

use Elastica\Client;

class Reader implements ReaderInterface
{
    public const READER_NAME = 'elastic-search-reader';
    public const META_ATTRIBUTE = '_meta';

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
     * @param string|null $typeName
     * @param string|null $indexName
     *
     * @return \Elastica\Document
     */
    public function read($key, $typeName = null, $indexName = null)
    {
        $defaultType = $this->type;
        $defaultIndex = $this->index;

        if ($typeName) {
            $defaultType = $typeName;
        }

        if ($indexName) {
            $defaultIndex = $this->client->getIndex($indexName);
        }

        return $defaultIndex->getType($defaultType)->getDocument($key);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return static::READER_NAME;
    }
}
