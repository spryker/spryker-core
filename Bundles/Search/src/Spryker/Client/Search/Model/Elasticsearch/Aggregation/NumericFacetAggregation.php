<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Model\Elasticsearch\Aggregation;

use Elastica\Aggregation\Stats;
use Generated\Shared\Transfer\FacetConfigTransfer;

class NumericFacetAggregation extends AbstractFacetAggregation
{

    /**
     * @var \Generated\Shared\Transfer\FacetConfigTransfer
     */
    protected $facetConfigTransfer;

    /**
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     */
    public function __construct(FacetConfigTransfer $facetConfigTransfer)
    {
        $this->facetConfigTransfer = $facetConfigTransfer;
    }

    /**
     * @return \Elastica\Aggregation\AbstractAggregation
     */
    public function createAggregation()
    {
        $fieldName = $this->facetConfigTransfer->getFieldName();

        $prefixedFieldName = $this->addNestedFieldPrefix($fieldName, self::FACET_VALUE);
        $facetValueStats = (new Stats($fieldName . '-stats'))
            ->setField($prefixedFieldName);

        $facetNameAgg = $this
            ->createFacetNameAggregation($fieldName)
            ->addAggregation($facetValueStats);

        return $this->createNestedFacetAggregation($fieldName, $facetNameAgg);
    }

}
