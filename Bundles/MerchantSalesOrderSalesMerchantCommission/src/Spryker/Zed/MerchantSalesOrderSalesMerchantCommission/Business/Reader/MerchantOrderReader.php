<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrderSalesMerchantCommission\Business\Reader;

use ArrayObject;
use Generated\Shared\Transfer\MerchantOrderCriteriaTransfer;
use Generated\Shared\Transfer\MerchantOrderTransfer;
use Spryker\Zed\MerchantSalesOrderSalesMerchantCommission\Dependency\Facade\MerchantSalesOrderSalesMerchantCommissionToMerchantSalesOrderFacadeInterface;

class MerchantOrderReader implements MerchantOrderReaderInterface
{
    /**
     * @var \Spryker\Zed\MerchantSalesOrderSalesMerchantCommission\Dependency\Facade\MerchantSalesOrderSalesMerchantCommissionToMerchantSalesOrderFacadeInterface
     */
    protected MerchantSalesOrderSalesMerchantCommissionToMerchantSalesOrderFacadeInterface $merchantSalesOrderFacade;

    /**
     * @param \Spryker\Zed\MerchantSalesOrderSalesMerchantCommission\Dependency\Facade\MerchantSalesOrderSalesMerchantCommissionToMerchantSalesOrderFacadeInterface $merchantSalesOrderFacade
     */
    public function __construct(
        MerchantSalesOrderSalesMerchantCommissionToMerchantSalesOrderFacadeInterface $merchantSalesOrderFacade
    ) {
        $this->merchantSalesOrderFacade = $merchantSalesOrderFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantOrderTransfer $merchantOrderTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantOrderTransfer|null
     */
    public function findMerchantOrder(MerchantOrderTransfer $merchantOrderTransfer): ?MerchantOrderTransfer
    {
        $merchantOrderCriteriaTransfer = (new MerchantOrderCriteriaTransfer())
            ->setIdOrder($merchantOrderTransfer->getIdOrderOrFail())
            ->setWithOrder(true)
            ->setWithItems(true);

        $merchantOrderTransfer = $this->merchantSalesOrderFacade
            ->getMerchantOrderCollection($merchantOrderCriteriaTransfer)
            ->getMerchantOrders()
            ->getIterator()
            ->current();

        if (!$merchantOrderTransfer) {
            return null;
        }

        return $this->sanitizeDuplicatedMerchantOrderItems($merchantOrderTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantOrderTransfer $merchantOrderTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantOrderTransfer
     */
    protected function sanitizeDuplicatedMerchantOrderItems(MerchantOrderTransfer $merchantOrderTransfer): MerchantOrderTransfer
    {
        $uniqueMerchantOrderItems = [];
        foreach ($merchantOrderTransfer->getMerchantOrderItems() as $merchantOrderItemTransfer) {
            $idMerchantOrderItem = $merchantOrderItemTransfer->getIdMerchantOrderItemOrFail();
            $uniqueMerchantOrderItems[$idMerchantOrderItem] = $merchantOrderItemTransfer;
        }

        return $merchantOrderTransfer->setMerchantOrderItems(new ArrayObject($uniqueMerchantOrderItems));
    }
}
