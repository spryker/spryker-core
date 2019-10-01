<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProduct\Persistence;

use Generated\Shared\Transfer\PriceProductCriteriaTransfer;
use Generated\Shared\Transfer\QueryCriteriaTransfer;
use Orm\Zed\PriceProduct\Persistence\SpyPriceProductStoreQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\PriceProductExtension\Dependency\Plugin\PriceDimensionQueryCriteriaPluginInterface;
use Spryker\Zed\PriceProductExtension\Dependency\Plugin\PriceDimensionUnconditionalQueryCriteriaPluginInterface;

class PriceProductDimensionQueryExpander implements PriceProductDimensionQueryExpanderInterface
{
    /**
     * @var \Spryker\Zed\PriceProductExtension\Dependency\Plugin\PriceDimensionQueryCriteriaPluginInterface[]
     */
    protected $priceDimensionQueryCriteriaPlugins;

    /**
     * @param \Spryker\Zed\PriceProductExtension\Dependency\Plugin\PriceDimensionQueryCriteriaPluginInterface[] $priceProductDimensionQueryCriteriaPlugins
     */
    public function __construct(array $priceProductDimensionQueryCriteriaPlugins)
    {
        $this->priceDimensionQueryCriteriaPlugins = $priceProductDimensionQueryCriteriaPlugins;
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
        foreach ($this->priceDimensionQueryCriteriaPlugins as $priceDimensionQueryCriteriaPlugin) {
            if (!($priceDimensionQueryCriteriaPlugin instanceof PriceDimensionUnconditionalQueryCriteriaPluginInterface)) {
                $this->executePriceProductStoreQueryWithPriceDimensionForDeletePlugin(
                    $priceProductStoreQuery,
                    $priceProductCriteriaTransfer,
                    $priceDimensionQueryCriteriaPlugin
                );

                continue;
            }

            $queryCriteriaTransfer = $priceDimensionQueryCriteriaPlugin->buildUnconditionalPriceDimensionQueryCriteria();

            $this->addJoin($priceProductStoreQuery, $queryCriteriaTransfer);
            $this->addWithColumns($priceProductStoreQuery, $queryCriteriaTransfer);
            $this->filterEmptyDimensions($priceProductStoreQuery, $queryCriteriaTransfer);
        }

        return $priceProductStoreQuery;
    }

    /**
     * @deprecated this method exists for BC reasons only.
     *
     * @param \Orm\Zed\PriceProduct\Persistence\SpyPriceProductStoreQuery $priceProductStoreQuery
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     * @param \Spryker\Zed\PriceProductExtension\Dependency\Plugin\PriceDimensionQueryCriteriaPluginInterface $priceDimensionQueryCriteriaPlugin
     *
     * @return void
     */
    protected function executePriceProductStoreQueryWithPriceDimensionForDeletePlugin(
        SpyPriceProductStoreQuery $priceProductStoreQuery,
        PriceProductCriteriaTransfer $priceProductCriteriaTransfer,
        PriceDimensionQueryCriteriaPluginInterface $priceDimensionQueryCriteriaPlugin
    ): void {
        $priceDimensionQueryCriteriaTransfer = $this->runPlugin(
            $priceProductStoreQuery,
            $priceProductCriteriaTransfer,
            $priceDimensionQueryCriteriaPlugin
        );

        if (!$priceDimensionQueryCriteriaTransfer) {
            return;
        }

        $this->filterEmptyDimensions($priceProductStoreQuery, $priceDimensionQueryCriteriaTransfer);
    }

