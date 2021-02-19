<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesReturn\Business\Model;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderItemFilterTransfer;
use Generated\Shared\Transfer\ReturnTransfer;
use ArrayObject;
use Spryker\Zed\MerchantSalesReturn\Dependency\Facade\MerchantSalesReturnToSalesFacadeInterface;

class MerchantReturnPreparer implements MerchantReturnPreparerInterface
{
    /**
     * @var \Spryker\Zed\MerchantSalesReturn\Dependency\Facade\MerchantSalesReturnToSalesFacadeInterface
     */
    protected $salesFacade;

    /**
     * @param \Spryker\Zed\MerchantSalesReturn\Dependency\Facade\MerchantSalesReturnToSalesFacadeInterface $salesFacade
     */
    public function __construct(MerchantSalesReturnToSalesFacadeInterface $salesFacade)
    {
        $this->salesFacade = $salesFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ReturnTransfer $returnTransfer
     *
     * @return \Generated\Shared\Transfer\ReturnTransfer
     */
    public function prepareReturn(ReturnTransfer $returnTransfer): ReturnTransfer
    {
        $returnItemTransfers = $returnTransfer
            ->requireReturnItems()
            ->getReturnItems();


        $firstOrderItemTransfer = $this->getFirstOrderItem($returnItemTransfers);

        $merchantReference = $firstOrderItemTransfer
            ->getMerchantReferenceOrFail();

        $returnTransfer->setMerchantReference($merchantReference);

        return $returnTransfer;
    }

    /**
     * @param \ArrayObject $returnItemTransfers
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     * @throws \Exception
     */
    public function getFirstOrderItem(ArrayObject $returnItemTransfers): ItemTransfer
    {
        /** @var \Generated\Shared\Transfer\ReturnItemTransfer $firstReturnItem */
        $firstReturnItem = $returnItemTransfers->offsetGet(0);

        $orderItemFilterTransfer = (new OrderItemFilterTransfer())
            ->addSalesOrderItemId($firstReturnItem->getOrderItem()->getId());

        /** @var \Generated\Shared\Transfer\ItemTransfer[] $orderItemTransfers */
        $orderItemTransfers = $this->salesFacade->getOrderItems($orderItemFilterTransfer);
        foreach ($orderItemTransfers as $orderItemTransfer) {
            if ($orderItemTransfer->getId() === $firstReturnItem->getOrderItem()->getId()) {
                return $orderItemTransfer;
            }
        }

        throw new \Exception();
    }
}
