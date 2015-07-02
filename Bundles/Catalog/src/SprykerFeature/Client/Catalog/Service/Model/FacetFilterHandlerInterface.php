<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Catalog\Service\Model;

use Elastica\Query;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class FacetFilter
 * @package SprykerFeature\Client\Catalog\Service\Model
 */
interface FacetFilterHandlerInterface
{
    /**
     * @param Query   $query
     * @param Request $request
     */
    public function addFacetFiltersToQuery(Query $query, Request $request);
}
