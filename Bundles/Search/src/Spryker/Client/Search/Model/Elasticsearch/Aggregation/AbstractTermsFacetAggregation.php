<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Model\Elasticsearch\Aggregation;

use Elastica\Aggregation\AbstractTermsAggregation;

abstract class AbstractTermsFacetAggregation extends AbstractFacetAggregation
{
    public const AGGREGATION_PARAM_SIZE = 'size';

    /**
     * @deprecated Use FacetConfigTransfer::setAggregationParams() instead
     *
     * @param \Elastica\Aggregation\AbstractTermsAggregation $aggregation
     * @param int|null $size
     *
     * @return void
     */
    protected function setTermsAggregationSize(AbstractTermsAggregation $aggregation, $size)
    {
        if ($size === null) {
            return;
        }

        $aggregation->setSize($size);
    }
}
