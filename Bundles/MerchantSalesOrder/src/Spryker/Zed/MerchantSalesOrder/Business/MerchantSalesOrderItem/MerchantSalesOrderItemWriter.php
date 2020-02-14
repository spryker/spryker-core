<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrder\Business\MerchantSalesOrderItem;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MerchantOrderItemTransfer;
use Generated\Shared\Transfer\MerchantOrderTransfer;
use Spryker\Zed\MerchantSalesOrder\Persistence\MerchantSalesOrderEntityManagerInterface;

class MerchantSalesOrderItemWriter implements MerchantSalesOrderItemWriterInterface
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
    public function createMerchantSalesOrderItem(
        ItemTransfer $itemTransfer,
        MerchantOrderTransfer $merchantOrderTransfer
    ): MerchantOrderItemTransfer {
        $merchantOrderItemTransfer = $this->getMerchantOrderItemTransfer($itemTransfer, $merchantOrderTransfer);

        return $this->merchantSalesOrderEntityManager->createMerchantSalesOrderItem($merchantOrderItemTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\MerchantOrderTransfer $merchantOrderTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantOrderItemTransfer
     */
    protected function getMerchantOrderItemTransfer(
        ItemTransfer $itemTransfer,
        MerchantOrderTransfer $merchantOrderTransfer
    ): MerchantOrderItemTransfer {
        return (new MerchantOrderItemTransfer())
            ->setIdMerchantSalesOrder($merchantOrderTransfer->getIdMerchantSalesOrder())
            ->setIdSalesOrderItem($itemTransfer->getIdSalesOrderItem());
    }
}
