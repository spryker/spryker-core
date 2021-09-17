<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesProductConnector\Persistence;

use DateInterval;
use DateTimeImmutable;
use Generated\Shared\Transfer\ProductPayloadTransfer;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\Sales\Persistence\Map\SpySalesOrderItemTableMap;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\SalesProductConnector\Persistence\SalesProductConnectorPersistenceFactory getFactory()
 */
class SalesProductConnectorRepository extends AbstractRepository implements SalesProductConnectorRepositoryInterface
{
    protected const FIELD_FK_PRODUCT_ABSTRACT = ProductPayloadTransfer::ID_PRODUCT_ABSTRACT;
    protected const FIELD_POPULARITY = ProductPayloadTransfer::POPULARITY;

    /**
     * @param array<int> $salesOrderItemIds
     *
     * @return array<\Generated\Shared\Transfer\ItemMetadataTransfer>
     */
    public function getSalesOrderItemMetadataByOrderItemIds(array $salesOrderItemIds): array
    {
        if (!$salesOrderItemIds) {
            return [];
        }

        $salesOrderItemMetadataQuery = $this->getFactory()
            ->createProductMetadataQuery()
            ->filterByFkSalesOrderItem_In($salesOrderItemIds);

        return $this->getFactory()
            ->createSalesOrderItemMetadataMapper()
            ->mapSalesOrderItemMetadataEntityCollectionToItemMetadataTransfers($salesOrderItemMetadataQuery->find());
    }

    /**
     * @param array<string> $productConcreteSkus
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    public function getRawProductConcreteTransfersByConcreteSkus(array $productConcreteSkus): array
    {
        $productQuery = $this->getFactory()
            ->getProductQueryContainer()
            ->queryProduct()
            ->filterBySku_In($productConcreteSkus);

        return $this->getFactory()
            ->createProductMapper()
            ->mapProductEntityCollectionToRawProductConcreteTransfers($productQuery->find());
    }

    /**
     * @param array<int> $productAbstractIds
     * @param int $interval
     *
     * @return array<mixed>
     */
    public function getRawProductPopularityByProductAbstractIdsAndInterval(array $productAbstractIds, int $interval): array
    {
        if (!$productAbstractIds) {
            return [];
        }

        $productAbstractIds = implode(',', $productAbstractIds);

        $salesOrderQuery = $this->getFactory()->createSalesOrderItemQuery();

        $this->addJoinByColSku($salesOrderQuery);
        $this->addWhereByInterval($salesOrderQuery, $interval);

        return $salesOrderQuery
            ->withColumn(sprintf('SUM(%s)', SpySalesOrderItemTableMap::COL_QUANTITY), static::FIELD_POPULARITY)
            ->where(sprintf(
                '%s IN (%s)',
                SpyProductTableMap::COL_FK_PRODUCT_ABSTRACT,
                $productAbstractIds
            ))
            ->select([static::FIELD_POPULARITY])
            ->groupBy(SpySalesOrderItemTableMap::COL_SKU)
            ->find()
            ->toArray(static::FIELD_FK_PRODUCT_ABSTRACT);
    }

    /**
     * @param int $interval
     *
     * @return array
     */
    public function getProductAbstractIdsForRefreshByInterval(int $interval): array
    {
        $salesOrderQuery = $this->getFactory()->createSalesOrderItemQuery();

        $this->addJoinByColSku($salesOrderQuery);
        $this->addWhereByInterval($salesOrderQuery, $interval);

        return $salesOrderQuery
            ->select([static::FIELD_FK_PRODUCT_ABSTRACT])
            ->distinct()
            ->find()
            ->toArray();
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery $query
     *
     * @return void
     */
    protected function addJoinByColSku(SpySalesOrderItemQuery $query)
    {
        $query
            ->addJoin(SpySalesOrderItemTableMap::COL_SKU, SpyProductTableMap::COL_SKU, Criteria::LEFT_JOIN)
            ->withColumn(SpyProductTableMap::COL_FK_PRODUCT_ABSTRACT, static::FIELD_FK_PRODUCT_ABSTRACT);
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery $query
     * @param int $interval
     *
     * @return void
     */
    protected function addWhereByInterval(SpySalesOrderItemQuery $query, int $interval)
    {
        $dateNow = new DateTimeImmutable();
        $dateFrom = $dateNow->sub(new DateInterval('P' . $interval . 'D'));

        $query->where(sprintf(
            "%s >= '%s'",
            SpySalesOrderItemTableMap::COL_CREATED_AT,
            $dateFrom->format('Y-m-d H:i:s')
        ));
    }
}
