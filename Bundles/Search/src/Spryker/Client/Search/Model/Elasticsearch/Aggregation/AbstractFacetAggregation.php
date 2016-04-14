<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Model\Elasticsearch\Aggregation;

use Elastica\Aggregation\AbstractAggregation;
use Elastica\Aggregation\Nested;
use Elastica\Aggregation\Terms;

abstract class AbstractFacetAggregation implements FacetAggregationInterface
{

    const FACET_VALUE = 'facet-value';
    const FACET_NAME = 'facet-name';

    /**
     * @param string $fieldName
     * @param \Elastica\Aggregation\AbstractAggregation $aggregation
     *
     * @return \Elastica\Aggregation\AbstractAggregation
     */
    protected function createNestedFacetAggregation($fieldName, AbstractAggregation $aggregation)
    {
        return (new Nested($fieldName, $fieldName))
            ->addAggregation($aggregation);
    }

    /**
     * @param string $fieldName
     *
     * @return \Elastica\Aggregation\AbstractSimpleAggregation
     */
    protected function createFacetNameAggregation($fieldName)
    {
        return (new Terms($fieldName . '-name'))
            ->setField($this->addNestedFieldPrefix($fieldName, self::FACET_NAME));
    }

    /**
     * @param string $nestedFieldName
     * @param string $fieldName
     *
     * @return string
     */
    protected function addNestedFieldPrefix($nestedFieldName, $fieldName)
    {
        return $nestedFieldName . '.' . $fieldName;
    }

}
