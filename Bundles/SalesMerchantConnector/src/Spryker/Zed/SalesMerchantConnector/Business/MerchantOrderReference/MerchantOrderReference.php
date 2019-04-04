<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesMerchantConnector\Business\MerchantOrderReference;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer;
use Spryker\Zed\SalesMerchantConnector\SalesMerchantConnectorConfig;

class MerchantOrderReference implements MerchantOrderReferenceInterface
{
    /**
     * @var \Spryker\Zed\SalesMerchantConnector\SalesMerchantConnectorConfig
     */
    protected $salesMerchantConnectorConfig;

    /**
     * @param \Spryker\Zed\SalesMerchantConnector\SalesMerchantConnectorConfig $salesMerchantConnectorConfig
     */
    public function __construct(SalesMerchantConnectorConfig $salesMerchantConnectorConfig)
    {
        $this->salesMerchantConnectorConfig = $salesMerchantConnectorConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer $salesOrderItemEntity
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer
     */
    public function addMerchantOrderReferenceToSalesOrderItem(
        SpySalesOrderItemEntityTransfer $salesOrderItemEntity,
        ItemTransfer $itemTransfer
    ): SpySalesOrderItemEntityTransfer {
        $merchantId = $itemTransfer->getFkMerchant();
        if (!$merchantId) {
            return $salesOrderItemEntity;
        }

        return $salesOrderItemEntity->setMerchantOrderReference(sprintf(
            $this->salesMerchantConnectorConfig->getMerchantOrderReferencePattern(),
            $salesOrderItemEntity->getFkSalesOrder(),
            $itemTransfer->getFkMerchant()
        ));
    }
}
