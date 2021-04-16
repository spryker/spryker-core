<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesReturnGui\Communication\Reader;

use ArrayObject;
use Generated\Shared\Transfer\MerchantOrderCriteriaTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\MerchantSalesReturnGui\Dependency\Facade\MerchantSalesReturnGuiToMerchantSalesOrderFacadeInterface;

class MerchantSalesReturnReader implements MerchantSalesReturnReaderInterface
{
    /**
     * @var \Spryker\Zed\MerchantSalesReturnGui\Dependency\Facade\MerchantSalesReturnGuiToMerchantSalesOrderFacadeInterface
     */
    protected $merchantSalesOrderFacade;

    /**
     * @param \Spryker\Zed\MerchantSalesReturnGui\Dependency\Facade\MerchantSalesReturnGuiToMerchantSalesOrderFacadeInterface $merchantSalesOrderFacade
     */
    public function __construct(MerchantSalesReturnGuiToMerchantSalesOrderFacadeInterface $merchantSalesOrderFacade)
    {
        $this->merchantSalesOrderFacade = $merchantSalesOrderFacade;
    }

    /**
     * @phpstan-return \ArrayObject<int,\Generated\Shared\Transfer\MerchantOrderTransfer>
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\MerchantOrderTransfer[]
     */
    public function getMerchantOrders(OrderTransfer $orderTransfer): ArrayObject
    {
        $merchantOrderCriteriaTransfer = (new MerchantOrderCriteriaTransfer())
            ->setIdOrder($orderTransfer->getIdSalesOrder())
            ->setWithMerchant(true);

        $merchantOrderCollection = $this->merchantSalesOrderFacade
            ->getMerchantOrderCollection($merchantOrderCriteriaTransfer);

        return $merchantOrderCollection->getMerchantOrders();
    }
}
