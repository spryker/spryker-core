<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Model\Elasticsearch\Aggregation;

use Elastica\Aggregation\Filter;
use Elastica\Aggregation\GlobalAggregation;
use Elastica\Aggregation\Terms;
use Elastica\Query\BoolQuery;
use Generated\Shared\Transfer\FacetConfigTransfer;

class CategoryFacetAggregation extends AbstractFacetAggregation
{

    /**
     * @var \Generated\Shared\Transfer\FacetConfigTransfer
     */
    protected $facetConfigTransfer;

    /**
     * @var \Elastica\Query\BoolQuery
     */
    protected $filters;

    /**
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     */
    public function __construct(FacetConfigTransfer $facetConfigTransfer, BoolQuery $filters)
    {
        $this->facetConfigTransfer = $facetConfigTransfer;
//        $this->filters = $filters;
    }

    /**
     * @return \Elastica\Aggregation\AbstractAggregation
     */
    public function createAggregation()
    {
        $fieldName = $this->facetConfigTransfer->getFieldName();

        return $facetAgg = (new Terms($fieldName))
            ->setField($fieldName);

        // TODO: use this for partially filtered aggregation or remove it if not needed
//        $filterAgg = (new Filter($fieldName))
//            ->setFilter($this->filters)
//            ->addAggregation($facetAgg);
//
//        $globalAgg = (new GlobalAggregation($fieldName))
//            ->addAggregation($filterAgg);
//
//        return $globalAgg;
    }

}
