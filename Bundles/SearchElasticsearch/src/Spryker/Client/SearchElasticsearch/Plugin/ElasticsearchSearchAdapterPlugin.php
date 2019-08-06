<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchElasticsearch\Plugin;

use Elastica\ResultSet;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\SearchAdapterPluginInterface;
use Spryker\Client\SearchExtension\Dependency\Response\ResponseInterface;

/**
 * @method \Spryker\Client\SearchElasticsearch\SearchElasticsearchClientInterface getClient()
 */
class ElasticsearchSearchAdapterPlugin extends AbstractPlugin implements SearchAdapterPluginInterface
{
    /**
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $searchQuery
     * @param array $resultFormatters
     * @param array $requestParameters
     *
     * @return \Elastica\ResultSet
     */
    public function search(QueryInterface $searchQuery, array $resultFormatters = [], array $requestParameters = []): ResultSet
    {
        return $this->getClient()->search($searchQuery, $resultFormatters, $requestParameters);
    }

    /**
     * @param string|null $indexName
     *
     * @return int
     */
    public function getTotalCount(?string $indexName = null): int
    {
        return $this->getClient()->getTotalCount($indexName);
    }

    /**
     * @param string|null $indexName
     *
     * @return array
     */
    public function getMetaData(?string $indexName = null): array
    {
        return $this->getClient()->getMetaData($indexName);
    }

    /**
     * @param string $key
     * @param string $indexName
     *
     * @return mixed
     */
    public function read(string $key, string $indexName)
    {
        // TODO add real return value
        return 'foo';
    }

    /**
     * @param string|null $indexName
     *
     * @return \Spryker\Client\SearchExtension\Dependency\Response\ResponseInterface
     */
    public function delete(?string $indexName = null): ResponseInterface
    {
        return $this->getClient()->delete($indexName);
    }

    /**
     * @param array $searchDocumentTransfers
     *
     * @return bool
     */
    public function deleteDocuments(array $searchDocumentTransfers): bool
    {
        // TODO add real return value
        return false;
    }

    /**
     * @param string $indexName
     *
     * @return bool
     */
    public function isApplicable(string $indexName): bool
    {
        return true;
    }
}
