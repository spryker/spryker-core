<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductReview\Pagination;

use Spryker\Client\Search\Dependency\Plugin\PaginationConfigBuilderInterface;
use Spryker\Client\Search\Dependency\Plugin\QueryInterface;

class PaginationExpander implements PaginationExpanderInterface
{
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
        /** @var \Elastica\Query $query */
        $query = $searchQuery->getSearchQuery();
        if (
            isset($requestParameters[static::PARAMETER_NAME_OFFSET])
            && isset($requestParameters[static::PARAMETER_NAME_LIMIT])
        ) {
            $query->setSize($requestParameters[static::PARAMETER_NAME_LIMIT]);
            $query->setFrom($requestParameters[static::PARAMETER_NAME_OFFSET]);

            return $searchQuery;
        }

        $currentPage = $this->paginationConfigBuilder->getCurrentPage($requestParameters);
        $itemsPerPage = $this->paginationConfigBuilder->getCurrentItemsPerPage($requestParameters);

        $query->setSize($itemsPerPage);
        $query->setFrom(($currentPage - 1) * $itemsPerPage);

        return $searchQuery;
    }
}
