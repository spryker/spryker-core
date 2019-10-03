<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchElasticsearch\Aggregation;

use Generated\Shared\Transfer\FacetConfigTransfer;
use Spryker\Client\SearchElasticsearch\Exception\MissingFacetAggregationException;
use Spryker\Client\SearchElasticsearch\SearchElasticsearchConfig;
use Spryker\Shared\SearchElasticsearch\SearchElasticsearchConfig as SharedSearchElasticsearchConfig;
use Spryker\Shared\SearchExtension\SourceInterface;

class FacetAggregationFactory implements FacetAggregationFactoryInterface
{
    /**
     * @var \Spryker\Shared\SearchExtension\SourceInterface
     */
    protected $indexMap;

    /**
     * @var \Spryker\Client\SearchElasticsearch\Aggregation\AggregationBuilderInterface
     */
    protected $aggregationBuilder;

    /**
     * @var \Spryker\Client\SearchElasticsearch\SearchElasticsearchConfig
     */
    protected $searchConfig;

    /**
     * @param \Spryker\Shared\SearchExtension\SourceInterface $indexMap
     * @param \Spryker\Client\SearchElasticsearch\Aggregation\AggregationBuilderInterface $aggregationBuilder
     * @param \Spryker\Client\SearchElasticsearch\SearchElasticsearchConfig $searchConfig
     */
    public function __construct(SourceInterface $indexMap, AggregationBuilderInterface $aggregationBuilder, SearchElasticsearchConfig $searchConfig)
    {
        $this->indexMap = $indexMap;
        $this->aggregationBuilder = $aggregationBuilder;
        $this->searchConfig = $searchConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     *
     * @return \Spryker\Client\SearchElasticsearch\Aggregation\FacetAggregationInterface
     */
    public function create(FacetConfigTransfer $facetConfigTransfer): FacetAggregationInterface
    {
        return $this->createByFacetType($facetConfigTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     *
     * @return \Spryker\Client\SearchElasticsearch\Aggregation\FacetAggregationInterface
     */
    protected function createByFacetType(FacetConfigTransfer $facetConfigTransfer): FacetAggregationInterface
    {
        switch ($facetConfigTransfer->getType()) {
            case SharedSearchElasticsearchConfig::FACET_TYPE_CATEGORY:
                return $this->createCategoryFacetAggregation($facetConfigTransfer);

            default:
                return $this->createByFacetValueType($facetConfigTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     *
     * @throws \Spryker\Client\SearchElasticsearch\Exception\MissingFacetAggregationException
     *
     * @return \Spryker\Client\SearchElasticsearch\Aggregation\FacetAggregationInterface
     */
    protected function createByFacetValueType(FacetConfigTransfer $facetConfigTransfer): FacetAggregationInterface
    {
        $valueType = $this->getFacetValueType($facetConfigTransfer);

        switch ($valueType) {
            case 'string':
            case 'keyword':
            case 'text':
                return $this->createStringFacetAggregation($facetConfigTransfer);

            case 'integer':
            case 'float':
                return $this->createNumericFacetAggregation($facetConfigTransfer);

            default:
                throw new MissingFacetAggregationException(sprintf(
                    'Missing facet aggregation for type "%s" in field "%s".',
                    $valueType,
                    $facetConfigTransfer->getFieldName()
                ));
        }
    }

    /**
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     *
     * @return string|null
     */
    protected function getFacetValueType(FacetConfigTransfer $facetConfigTransfer): ?string
    {
        return $this->indexMap->getType($facetConfigTransfer->getFieldName() . '.facet-value');
    }

    /**
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     *
     * @return \Spryker\Client\SearchElasticsearch\Aggregation\FacetAggregationInterface
     */
    protected function createCategoryFacetAggregation(FacetConfigTransfer $facetConfigTransfer): FacetAggregationInterface
    {
        return new CategoryFacetAggregation($facetConfigTransfer, $this->aggregationBuilder);
    }

    /**
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     *
     * @return \Spryker\Client\SearchElasticsearch\Aggregation\FacetAggregationInterface
     */
    protected function createStringFacetAggregation(FacetConfigTransfer $facetConfigTransfer): FacetAggregationInterface
    {
        return new StringFacetAggregation(
            $facetConfigTransfer,
            $this->aggregationBuilder,
            $this->searchConfig
        );
    }

    /**
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     *
     * @return \Spryker\Client\SearchElasticsearch\Aggregation\FacetAggregationInterface
     */
    protected function createNumericFacetAggregation(FacetConfigTransfer $facetConfigTransfer): FacetAggregationInterface
    {
        return new NumericFacetAggregation($facetConfigTransfer, $this->aggregationBuilder, $this->searchConfig);
    }
}
