<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrderMerchantUserGui\Dependency\Facade;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CustomerTransfer;

class MerchantSalesOrderMerchantUserGuiToCustomerFacadeBridge implements MerchantSalesOrderMerchantUserGuiToCustomerFacadeInterface
{
    /**
     * @var \Spryker\Zed\Customer\Business\CustomerFacadeInterface
     */
    protected $customerFacade;

    /**
     * @param \Spryker\Zed\Customer\Business\CustomerFacadeInterface $customerFacade
     */
    public function __construct($customerFacade)
    {
        $this->customerFacade = $customerFacade;
    }

    /**
     * @param string $customerReference
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer|null
     */
    public function findByReference($customerReference)
    {
        return $this->customerFacade->findByReference($customerReference);
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer|null
     */
    public function findCustomerAddressByAddressData(AddressTransfer $addressTransfer): ?AddressTransfer
    {
        return $this->customerFacade->findCustomerAddressByAddressData($addressTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\AddressesTransfer
     */
    public function getAddresses(CustomerTransfer $customerTransfer)
    {
        return $this->customerFacade->getAddresses($customerTransfer);
    }

    /**
     * @api
     *
     * @phpstan-return array<mixed>
     *
     * @return array
     */
    public function getAllSalutations(): array
    {
        return $this->customerFacade->getAllSalutations();
    }
}
