<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
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
