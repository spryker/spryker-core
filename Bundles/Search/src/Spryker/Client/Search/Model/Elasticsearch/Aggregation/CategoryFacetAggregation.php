<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Model\Elasticsearch\Aggregation;

use Generated\Shared\Transfer\FacetConfigTransfer;

class CategoryFacetAggregation extends AbstractTermsFacetAggregation
{

    /**
     * @var \Generated\Shared\Transfer\FacetConfigTransfer
     */
    protected $facetConfigTransfer;

    /**
     * @var \Spryker\Client\Search\Model\Elasticsearch\Aggregation\AggregationBuilderInterface
     */
    protected $aggregationBuilder;

    /**
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     * @param \Spryker\Client\Search\Model\Elasticsearch\Aggregation\AggregationBuilderInterface $aggregationBuilder
     */
    public function __construct(FacetConfigTransfer $facetConfigTransfer, AggregationBuilderInterface $aggregationBuilder)
    {
        $this->facetConfigTransfer = $facetConfigTransfer;
        $this->aggregationBuilder = $aggregationBuilder;
    }

    /**
     * @return \Elastica\Aggregation\AbstractAggregation
     */
    public function createAggregation()
    {
        $fieldName = $this->facetConfigTransfer->getFieldName();

        if ($this->facetConfigTransfer->getAggregationParams()) {
            $fieldName = $this->addNestedFieldPrefix(
                $fieldName,
                $this->facetConfigTransfer->getName()
            );
        }

        $aggregation = $this
            ->aggregationBuilder
            ->createTermsAggregation($fieldName)
            ->setField($fieldName);

        $this->setTermsAggregationSize(
            $aggregation,
            $this->getSizeParam($this->facetConfigTransfer)
        );

        return $aggregation;
    }

    /**
     * @deprecated Use aggregationParams to set a category facet size instead.
     * Will be removed with the next major release.
     *
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     *
     * @return int|null
     */
    protected function getSizeParam(FacetConfigTransfer $facetConfigTransfer)
    {
        if (isset($facetConfigTransfer->getAggregationParams()[static::AGGREGATION_PARAM_SIZE])) {
            return $facetConfigTransfer->getAggregationParams()[static::AGGREGATION_PARAM_SIZE];
        }

        return $facetConfigTransfer->getSize();
    }

}
