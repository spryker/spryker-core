<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Model\Elasticsearch\Query;

use Generated\Shared\Transfer\FacetConfigTransfer;
use Spryker\Client\Search\Plugin\Config\FacetConfigBuilder;

class NestedQueryFactory implements NestedQueryFactoryInterface
{

    /**
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     * @param mixed $filterValue
     *
     * @return \Spryker\Client\Search\Model\Elasticsearch\Query\NestedQueryInterface
     */
    public function create(FacetConfigTransfer $facetConfigTransfer, $filterValue)
    {
        $query = $this->createByFacetType($facetConfigTransfer, $filterValue);

        if ($query !== null) {
            return $query;
        }

        $query = $this->createByFilterValue($facetConfigTransfer, $filterValue);

        return $query;
    }

    /**
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     * @param string $filterValue
     *
     * @return \Spryker\Client\Search\Model\Elasticsearch\Query\NestedQueryInterface|null
     */
    protected function createByFacetType(FacetConfigTransfer $facetConfigTransfer, $filterValue)
    {
        switch ($facetConfigTransfer->getType()) {
            case FacetConfigBuilder::TYPE_RANGE:
                return $this->createNestedRangeQuery($facetConfigTransfer, $filterValue);

            case FacetConfigBuilder::TYPE_PRICE_RANGE:
                return $this->createNestedPriceRangeQuery($facetConfigTransfer, $filterValue);

            default:
                return null;
        }
    }

    /**
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     * @param mixed $filterValue
     *
     * @return \Spryker\Client\Search\Model\Elasticsearch\Query\NestedQueryInterface
     */
    protected function createByFilterValue(FacetConfigTransfer $facetConfigTransfer, $filterValue)
    {
        if (is_array($filterValue)) {
            return $this->createNestedTermsQuery($facetConfigTransfer, $filterValue);
        }

        return $this->createNestedTermQuery($facetConfigTransfer, $filterValue);
    }

    /**
     * @return \Spryker\Client\Search\Model\Elasticsearch\Query\QueryBuilderInterface
     */
    protected function createQueryBuilder()
    {
        return new QueryBuilder();
    }

    /**
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     * @param string $filterValue
     *
     * @return \Spryker\Client\Search\Model\Elasticsearch\Query\NestedQueryInterface
     */
    protected function createNestedRangeQuery(FacetConfigTransfer $facetConfigTransfer, $filterValue)
    {
        return new NestedRangeQuery($facetConfigTransfer, $filterValue, $this->createQueryBuilder());
    }

    /**
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     * @param string $filterValue
     *
     * @return \Spryker\Client\Search\Model\Elasticsearch\Query\NestedQueryInterface
     */
    protected function createNestedPriceRangeQuery(FacetConfigTransfer $facetConfigTransfer, $filterValue)
    {
        return new NestedPriceRangeQuery($facetConfigTransfer, $filterValue, $this->createQueryBuilder());
    }

    /**
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     * @param array $filterValues
     *
     * @return \Spryker\Client\Search\Model\Elasticsearch\Query\NestedQueryInterface
     */
    protected function createNestedTermsQuery(FacetConfigTransfer $facetConfigTransfer, array $filterValues)
    {
        return new NestedTermsQuery($facetConfigTransfer, $filterValues, $this->createQueryBuilder());
    }

    /**
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     * @param string $filterValue
     *
     * @return \Spryker\Client\Search\Model\Elasticsearch\Query\NestedQueryInterface
     */
    protected function createNestedTermQuery(FacetConfigTransfer $facetConfigTransfer, $filterValue)
    {
        return new NestedTermQuery($facetConfigTransfer, $filterValue, $this->createQueryBuilder());
    }

}
