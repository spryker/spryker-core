<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesReturn\Business\Creator;

use ArrayObject;
use Generated\Shared\Transfer\MerchantOrderCriteriaTransfer;
use Generated\Shared\Transfer\MerchantOrderTransfer;
use Generated\Shared\Transfer\ReturnTransfer;
use Spryker\Zed\MerchantSalesReturn\Dependency\Facade\MerchantSalesReturnToMerchantSalesOrderFacadeInterface;

class MerchantReturnCreator implements MerchantReturnCreatorInterface
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

        $merchantOrderTransfer = $this->findMerchantOrder($returnItemTransfers);

        if ($merchantOrderTransfer === null) {
            return $returnTransfer;
        }

        return $returnTransfer->setMerchantSalesOrderReference(
            $merchantOrderTransfer->getMerchantOrderReference()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ReturnItemTransfer[]|\ArrayObject $returnItemTransfers
     *
     * @return \Generated\Shared\Transfer\MerchantOrderTransfer|null
     */
    protected function findMerchantOrder(ArrayObject $returnItemTransfers): ?MerchantOrderTransfer
    {
        /** @var \Generated\Shared\Transfer\ReturnItemTransfer $firstReturnItem */
        $firstReturnItem = $returnItemTransfers->offsetGet(0);

        $merchantOrderCriteriaTransfer = (new MerchantOrderCriteriaTransfer())
            ->setOrderItemUuid($firstReturnItem->getOrderItemOrFail()->getUuidOrFail());

        return $this->merchantSalesOrderFacade
            ->findMerchantOrder($merchantOrderCriteriaTransfer);
    }
}
