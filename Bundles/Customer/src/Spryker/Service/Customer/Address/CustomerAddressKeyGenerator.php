<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Customer\Address;

use Generated\Shared\Transfer\AddressTransfer;
use Spryker\Service\Customer\CustomerConfig;

class CustomerAddressKeyGenerator implements CustomerAddressKeyGeneratorInterface
{
    /**
     * @var \Spryker\Zed\Customer\CustomerConfig
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
     * @return string
     */
    public function getUniqueAddressKey(AddressTransfer $addressTransfer): string
    {
        $addressData = $addressTransfer->toArray(true, true);

        foreach ($this->customerConfig->getAddressKeyGenerationExcludedFields() as $addressExcludedField) {
            unset($addressData[$addressExcludedField]);
        }

        /**
         * @todo Use UtilEncode.
         */
        return md5(json_encode($addressData));
    }
}
