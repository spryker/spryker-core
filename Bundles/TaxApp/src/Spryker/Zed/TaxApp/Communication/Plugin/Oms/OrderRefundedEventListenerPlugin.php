<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxApp\Communication\Plugin\Oms;

use Generated\Shared\Transfer\OmsEventTriggeredTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\OmsExtension\Dependency\Plugin\OmsEventTriggeredListenerPluginInterface;

/**
 * @method \Spryker\Zed\TaxApp\Business\TaxAppFacadeInterface getFacade()
 * @method \Spryker\Zed\TaxApp\Communication\TaxAppCommunicationFactory getFactory()
 * @method \Spryker\Zed\TaxApp\TaxAppConfig getConfig()
 */
class OrderRefundedEventListenerPlugin extends AbstractPlugin implements OmsEventTriggeredListenerPluginInterface
{
    /**
     * {@inheritDoc}
     * - Executed after OMS event with `refund` ID has been triggered.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OmsEventTriggeredTransfer $omsEventTriggeredTransfer
     *
     * @return bool
     */
    public function isApplicable(OmsEventTriggeredTransfer $omsEventTriggeredTransfer): bool
    {
        return $omsEventTriggeredTransfer->getIdEvent() === 'refund';
    }

    /**
     * {@inheritDoc}
     * - Triggers order refund processing by sending a request to Tax App.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OmsEventTriggeredTransfer $omsEventTriggeredTransfer
     *
     * @return void
     */
    public function onEventTriggered(OmsEventTriggeredTransfer $omsEventTriggeredTransfer): void
    {
        $this->getFacade()->processOrderRefund($omsEventTriggeredTransfer->getOrderItemIds(), $omsEventTriggeredTransfer->getIdSalesOrderOrFail());
    }
}
