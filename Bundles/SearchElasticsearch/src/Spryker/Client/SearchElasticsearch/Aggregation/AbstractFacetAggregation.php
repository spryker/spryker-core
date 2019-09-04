<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchElasticsearch\Aggregation;

use Elastica\Aggregation\AbstractAggregation;
use Elastica\Aggregation\AbstractSimpleAggregation;
use Elastica\Aggregation\Filter;
use Elastica\Aggregation\Nested;
use Elastica\Aggregation\Terms;
use Elastica\Query\Term;
use Generated\Shared\Transfer\FacetConfigTransfer;

abstract class AbstractFacetAggregation implements FacetAggregationInterface
{
    public const FACET_VALUE = 'facet-value';
    public const FACET_NAME = 'facet-name';
    public const NAME_SUFFIX = '-name';
    public const PATH_SEPARATOR = '.';

    /**
     * @param string $fieldName
     * @param \Elastica\Aggregation\AbstractAggregation $aggregation
     * @param string|null $path
     *
     * @return \Elastica\Aggregation\AbstractAggregation
     */
    protected function createNestedFacetAggregation(string $fieldName, AbstractAggregation $aggregation, ?string $path = null): AbstractAggregation
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
    protected function createFacetNameAggregation(string $fieldName, int $size): AbstractSimpleAggregation
    {
        $terms = (new Terms($fieldName . static::NAME_SUFFIX))
            ->setField($this->addNestedFieldPrefix($fieldName, static::FACET_NAME))
            ->setSize($size);

        return $terms;
    }

    /**
     * @param string $parentFieldName
     * @param string $fieldName
     *
     * @return \Elastica\Aggregation\AbstractAggregation
     */
    protected function createStandaloneFacetNameAggregation(string $parentFieldName, string $fieldName): AbstractAggregation
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
    protected function addNestedFieldPrefix(string $nestedFieldName, string $fieldName): string
    {
        return $nestedFieldName . static::PATH_SEPARATOR . $fieldName;
    }

    /**
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     *
     * @return string
     */
    protected function getNestedFieldName(FacetConfigTransfer $facetConfigTransfer): string
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
    protected function applyAggregationParams(AbstractAggregation $aggregation, FacetConfigTransfer $facetConfigTransfer): AbstractAggregation
    {
        foreach ($facetConfigTransfer->getAggregationParams() as $aggregationParamKey => $aggregationParamValue) {
            $aggregation->setParam($aggregationParamKey, $aggregationParamValue);
        }

        return $aggregation;
    }
}
