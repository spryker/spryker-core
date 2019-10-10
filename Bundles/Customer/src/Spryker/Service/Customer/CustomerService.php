<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Customer;

use Generated\Shared\Transfer\AddressTransfer;
use Spryker\Service\Kernel\AbstractService;

/**
 * @method \Spryker\Service\Customer\CustomerServiceFactory getFactory()
 */
class CustomerService extends AbstractService implements CustomerServiceInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return string
     */
    public function getUniqueAddressKey(AddressTransfer $addressTransfer): string
    {
        return $this->getFactory()->createCustomerAddressKeyGenerator()->getUniqueAddressKey($addressTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    public function sanitizeUniqueAddressValues(AddressTransfer $addressTransfer): AddressTransfer
    {
        return $this->getFactory()->createCustomerAddressSanitizer()->sanitizeUniqueAddressValues($addressTransfer);
    }
}
