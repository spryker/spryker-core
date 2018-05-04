<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Model\Elasticsearch\Aggregation;

use Elastica\Aggregation\AbstractAggregation;
use Elastica\Aggregation\Filter;
use Elastica\Aggregation\Nested;
use Elastica\Aggregation\Terms;
use Elastica\Query\Term;
use Generated\Shared\Transfer\FacetConfigTransfer;

abstract class AbstractFacetAggregation implements FacetAggregationInterface
{
    const FACET_VALUE = 'facet-value';
    const FACET_NAME = 'facet-name';
    const NAME_SUFFIX = '-name';
    const PATH_SEPARATOR = '.';

    /**
     * @param string $fieldName
     * @param \Elastica\Aggregation\AbstractAggregation $aggregation
     * @param string|null $path
     *
     * @return \Elastica\Aggregation\AbstractAggregation
     */
    protected function createNestedFacetAggregation($fieldName, AbstractAggregation $aggregation, $path = null)
    {
        if ($path === null) {
            $path = $fieldName;
        }

        return (new Nested($fieldName, $path))
            ->addAggregation($aggregation);
    }

    /**
     * @param string $fieldName
     * @param int $size
     *
     * @return \Elastica\Aggregation\AbstractSimpleAggregation
     */
    protected function createFacetNameAggregation($fieldName, $size = 0)
    {
        $terms = (new Terms($fieldName . static::NAME_SUFFIX))
            ->setField($this->addNestedFieldPrefix($fieldName, static::FACET_NAME));
        if ($size > 0) {
            $terms->setSize($size);
        }

        return $terms;
    }

    /**
     * @param string $parentFieldName
     * @param string $fieldName
     *
     * @return \Elastica\Aggregation\AbstractAggregation
     */
    protected function createStandaloneFacetNameAggregation($parentFieldName, $fieldName)
    {
        $filterName = $this->addNestedFieldPrefix($parentFieldName, $fieldName);
        $filterName = $filterName . static::NAME_SUFFIX;

        return (new Filter($filterName))
            ->setFilter(new Term([
                $this->addNestedFieldPrefix($parentFieldName, static::FACET_NAME) => $fieldName,
            ]));
    }

    /**
     * @param string $nestedFieldName
     * @param string $fieldName
     *
     * @return string
     */
    protected function addNestedFieldPrefix($nestedFieldName, $fieldName)
    {
        return $nestedFieldName . static::PATH_SEPARATOR . $fieldName;
    }

    /**
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     *
     * @return string
     */
    protected function getNestedFieldName(FacetConfigTransfer $facetConfigTransfer)
    {
        $nestedFieldName = $facetConfigTransfer->getFieldName();

        if ($facetConfigTransfer->getAggregationParams()) {
            $nestedFieldName = $this->addNestedFieldPrefix(
                $nestedFieldName,
                $facetConfigTransfer->getName()
            );
        }

        return $nestedFieldName;
    }

    /**
     * @param \Elastica\Aggregation\AbstractAggregation $aggregation
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     *
     * @return \Elastica\Aggregation\AbstractAggregation
     */
    protected function applyAggregationParams(AbstractAggregation $aggregation, FacetConfigTransfer $facetConfigTransfer)
    {
        foreach ($facetConfigTransfer->getAggregationParams() as $aggregationParamKey => $aggregationParamValue) {
            $aggregation->setParam($aggregationParamKey, $aggregationParamValue);
        }

        return $aggregation;
    }
}
