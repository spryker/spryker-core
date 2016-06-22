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

    /**
     * @var \Generated\Shared\Transfer\FacetConfigTransfer
     */
    protected $facetConfigTransfer;

    /**
     * @var string
     */
    protected $filterValue;

    /**
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     * @param string $filterValue
     * @param \Spryker\Client\Search\Model\Elasticsearch\Query\QueryBuilderInterface $queryBuilder
     */
    public function __construct(FacetConfigTransfer $facetConfigTransfer, $filterValue, QueryBuilderInterface $queryBuilder)
    {
        $this->facetConfigTransfer = $facetConfigTransfer;
        $this->filterValue = $filterValue;

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
        $values = explode(self::RANGE_DIVIDER, $this->filterValue);
        $minValue = $values[0];
        $maxValue = $minValue;

        if (count($values) > 1) {
            $maxValue = $values[1];
        }

        return [$minValue, $maxValue];
    }

}
