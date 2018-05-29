<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProduct\Persistence;

use Generated\Shared\Transfer\PriceDimensionCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductCriteriaTransfer;
use Orm\Zed\PriceProduct\Persistence\SpyPriceProductStoreQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\PriceProduct\Dependency\Plugin\PriceDimensionQueryCriteriaPluginInterface;

class PriceProductDimensionQueryExpander implements PriceProductDimensionQueryExpanderInterface
{
    /**
     * @var \Spryker\Zed\PriceProduct\Dependency\Plugin\PriceDimensionQueryCriteriaPluginInterface[]
     */
    protected $priceProductDimensionQueryExpanders = [];

    /**
     * @param \Spryker\Zed\PriceProduct\Dependency\Plugin\PriceDimensionQueryCriteriaPluginInterface[] $priceProductDimensionQueryExpanders
     */
    public function __construct(array $priceProductDimensionQueryExpanders)
    {
        $this->priceProductDimensionQueryExpanders = $priceProductDimensionQueryExpanders;
    }

    /**
     * @param \Orm\Zed\PriceProduct\Persistence\SpyPriceProductStoreQuery $priceProductStoreQuery
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductStoreQuery
     */
    public function expandPriceProductStoreQueryWithPriceDimension(
        SpyPriceProductStoreQuery $priceProductStoreQuery,
        PriceProductCriteriaTransfer $priceProductCriteriaTransfer
    ): SpyPriceProductStoreQuery {

        if ($priceProductCriteriaTransfer->getPriceDimension()) {
            $this->runSinglePlugin($priceProductStoreQuery, $priceProductCriteriaTransfer);

            return $priceProductStoreQuery;
        }

        $this->runAllPlugins($priceProductStoreQuery, $priceProductCriteriaTransfer);

        return $priceProductStoreQuery;
    }

    /**
     * @param \Orm\Zed\PriceProduct\Persistence\SpyPriceProductStoreQuery $priceProductStoreQuery
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductStoreQuery
     */
    public function expandPriceProductStoreQueryWithPriceDimensionForDelete(
        SpyPriceProductStoreQuery $priceProductStoreQuery,
        PriceProductCriteriaTransfer $priceProductCriteriaTransfer
    ): SpyPriceProductStoreQuery {

        foreach ($this->priceProductDimensionQueryExpanders as $priceDimensionQueryCriteriaPlugin) {
            $productDimensionCriteriaTransfer = $this->runPlugin(
                $priceProductStoreQuery,
                $priceProductCriteriaTransfer,
                $priceDimensionQueryCriteriaPlugin
            );

            if (!$productDimensionCriteriaTransfer) {
                continue;
            }

            $this->filterEmptyDimensions($priceProductStoreQuery, $productDimensionCriteriaTransfer);
        }

        return $priceProductStoreQuery;
    }

    /**
     * @param string $priceDimensionName
     *
     * @return \Spryker\Zed\PriceProduct\Dependency\Plugin\PriceDimensionQueryCriteriaPluginInterface|null
     */
    protected function findPriceDimensionCriteriaPluginByName(string $priceDimensionName): ?PriceDimensionQueryCriteriaPluginInterface
    {
        foreach ($this->priceProductDimensionQueryExpanders as $priceDimensionQueryCriteriaPlugin) {
            if ($priceDimensionQueryCriteriaPlugin->getDimensionName() === $priceDimensionName) {
                return $priceDimensionQueryCriteriaPlugin;
            }
        }

        return null;
    }

    /**
     * @param \Orm\Zed\PriceProduct\Persistence\SpyPriceProductStoreQuery $priceProductStoreQuery
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return void
     */
    protected function runAllPlugins(
        SpyPriceProductStoreQuery $priceProductStoreQuery,
        PriceProductCriteriaTransfer $priceProductCriteriaTransfer
    ): void {

        foreach ($this->priceProductDimensionQueryExpanders as $priceDimensionQueryCriteriaPlugin) {
            $this->runPlugin($priceProductStoreQuery, $priceProductCriteriaTransfer, $priceDimensionQueryCriteriaPlugin);
        }
    }

