<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Customer\Plugin\Customer;

use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Client\CustomerExtension\Dependency\Plugin\DefaultAddressChangePluginInterface;
use Spryker\Client\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Client\Customer\CustomerClientInterface getClient()
 */
class CustomerAddressDefaultAddressChangePlugin extends AbstractPlugin implements DefaultAddressChangePluginInterface
{
    /**
     * {@inheritDoc}
     * - Expects `CustomerTransfer.addresses` to be provided.
     * - Gets customer from the session.
     * - Updates `addresses` field for the customer.
     * - Sets updated customer to session.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    public function process(CustomerTransfer $customerTransfer): void
    {
        $this->getClient()->updateCustomerAddresses($customerTransfer);
    }
}
