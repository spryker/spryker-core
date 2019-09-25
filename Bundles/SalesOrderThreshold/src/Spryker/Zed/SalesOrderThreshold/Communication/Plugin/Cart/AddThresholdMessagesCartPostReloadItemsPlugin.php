<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderThreshold\Communication\Plugin\Cart;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\CartExtension\Dependency\Plugin\PostReloadItemsPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\SalesOrderThreshold\Business\SalesOrderThresholdFacadeInterface getFacade()
 * @method \Spryker\Zed\SalesOrderThreshold\SalesOrderThresholdConfig getConfig()
 */
class AddThresholdMessagesCartPostReloadItemsPlugin extends AbstractPlugin implements PostReloadItemsPluginInterface
{
    /**
     * {@inheritDoc}
     * - Adds the soft threshold messages to the messenger for the applicable thresholds.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function postReloadItems(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        return $this->getFacade()->addSalesOrderThresholdMessages($quoteTransfer);
    }
}
