<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrder\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\MerchantOrderItemTransfer;
use Generated\Shared\Transfer\MerchantOrderTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrder;
use Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderItem;
use Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderTotals;

class MerchantSalesOrderMapper
{
    /**
     * @param \Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrder $merchantSalesOrderEntity
     * @param \Generated\Shared\Transfer\MerchantOrderTransfer $merchantOrderTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantOrderTransfer
     */
    public function mapMerchantSalesOrderEntityToMerchantOrderTransfer(
        SpyMerchantSalesOrder $merchantSalesOrderEntity,
        MerchantOrderTransfer $merchantOrderTransfer
    ): MerchantOrderTransfer {
        $merchantOrderTransfer = $merchantOrderTransfer
            ->fromArray($merchantSalesOrderEntity->toArray(), true)
            ->setMerchantOrderReference($merchantSalesOrderEntity->getMerchantSalesOrderReference())
            ->setIdMerchantOrder($merchantSalesOrderEntity->getIdMerchantSalesOrder())
            ->setIdOrder($merchantSalesOrderEntity->getFkSalesOrder());

        $merchantOrderTransfer->setTotals(
            $this->findTotalsTransferInMerchantSalesOrderEntity($merchantSalesOrderEntity)
        );

        $merchantSalesOrderEntity->initMerchantSalesOrderItems(false);

        if ($merchantSalesOrderEntity->countMerchantSalesOrderItems()) {
            foreach ($merchantSalesOrderEntity->getMerchantSalesOrderItems() as $merchantSalesOrderItemEntity) {
                $merchantOrderTransfer->addMerchantOrderItem(
                    $this->mapMerchantSalesOrderItemEntityToMerchantOrderItemTransfer(
                        $merchantSalesOrderItemEntity,
                        new MerchantOrderItemTransfer()
                    )
                );
            }
        }

        return $merchantOrderTransfer;
    }

    /**
     * @param \Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrder $merchantSalesOrderEntity
     *
     * @return \Generated\Shared\Transfer\TotalsTransfer|null
     */
    protected function findTotalsTransferInMerchantSalesOrderEntity(
        SpyMerchantSalesOrder $merchantSalesOrderEntity
    ): ?TotalsTransfer {
        if ($merchantSalesOrderEntity->getVirtualColumns()) {
            return (new TotalsTransfer())->fromArray($merchantSalesOrderEntity->getVirtualColumns(), true);
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantOrderTransfer $merchantOrderTransfer
     * @param \Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrder $merchantSalesOrderEntity
     *
     * @return \Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrder
     */
    public function mapMerchantOrderTransferToMerchantSalesOrderEntity(
        MerchantOrderTransfer $merchantOrderTransfer,
        SpyMerchantSalesOrder $merchantSalesOrderEntity
    ): SpyMerchantSalesOrder {
        $merchantSalesOrderEntity->fromArray($merchantOrderTransfer->modifiedToArray());
        $merchantSalesOrderEntity->setMerchantSalesOrderReference($merchantOrderTransfer->getMerchantOrderReference());
        $merchantSalesOrderEntity->setIdMerchantSalesOrder($merchantOrderTransfer->getIdMerchantOrder());
        $merchantSalesOrderEntity->setFkSalesOrder($merchantOrderTransfer->getIdOrder());

        return $merchantSalesOrderEntity;
    }

    /**
     * @param \Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderItem $merchantSalesOrderItemEntity
     * @param \Generated\Shared\Transfer\MerchantOrderItemTransfer $merchantOrderItemTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantOrderItemTransfer
     */
    public function mapMerchantSalesOrderItemEntityToMerchantOrderItemTransfer(
        SpyMerchantSalesOrderItem $merchantSalesOrderItemEntity,
        MerchantOrderItemTransfer $merchantOrderItemTransfer
    ): MerchantOrderItemTransfer {
        return $merchantOrderItemTransfer
            ->setIdMerchantOrderItem($merchantSalesOrderItemEntity->getIdMerchantSalesOrderItem())
            ->setIdOrderItem($merchantSalesOrderItemEntity->getFkSalesOrderItem())
            ->setIdMerchantOrder($merchantSalesOrderItemEntity->getFkMerchantSalesOrder());
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantOrderItemTransfer $merchantOrderItemTransfer
     * @param \Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderItem $merchantSalesOrderItemEntity
     *
     * @return \Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderItem
     */
    public function mapMerchantOrderItemTransferToMerchantSalesOrderItemEntity(
        MerchantOrderItemTransfer $merchantOrderItemTransfer,
        SpyMerchantSalesOrderItem $merchantSalesOrderItemEntity
    ): SpyMerchantSalesOrderItem {
        $merchantSalesOrderItemEntity->setIdMerchantSalesOrderItem($merchantOrderItemTransfer->getIdMerchantOrderItem());
        $merchantSalesOrderItemEntity->setFkSalesOrderItem($merchantOrderItemTransfer->getIdOrderItem());
        $merchantSalesOrderItemEntity->setFkMerchantSalesOrder($merchantOrderItemTransfer->getIdMerchantOrder());

        return $merchantSalesOrderItemEntity;
    }

    /**
     * @param \Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderTotals $merchantSalesOrderTotalsEntity
     * @param \Generated\Shared\Transfer\TotalsTransfer $totalsTransfer
     *
     * @return \Generated\Shared\Transfer\TotalsTransfer
     */
    public function mapMerchantSalesOrderTotalsEntityToTotalsTransfer(
        SpyMerchantSalesOrderTotals $merchantSalesOrderTotalsEntity,
        TotalsTransfer $totalsTransfer
    ): TotalsTransfer {
        return $totalsTransfer
            ->fromArray($merchantSalesOrderTotalsEntity->toArray(), true)
            ->setIdMerchantOrder($merchantSalesOrderTotalsEntity->getFkMerchantSalesOrder());
    }

    /**
     * @param \Generated\Shared\Transfer\TotalsTransfer $totalsTransfer
     * @param \Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderTotals $merchantSalesOrderTotalsEntity
     *
     * @return \Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderTotals
     */
    public function mapTotalsTransferToMerchantSalesOrderTotalsEntity(
        TotalsTransfer $totalsTransfer,
        SpyMerchantSalesOrderTotals $merchantSalesOrderTotalsEntity
    ): SpyMerchantSalesOrderTotals {
        $merchantSalesOrderTotalsEntity->fromArray($totalsTransfer->modifiedToArray());
        $merchantSalesOrderTotalsEntity->setFkMerchantSalesOrder($totalsTransfer->getIdMerchantOrder());

        return $merchantSalesOrderTotalsEntity;
    }
}
