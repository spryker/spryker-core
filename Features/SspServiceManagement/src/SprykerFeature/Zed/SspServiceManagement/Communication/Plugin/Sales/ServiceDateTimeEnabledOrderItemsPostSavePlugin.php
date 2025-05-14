<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspServiceManagement\Communication\Plugin\Sales;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SalesExtension\Dependency\Plugin\OrderItemsPostSavePluginInterface;

/**
 * @method \SprykerFeature\Zed\SspServiceManagement\SspServiceManagementConfig getConfig()
 * @method \SprykerFeature\Zed\SspServiceManagement\Business\SspServiceManagementFacadeInterface getFacade()
 * @method \SprykerFeature\Zed\SspServiceManagement\Communication\SspServiceManagementCommunicationFactory getFactory()
 */
class ServiceDateTimeEnabledOrderItemsPostSavePlugin extends AbstractPlugin implements OrderItemsPostSavePluginInterface
{
    /**
     * {@inheritDoc}
     * - Persists `isServiceDateTimeEnabled` information from `ItemTransfer.isServiceDateTimeEnabled`.
     * - Creates relation between sales order item and isServiceDateTimeEnabled flag in persistence.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\SaveOrderTransfer
     */
    public function execute(
        SaveOrderTransfer $saveOrderTransfer,
        QuoteTransfer $quoteTransfer
    ): SaveOrderTransfer {
        $this->saveServiceDateTimeEnabledForOrderItems($quoteTransfer);

        return $saveOrderTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function saveServiceDateTimeEnabledForOrderItems(QuoteTransfer $quoteTransfer): void
    {
        $this->getFactory()
            ->createServiceDateTimeEnabledSaver()
            ->saveServiceDateTimeEnabledForOrderItems($quoteTransfer);
    }
}
