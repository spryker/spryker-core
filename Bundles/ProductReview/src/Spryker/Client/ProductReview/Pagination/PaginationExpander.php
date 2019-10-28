<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductReview\Pagination;

use Elastica\Query;
use Spryker\Client\Search\Dependency\Plugin\PaginationConfigBuilderInterface;
use Spryker\Client\Search\Dependency\Plugin\QueryInterface;

class PaginationExpander implements PaginationExpanderInterface
{
    /**
     * @uses \Spryker\Client\Catalog\Plugin\Config\CatalogSearchConfigBuilder::PARAMETER_NAME_ITEMS_PER_PAGE;
     */
    protected const PARAMETER_NAME_ITEMS_PER_PAGE = 'ipp';
    protected const PARAMETER_NAME_OFFSET = 'offset';
    protected const PARAMETER_NAME_LIMIT = 'limit';

    /**
     * @var \Spryker\Client\Search\Dependency\Plugin\PaginationConfigBuilderInterface
     */
    protected $paginationConfigBuilder;

    /**
     * @param \Spryker\Client\Search\Dependency\Plugin\PaginationConfigBuilderInterface $paginationConfigBuilder
     */
    public function __construct(PaginationConfigBuilderInterface $paginationConfigBuilder)
    {
        $this->paginationConfigBuilder = $paginationConfigBuilder;
    }

    /**
     * @param \Spryker\Client\Search\Dependency\Plugin\QueryInterface $searchQuery
     * @param array $requestParameters
     *
     * @return \Spryker\Client\Search\Dependency\Plugin\QueryInterface
     */
    public function addPaginationToQuery(QueryInterface $searchQuery, array $requestParameters = []): QueryInterface
    {
        $currentPage = $this->paginationConfigBuilder->getCurrentPage($requestParameters);
        $itemsPerPage = $this->paginationConfigBuilder->getCurrentItemsPerPage($requestParameters);

        /** @var \Elastica\Query $query */
        $query = $searchQuery->getSearchQuery();

        if (!empty($requestParameters[static::PARAMETER_NAME_ITEMS_PER_PAGE])) {
            $query->setSize($requestParameters[static::PARAMETER_NAME_ITEMS_PER_PAGE]);

            return $searchQuery;
        }

        if (!empty($requestParameters[static::PARAMETER_NAME_OFFSET])
            && !empty($requestParameters[static::PARAMETER_NAME_LIMIT])
        ) {
            $query->setSize($requestParameters[static::PARAMETER_NAME_LIMIT]);
            $query->setFrom(($requestParameters[static::PARAMETER_NAME_OFFSET] / $requestParameters[static::PARAMETER_NAME_LIMIT]) + 1);

            return $searchQuery;
        }

        $query->setSize($itemsPerPage);
        $query->setFrom(($currentPage - 1) * $itemsPerPage);

        return $searchQuery;
    }

    /**
     * @param array $requestParameters
     * @param string $parameter
     *
     * @return bool
     */
    protected function validateParameter(array $requestParameters, string $parameter): bool
    {
        return (!empty($requestParameters[$parameter]));
    }

    /**
     * @param \Elastica\Query $query
     * @param int $currentPage
     * @param int $itemsPerPage
     * @param array $requestParameters
     *
     * @return \Elastica\Query
     */
    protected function setFrom(Query $query, int $currentPage, int $itemsPerPage, array $requestParameters): Query
    {
        if ($this->validateParameter($requestParameters, static::PARAMETER_NAME_LIMIT)
            && $this->validateParameter($requestParameters, static::PARAMETER_NAME_OFFSET)
        ) {
            return $query->setFrom(($currentPage / $itemsPerPage) + 1);
        }

        return $query->setFrom(($currentPage - 1) * $itemsPerPage);
    }
}
