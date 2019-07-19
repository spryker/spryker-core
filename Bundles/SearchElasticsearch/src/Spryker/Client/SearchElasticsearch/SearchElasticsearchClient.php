<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchElasticsearch;

use Elastica\ResultSet;
use Spryker\Client\Kernel\AbstractClient;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;
use Spryker\Client\SearchExtension\Dependency\Response\ResponseInterface;

/**
 * @method \Spryker\Client\SearchElasticsearch\SearchElasticsearchFactory getFactory()
 */
class SearchElasticsearchClient extends AbstractClient implements SearchElasticsearchClientInterface
{
    /**
     * @api
     *
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $searchQuery
     * @param array $resultFormatters
     * @param array $requestParameters
     *
     * @return \Elastica\ResultSet
     */
    public function search(QueryInterface $searchQuery, array $resultFormatters = [], array $requestParameters = []): ResultSet
    {
        return $this->getFactory()->createSearch()->search($searchQuery, $resultFormatters, $requestParameters);
    }

    /**
     * @api
     *
     * @param string|null $indexName
     *
     * @return int
     */
    public function getTotalCount(?string $indexName = null): int
    {
        return $this->getFactory()->createSearch()->getTotalCount($indexName);
    }

    /**
     * @api
     *
     * @param string|null $indexName
     *
     * @return array
     */
    public function getMetaData(?string $indexName = null): array
    {
        return $this->getFactory()->createSearch()->getMetaData($indexName);
    }

    /**
     * @api
     *
     * @param string $key
     * @param string $indexName
     *
     * @return mixed
     */
    public function read(string $key, string $indexName)
    {
        return $this->getFactory()->createSearch()->read($key, $indexName);
    }

    /**
     * @api
     *
     * @param string|null $indexName
     *
     * @return \Spryker\Client\SearchExtension\Dependency\Response\ResponseInterface
     */
    public function delete(?string $indexName = null): ResponseInterface
    {
        return $this->getFactory()->createSearch()->delete($indexName);
    }

    /**
     * @api
     *
     * @param array $searchDocumentTransfers
     *
     * @return bool
     */
    public function deleteDocuments(array $searchDocumentTransfers): bool
    {
        return $this->getFactory()->createSearch()->deleteDocuments($searchDocumentTransfers);
    }
}
