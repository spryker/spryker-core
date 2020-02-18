<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrder\Business\MerchantSalesOrderItem;

use Generated\Shared\Transfer\MerchantOrderItemTransfer;
use Spryker\Zed\MerchantSalesOrder\Persistence\MerchantSalesOrderEntityManagerInterface;

class MerchantOrderItemWriter implements MerchantOrderItemWriterInterface
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
     * @param \Generated\Shared\Transfer\MerchantOrderItemTransfer $merchantOrderItemTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantOrderItemTransfer
     */
    public function update(MerchantOrderItemTransfer $merchantOrderItemTransfer): MerchantOrderItemTransfer
    {
        $merchantOrderItemTransfer->requireIdMerchantOrderItem();

        return $this->merchantSalesOrderEntityManager->updateMerchantOrderItem($merchantOrderItemTransfer);
    }
}
