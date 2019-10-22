<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Model\Elasticsearch\Aggregation;

use Generated\Shared\Transfer\FacetConfigTransfer;
use Spryker\Client\Search\Exception\MissingFacetAggregationException;
use Spryker\Client\Search\SearchConfig as ClientSearchConfig;
use Spryker\Shared\Search\IndexMapInterface;
use Spryker\Shared\Search\SearchConfig;

/**
 * @deprecated Use `\Spryker\Client\SearchElasticsearch\Aggregation\FacetAggregationFactory` instead.
 */
class FacetAggregationFactory implements FacetAggregationFactoryInterface
{
    /**
     * @var \Spryker\Shared\Search\IndexMapInterface
     */
    protected $indexMap;

    /**
     * @var \Spryker\Client\Search\Model\Elasticsearch\Aggregation\AggregationBuilderInterface
     */
    protected $aggregationBuilder;

    /**
     * @var \Spryker\Client\Search\SearchConfig
     */
    protected $searchConfig;

    /**
     * @param \Spryker\Shared\Search\IndexMapInterface $indexMap
     * @param \Spryker\Client\Search\Model\Elasticsearch\Aggregation\AggregationBuilderInterface $aggregationBuilder
     * @param \Spryker\Client\Search\SearchConfig $searchConfig
     */
    public function __construct(IndexMapInterface $indexMap, AggregationBuilderInterface $aggregationBuilder, ClientSearchConfig $searchConfig)
    {
        $this->indexMap = $indexMap;
        $this->aggregationBuilder = $aggregationBuilder;
        $this->searchConfig = $searchConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     *
     * @return \Spryker\Client\Search\Model\Elasticsearch\Aggregation\FacetAggregationInterface
     */
    public function create(FacetConfigTransfer $facetConfigTransfer)
    {
        return $this->createByFacetType($facetConfigTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     *
     * @return \Spryker\Client\Search\Model\Elasticsearch\Aggregation\FacetAggregationInterface
     */
    protected function createByFacetType(FacetConfigTransfer $facetConfigTransfer)
    {
        switch ($facetConfigTransfer->getType()) {
            case SearchConfig::FACET_TYPE_CATEGORY:
                return $this->createCategoryFacetAggregation($facetConfigTransfer);

            default:
                return $this->createByFacetValueType($facetConfigTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     *
     * @throws \Spryker\Client\Search\Exception\MissingFacetAggregationException
     *
     * @return \Spryker\Client\Search\Model\Elasticsearch\Aggregation\FacetAggregationInterface
     */
    protected function createByFacetValueType(FacetConfigTransfer $facetConfigTransfer)
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
    protected function getFacetValueType(FacetConfigTransfer $facetConfigTransfer)
    {
        return $this->indexMap->getType($facetConfigTransfer->getFieldName() . '.facet-value');
    }

    /**
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     *
     * @return \Spryker\Client\Search\Model\Elasticsearch\Aggregation\FacetAggregationInterface
     */
    protected function createCategoryFacetAggregation(FacetConfigTransfer $facetConfigTransfer)
    {
        return new CategoryFacetAggregation($facetConfigTransfer, $this->aggregationBuilder);
    }

    /**
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     *
     * @return \Spryker\Client\Search\Model\Elasticsearch\Aggregation\FacetAggregationInterface
     */
    protected function createStringFacetAggregation(FacetConfigTransfer $facetConfigTransfer)
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
     * @return \Spryker\Client\Search\Model\Elasticsearch\Aggregation\FacetAggregationInterface
     */
    protected function createNumericFacetAggregation(FacetConfigTransfer $facetConfigTransfer)
    {
        return new NumericFacetAggregation($facetConfigTransfer, $this->aggregationBuilder, $this->searchConfig);
    }
}
