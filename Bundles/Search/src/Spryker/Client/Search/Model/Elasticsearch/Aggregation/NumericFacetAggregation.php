<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Model\Elasticsearch\Aggregation;

use Elastica\Aggregation\Max;
use Elastica\Aggregation\Min;
use Elastica\Aggregation\Terms;
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
        $facetValueTerm = (new Terms($fieldName . '-value'))
            ->setField($prefixedFieldName);
        $facetValueMin = (new Min($fieldName . '-min'))
            ->setField($prefixedFieldName);
        $facetValueMax = (new Max($fieldName . '-max'))
            ->setField($prefixedFieldName);

        $facetNameAgg = $this->createFacetNameAggregation($fieldName);

        $facetNameAgg->addAggregation($facetValueTerm);
        $facetNameAgg->addAggregation($facetValueMin);
        $facetNameAgg->addAggregation($facetValueMax);

        return $this->createNestedFacetAggregation($fieldName, $facetNameAgg);
    }

}
