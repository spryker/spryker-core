<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Customer\Address;

use Generated\Shared\Transfer\AddressTransfer;
use Spryker\Service\Customer\CustomerConfig;
use Spryker\Service\Customer\Dependency\Service\CustomerToUtilEncodingServiceInterface;

class CustomerAddressKeyGenerator implements CustomerAddressKeyGeneratorInterface
{
    /**
     * @var \Spryker\Service\Customer\CustomerConfig
     */
    protected $customerConfig;

    /**
     * @var \Spryker\Service\Customer\Dependency\Service\CustomerToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Service\Customer\CustomerConfig $customerConfig
     * @param \Spryker\Service\Customer\Dependency\Service\CustomerToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(
        CustomerConfig $customerConfig,
        CustomerToUtilEncodingServiceInterface $utilEncodingService
    ) {
        $this->customerConfig = $customerConfig;
        $this->utilEncodingService = $utilEncodingService;
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

        return md5($this->utilEncodingService->encodeJson($addressData));
    }
}
