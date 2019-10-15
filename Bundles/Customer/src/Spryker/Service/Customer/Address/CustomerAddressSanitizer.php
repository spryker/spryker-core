<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Customer\Address;

use Generated\Shared\Transfer\AddressTransfer;
use Spryker\Service\Customer\CustomerConfig;

class CustomerAddressSanitizer implements CustomerAddressSanitizerInterface
{
    /**
     * @var \Spryker\Service\Customer\CustomerConfig
     */
    protected $customerConfig;

    /**
     * @param \Spryker\Service\Customer\CustomerConfig $customerConfig
     */
    public function __construct(CustomerConfig $customerConfig)
    {
        $this->customerConfig = $customerConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    public function sanitizeUniqueAddressValues(AddressTransfer $addressTransfer): AddressTransfer
    {
        $addressFieldsList = $this->customerConfig->getAddressFieldsToSanitizeValuesList();
        if ($addressFieldsList === []) {
            return $addressTransfer;
        }

        $sanitizedAddressValues = array_fill_keys($addressFieldsList, null);

        return $addressTransfer->fromArray($sanitizedAddressValues, true);
    }
}
