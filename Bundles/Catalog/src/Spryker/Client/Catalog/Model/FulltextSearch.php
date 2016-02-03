<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Catalog\Model;

use Elastica\Query;
use Symfony\Component\HttpFoundation\Request;

/**
 */
class FulltextSearch extends AbstractSearch
{

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Elastica\Query
     */
    protected function createSearchQuery(Request $request)
    {
        $searchQuery = new Query();

        $this->addFacetAggregationToQuery($searchQuery);
        $this->addFacetFiltersToQuery($searchQuery, $request);

        $this->addSortingToQuery($searchQuery);
        $this->addFacetAggregationToQuery($searchQuery);
        $this->addFacetFiltersToQuery($searchQuery, $request);
        $this->addPaginationToQuery($searchQuery);
        $this->addFulltextSearchToQuery($request, $searchQuery);

        $searchQuery->setSource(['search-result-data']);

        return $searchQuery;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Elastica\Query $searchQuery
     *
     * @return void
     */
    protected function addFulltextSearchToQuery(Request $request, Query $searchQuery)
    {
        $searchString = $request->get('q');
        $searchQuery->setQuery(
            (new Query\Match())->setField('full-text', $searchString)
        );
    }

}
