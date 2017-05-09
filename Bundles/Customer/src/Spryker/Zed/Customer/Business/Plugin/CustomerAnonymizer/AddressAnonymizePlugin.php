<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer\Business\Plugin\CustomerAnonymizer;

use DateTime;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Zed\Customer\Dependency\Plugin\CustomerAnonymizerPluginInterface;

class AddressAnonymizePlugin implements CustomerAnonymizerPluginInterface
{

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function processCustomer(CustomerTransfer $customerTransfer)
    {
        $addressesTransfer = $customerTransfer->getAddresses();

        foreach ($addressesTransfer as &$addressTransfer) {
            $addressTransfer = $this->processCustomerAddress($addressTransfer);
        }

        return $customerTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $customerAddressTransfer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    protected function processCustomerAddress(AddressTransfer $customerAddressTransfer)
    {
        $customerAddressTransfer->setAnonymizedAt(new DateTime());

        $customerAddressTransfer->setFirstName('');
        $customerAddressTransfer->setLastName('');
        $customerAddressTransfer->setSalutation(null);
        $customerAddressTransfer->setAddress1(null);
        $customerAddressTransfer->setAddress2(null);
        $customerAddressTransfer->setAddress3(null);
        $customerAddressTransfer->setCompany(null);
        $customerAddressTransfer->setCity(null);
        $customerAddressTransfer->setZipCode(null);
        $customerAddressTransfer->setPhone(null);

        return $customerAddressTransfer;
    }

}
