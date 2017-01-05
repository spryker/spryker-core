<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Model\Elasticsearch\Aggregation;

use Generated\Shared\Transfer\FacetConfigTransfer;

class StringFacetAggregation extends AbstractTermsFacetAggregation
{

    const VALUE_SUFFIX = '-value';

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

        $facetValueAgg = $this
            ->aggregationBuilder
            ->createTermsAggregation($fieldName . self::VALUE_SUFFIX)
            ->setField($this->addNestedFieldPrefix($fieldName, self::FACET_VALUE));

        $this->setTermsAggregationSize($facetValueAgg, $this->facetConfigTransfer->getSize());

        $facetNameAgg = $this
            ->createFacetNameAggregation($fieldName)
            ->addAggregation($facetValueAgg);

        return $this->createNestedFacetAggregation($fieldName, $facetNameAgg);
    }

}
