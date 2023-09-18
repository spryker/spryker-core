<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfile\Communication\Plugin\TaxApp;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\TaxAppExtension\Dependency\Plugin\OrderTaxAppExpanderPluginInterface;

/**
 * @method \Spryker\Zed\MerchantProfile\Business\MerchantProfileFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantProfile\MerchantProfileConfig getConfig()
 */
class MerchantProfileAddressOrderTaxAppExpanderPlugin extends AbstractPlugin implements OrderTaxAppExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expand OrderTransfer.Items with Address from MerchantProfile.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function expand(OrderTransfer $orderTransfer): OrderTransfer
    {
        /** @var \Generated\Shared\Transfer\OrderTransfer $orderTransfer */
        $orderTransfer = $this->getFacade()->expandOrderItemsWithMerchantProfileAddress($orderTransfer);

        return $orderTransfer;
    }
}
