<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Model\Elasticsearch\Aggregation;

use Generated\Shared\Transfer\FacetConfigTransfer;
use Spryker\Client\Search\SearchConfig;

class StringFacetAggregation extends AbstractTermsFacetAggregation
{
    public const VALUE_SUFFIX = '-value';

    /**
     * @var \Generated\Shared\Transfer\FacetConfigTransfer
     */
    protected $facetConfigTransfer;

    /**
     * @var \Spryker\Client\Search\Model\Elasticsearch\Aggregation\AggregationBuilderInterface
     */
    protected $aggregationBuilder;

    /**
     * @var \Spryker\Client\Search\SearchConfig
     */
    protected $searchConfig;

    /**
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     * @param \Spryker\Client\Search\Model\Elasticsearch\Aggregation\AggregationBuilderInterface $aggregationBuilder
     * @param \Spryker\Client\Search\SearchConfig $searchConfig
     */
    public function __construct(
        FacetConfigTransfer $facetConfigTransfer,
        AggregationBuilderInterface $aggregationBuilder,
        SearchConfig $searchConfig
    ) {
        $this->facetConfigTransfer = $facetConfigTransfer;
        $this->aggregationBuilder = $aggregationBuilder;
        $this->searchConfig = $searchConfig;
    }

    /**
     * @return \Elastica\Aggregation\AbstractAggregation
     */
    public function createAggregation()
    {
        $fieldName = $this->facetConfigTransfer->getFieldName();
        $nestedFieldName = $this->getNestedFieldName($this->facetConfigTransfer);

        $facetValueAgg = $this->createValueAgg($fieldName, $nestedFieldName);

        $facetNameAgg = $this
            ->createNameAgg($this->facetConfigTransfer)
            ->addAggregation($facetValueAgg);

        return $this->createNestedFacetAggregation($nestedFieldName, $facetNameAgg, $fieldName);
    }

    /**
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     *
     * @return \Elastica\Aggregation\AbstractAggregation|\Elastica\Aggregation\AbstractSimpleAggregation
     */
    protected function createNameAgg(FacetConfigTransfer $facetConfigTransfer)
    {
        if ($facetConfigTransfer->getAggregationParams()) {
            return $this->createStandaloneFacetNameAggregation(
                $facetConfigTransfer->getFieldName(),
                $facetConfigTransfer->getName()
            );
        }

        return $this->createFacetNameAggregation(
            $facetConfigTransfer->getFieldName(),
            $this->getFacetNameAggregationSize()
        );
    }

    /**
     * @param string $fieldName
     * @param string $nestedFieldName
     *
     * @return \Elastica\Aggregation\AbstractAggregation
     */
    protected function createValueAgg($fieldName, $nestedFieldName)
    {
        $aggregation = $this
            ->aggregationBuilder
            ->createTermsAggregation($nestedFieldName . static::VALUE_SUFFIX)
            ->setField($this->addNestedFieldPrefix($fieldName, static::FACET_VALUE));

        $this->setTermsAggregationSize($aggregation, $this->facetConfigTransfer->getSize());
        $aggregation = $this->applyAggregationParams($aggregation, $this->facetConfigTransfer);

        return $aggregation;
    }

    /**
     * @return int
     */
    protected function getFacetNameAggregationSize(): int
    {
        return $this->searchConfig->getFacetNameAggregationSize();
    }
}
