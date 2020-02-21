<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantSalesOrder\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\MerchantOrderBuilder;
use Generated\Shared\DataBuilder\MerchantOrderItemBuilder;
use Generated\Shared\DataBuilder\TotalsBuilder;
use Generated\Shared\Transfer\MerchantOrderItemTransfer;
use Generated\Shared\Transfer\MerchantOrderTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrder;
use Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderItem;
use Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderTotals;

class MerchantSalesHelper extends Module
{
    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\MerchantOrderTransfer
     */
    public function haveMerchantOrder(array $seedData = []): MerchantOrderTransfer
    {
        $merchantOrderTransfer = (new MerchantOrderBuilder($seedData))->build();
        $merchantOrderTransfer->setIdMerchantOrder(null);

        $merchantSalesOrderEntity = new SpyMerchantSalesOrder();
        $merchantSalesOrderEntity->fromArray($merchantOrderTransfer->modifiedToArray());
        $merchantSalesOrderEntity->setMerchantSalesOrderReference($merchantOrderTransfer->getMerchantOrderReference());
        $merchantSalesOrderEntity->setFkSalesOrder($merchantOrderTransfer->getIdOrder());
        $merchantSalesOrderEntity->save();

        $merchantOrderTransfer->fromArray($merchantSalesOrderEntity->toArray(), true);
        $merchantOrderTransfer->setMerchantOrderReference($merchantSalesOrderEntity->getMerchantSalesOrderReference());
        $merchantOrderTransfer->setIdMerchantOrder($merchantSalesOrderEntity->getIdMerchantSalesOrder());
        $merchantOrderTransfer->setIdOrder($merchantSalesOrderEntity->getFkSalesOrder());

        return $merchantOrderTransfer;
    }

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\MerchantOrderItemTransfer
     */
    public function haveMerchantOrderItem(array $seedData = []): MerchantOrderItemTransfer
    {
        $merchantOrderItemTransfer = (new MerchantOrderItemBuilder($seedData))->build();
        $merchantOrderItemTransfer->setIdMerchantOrderItem(null);

        $merchantSalesOrderItemEntity = new SpyMerchantSalesOrderItem();
        $merchantSalesOrderItemEntity->fromArray($merchantOrderItemTransfer->modifiedToArray());
        $merchantSalesOrderItemEntity->setFkSalesOrderItem($merchantOrderItemTransfer->getIdOrderItem());
        $merchantSalesOrderItemEntity->setFkMerchantSalesOrder($merchantOrderItemTransfer->getIdMerchantOrder());
        $merchantSalesOrderItemEntity->save();

        $merchantOrderItemTransfer->fromArray($merchantSalesOrderItemEntity->toArray(), true);
        $merchantOrderItemTransfer->setIdMerchantOrderItem($merchantSalesOrderItemEntity->getIdMerchantSalesOrderItem());
        $merchantOrderItemTransfer->setIdOrderItem($merchantSalesOrderItemEntity->getFkSalesOrderItem());
        $merchantOrderItemTransfer->setIdMerchantOrder($merchantSalesOrderItemEntity->getFkMerchantSalesOrder());

        return $merchantOrderItemTransfer;
    }

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\TotalsTransfer
     */
    public function haveMerchantOrderTotals(array $seedData = []): TotalsTransfer
    {
        $totalsTransfer = (new TotalsBuilder($seedData))->build();

        $merchantSalesOrderTotalsEntity = new SpyMerchantSalesOrderTotals();
        $merchantSalesOrderTotalsEntity->fromArray($totalsTransfer->modifiedToArray());
        $merchantSalesOrderTotalsEntity->setFkMerchantSalesOrder($totalsTransfer->getIdMerchantOrder());
        $merchantSalesOrderTotalsEntity->save();

        $totalsTransfer->fromArray($merchantSalesOrderTotalsEntity->toArray(), true);
        $totalsTransfer->setIdMerchantOrder($merchantSalesOrderTotalsEntity->getFkMerchantSalesOrder());

        return $totalsTransfer;
    }
}
