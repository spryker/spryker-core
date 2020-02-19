<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantOms\Communication\Plugin\MerchantSalesOrder;

use Generated\Shared\Transfer\MerchantOrderResponseTransfer;
use Generated\Shared\Transfer\MerchantOrderTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MerchantSalesOrderExtension\Dependency\Plugin\MerchantOrderPostCreatePluginInterface;

/**
 * @method \Spryker\Zed\MerchantOms\Business\MerchantOmsFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantOms\MerchantOmsConfig getConfig()
 */
class MerchantOmsMerchantOrderPostCreatePlugin extends AbstractPlugin implements MerchantOrderPostCreatePluginInterface
{
    /**
     * {@inheritDoc}
     * - Dispatches initial oms event for each merchant order item.
     * - Returns MerchantOrderResponse::isSuccessful = true if at least one transition has been completed.
     * - Returns MerchantOrderResponse::isSuccessful = false otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantOrderTransfer $merchantOrderTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantOrderResponseTransfer
     */
    public function postCreate(MerchantOrderTransfer $merchantOrderTransfer): MerchantOrderResponseTransfer
    {
        return $this->getFacade()->dispatchNewMerchantOrderEvent($merchantOrderTransfer);
    }
}
