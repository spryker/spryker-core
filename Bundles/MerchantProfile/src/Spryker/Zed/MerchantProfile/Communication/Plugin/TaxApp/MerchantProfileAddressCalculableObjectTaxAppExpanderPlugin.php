<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfile\Communication\Plugin\TaxApp;

use Generated\Shared\Transfer\CalculableObjectTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\TaxAppExtension\Dependency\Plugin\CalculableObjectTaxAppExpanderPluginInterface;

/**
 * @method \Spryker\Zed\MerchantProfile\Business\MerchantProfileFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantProfile\MerchantProfileConfig getConfig()
 */
class MerchantProfileAddressCalculableObjectTaxAppExpanderPlugin extends AbstractPlugin implements CalculableObjectTaxAppExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expand CalculableObjectTransfer.Items with Address from MerchantProfile.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\CalculableObjectTransfer
     */
    public function expand(CalculableObjectTransfer $quoteTransfer): CalculableObjectTransfer
    {
        /** @var \Generated\Shared\Transfer\CalculableObjectTransfer $quoteTransfer */
        $quoteTransfer = $this->getFacade()->expandQuoteItemsWithMerchantProfileAddress($quoteTransfer);

        return $quoteTransfer;
    }
}
