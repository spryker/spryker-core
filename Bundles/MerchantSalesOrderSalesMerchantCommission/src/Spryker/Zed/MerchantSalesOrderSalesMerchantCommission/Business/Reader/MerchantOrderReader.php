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
     * @param int $idSalesOrder
     * @param string $merchantReference
     *
     * @return \Generated\Shared\Transfer\MerchantOrderTransfer|null
     */
    public function findMerchantOrderByIdSalesOrderAndMerchantReference(int $idSalesOrder, string $merchantReference): ?MerchantOrderTransfer
    {
        $merchantOrderCriteriaTransfer = (new MerchantOrderCriteriaTransfer())
            ->setIdOrder($idSalesOrder)
            ->setMerchantReference($merchantReference)
            ->setWithOrder(true)
            ->setWithItems(true);

        return $this->findMerchantOrder($merchantOrderCriteriaTransfer);
    }

    /**
     * @param int $idMerchantOrder
     *
     * @return \Generated\Shared\Transfer\MerchantOrderTransfer|null
     */
    public function findMerchantOrderByIdMerchantOrder(int $idMerchantOrder): ?MerchantOrderTransfer
    {
        $merchantOrderCriteriaTransfer = (new MerchantOrderCriteriaTransfer())
            ->setIdMerchantOrder($idMerchantOrder)
            ->setWithOrder(true)
            ->setWithItems(true);

        return $this->findMerchantOrder($merchantOrderCriteriaTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantOrderCriteriaTransfer $merchantOrderCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantOrderTransfer|null
     */
    protected function findMerchantOrder(MerchantOrderCriteriaTransfer $merchantOrderCriteriaTransfer): ?MerchantOrderTransfer
    {
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
