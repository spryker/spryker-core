<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Customer\Model;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Client\Customer\Zed\CustomerStubInterface;

class CustomerAddress implements CustomerAddressInterface
{
    /**
     * @var \Spryker\Client\Customer\Zed\CustomerStubInterface
     */
    protected $customerStub;

    /**
     * @var \Spryker\Client\Customer\Dependency\Plugin\DefaultAddressChangePluginInterface[]
     */
    protected $defaultAddressChangePlugins;

    /**
     * CustomerAddress constructor.
     *
     * @param \Spryker\Client\Customer\Zed\CustomerStubInterface $customerStub
     * @param \Spryker\Client\Customer\Dependency\Plugin\DefaultAddressChangePluginInterface[] $defaultAddressChangePlugins
     */
    public function __construct(CustomerStubInterface $customerStub, array $defaultAddressChangePlugins)
    {
        $this->customerStub = $customerStub;
        $this->defaultAddressChangePlugins = $defaultAddressChangePlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function updateAddressAndCustomerDefaultAddresses(AddressTransfer $addressTransfer)
    {
        $customerTransfer = $this->customerStub->updateAddressAndCustomerDefaultAddresses($addressTransfer);

        $this->callDefaultAddressChangePlugins($customerTransfer);

        return $customerTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function createAddressAndUpdateCustomerDefaultAddresses(AddressTransfer $addressTransfer)
    {
        $customerTransfer = $this->customerStub->createAddressAndUpdateCustomerDefaultAddresses($addressTransfer);

        $this->callDefaultAddressChangePlugins($customerTransfer);

        return $customerTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    protected function callDefaultAddressChangePlugins(CustomerTransfer $customerTransfer)
    {
        foreach ($this->defaultAddressChangePlugins as $defaultAddressChangePlugin) {
            $defaultAddressChangePlugin->process($customerTransfer);
        }
    }
}
