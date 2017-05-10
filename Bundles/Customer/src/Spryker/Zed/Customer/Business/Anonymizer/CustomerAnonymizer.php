<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer\Business\Anonymizer;

use DateTime;
use Generated\Shared\Transfer\AddressesTransfer;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CustomerTransfer;

class CustomerAnonymizer implements CustomerAnonymizerInterface
{

    /**
     * @var \Spryker\Zed\Customer\Dependency\Plugin\CustomerAnonymizerPluginInterface[]
     */
    protected $plugins;

    /**
     * CustomerAnonymizer constructor.
     *
     * @param \Spryker\Zed\Customer\Dependency\Plugin\CustomerAnonymizerPluginInterface[] $customerAnonymizerPlugins
     */
    public function __construct(array $customerAnonymizerPlugins)
    {
        $this->plugins = $customerAnonymizerPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function process(CustomerTransfer $customerTransfer)
    {
        foreach ($this->plugins as $plugin) {
            $plugin->process($customerTransfer);
        }

        $customerTransfer = $this->processCustomer($customerTransfer);

        $addressesTransfer = $customerTransfer->getAddresses();
        $addressesTransfer = $this->processCustomerAddresses($addressesTransfer);
        $customerTransfer->setAddresses($addressesTransfer);

        return $customerTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function processCustomer(CustomerTransfer $customerTransfer)
    {
        $customerTransfer->setAnonymizedAt(new DateTime());
        $customerTransfer->setEmail(md5($customerTransfer->getEmail()));

        $customerTransfer->setFirstName(null);
        $customerTransfer->setLastName(null);
        $customerTransfer->setSalutation(null);
        $customerTransfer->setGender(null);
        $customerTransfer->setDateOfBirth(null);

        return $customerTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\AddressesTransfer $addressesTransfer
     *
     * @return \Generated\Shared\Transfer\AddressesTransfer
     */
    protected function processCustomerAddresses(AddressesTransfer $addressesTransfer)
    {
        foreach ($addressesTransfer->getAddresses() as &$addressTransfer) {
            $addressTransfer = $this->processCustomerAddress($addressTransfer);
        }

        return $addressesTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    protected function processCustomerAddress(AddressTransfer $addressTransfer)
    {
        $addressTransfer->setAnonymizedAt(new DateTime());
        $addressTransfer->setIsDeleted(true);

        $addressTransfer->setFirstName('');
        $addressTransfer->setLastName('');

        $addressTransfer->setSalutation(null);
        $addressTransfer->setAddress1(null);
        $addressTransfer->setAddress2(null);
        $addressTransfer->setAddress3(null);
        $addressTransfer->setCompany(null);
        $addressTransfer->setCity(null);
        $addressTransfer->setZipCode(null);
        $addressTransfer->setPhone(null);

        return $addressTransfer;
    }

}
