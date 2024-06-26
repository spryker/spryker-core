<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrder\Persistence;

use Generated\Shared\Transfer\MerchantOrderItemTransfer;
use Generated\Shared\Transfer\MerchantOrderTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrder;
use Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderItem;
use Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderTotals;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\MerchantSalesOrder\Persistence\MerchantSalesOrderPersistenceFactory getFactory()
 */
class MerchantSalesOrderEntityManager extends AbstractEntityManager implements MerchantSalesOrderEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantOrderTransfer $merchantOrderTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantOrderTransfer
     */
    public function createMerchantOrder(MerchantOrderTransfer $merchantOrderTransfer): MerchantOrderTransfer
    {
        $merchantSalesOrderMapper = $this->getFactory()->createMerchantSalesOrderMapper();

        $merchantSalesOrderEntity = $merchantSalesOrderMapper->mapMerchantOrderTransferToMerchantSalesOrderEntity(
            $merchantOrderTransfer,
            new SpyMerchantSalesOrder(),
        );

        $merchantSalesOrderEntity->save();

        return $merchantSalesOrderMapper->mapMerchantSalesOrderEntityToMerchantOrderTransfer(
            $merchantSalesOrderEntity,
            $merchantOrderTransfer,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantOrderItemTransfer $merchantOrderItemTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantOrderItemTransfer
     */
    public function createMerchantOrderItem(MerchantOrderItemTransfer $merchantOrderItemTransfer): MerchantOrderItemTransfer
    {
        $merchantSalesOrderMapper = $this->getFactory()->createMerchantSalesOrderMapper();

        $merchantSalesOrderItemEntity = $merchantSalesOrderMapper
            ->mapMerchantOrderItemTransferToMerchantSalesOrderItemEntity(
                $merchantOrderItemTransfer,
                new SpyMerchantSalesOrderItem(),
            );

        $merchantSalesOrderItemEntity->save();

        return $merchantSalesOrderMapper->mapMerchantSalesOrderItemEntityToMerchantOrderItemTransfer(
            $merchantSalesOrderItemEntity,
            $merchantOrderItemTransfer,
        );
    }

    /**
     * @param int $idMerchantOrder
     * @param \Generated\Shared\Transfer\TotalsTransfer $totalsTransfer
     *
     * @return \Generated\Shared\Transfer\TotalsTransfer
     */
    public function createMerchantOrderTotals(int $idMerchantOrder, TotalsTransfer $totalsTransfer): TotalsTransfer
    {
        $merchantSalesOrderMapper = $this->getFactory()->createMerchantSalesOrderMapper();

        $merchantSalesOrderTotalsEntity = $merchantSalesOrderMapper->mapTotalsTransferToMerchantSalesOrderTotalsEntity(
            $idMerchantOrder,
            $totalsTransfer,
            new SpyMerchantSalesOrderTotals(),
        );

        $merchantSalesOrderTotalsEntity->save();

        return $merchantSalesOrderMapper->mapMerchantSalesOrderTotalsEntityToTotalsTransfer(
            $merchantSalesOrderTotalsEntity,
            $totalsTransfer,
        );
    }

    /**
     * @param int $idMerchantOrder
     * @param \Generated\Shared\Transfer\TotalsTransfer $totalsTransfer
     *
     * @return \Generated\Shared\Transfer\TotalsTransfer
     */
    public function updateMerchantOrderTotals(int $idMerchantOrder, TotalsTransfer $totalsTransfer): TotalsTransfer
    {
        $merchantSalesOrderMapper = $this->getFactory()->createMerchantSalesOrderMapper();

        $merchantSalesOrderTotalsEntity = $this->getFactory()
            ->createMerchantSalesOrderTotalsQuery()
            ->filterByFkMerchantSalesOrder($idMerchantOrder)
            ->findOne();

        if ($merchantSalesOrderTotalsEntity === null) {
            return $totalsTransfer;
        }

        $merchantSalesOrderTotalsEntity = $merchantSalesOrderMapper->mapTotalsTransferToMerchantSalesOrderTotalsEntity(
            $idMerchantOrder,
            $totalsTransfer,
            $merchantSalesOrderTotalsEntity,
        );

        $merchantSalesOrderTotalsEntity->save();

        return $merchantSalesOrderMapper->mapMerchantSalesOrderTotalsEntityToTotalsTransfer(
            $merchantSalesOrderTotalsEntity,
            $totalsTransfer,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantOrderItemTransfer $merchantOrderItemTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantOrderItemTransfer
     */
    public function updateMerchantOrderItem(MerchantOrderItemTransfer $merchantOrderItemTransfer): MerchantOrderItemTransfer
    {
        $merchantSalesOrderItemEntity = $this->getFactory()->createMerchantSalesOrderItemQuery()
            ->filterByIdMerchantSalesOrderItem($merchantOrderItemTransfer->getIdMerchantOrderItem())
            ->findOne();

        if (!$merchantSalesOrderItemEntity) {
            return $merchantOrderItemTransfer;
        }

        $merchantSalesOrderMapper = $this->getFactory()->createMerchantSalesOrderMapper();

        $merchantSalesOrderItemEntity = $merchantSalesOrderMapper->mapMerchantOrderItemTransferToMerchantSalesOrderItemEntity(
            $merchantOrderItemTransfer,
            $merchantSalesOrderItemEntity,
        );

        $merchantSalesOrderItemEntity->save();

        return $merchantSalesOrderMapper->mapMerchantSalesOrderItemEntityToMerchantOrderItemTransfer(
            $merchantSalesOrderItemEntity,
            $merchantOrderItemTransfer,
        );
    }
}
