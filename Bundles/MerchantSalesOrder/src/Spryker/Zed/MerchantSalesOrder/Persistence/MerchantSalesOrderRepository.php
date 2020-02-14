<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrder\Persistence;

use Generated\Shared\Transfer\MerchantOrderCollectionTransfer;
use Generated\Shared\Transfer\MerchantOrderCriteriaFilterTransfer;
use Generated\Shared\Transfer\MerchantOrderTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Orm\Zed\Merchant\Persistence\Map\SpyMerchantTableMap;
use Orm\Zed\MerchantSalesOrder\Persistence\Map\SpyMerchantSalesOrderTableMap;
use Orm\Zed\MerchantSalesOrder\Persistence\Map\SpyMerchantSalesOrderTotalsTableMap;
use Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderQuery;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

/**
 * @method \Spryker\Zed\MerchantSalesOrder\Persistence\MerchantSalesOrderPersistenceFactory getFactory()
 */
class MerchantSalesOrderRepository extends AbstractRepository implements MerchantSalesOrderRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantOrderCriteriaFilterTransfer $merchantOrderCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantOrderCollectionTransfer
     */
    public function getMerchantOrderCollection(
        MerchantOrderCriteriaFilterTransfer $merchantOrderCriteriaFilterTransfer
    ): MerchantOrderCollectionTransfer {
        $merchantSalesOrderQuery = $this->getFactory()->createMerchantSalesOrderQuery();

        if ($merchantOrderCriteriaFilterTransfer->getWithItems()) {
            $merchantSalesOrderQuery->leftJoinWithMerchantSalesOrderItem();
        }

        $merchantSalesOrderQuery = $this->applyFilters($merchantSalesOrderQuery, $merchantOrderCriteriaFilterTransfer);
        /** @var \Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderQuery $merchantSalesOrderQuery */
        $merchantSalesOrderQuery = $this->buildQueryFromCriteria(
            $merchantSalesOrderQuery,
            $merchantOrderCriteriaFilterTransfer->getFilter()
        );
        $merchantSalesOrderQuery->setFormatter(ModelCriteria::FORMAT_OBJECT);
        $merchantSalesOrderCollection = $this
            ->applyPagination($merchantSalesOrderQuery, $merchantOrderCriteriaFilterTransfer->getPagination())
            ->leftJoinWithMerchantSalesOrderTotal()
            ->find();

        return $this->getFactory()
            ->createMerchantSalesOrderMapper()
            ->mapMerchantSalesOrderEntityCollectionToMerchantOrderCollectionTransfer(
                $merchantSalesOrderCollection,
                new MerchantOrderCollectionTransfer()
            );
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantOrderCriteriaFilterTransfer $merchantOrderCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantOrderTransfer|null
     */
    public function findMerchantOrder(
        MerchantOrderCriteriaFilterTransfer $merchantOrderCriteriaFilterTransfer
    ): ?MerchantOrderTransfer {
        $merchantSalesOrderQuery = $this->getFactory()->createMerchantSalesOrderQuery();
        $merchantSalesOrderQuery = $this->addMerchantSalesOrderTotalsDataToMerchantSalesOrderQuery(
            $merchantSalesOrderQuery
        );
        $merchantSalesOrderEntity = $this
            ->applyFilters($merchantSalesOrderQuery, $merchantOrderCriteriaFilterTransfer)
            ->findOne();

        if (!$merchantSalesOrderEntity) {
            return null;
        }

        if ($merchantOrderCriteriaFilterTransfer->getWithItems()) {
            $merchantSalesOrderEntity->getMerchantSalesOrderItems();
        }

        return $this->getFactory()
            ->createMerchantSalesOrderMapper()
            ->mapMerchantSalesOrderEntityToMerchantOrderTransfer($merchantSalesOrderEntity, new MerchantOrderTransfer());
    }

    /**
     * @param \Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderQuery $merchantSalesOrderQuery
     *
     * @return \Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderQuery
     */
    protected function addMerchantSalesOrderTotalsDataToMerchantSalesOrderQuery(
        SpyMerchantSalesOrderQuery $merchantSalesOrderQuery
    ): SpyMerchantSalesOrderQuery {
        $merchantSalesOrderQuery->useMerchantSalesOrderTotalQuery()
            ->withColumn(SpyMerchantSalesOrderTotalsTableMap::COL_REFUND_TOTAL, TotalsTransfer::REFUND_TOTAL)
            ->withColumn(SpyMerchantSalesOrderTotalsTableMap::COL_GRAND_TOTAL, TotalsTransfer::GRAND_TOTAL)
            ->withColumn(SpyMerchantSalesOrderTotalsTableMap::COL_TAX_TOTAL, TotalsTransfer::TAX_TOTAL)
            ->withColumn(SpyMerchantSalesOrderTotalsTableMap::COL_ORDER_EXPENSE_TOTAL, TotalsTransfer::EXPENSE_TOTAL)
            ->withColumn(SpyMerchantSalesOrderTotalsTableMap::COL_SUBTOTAL, TotalsTransfer::SUBTOTAL)
            ->withColumn(SpyMerchantSalesOrderTotalsTableMap::COL_DISCOUNT_TOTAL, TotalsTransfer::DISCOUNT_TOTAL)
            ->withColumn(SpyMerchantSalesOrderTotalsTableMap::COL_CANCELED_TOTAL, TotalsTransfer::CANCELED_TOTAL)
        ->endUse();

        return $merchantSalesOrderQuery;
    }

    /**
     * @param \Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderQuery $merchantSalesOrderQuery
     * @param \Generated\Shared\Transfer\MerchantOrderCriteriaFilterTransfer $merchantOrderCriteriaFilterTransfer
     *
     * @return \Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderQuery
     */
    protected function applyFilters(
        SpyMerchantSalesOrderQuery $merchantSalesOrderQuery,
        MerchantOrderCriteriaFilterTransfer $merchantOrderCriteriaFilterTransfer
    ): SpyMerchantSalesOrderQuery {
        if ($merchantOrderCriteriaFilterTransfer->getIdMerchantSalesOrder() !== null) {
            $merchantSalesOrderQuery->filterByIdMerchantSalesOrder(
                $merchantOrderCriteriaFilterTransfer->getIdMerchantSalesOrder()
            );
        }

        if ($merchantOrderCriteriaFilterTransfer->getMerchantSalesOrderReference() !== null) {
            $merchantSalesOrderQuery->filterByMerchantSalesOrderReference(
                $merchantOrderCriteriaFilterTransfer->getMerchantSalesOrderReference()
            );
        }

        if ($merchantOrderCriteriaFilterTransfer->getMerchantReference() !== null) {
            $merchantSalesOrderQuery->filterByMerchantReference(
                $merchantOrderCriteriaFilterTransfer->getMerchantReference()
            );
        }

        if ($merchantOrderCriteriaFilterTransfer->getIdSalesOrder() !== null) {
            $merchantSalesOrderQuery->filterByFkSalesOrder(
                $merchantOrderCriteriaFilterTransfer->getIdSalesOrder()
            );
        }

        if ($merchantOrderCriteriaFilterTransfer->getIdMerchant() !== null) {
            $merchantSalesOrderQuery->addJoin(
                SpyMerchantSalesOrderTableMap::COL_MERCHANT_REFERENCE,
                SpyMerchantTableMap::COL_MERCHANT_REFERENCE,
                Criteria::INNER_JOIN
            );
            $merchantSalesOrderQuery->addAnd(
                SpyMerchantTableMap::COL_ID_MERCHANT,
                $merchantOrderCriteriaFilterTransfer->getIdMerchant()
            );
        }

        return $merchantSalesOrderQuery;
    }

    /**
     * @param \Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderQuery $merchantSalesOrderQuery
     * @param \Generated\Shared\Transfer\PaginationTransfer|null $paginationTransfer
     *
     * @return \Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderQuery
     */
    protected function applyPagination(
        SpyMerchantSalesOrderQuery $merchantSalesOrderQuery,
        ?PaginationTransfer $paginationTransfer = null
    ): SpyMerchantSalesOrderQuery {
        if (!$paginationTransfer) {
            return $merchantSalesOrderQuery;
        }

        $page = $paginationTransfer
            ->requirePage()
            ->getPage();
        $maxPerPage = $paginationTransfer
            ->requireMaxPerPage()
            ->getMaxPerPage();
        $paginationModel = $merchantSalesOrderQuery->paginate($page, $maxPerPage);

        $paginationTransfer->setNbResults($paginationModel->getNbResults());
        $paginationTransfer->setFirstIndex($paginationModel->getFirstIndex());
        $paginationTransfer->setLastIndex($paginationModel->getLastIndex());
        $paginationTransfer->setFirstPage($paginationModel->getFirstPage());
        $paginationTransfer->setLastPage($paginationModel->getLastPage());
        $paginationTransfer->setNextPage($paginationModel->getNextPage());
        $paginationTransfer->setPreviousPage($paginationModel->getPreviousPage());

        return $paginationModel->getQuery();
    }
}
