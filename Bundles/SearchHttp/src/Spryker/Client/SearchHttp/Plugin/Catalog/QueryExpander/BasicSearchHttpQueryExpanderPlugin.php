<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchHttp\Plugin\Catalog\QueryExpander;

use Generated\Shared\Transfer\SearchQueryPaginationTransfer;
use Generated\Shared\Transfer\SearchQuerySortingTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryExpanderPluginInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;

/**
 * @method \Spryker\Client\SearchHttp\SearchHttpFactory getFactory()
 */
class BasicSearchHttpQueryExpanderPlugin extends AbstractPlugin implements QueryExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Adds pagination filter to query transfer.
     * - Adds sorting to query transfer.
     *
     * @api
     *
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $searchQuery
     * @param array<string, mixed> $requestParameters
     *
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface
     */
    public function expandQuery(QueryInterface $searchQuery, array $requestParameters = []): QueryInterface
    {
        $searchQuery = $this->addPaginationToQuery($searchQuery, $requestParameters);

        return $this->addSortingToQuery($searchQuery, $requestParameters);
    }

    /**
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $searchQuery
     * @param array<string, mixed> $requestParameters
     *
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface
     */
    protected function addPaginationToQuery(QueryInterface $searchQuery, array $requestParameters): QueryInterface
    {
        $paginationConfig = $this->getFactory()->getSearchConfig()->getPaginationConfig();
        $currentPage = $paginationConfig->getCurrentPage($requestParameters);
        $itemsPerPage = $paginationConfig->getCurrentItemsPerPage($requestParameters);

        $searchQuery->getSearchQuery()->setPagination(
            (new SearchQueryPaginationTransfer())
                ->setPage($currentPage)
                ->setItemsPerPage($itemsPerPage),
        );

        return $searchQuery;
    }

    /**
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $searchQuery
     * @param array<string, mixed> $requestParameters
     *
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface
     */
    protected function addSortingToQuery(QueryInterface $searchQuery, array $requestParameters): QueryInterface
    {
        $sortConfig = $this->getFactory()->getSearchConfig()->getSortConfig();
        $sortParamName = $sortConfig->getActiveCleanedParamName($requestParameters);

        if ($sortParamName) {
            $searchQuery->getSearchQuery()->setSort(
                (new SearchQuerySortingTransfer())
                    ->setFieldName($sortParamName)
                    ->setSortDirection($sortConfig->getSortDirection($requestParameters)),
            );
        }

        return $searchQuery;
    }
}
