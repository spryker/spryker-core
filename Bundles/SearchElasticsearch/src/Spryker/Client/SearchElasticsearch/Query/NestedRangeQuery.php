<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchElasticsearch\Query;

use Elastica\Query\Nested;
use Generated\Shared\Transfer\FacetConfigTransfer;

class NestedRangeQuery extends AbstractNestedQuery
{
    protected const RANGE_DIVIDER = '-';
    protected const RANGE_MIN = 'min';
    protected const RANGE_MAX = 'max';

    /**
     * @var \Generated\Shared\Transfer\FacetConfigTransfer
     */
    protected $facetConfigTransfer;

    /**
     * @var string
     */
    protected $minValue;

    /**
     * @var string
     */
    protected $maxValue;

    /**
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     * @param array|string $rangeValues
     * @param \Spryker\Client\SearchElasticsearch\Query\QueryBuilderInterface $queryBuilder
     */
    public function __construct(FacetConfigTransfer $facetConfigTransfer, $rangeValues, QueryBuilderInterface $queryBuilder)
    {
        $this->facetConfigTransfer = $facetConfigTransfer;
        $this->setMinMaxValues($rangeValues);

        parent::__construct($queryBuilder);
    }

    /**
     * @return \Elastica\Query\Nested
     */
    public function createNestedQuery(): Nested
    {
        $fieldName = $this->facetConfigTransfer->getFieldName();
        $nestedFieldName = $this->facetConfigTransfer->getName();

        return $this->bindMultipleNestedQuery($fieldName, [
            $this->queryBuilder->createTermQuery($fieldName . static::FACET_NAME_SUFFIX, $nestedFieldName),
            $this->queryBuilder->createRangeQuery($fieldName . static::FACET_VALUE_SUFFIX, $this->minValue, $this->maxValue),
        ]);
    }

    /**
     * @param array|string $rangeValues
     *
     * @return void
     */
    protected function setMinMaxValues($rangeValues): void
    {
        if (is_array($rangeValues)) {
            $this->setMinMaxValuesFromArray($rangeValues);

            return;
        }

        $this->setMinMaxValuesFromString($rangeValues);
    }

    /**
     * @param array $rangeValues
     *
     * @return void
     */
    protected function setMinMaxValuesFromArray(array $rangeValues): void
    {
        $this->minValue = isset($rangeValues[static::RANGE_MIN]) ? $rangeValues[static::RANGE_MIN] : null;
        $this->maxValue = isset($rangeValues[static::RANGE_MAX]) ? $rangeValues[static::RANGE_MAX] : null;

        $this->convertMinMaxValues();
    }

    /**
     * @param string $rangeValues
     *
     * @return void
     */
    protected function setMinMaxValuesFromString(string $rangeValues): void
    {
        $values = explode(static::RANGE_DIVIDER, $rangeValues);

        if ($values[0] !== '') {
            $this->minValue = $values[0];
        }

        if (count($values) > 1 && $values[1] !== '') {
            $this->maxValue = $values[1];
        }

        $this->convertMinMaxValues();
    }

    /**
     * @return void
     */
    protected function convertMinMaxValues(): void
    {
        if ($this->minValue !== null) {
            $this->minValue = (string)(float)$this->minValue;
        }

        if ($this->maxValue !== null) {
            $this->maxValue = (string)(float)$this->maxValue;
        }
    }
}
