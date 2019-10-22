<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchElasticsearch\Query;

use Elastica\Query\AbstractQuery;
use Generated\Shared\Transfer\FacetConfigTransfer;

interface QueryFactoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     * @param mixed $filterValue
     *
     * @return \Elastica\Query\AbstractQuery
     */
    public function createQuery(FacetConfigTransfer $facetConfigTransfer, $filterValue): AbstractQuery;
}
