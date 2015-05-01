<?php

namespace SprykerFeature\Sdk\Catalog\Model;

use Elastica\Query;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class FacetFilter
 * @package SprykerFeature\Sdk\Catalog\Model
 */
interface FacetFilterHandlerInterface
{
    /**
     * @param Query   $query
     * @param Request $request
     */
    public function addFacetFiltersToQuery(Query $query, Request $request);
}