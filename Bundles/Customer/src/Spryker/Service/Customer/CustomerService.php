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
     * {@inheritdoc}
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
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AddressTransfer|null $addressTransfer
     *
     * @return bool
     */
    public function isAddressEmpty(?AddressTransfer $addressTransfer = null): bool
    {
        return $this->getFactory()->createAddressDataChecker()->isAddressEmpty($addressTransfer);
    }
}
