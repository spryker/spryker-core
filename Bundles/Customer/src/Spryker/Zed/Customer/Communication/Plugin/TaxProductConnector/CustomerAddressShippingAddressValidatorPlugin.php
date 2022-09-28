<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer\Communication\Plugin\TaxProductConnector;

use Generated\Shared\Transfer\AddressTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\TaxProductConnectorExtension\Communication\Dependency\Plugin\ShippingAddressValidatorPluginInterface;

/**
 * @deprecated Exists for Backward Compatibility reasons only.
 *
 * @method \Spryker\Zed\Customer\CustomerConfig getConfig()
 * @method \Spryker\Zed\Customer\Business\CustomerFacadeInterface getFacade()
 * @method \Spryker\Zed\Customer\Communication\CustomerCommunicationFactory getFactory()
 * @method \Spryker\Zed\Customer\Persistence\CustomerQueryContainerInterface getQueryContainer()
 */
class CustomerAddressShippingAddressValidatorPlugin extends AbstractPlugin implements ShippingAddressValidatorPluginInterface
{
    /**
     * {@inheritDoc}
     * - Checks if `AddressTransfer.idCustomerAddress` or `AddressTransfer.firstName` and `AddressTransfer.lastName` are not empty.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return bool
     */
    public function isValid(AddressTransfer $addressTransfer): bool
    {
        return $addressTransfer->getIdCustomerAddress() !== null
            || (trim($addressTransfer->getFirstName()) && trim($addressTransfer->getLastName()));
    }
}
