<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesReturn\Business\Model;

use ArrayObject;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderItemFilterTransfer;
use Generated\Shared\Transfer\ReturnTransfer;
use Spryker\Zed\MerchantSalesReturn\Dependency\Facade\MerchantSalesReturnToSalesFacadeInterface;

class MerchantReturnPreCreator implements MerchantReturnPreCreatorInterface
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
    public function preCreate(ReturnTransfer $returnTransfer): ReturnTransfer
    {
        $returnItemTransfers = $returnTransfer
            ->requireReturnItems()
            ->getReturnItems();

        $firstOrderItemTransfer = $this->findFirstOrderItem($returnItemTransfers);

        if (!$firstOrderItemTransfer) {
            return $returnTransfer;
        }

        $merchantReference = $firstOrderItemTransfer
            ->getMerchantReferenceOrFail();

        $returnTransfer->setMerchantReference($merchantReference);

        return $returnTransfer;
    }

    /**
     * @param \ArrayObject $returnItemTransfers
     *
     * @return \Generated\Shared\Transfer\ItemTransfer|null
     */
    protected function findFirstOrderItem(ArrayObject $returnItemTransfers): ?ItemTransfer
    {
        /** @var \Generated\Shared\Transfer\ReturnItemTransfer $firstReturnItem */
        $firstReturnItem = $returnItemTransfers->offsetGet(0);

        $orderItemFilterTransfer = (new OrderItemFilterTransfer())
            ->addSalesOrderItemId($firstReturnItem->getOrderItemOrFail()->getIdSalesOrderItemOrFail());

        /** @var \Generated\Shared\Transfer\ItemTransfer[] $orderItemTransfers */
        $orderItemTransfers = $this->salesFacade
            ->getOrderItems($orderItemFilterTransfer)
            ->getItems();

        foreach ($orderItemTransfers as $orderItemTransfer) {
            $idSalesOrderItem = $orderItemTransfer->getIdSalesOrderItemOrFail();
            $firstIdSalesOrderItem = $firstReturnItem
                ->getOrderItemOrFail()
                ->getIdSalesOrderItemOrFail();

            if ($idSalesOrderItem === $firstIdSalesOrderItem) {
                return $orderItemTransfer;
            }
        }

        return null;
    }
}
