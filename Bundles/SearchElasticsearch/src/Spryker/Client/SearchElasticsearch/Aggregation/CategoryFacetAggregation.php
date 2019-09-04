<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchElasticsearch\Aggregation;

use Elastica\Aggregation\AbstractAggregation;
use Generated\Shared\Transfer\FacetConfigTransfer;

class CategoryFacetAggregation extends AbstractTermsFacetAggregation
{
    /**
     * @var \Generated\Shared\Transfer\FacetConfigTransfer
     */
    protected $facetConfigTransfer;

    /**
     * @var \Spryker\Client\SearchElasticsearch\Aggregation\AggregationBuilderInterface
     */
    protected $aggregationBuilder;

    /**
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     * @param \Spryker\Client\SearchElasticsearch\Aggregation\AggregationBuilderInterface $aggregationBuilder
     */
    public function __construct(FacetConfigTransfer $facetConfigTransfer, AggregationBuilderInterface $aggregationBuilder)
    {
        $this->facetConfigTransfer = $facetConfigTransfer;
        $this->aggregationBuilder = $aggregationBuilder;
    }

    /**
     * @return \Elastica\Aggregation\AbstractAggregation
     */
    public function createAggregation(): AbstractAggregation
    {
        $fieldName = $this->facetConfigTransfer->getFieldName();
        $nestedFieldName = $this->getNestedFieldName($this->facetConfigTransfer);

        $aggregation = $this
            ->aggregationBuilder
            ->createTermsAggregation($nestedFieldName)
            ->setField($fieldName);

        $this->setTermsAggregationSize($aggregation, $this->facetConfigTransfer->getSize());
        $aggregation = $this->applyAggregationParams($aggregation, $this->facetConfigTransfer);

        return $aggregation;
    }
}
