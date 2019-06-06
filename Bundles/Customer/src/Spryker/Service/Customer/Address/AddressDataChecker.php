<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Customer\Address;

use Generated\Shared\Transfer\AddressTransfer;

class AddressDataChecker implements AddressDataCheckerInterface
{
    /**
     * @param \Generated\Shared\Transfer\AddressTransfer|null $addressTransfer
     *
     * @return bool
     */
    public function isAddressEmpty(?AddressTransfer $addressTransfer = null): bool
    {
        if ($addressTransfer === null) {
            return true;
        }

        $firstName = trim($addressTransfer->getFirstName());
        $lastName = trim($addressTransfer->getLastName());

        return $addressTransfer->getIdCustomerAddress() === null
            && $addressTransfer->getIdCompanyUnitAddress() === null
            && ($firstName === null || $firstName === '')
            && ($lastName === null || $lastName === '');
    }
}