    /**
     * @param \Orm\Zed\PriceProduct\Persistence\SpyPriceProductStoreQuery $priceProductStoreQuery
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return void
     */
    protected function runSinglePlugin(
        SpyPriceProductStoreQuery $priceProductStoreQuery,
        PriceProductCriteriaTransfer $priceProductCriteriaTransfer
    ): void {

        $priceDimensionQueryCriteriaPlugin = $this->findPriceDimensionCriteriaPluginByName(
            $priceProductCriteriaTransfer->getPriceDimension()
        );

        if ($priceDimensionQueryCriteriaPlugin) {
            $this->runPlugin(
                $priceProductStoreQuery,
                $priceProductCriteriaTransfer,
                $priceDimensionQueryCriteriaPlugin,
                Criteria::INNER_JOIN
            );
        }
    }

    /**
     * @param \Orm\Zed\PriceProduct\Persistence\SpyPriceProductStoreQuery $priceProductStoreQuery
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     * @param \Spryker\Zed\PriceProduct\Dependency\Plugin\PriceDimensionQueryCriteriaPluginInterface $priceProductDimensionQueryExpanderPlugin
     * @param null|string $joinType
     *
     * @return \Generated\Shared\Transfer\PriceDimensionCriteriaTransfer|null
     */
    protected function runPlugin(
        SpyPriceProductStoreQuery $priceProductStoreQuery,
        PriceProductCriteriaTransfer $priceProductCriteriaTransfer,
        PriceDimensionQueryCriteriaPluginInterface $priceProductDimensionQueryExpanderPlugin,
        $joinType = null
    ): ?PriceDimensionCriteriaTransfer {

        $priceDimensionCriteriaTransfer = $priceProductDimensionQueryExpanderPlugin
            ->buildPriceDimensionCriteria($priceProductCriteriaTransfer);

        if (!$priceDimensionCriteriaTransfer) {
            return null;
        }
        $this->addJoin($priceProductStoreQuery, $priceDimensionCriteriaTransfer, $joinType);
        $this->addWithColumns($priceProductStoreQuery, $priceDimensionCriteriaTransfer);

        return $priceDimensionCriteriaTransfer;
    }

    /**
     * @param \Orm\Zed\PriceProduct\Persistence\SpyPriceProductStoreQuery $priceProductStoreQuery
     * @param \Generated\Shared\Transfer\PriceDimensionCriteriaTransfer $priceDimensionCriteriaTransfer
     * @param null|string $joinType
     *
     * @return void
     */
    protected function addJoin(
        SpyPriceProductStoreQuery $priceProductStoreQuery,
        PriceDimensionCriteriaTransfer $priceDimensionCriteriaTransfer,
        $joinType = null
    ): void {

        foreach ($priceDimensionCriteriaTransfer->getPriceDimensionJoins() as $priceDimensionJoinTransfer) {
            $priceProductStoreQuery->addJoin(
                $priceDimensionJoinTransfer->getLeft(),
                $priceDimensionJoinTransfer->getRight(),
                $joinType ?: $priceDimensionJoinTransfer->getJoinType()
            );
        }
    }

    /**
     * @param \Orm\Zed\PriceProduct\Persistence\SpyPriceProductStoreQuery $priceProductStoreQuery
     * @param \Generated\Shared\Transfer\PriceDimensionCriteriaTransfer $priceDimensionCriteriaTransfer
     *
     * @return void
     */
    protected function addWithColumns(
        SpyPriceProductStoreQuery $priceProductStoreQuery,
        PriceDimensionCriteriaTransfer $priceDimensionCriteriaTransfer
    ): void {
        foreach ($priceDimensionCriteriaTransfer->getWithColumns() as $field => $value) {
            $priceProductStoreQuery->withColumn($field, $value);
        }
    }

    /**
     * @param \Orm\Zed\PriceProduct\Persistence\SpyPriceProductStoreQuery $priceProductStoreQuery
     * @param \Generated\Shared\Transfer\PriceDimensionCriteriaTransfer $priceDimensionCriteriaTransfer
     *
     * @return void
     */
    protected function filterEmptyDimensions(
        SpyPriceProductStoreQuery $priceProductStoreQuery,
        PriceDimensionCriteriaTransfer $priceDimensionCriteriaTransfer
    ): void {
        foreach ($priceDimensionCriteriaTransfer->getWithColumns() as $field => $value) {
            $priceProductStoreQuery->addAnd($field, null, Criteria::ISNULL);
        }
    }
}
