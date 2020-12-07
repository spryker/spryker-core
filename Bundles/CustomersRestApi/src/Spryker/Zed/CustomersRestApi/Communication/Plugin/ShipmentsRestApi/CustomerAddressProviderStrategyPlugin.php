<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomersRestApi\Communication\Plugin\ShipmentsRestApi;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestAddressTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ShipmentsRestApiExtension\Dependency\Plugin\AddressProviderStrategyPluginInterface;

/**
 * @method \Spryker\Zed\CustomersRestApi\CustomersRestApiConfig getConfig()
 * @method \Spryker\Zed\CustomersRestApi\Business\CustomersRestApiFacadeInterface getFacade()
 */
class CustomerAddressProviderStrategyPlugin extends AbstractPlugin implements AddressProviderStrategyPluginInterface
{
    /**
     * {@inheritDoc}
     * - Checks if `RestAddressTransfer.id` is provided.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\RestAddressTransfer $restAddressTransfer
     *
     * @return bool
     */
    public function isApplicable(RestAddressTransfer $restAddressTransfer): bool
    {
        return $restAddressTransfer->getId() !== null;
    }

    /**
     * {@inheritDoc}
     * - Finds customer address based on the UUID provided in `RestAddressTransfer.id`.
     * - Returns customer address if it was found.
     * - If customer address was found then address information provided in `RestAddressTransfer` will be skipped.
     * - Returns `AddressTransfer` filled with attributes from `RestAddressTransfer` otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\RestAddressTransfer $restAddressTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    public function provideAddress(
        RestAddressTransfer $restAddressTransfer,
        QuoteTransfer $quoteTransfer
    ): AddressTransfer {
        return $this->getFacade()->getCustomerAddress($restAddressTransfer, $quoteTransfer);
    }
}
