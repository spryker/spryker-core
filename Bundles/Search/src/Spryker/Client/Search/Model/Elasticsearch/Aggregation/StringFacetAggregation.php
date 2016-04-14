<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Model\Elasticsearch\Aggregation;

use Elastica\Aggregation\Terms;
use Generated\Shared\Transfer\FacetConfigTransfer;

class StringFacetAggregation extends AbstractFacetAggregation
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

        $facetValueAgg = (new Terms($fieldName . '-value'))
            ->setField($this->addNestedFieldPrefix($fieldName, self::FACET_VALUE));
        $facetNameAgg = $this->createFacetNameAggregation($fieldName);
        $facetNameAgg->addAggregation($facetValueAgg);

        return $this->createNestedFacetAggregation($fieldName, $facetNameAgg);
    }

}
