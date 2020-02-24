<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantOms\Communication\Plugin\MerchantSalesOrder;

use Generated\Shared\Transfer\MerchantOmsTriggerRequestTransfer;
use Generated\Shared\Transfer\MerchantOrderTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MerchantSalesOrderExtension\Dependency\Plugin\MerchantOrderPostCreatePluginInterface;

/**
 * @method \Spryker\Zed\MerchantOms\Business\MerchantOmsFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantOms\MerchantOmsConfig getConfig()
 */
class EventTriggerMerchantOrderPostCreatePlugin extends AbstractPlugin implements MerchantOrderPostCreatePluginInterface
{
    /**
     * {@inheritDoc}
     * - Requires MerchantOrder.merchantOrderItems transfer field to be set.
     * - Requires MerchantOrder.merchantReference transfer field to be set.
     * - Tries to find merchant state machine process by merchant reference, if not found takes process name from config.
     * - Dispatches an initial merchant OMS event of merchant state machine process for each merchant order item.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantOrderTransfer $merchantOrderTransfer
     *
     * @return void
     */
    public function postCreate(MerchantOrderTransfer $merchantOrderTransfer): void
    {
        $this->getFacade()->triggerForNewMerchantOrderItems(
            (new MerchantOmsTriggerRequestTransfer())
                ->setMerchantOrderItems($merchantOrderTransfer->getMerchantOrderItems())
                ->setMerchant((new MerchantTransfer())->setMerchantReference($merchantOrderTransfer->getMerchantReference()))
        );
    }
}
