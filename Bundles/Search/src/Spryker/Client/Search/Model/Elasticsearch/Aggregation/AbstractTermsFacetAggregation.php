<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Model\Elasticsearch\Aggregation;

use Elastica\Aggregation\AbstractAggregation;
use Elastica\Aggregation\AbstractTermsAggregation;
use Generated\Shared\Transfer\FacetConfigTransfer;

abstract class AbstractTermsFacetAggregation extends AbstractFacetAggregation
{

    const AGGREGATION_PARAM_SIZE = 'size';

    /**
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

    /**
     * @param AbstractAggregation $aggregation
     * @param FacetConfigTransfer $facetConfigTransfer
     *
     * @return AbstractAggregation
     */
    protected function applyAggregationParams(AbstractAggregation $aggregation, FacetConfigTransfer $facetConfigTransfer)
    {
        $aggregation = parent::applyAggregationParams($aggregation, $facetConfigTransfer);
        $aggregation = $this->applyAggregationSize($aggregation, $facetConfigTransfer);

        return $aggregation;
    }

    /**
     * @deprecated Use FacetConfigTransfer::setAggregationParams() instead
     *
     * @param AbstractAggregation $aggregation
     * @param FacetConfigTransfer $facetConfigTransfer
     *
     * @return AbstractAggregation
     */
    protected function applyAggregationSize(AbstractAggregation $aggregation, FacetConfigTransfer $facetConfigTransfer)
    {
        if ($facetConfigTransfer->getSize() !== null) {
            $aggregation->setParam(static::AGGREGATION_PARAM_SIZE, $facetConfigTransfer->getSize());
        }

        return $aggregation;
    }

}
