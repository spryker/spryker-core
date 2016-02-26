<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Catalog\Model\Query\Decorator;

use Elastica\Query;
use Spryker\Client\Catalog\Model\FacetConfig;
use Spryker\Client\Search\Model\Query\Decorator\AbstractQueryDecorator;
use Spryker\Client\Search\Model\Query\QueryInterface;

class SortedQuery extends AbstractQueryDecorator
{

    /**
     * @var \Spryker\Client\Catalog\Model\FacetConfig
     */
    protected $facetConfig;

    /**
     * @var array
     */
    protected $parameters;

    /**
     * @param \Spryker\Client\Search\Model\Query\QueryInterface $searchQuery
     * @param \Spryker\Client\Catalog\Model\FacetConfig $facetConfig
     * @param array $parameters
     */
    public function __construct(QueryInterface $searchQuery, FacetConfig $facetConfig, array $parameters)
    {
        parent::__construct($searchQuery);

        $this->facetConfig = $facetConfig;
        $this->parameters = $parameters;
    }

    /**
     * @return \Elastica\Query
     */
    public function getSearchQuery()
    {
        return $this->addSortingToQuery($this->searchQuery->getSearchQuery());
    }

    /**
     * @param \Elastica\Query $query
     *
     * @return \Elastica\Query
     */
    protected function addSortingToQuery(Query $query)
    {
        if (isset($this->parameters['sort'])) {
            // TODO: Move these outside somehow
            $sortParam = $this->parameters['sort'];
            $sortOrder = isset($this->parameters['sort_order']) ? $this->parameters['sort_order'] : 'asc';

            $sortField = $this->facetConfig->getSortFieldFromParam($sortParam);
            $nestedSortField = implode('.', [$sortField, $sortParam]);
            $query->setSort(
                [
                    $nestedSortField => [
                        'order' => $sortOrder,
                        'mode' => 'min',
                    ],
                ]
            );
        }

        return $query;
    }

}
