<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Catalog\Model;

use Elastica\Query;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class FacetFilter
 */
interface FacetFilterHandlerInterface
{

    /**
     * @param \Elastica\Query $query
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function addFacetFiltersToQuery(Query $query, Request $request);

}
