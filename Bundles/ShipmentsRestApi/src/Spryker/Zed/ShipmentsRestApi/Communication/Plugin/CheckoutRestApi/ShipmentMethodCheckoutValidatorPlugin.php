<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentsRestApi\Communication\Plugin\CheckoutRestApi;

use Generated\Shared\Transfer\CheckoutDataTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Spryker\Zed\CheckoutRestApiExtension\Dependency\Plugin\CheckoutValidatorPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @deprecated Will be removed with next major release. Use item level shipment instead.
 *
 * @method \Spryker\Zed\ShipmentsRestApi\Business\ShipmentsRestApiFacadeInterface getFacade()
 * @method \Spryker\Zed\ShipmentsRestApi\ShipmentsRestApiConfig getConfig()
 */
class ShipmentMethodCheckoutValidatorPlugin extends AbstractPlugin implements CheckoutValidatorPluginInterface
{
    /**
     * {@inheritDoc}
     * - Verifies if shipment method is valid.
     * - Returns CheckoutResponseTransfer with error if any check was failed.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CheckoutDataTransfer $checkoutDataTransfer
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    public function validateCheckout(CheckoutDataTransfer $checkoutDataTransfer): CheckoutResponseTransfer
    {
        return $this->getFacade()->validateShipmentMethodCheckoutData($checkoutDataTransfer);
    }
}
