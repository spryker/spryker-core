<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchElasticsearch\Query;

use Elastica\Query\AbstractQuery;
use Elastica\Query\Term;
use Generated\Shared\Transfer\FacetConfigTransfer;
use Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface;
use Spryker\Shared\SearchElasticsearch\SearchElasticsearchConfig;

class QueryFactory implements QueryFactoryInterface
{
    /**
     * @var \Spryker\Client\SearchElasticsearch\Query\QueryBuilderInterface
     */
    protected $queryBuilder;

    /**
     * @var \Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface
     */
    protected $moneyPlugin;

    /**
     * @param \Spryker\Client\SearchElasticsearch\Query\QueryBuilderInterface $queryBuilder
     * @param \Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface $moneyPlugin
     */
    public function __construct(QueryBuilderInterface $queryBuilder, MoneyPluginInterface $moneyPlugin)
    {
        $this->queryBuilder = $queryBuilder;
        $this->moneyPlugin = $moneyPlugin;
    }

    /**
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     * @param mixed $filterValue
     *
     * @return \Elastica\Query\AbstractQuery
     */
    public function create(FacetConfigTransfer $facetConfigTransfer, $filterValue): AbstractQuery
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
     * @param mixed $filterValue
     *
     * @return \Elastica\Query\AbstractQuery|null
     */
    protected function createByFacetType(FacetConfigTransfer $facetConfigTransfer, $filterValue): ?AbstractQuery
    {
        switch ($facetConfigTransfer->getType()) {
            case SearchElasticsearchConfig::FACET_TYPE_RANGE:
                return $this->createNestedRangeQuery($facetConfigTransfer, $filterValue)->createNestedQuery();

            case SearchElasticsearchConfig::FACET_TYPE_PRICE_RANGE:
                return $this->createNestedPriceRangeQuery($facetConfigTransfer, $filterValue)->createNestedQuery();

            case SearchElasticsearchConfig::FACET_TYPE_CATEGORY:
                return $this->createTermQuery($facetConfigTransfer, $filterValue);

            default:
                return null;
        }
    }

    /**
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     * @param mixed $filterValue
     *
     * @return \Elastica\Query\AbstractQuery
     */
    protected function createByFilterValue(FacetConfigTransfer $facetConfigTransfer, $filterValue): AbstractQuery
    {
        if (is_array($filterValue)) {
            return $this->createNestedTermsQuery($facetConfigTransfer, $filterValue)->createNestedQuery();
        }

        return $this->createNestedTermQuery($facetConfigTransfer, $filterValue)->createNestedQuery();
    }

    /**
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     * @param mixed $filterValue
     *
     * @return \Spryker\Client\SearchElasticsearch\Query\NestedQueryInterface
     */
    protected function createNestedRangeQuery(FacetConfigTransfer $facetConfigTransfer, $filterValue): NestedQueryInterface
    {
        return new NestedRangeQuery($facetConfigTransfer, $filterValue, $this->queryBuilder);
    }

    /**
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     * @param mixed $filterValue
     *
     * @return \Spryker\Client\SearchElasticsearch\Query\NestedQueryInterface
     */
    protected function createNestedPriceRangeQuery(FacetConfigTransfer $facetConfigTransfer, $filterValue): NestedQueryInterface
    {
        return new NestedPriceRangeQuery($facetConfigTransfer, $filterValue, $this->queryBuilder, $this->moneyPlugin);
    }

    /**
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     * @param array $filterValues
     *
     * @return \Spryker\Client\SearchElasticsearch\Query\NestedQueryInterface
     */
    protected function createNestedTermsQuery(FacetConfigTransfer $facetConfigTransfer, array $filterValues): NestedQueryInterface
    {
        return new NestedTermsQuery($facetConfigTransfer, $filterValues, $this->queryBuilder);
    }

    /**
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     * @param string $filterValue
     *
     * @return \Spryker\Client\SearchElasticsearch\Query\NestedQueryInterface
     */
    protected function createNestedTermQuery(FacetConfigTransfer $facetConfigTransfer, string $filterValue): NestedQueryInterface
    {
        return new NestedTermQuery($facetConfigTransfer, $filterValue, $this->queryBuilder);
    }

    /**
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     * @param string $filterValue
     *
     * @return \Elastica\Query\Term
     */
    protected function createTermQuery(FacetConfigTransfer $facetConfigTransfer, string $filterValue): Term
    {
        return $this
            ->queryBuilder
            ->createTermQuery($facetConfigTransfer->getFieldName(), $filterValue);
    }
}
