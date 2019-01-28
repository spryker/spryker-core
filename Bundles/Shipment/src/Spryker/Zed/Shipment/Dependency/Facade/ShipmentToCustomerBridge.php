<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Dependency\Facade;

use Generated\Shared\Transfer\AddressTransfer;

class ShipmentToCustomerBridge implements ShipmentToCustomerInterface
{
    /**
     * @var \Spryker\Zed\Customer\Business\CustomerFacadeInterface
     */
    protected $customerFacade;

    /**
     * @param \Spryker\Zed\Customer\Business\CustomerFacadeInterface $currencyFacade
     */
    public function __construct($customerFacade)
    {
        $this->customerFacade = $customerFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    public function findCustomerAddressByAddressData(AddressTransfer $addressTransfer): ?AddressTransfer
    {
        return $this->customerFacade->findCustomerAddressByAddressData($addressTransfer);
    }
}
