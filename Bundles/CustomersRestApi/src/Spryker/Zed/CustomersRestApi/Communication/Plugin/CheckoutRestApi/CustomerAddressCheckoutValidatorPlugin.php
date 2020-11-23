<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomersRestApi\Communication\Plugin\CheckoutRestApi;

use Generated\Shared\Transfer\CheckoutDataTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Spryker\Zed\CheckoutRestApiExtension\Dependency\Plugin\CheckoutValidatorPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CustomersRestApi\Business\CustomersRestApiFacadeInterface getFacade()
 * @method \Spryker\Zed\CustomersRestApi\CustomersRestApiConfig getConfig()
 */
class CustomerAddressCheckoutValidatorPlugin extends AbstractPlugin implements CheckoutValidatorPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expects `checkoutDataTransfer.quote.customer.idCustomer` to be set.
     * - Collects shipping address uuids from `checkoutDataTransfer.shipments`.
     * - Returns CheckoutResponseTransfer with error when customer address uuid was provided for non-logged customer.
     * - Checks if customer addresses exists.
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
        return $this->getFacade()->validateCustomerAddressesInCheckoutData($checkoutDataTransfer);
    }
}
