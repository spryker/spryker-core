<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchElasticsearch\Search;

use Elastica\Client;
use Elastica\Query;
use Elastica\Response as ElasticaResponse;
use Elastica\Result;
use Elastica\ResultSet;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;

class Search implements SearchInterface
{
    /**
     * @var \Elastica\Client
     */
    protected $client;

    /**
     * @param \Elastica\Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * {@inheritdoc}
     *
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $searchQuery
     * @param array $resultFormatters
     * @param array $requestParameters
     *
     * @return array|\Elastica\ResultSet
     */
    public function search(QueryInterface $searchQuery, array $resultFormatters = [], array $requestParameters = [])
    {
        // TODO return real value;
        return new ResultSet(new ElasticaResponse('foo'), new Query([]), [new Result([])]);
    }

    /**
     * {@inheritdoc}
     *
     * @param string|null $indexName
     *
     * @return int
     */
    public function getTotalCount(?string $indexName = null): int
    {
        if (!$indexName) {
            $indexName = '_all';
        }

        return $this->client->getIndex($indexName)->count();
    }

    /**
     * {@inheritdoc}
     *
     * @param string|null $indexName
     *
     * @return array
     */
    public function getMetaData(?string $indexName = null): array
    {
        if (!$indexName) {
            $indexName = '_all';
        }

        return $this->client->getIndex($indexName)->getMapping();
    }

    /**
     * {@inheritdoc}
     *
     * @param string $key
     * @param string $indexName
     *
     * @return mixed
     */
    public function read(string $key, string $indexName)
    {
        // TODO return real value;
        return 'foo';
    }

    /**
     * {@inheritdoc}
     *
     * @param string|null $indexName
     *
     * @return bool
     */
    public function delete(?string $indexName = null): bool
    {
        if (!$indexName) {
            $indexName = '_all';
        }

        // TODO return real value;
        return false;
    }

    /**
     * {@inheritdoc}
     *
     * @param array $searchDocumentTransfers
     *
     * @return bool
     */
    public function deleteDocuments(array $searchDocumentTransfers): bool
    {
        // TODO return real value;
        return false;
    }
}
