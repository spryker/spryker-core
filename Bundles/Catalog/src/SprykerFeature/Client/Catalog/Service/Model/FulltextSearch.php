<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Catalog\Service\Model;

use Elastica\Query;
use Symfony\Component\HttpFoundation\Request;

/**
 */
class FulltextSearch extends AbstractSearch
{

    /**
     * @param Request $request
     *
     * @return Query
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
     * @param Request $request
     * @param Query $searchQuery
     */
    protected function addFulltextSearchToQuery(Request $request, Query $searchQuery)
    {
        $searchString = $request->get('q');
        $searchQuery->setQuery(
            (new Query\Match())->setField('full-text', $searchString)
        );
    }

}
