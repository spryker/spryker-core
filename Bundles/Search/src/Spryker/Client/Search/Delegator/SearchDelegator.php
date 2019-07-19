<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Delegator;

use Elastica\Response as ElasticaResponse;
use Elastica\ResultSet;
use Exception;
use Spryker\Client\Search\Exception\SearchDelegatorException;
use Spryker\Client\Search\Response\Response;
use Spryker\Client\SearchExtension\Dependency\Plugin\ClientAdapterPluginInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;
use Spryker\Client\SearchExtension\Dependency\Response\ResponseInterface;

class SearchDelegator implements SearchDelegatorInterface
{
    /**
     * @var \Spryker\Client\SearchExtension\Dependency\Plugin\ClientAdapterPluginInterface[]
     */
    protected $searchAdapterPlugins;

    /**
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\ClientAdapterPluginInterface[] $searchAdapter
     */
    public function __construct(array $searchAdapter)
    {
        $this->searchAdapterPlugins = $searchAdapter;
    }

    /**
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $searchQuery
     * @param array $resultFormatters
     * @param array $requestParameters
     *
     * @throws \Exception
     *
     * @return \Elastica\ResultSet
     */
    public function search(QueryInterface $searchQuery, array $resultFormatters = [], array $requestParameters = []): ResultSet
    {
        if (!method_exists($searchQuery, 'getIndexName')) {
            throw new Exception(sprintf('Your query class "%s" must implement a "getIndexName()" method.', get_class($searchQuery)));
        }

        return $this->getSearchAdapterByIndexName($searchQuery->getIndexName())->search($searchQuery, $resultFormatters, $requestParameters);
    }

    /**
     * @param string|null $indexName
     *
     * @return int
     */
    public function getTotalCount(?string $indexName = null): int
    {
        if ($indexName !== null) {
            return $this->getSearchAdapterByIndexName($indexName)->getTotalCount($indexName);
        }

        $totalCount = 0;
        foreach ($this->searchAdapterPlugins as $searchAdapterPlugin) {
            $totalCount += $searchAdapterPlugin->getTotalCount();
        }

        return $totalCount;
    }

    /**
     * @param string|null $indexName
     *
     * @return array
     */
    public function getMetaData(?string $indexName = null): array
    {
        if ($indexName !== null) {
            return $this->getSearchAdapterByIndexName($indexName)->getMetaData($indexName);
        }

        $metaData = [];

        foreach ($this->searchAdapterPlugins as $searchAdapterPlugin) {
            $metaData[] = $searchAdapterPlugin->getMetaData();
        }

        return $metaData;
    }

    /**
     * @param string $key
     * @param string $indexName
     *
     * @return mixed
     */
    public function read(string $key, string $indexName)
    {
        return $this->getSearchAdapterByIndexName($indexName)->read($key, $indexName);
    }

    /**
     * @param string|null $indexName
     *
     * @return \Spryker\Client\SearchExtension\Dependency\Response\ResponseInterface
     */
    public function delete(?string $indexName = null): ResponseInterface
    {
        if ($indexName !== null) {
            return $this->getSearchAdapterByIndexName($indexName)->delete($indexName);
        }

        $responses = [];

        foreach ($this->searchAdapterPlugins as $searchAdapterPlugin) {
            $responses[] = $searchAdapterPlugin->delete();
        }

        return $this->mergeResponses($responses);
    }

    /**
     * @param array $responses
     *
     * @return \Spryker\Client\SearchExtension\Dependency\Response\ResponseInterface
     */
    protected function mergeResponses(array $responses): ResponseInterface
    {
        // TODO refactor to return real response.
        return new Response(new ElasticaResponse('foo', 200));
    }

    /**
     * @param array $searchDocumentTransfers
     *
     * @return bool
     */
    public function deleteDocuments(array $searchDocumentTransfers): bool
    {
        $result = false;

        foreach ($this->searchAdapterPlugins as $searchAdapterPlugin) {
            $result |= $searchAdapterPlugin->deleteDocuments($searchDocumentTransfers);
        }

        return (bool)$result;
    }

    /**
     * @param string $indexName
     *
     * @throws \Spryker\Client\Search\Exception\SearchDelegatorException
     *
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\ClientAdapterPluginInterface
     */
    protected function getSearchAdapterByIndexName(string $indexName): ClientAdapterPluginInterface
    {
        foreach ($this->searchAdapterPlugins as $searchAdapterPlugin) {
            if ($searchAdapterPlugin->isApplicable($indexName)) {
                return $searchAdapterPlugin;
            }
        }

        throw new SearchDelegatorException(sprintf(
            'None of the applied "%s"s is applicable. Please add one which is able to use the index "%s"',
            ClientAdapterPluginInterface::class,
            $indexName
        ));
    }
}
