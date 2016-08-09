<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Model\Elasticsearch\Query;

use Generated\Shared\Transfer\FacetConfigTransfer;

class NestedRangeQuery extends AbstractNestedQuery
{

    const RANGE_DIVIDER = '-';
    const RANGE_MIN = 'min';
    const RANGE_MAX = 'max';

    /**
     * @var \Generated\Shared\Transfer\FacetConfigTransfer
     */
    protected $facetConfigTransfer;

    /**
     * @var mixed
     */
    protected $rangeValues;

    /**
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     * @param mixed $rangeValues
     * @param \Spryker\Client\Search\Model\Elasticsearch\Query\QueryBuilderInterface $queryBuilder
     */
    public function __construct(FacetConfigTransfer $facetConfigTransfer, $rangeValues, QueryBuilderInterface $queryBuilder)
    {
        $this->facetConfigTransfer = $facetConfigTransfer;
        $this->rangeValues = $rangeValues;

        parent::__construct($queryBuilder);
    }

    /**
     * @return \Elastica\Query\Nested
     */
    public function createNestedQuery()
    {
        $fieldName = $this->facetConfigTransfer->getFieldName();
        $nestedFieldName = $this->facetConfigTransfer->getName();

        list($minValue, $maxValue) = $this->getMinMaxValue();

        return $this->bindMultipleNestedQuery($fieldName, [
            $this->queryBuilder->createTermQuery($fieldName . self::FACET_NAME_SUFFIX, $nestedFieldName),
            $this->queryBuilder->createRangeQuery($fieldName . self::FACET_VALUE_SUFFIX, $minValue, $maxValue),
        ]);
    }

    /**
     * @return array
     */
    protected function getMinMaxValue()
    {
        if (is_array($this->rangeValues)) {
            return $this->getMinMaxValueFromArray($this->rangeValues);
        }

        return $this->getMinMaxValueFromString($this->rangeValues);
    }

    /**
     * @param array $rangeValues
     *
     * @return array
     */
    protected function getMinMaxValueFromArray(array $rangeValues)
    {
        $minValue = isset($rangeValues[self::RANGE_MIN]) ? $rangeValues[self::RANGE_MIN] : 0;
        $maxValue = isset($rangeValues[self::RANGE_MAX]) ? $rangeValues[self::RANGE_MAX] : $minValue;

        return [$minValue, $maxValue];
    }

    /**
     * @param string $rangeValues
     *
     * @return array
     */
    protected function getMinMaxValueFromString($rangeValues)
    {
        $values = explode(self::RANGE_DIVIDER, $rangeValues);
        $minValue = $values[0];
        $maxValue = $minValue;

        if (count($values) > 1) {
            $maxValue = $values[1];
        }

        return [$minValue, $maxValue];
    }

}
