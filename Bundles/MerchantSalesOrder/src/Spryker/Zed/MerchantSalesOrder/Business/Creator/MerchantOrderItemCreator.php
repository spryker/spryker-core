<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrder\Business\Creator;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MerchantOrderItemTransfer;
use Generated\Shared\Transfer\MerchantOrderTransfer;
use Spryker\Zed\MerchantSalesOrder\Persistence\MerchantSalesOrderEntityManagerInterface;

class MerchantOrderItemCreator implements MerchantOrderItemCreatorInterface
{
    /**
     * @var \Spryker\Zed\MerchantSalesOrder\Persistence\MerchantSalesOrderEntityManagerInterface
     */
    protected $merchantSalesOrderEntityManager;

    /**
     * @param \Spryker\Zed\MerchantSalesOrder\Persistence\MerchantSalesOrderEntityManagerInterface $merchantSalesOrderEntityManager
     */
    public function __construct(MerchantSalesOrderEntityManagerInterface $merchantSalesOrderEntityManager)
    {
        $this->merchantSalesOrderEntityManager = $merchantSalesOrderEntityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\MerchantOrderTransfer $merchantOrderTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantOrderItemTransfer
     */
    public function createMerchantOrderItem(
        ItemTransfer $itemTransfer,
        MerchantOrderTransfer $merchantOrderTransfer
    ): MerchantOrderItemTransfer {
        $merchantOrderItemTransfer = (new MerchantOrderItemTransfer())
            ->setIdMerchantOrder($merchantOrderTransfer->getIdMerchantOrder())
            ->setIdOrderItem($itemTransfer->getIdSalesOrderItem())
            ->setOrderItem($itemTransfer);

        return $this->merchantSalesOrderEntityManager->createMerchantOrderItem($merchantOrderItemTransfer);
    }
}
