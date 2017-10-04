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
     *
     * @return \Elastica\Aggregation\AbstractSimpleAggregation
     */
    protected function createFacetNameAggregation($fieldName)
    {
        return (new Terms($fieldName . static::NAME_SUFFIX))
            ->setField($this->addNestedFieldPrefix($fieldName, static::FACET_NAME));
    }

    /**
     * @param string $fieldName
     * @param string $nestedFieldName
     *
     * @return \Elastica\Aggregation\AbstractAggregation
     */
    protected function createStandaloneFacetNameAggregation($fieldName, $nestedFieldName)
    {
        $filterName = $this->addNestedFieldPrefix($fieldName, $nestedFieldName);
        $filterName = $filterName . static::NAME_SUFFIX;

        return (new Filter($filterName))
            ->setFilter(new Term([
                $this->addNestedFieldPrefix($fieldName, static::FACET_NAME) => $nestedFieldName,
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
     * @param AbstractAggregation $aggregation
     * @param FacetConfigTransfer $facetConfigTransfer
     *
     * @return AbstractAggregation
     */
    protected function applyAggregationParams(AbstractAggregation $aggregation, FacetConfigTransfer $facetConfigTransfer)
    {
        foreach ($facetConfigTransfer->getAggregationParams() as $aggregationParamKey => $aggregationParamValue) {
            $aggregation->setParam($aggregationParamKey, $aggregationParamValue);
        }

        return $aggregation;
    }

}
