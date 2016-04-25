<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Model\Elasticsearch\Aggregation;

use Elastica\Query\BoolQuery;
use Generated\Shared\Transfer\FacetConfigTransfer;
use Spryker\Client\Search\Exception\MissingFacetAggregationException;
use Spryker\Client\Search\Plugin\Config\FacetConfigBuilder;
use Spryker\Shared\Search\IndexMapInterface;

class FacetAggregationFactory implements FacetAggregationFactoryInterface
{

    /**
     * @var \Spryker\Shared\Search\IndexMapInterface
     */
    protected $indexMap;

    /**
     * @param \Spryker\Shared\Search\IndexMapInterface $indexMap
     */
    public function __construct(IndexMapInterface $indexMap)
    {
        $this->indexMap = $indexMap;
    }

    /**
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     *
     * @return \Spryker\Client\Search\Model\Elasticsearch\Aggregation\FacetAggregationInterface
     */
    public function create(FacetConfigTransfer $facetConfigTransfer, BoolQuery $filters)
    {
        return $this->createByFacetType($facetConfigTransfer, $filters);
    }

    /**
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     *
     * @throws \Spryker\Client\Search\Exception\MissingFacetAggregationException
     *
     * @return \Spryker\Client\Search\Model\Elasticsearch\Aggregation\FacetAggregationInterface
     */
    protected function createByFacetType(FacetConfigTransfer $facetConfigTransfer, $filters)
    {
        switch ($facetConfigTransfer->getType()) {
            case FacetConfigBuilder::TYPE_CATEGORY:
                return $this->createCategoryFacetAggregation($facetConfigTransfer, $filters);

            default:
                return $this->createByFacetValueType($facetConfigTransfer, $filters);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     *
     * @throws \Spryker\Client\Search\Exception\MissingFacetAggregationException
     *
     * @return \Spryker\Client\Search\Model\Elasticsearch\Aggregation\FacetAggregationInterface
     */
    protected function createByFacetValueType(FacetConfigTransfer $facetConfigTransfer, $filters)
    {
        $type = $this->getFacetValueType($facetConfigTransfer);

        switch ($type) {
            case 'string':
                return $this->createStringFacetAggregation($facetConfigTransfer, $filters);

            case 'integer':
            case 'float':
                return $this->createNumericFacetAggregation($facetConfigTransfer, $filters);

            default:
                throw new MissingFacetAggregationException(sprintf(
                    'Missing facet aggregation for type "%s" in field "%s".',
                    $type,
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
        if ($facetConfigTransfer->getFieldName() === 'category.all-parents') {
            return 'string';
        }

        return $this->indexMap->getType($facetConfigTransfer->getFieldName() . '.facet-value');
    }

    /**
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     *
     * @return \Spryker\Client\Search\Model\Elasticsearch\Aggregation\FacetAggregationInterface
     */
    protected function createCategoryFacetAggregation(FacetConfigTransfer $facetConfigTransfer, $filters)
    {
        return new CategoryFacetAggregation($facetConfigTransfer, $filters);
    }

    /**
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     *
     * @return \Spryker\Client\Search\Model\Elasticsearch\Aggregation\FacetAggregationInterface
     */
    protected function createStringFacetAggregation(FacetConfigTransfer $facetConfigTransfer, $filters)
    {
        return new StringFacetAggregation($facetConfigTransfer, $filters);
    }

    /**
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     *
     * @return \Spryker\Client\Search\Model\Elasticsearch\Aggregation\FacetAggregationInterface
     */
    protected function createNumericFacetAggregation(FacetConfigTransfer $facetConfigTransfer, $filters)
    {
        return new NumericFacetAggregation($facetConfigTransfer, $filters);
    }

}
