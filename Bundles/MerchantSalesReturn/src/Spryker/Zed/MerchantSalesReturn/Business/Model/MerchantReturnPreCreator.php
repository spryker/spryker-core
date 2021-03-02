<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesReturn\Business\Model;

use ArrayObject;
use Generated\Shared\Transfer\MerchantOrderCriteriaTransfer;
use Generated\Shared\Transfer\MerchantOrderTransfer;
use Generated\Shared\Transfer\ReturnTransfer;
use Spryker\Zed\MerchantSalesReturn\Dependency\Facade\MerchantSalesReturnToMerchantSalesOrderFacadeInterface;

class MerchantReturnPreCreator implements MerchantReturnPreCreatorInterface
{
    /**
     * @var \Spryker\Zed\MerchantSalesReturn\Dependency\Facade\MerchantSalesReturnToMerchantSalesOrderFacadeInterface
     */
    protected $merchantSalesOrderFacade;

    /**
     * @param \Spryker\Zed\MerchantSalesReturn\Dependency\Facade\MerchantSalesReturnToMerchantSalesOrderFacadeInterface $merchantSalesOrderFacade
     */
    public function __construct(MerchantSalesReturnToMerchantSalesOrderFacadeInterface $merchantSalesOrderFacade)
    {
        $this->merchantSalesOrderFacade = $merchantSalesOrderFacade;
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

        $merchantOrderTransfer = $this->getMerchantOrder($returnItemTransfers);

        if ($merchantOrderTransfer !== null) {
            $merchantOrderReference = $merchantOrderTransfer->getMerchantOrderReference();

            $returnTransfer->setMerchantSalesOrderReference($merchantOrderReference);
        }

        return $returnTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ReturnItemTransfer[]|\ArrayObject $returnItemTransfers
     *
     * @return \Generated\Shared\Transfer\MerchantOrderTransfer|null
     */
    protected function getMerchantOrder(ArrayObject $returnItemTransfers): ?MerchantOrderTransfer
    {
        /** @var \Generated\Shared\Transfer\ReturnItemTransfer $firstReturnItem */
        $firstReturnItem = $returnItemTransfers->offsetGet(0);

        $merchantOrderItemCriteriaTransfer = (new MerchantOrderCriteriaTransfer())
            ->setIdOrderItem($firstReturnItem->getOrderItemOrFail()->getIdSalesOrderItemOrFail());

        return $this->merchantSalesOrderFacade
            ->findMerchantOrder($merchantOrderItemCriteriaTransfer);
    }
}
