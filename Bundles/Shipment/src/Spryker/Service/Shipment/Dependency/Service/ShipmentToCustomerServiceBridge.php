<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Shipment\Dependency\Service;

use Generated\Shared\Transfer\AddressTransfer;

class ShipmentToCustomerServiceBridge implements ShipmentToCustomerServiceInterface
{
    /**
     * @var \Spryker\Service\Customer\CustomerServiceInterface
     */
    protected $customerService;

    /**
     * @param \Spryker\Service\Customer\CustomerServiceInterface $customerService
     */
    public function __construct($customerService)
    {
        $this->customerService = $customerService;
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return string
     */
    public function getUniqueAddressKey(AddressTransfer $addressTransfer): string
    {
        return $this->customerService->getUniqueAddressKey($addressTransfer);
    }
}
