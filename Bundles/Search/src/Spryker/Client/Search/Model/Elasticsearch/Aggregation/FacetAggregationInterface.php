<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Model\Elasticsearch\Aggregation;

/**
 * @deprecated Use `\Spryker\Client\SearchElasticsearch\Aggregation\FacetAggregationInterface` instead.
 */
interface FacetAggregationInterface
{
    /**
     * @return \Elastica\Aggregation\AbstractAggregation
     */
    public function createAggregation();
}
