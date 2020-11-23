<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartsRestApi\Communication\Plugin\CheckoutRestApi;

use Generated\Shared\Transfer\CheckoutDataTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Spryker\Zed\CheckoutRestApiExtension\Dependency\Plugin\CheckoutValidatorPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CartsRestApi\Business\CartsRestApiFacadeInterface getFacade()
 * @method \Spryker\Zed\CartsRestApi\CartsRestApiConfig getConfig()
 */
class ItemsCheckoutValidatorPlugin extends AbstractPlugin implements CheckoutValidatorPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expects `checkoutDataTransfer.shipments` to be provided.
     * - Requires `checkoutDataTransfer.quote` to be set.
     * - Validates if `CheckoutDataTransfer` provides shipment data per item level.
     * - Validates if `CheckoutDataTransfer` provides shipment data per bundle item level.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CheckoutDataTransfer $checkoutDataTransfer
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    public function validateCheckout(CheckoutDataTransfer $checkoutDataTransfer): CheckoutResponseTransfer
    {
        return $this->getFacade()->validateItemsInCheckoutData($checkoutDataTransfer);
    }
}
