<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Model\Elasticsearch\Aggregation;

use Generated\Shared\Transfer\FacetConfigTransfer;

/**
 * @deprecated Use `\Spryker\Client\SearchElasticsearch\Aggregation\FacetAggregationFactoryInterface` instead.
 */
interface FacetAggregationFactoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     *
     * @return \Spryker\Client\Search\Model\Elasticsearch\Aggregation\FacetAggregationInterface
     */
    public function create(FacetConfigTransfer $facetConfigTransfer);
}
