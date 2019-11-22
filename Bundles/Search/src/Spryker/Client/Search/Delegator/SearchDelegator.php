<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Delegator;

use Exception;
use Generated\Shared\Transfer\SearchContextTransfer;
use Spryker\Client\Search\Exception\SearchDelegatorException;
use Spryker\Client\Search\SearchContext\SourceIdentifierMapperInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\SearchAdapterPluginInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\SearchContextAwareQueryInterface;

class SearchDelegator implements SearchDelegatorInterface
{
    /**
     * @var \Spryker\Client\SearchExtension\Dependency\Plugin\SearchAdapterPluginInterface[]
     */
    protected $searchAdapterPlugins;

    /**
     * @var \Spryker\Client\Search\SearchContext\SourceIdentifierMapperInterface
     */
    protected $sourceIdentifierMapper;

    /**
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\SearchAdapterPluginInterface[] $searchAdapter
     * @param \Spryker\Client\Search\SearchContext\SourceIdentifierMapperInterface $sourceIdentifierMapper
     */
    public function __construct(array $searchAdapter, SourceIdentifierMapperInterface $sourceIdentifierMapper)
    {
        $this->searchAdapterPlugins = $searchAdapter;
        $this->sourceIdentifierMapper = $sourceIdentifierMapper;
    }

    /**
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $searchQuery
     * @param array $resultFormatters
     * @param array $requestParameters
     *
     * @throws \Exception
     *
     * @return mixed
     */
    public function search(QueryInterface $searchQuery, array $resultFormatters = [], array $requestParameters = [])
    {
        if (!$searchQuery instanceof SearchContextAwareQueryInterface) {
            throw new Exception(sprintf('Your query class "%s" must implement %s interface.', get_class($searchQuery), SearchContextAwareQueryInterface::class));
        }

        $searchQuery = $this->mapSearchContextTransferForQuery($searchQuery);

        return $this->getSearchAdapterByIndexName($searchQuery->getSearchContext())
            ->search($searchQuery, $resultFormatters, $requestParameters);
    }

    /**
     * @param string|null $indexName
     *
     * @return int
     */
    public function getTotalCount(?string $indexName = null): int
    {
        return 0;
    }

    /**
     * @param string|null $indexName
     *
     * @return array
     */
    public function getMetaData(?string $indexName = null): array
    {
        return [];
    }

    /**
     * @param string $key
     * @param string $indexName
     *
     * @return mixed
     */
    public function read(string $key, string $indexName)
    {
        return true;
    }

    /**
     * @param string|null $indexName
     *
     * @return bool
     */
    public function delete(?string $indexName = null): bool
    {
        return true;
    }

    /**
     * @param array $searchDocumentTransfers
     *
     * @return bool
     */
    public function deleteDocuments(array $searchDocumentTransfers): bool
    {
        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\SearchContextTransfer $searchContextTransfer
     *
     * @throws \Spryker\Client\Search\Exception\SearchDelegatorException
     *
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\SearchAdapterPluginInterface
     */
    protected function getSearchAdapterByIndexName(SearchContextTransfer $searchContextTransfer): SearchAdapterPluginInterface
    {
        foreach ($this->searchAdapterPlugins as $searchAdapterPlugin) {
            if ($searchAdapterPlugin->isApplicable($searchContextTransfer)) {
                return $searchAdapterPlugin;
            }
        }

        throw new SearchDelegatorException(sprintf(
            'None of the applied "%s"s is applicable for the specified context.',
            SearchAdapterPluginInterface::class
        ));
    }

    /**
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $searchQuery
     *
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface
     */
    protected function mapSearchContextTransferForQuery(QueryInterface $searchQuery): QueryInterface
    {
        $mappedSearchContextTransfer = $this->sourceIdentifierMapper
            ->mapSourceIdentifier($searchQuery->getSearchContext());
        $searchQuery->setSearchContext($mappedSearchContextTransfer);

        return $searchQuery;
    }
}
