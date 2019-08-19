<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesConfigurableBundle\Communication\Plugin\Sales;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SalesExtension\Dependency\Plugin\OrderPostSavePluginInterface;

/**
 * @method \Spryker\Zed\SalesConfigurableBundle\Business\SalesConfigurableBundleFacadeInterface getFacade()
 * @method \Spryker\Zed\SalesConfigurableBundle\SalesConfigurableBundleConfig getConfig()
 * @method \Spryker\Zed\SalesConfigurableBundle\Communication\SalesConfigurableBundleCommunicationFactory getFactory()
 */
class ConfiguredBundlesOrderPostSavePlugin extends AbstractPlugin implements OrderPostSavePluginInterface
{
    /**
     * {@inheritdoc}
     * - Persists configured bundles from ItemTransfer in Quote to sales_order configured bundle tables.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\SaveOrderTransfer
     */
    public function execute(SaveOrderTransfer $saveOrderTransfer, QuoteTransfer $quoteTransfer): SaveOrderTransfer
    {
        $this->getFacade()->saveSalesOrderConfiguredBundlesFromQuote($quoteTransfer);

        return $saveOrderTransfer;
    }
}