    /**
     * @param string $priceDimensionName
     *
     * @return \Spryker\Zed\PriceProductExtension\Dependency\Plugin\PriceDimensionQueryCriteriaPluginInterface|null
     */
    protected function findPriceDimensionCriteriaPluginByName(string $priceDimensionName): ?PriceDimensionQueryCriteriaPluginInterface
    {
        foreach ($this->priceDimensionQueryCriteriaPlugins as $priceDimensionQueryCriteriaPlugin) {
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
        foreach ($this->priceDimensionQueryCriteriaPlugins as $priceDimensionQueryCriteriaPlugin) {
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
            $priceProductCriteriaTransfer->getPriceDimension()->getType()
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
     * @param \Spryker\Zed\PriceProductExtension\Dependency\Plugin\PriceDimensionQueryCriteriaPluginInterface $priceProductDimensionQueryExpanderPlugin
     * @param string|null $joinType
     *
     * @return \Generated\Shared\Transfer\QueryCriteriaTransfer|null
     */
    protected function runPlugin(
        SpyPriceProductStoreQuery $priceProductStoreQuery,
        PriceProductCriteriaTransfer $priceProductCriteriaTransfer,
        PriceDimensionQueryCriteriaPluginInterface $priceProductDimensionQueryExpanderPlugin,
        $joinType = null
    ): ?QueryCriteriaTransfer {
        $queryCriteriaTransfer = $priceProductDimensionQueryExpanderPlugin
            ->buildPriceDimensionQueryCriteria($priceProductCriteriaTransfer);

        if (!$queryCriteriaTransfer) {
            return null;
        }
        $this->addJoin($priceProductStoreQuery, $queryCriteriaTransfer, $joinType);
        $this->addWithColumns($priceProductStoreQuery, $queryCriteriaTransfer);

        return $queryCriteriaTransfer;
    }

    /**
     * @param \Orm\Zed\PriceProduct\Persistence\SpyPriceProductStoreQuery $priceProductStoreQuery
     * @param \Generated\Shared\Transfer\QueryCriteriaTransfer $queryCriteriaTransfer
     * @param string|null $joinType
     *
     * @return void
     */
    protected function addJoin(
        SpyPriceProductStoreQuery $priceProductStoreQuery,
        QueryCriteriaTransfer $queryCriteriaTransfer,
        $joinType = null
    ): void {
        foreach ($queryCriteriaTransfer->getJoins() as $queryJoinTransfer) {
            if ($queryJoinTransfer->getRelation()) {
                $joinDirection = $joinType ?? $queryJoinTransfer->getJoinType();
                $priceProductStoreQuery->join($queryJoinTransfer->getRelation(), $joinDirection);

                if ($queryJoinTransfer->getCondition()) {
                    $priceProductStoreQuery->addJoinCondition($queryJoinTransfer->getRelation(), $queryJoinTransfer->getCondition());
                }
                continue;
            }
            $priceProductStoreQuery->addJoin(
                $queryJoinTransfer->getLeft(),
                $queryJoinTransfer->getRight(),
                $joinType ?: $queryJoinTransfer->getJoinType()
            );
        }
    }

    /**
     * @param \Orm\Zed\PriceProduct\Persistence\SpyPriceProductStoreQuery $priceProductStoreQuery
     * @param \Generated\Shared\Transfer\QueryCriteriaTransfer $queryCriteriaTransfer
     *
     * @return void
     */
    protected function addWithColumns(
        SpyPriceProductStoreQuery $priceProductStoreQuery,
        QueryCriteriaTransfer $queryCriteriaTransfer
    ): void {
        foreach ($queryCriteriaTransfer->getWithColumns() as $field => $value) {
            $priceProductStoreQuery->withColumn($field, $value);
        }
    }

    /**
     * @param \Orm\Zed\PriceProduct\Persistence\SpyPriceProductStoreQuery $priceProductStoreQuery
     * @param \Generated\Shared\Transfer\QueryCriteriaTransfer $queryCriteriaTransfer
     *
     * @return void
     */
    protected function filterEmptyDimensions(
        SpyPriceProductStoreQuery $priceProductStoreQuery,
        QueryCriteriaTransfer $queryCriteriaTransfer
    ): void {
        foreach ($queryCriteriaTransfer->getWithColumns() as $field => $value) {
            $priceProductStoreQuery->addAnd($field, null, Criteria::ISNULL);
        }
    }
}
