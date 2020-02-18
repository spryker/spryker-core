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
            new SpyMerchantSalesOrder()
        );

        $merchantSalesOrderEntity->save();

        return $merchantSalesOrderMapper->mapMerchantSalesOrderEntityToMerchantOrderTransfer(
            $merchantSalesOrderEntity,
            $merchantOrderTransfer
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
                new SpyMerchantSalesOrderItem()
            );

        $merchantSalesOrderItemEntity->save();

        return $merchantSalesOrderMapper->mapMerchantSalesOrderItemEntityToMerchantOrderItemTransfer(
            $merchantSalesOrderItemEntity,
            $merchantOrderItemTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\TotalsTransfer $totalsTransfer
     *
     * @return \Generated\Shared\Transfer\TotalsTransfer
     */
    public function createMerchantOrderTotals(TotalsTransfer $totalsTransfer): TotalsTransfer
    {
        $merchantSalesOrderMapper = $this->getFactory()->createMerchantSalesOrderMapper();

        $merchantSalesOrderTotalsEntity = $merchantSalesOrderMapper->mapTotalsTransferToMerchantSalesOrderTotalsEntity(
            $totalsTransfer,
            new SpyMerchantSalesOrderTotals()
        );

        $merchantSalesOrderTotalsEntity->save();

        return $merchantSalesOrderMapper->mapMerchantSalesOrderTotalsEntityToTotalsTransfer(
            $merchantSalesOrderTotalsEntity,
            $totalsTransfer
        );
    }

    /**
     * @inheritDoc
     */
    public function updateMerchantOrderItem(MerchantOrderItemTransfer $merchantOrderItemTransfer): MerchantOrderItemTransfer
    {
        $merchantOrderItemTransfer->requireIdMerchantOrderItem();

        $merchantSalesOrderMapper = $this->getFactory()->createMerchantSalesOrderMapper();

        $merchantSalesOrderItemEntity = $this->getFactory()->createMerchantSalesOrderItemQuery()
            ->filterByIdMerchantSalesOrderItem($merchantOrderItemTransfer->getIdMerchantOrderItem())
            ->findOne();

        $merchantSalesOrderItemEntity = $merchantSalesOrderMapper->mapMerchantOrderItemTransferToMerchantSalesOrderItemEntity(
            $merchantOrderItemTransfer,
            $merchantSalesOrderItemEntity
        );

        $merchantSalesOrderItemEntity->save();

        return $merchantSalesOrderMapper->mapMerchantSalesOrderItemEntityToMerchantOrderItemTransfer(
            $merchantSalesOrderItemEntity,
            $merchantOrderItemTransfer
        );
    }
}
