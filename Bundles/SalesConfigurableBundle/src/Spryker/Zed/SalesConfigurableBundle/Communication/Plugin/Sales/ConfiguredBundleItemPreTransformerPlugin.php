<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesConfigurableBundle\Communication\Plugin\Sales;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SalesExtension\Dependency\Plugin\ItemPreTransformerPluginInterface;

/**
 * @method \Spryker\Zed\SalesConfigurableBundle\Business\SalesConfigurableBundleFacadeInterface getFacade()
 * @method \Spryker\Zed\SalesConfigurableBundle\SalesConfigurableBundleConfig getConfig()
 * @method \Spryker\Zed\SalesConfigurableBundle\Communication\SalesConfigurableBundleCommunicationFactory getFactory()
 */
class ConfiguredBundleItemPreTransformerPlugin extends AbstractPlugin implements ItemPreTransformerPluginInterface
{
    /**
     * {@inheritDoc}
     * - Splits items by configurable bundle quantity.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function execute(OrderTransfer $orderTransfer, QuoteTransfer $quoteTransfer): OrderTransfer
    {
        return $this->getFacade()->transformConfiguredBundleOrderItems($orderTransfer);
    }
}
