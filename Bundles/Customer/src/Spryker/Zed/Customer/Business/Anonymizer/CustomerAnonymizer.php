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
use Spryker\Zed\Customer\Business\Customer\AddressInterface;
use Spryker\Zed\Customer\Business\Customer\CustomerInterface;
use Spryker\Zed\Customer\Persistence\CustomerQueryContainerInterface;

class CustomerAnonymizer implements CustomerAnonymizerInterface
{

    /**
     * @var \Spryker\Zed\Customer\Persistence\CustomerQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\Customer\Business\Customer\CustomerInterface
     */
    protected $customer;

    /**
     * @var \Spryker\Zed\Customer\Business\Customer\AddressInterface
     */
    protected $address;

    /**
     * @var \Spryker\Zed\Customer\Dependency\Plugin\CustomerAnonymizerPluginInterface[]
     */
    protected $plugins;

    /**
     * @param \Spryker\Zed\Customer\Persistence\CustomerQueryContainerInterface $customerQueryContainer
     * @param array $customerAnonymizerPlugins
     */
    public function __construct(CustomerQueryContainerInterface $customerQueryContainer, CustomerInterface $customer, AddressInterface $address, array $customerAnonymizerPlugins)
    {
        $this->queryContainer = $customerQueryContainer;
        $this->customer = $customer;
        $this->address = $address;
        $this->plugins = $customerAnonymizerPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    public function process(CustomerTransfer $customerTransfer)
    {
        foreach ($this->plugins as $plugin) {
            $plugin->process($customerTransfer);
        }

        $addressesTransfer = $customerTransfer->getAddresses();
        $addressesTransfer = $this->anonymizeCustomerAddresses($addressesTransfer);
        $customerTransfer->setAddresses($addressesTransfer);

        $customerTransfer = $this->anonymizeCustomer($customerTransfer);

        $this->updateCustomerAddresses($customerTransfer->getAddresses());
        $this->updateCustomer($customerTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function anonymizeCustomer(CustomerTransfer $customerTransfer)
    {
        $customerTransfer->setAnonymizedAt(new DateTime());
        $customerTransfer->setFirstName(null);
        $customerTransfer->setLastName(null);
        $customerTransfer->setSalutation(null);
        $customerTransfer->setGender(null);
        $customerTransfer->setDateOfBirth(null);
        $customerTransfer->setEmail($this->generateRandomEmail());

        return $customerTransfer;
    }

    /**
     * @return string
     */
    protected function generateRandomEmail()
    {
        do {
            $randomEmail = md5(mt_rand());
        } while ($this->queryContainer->queryCustomerByEmail($randomEmail)->exists());

        return $randomEmail;
    }

    /**
     * @param \Generated\Shared\Transfer\AddressesTransfer $addressesTransfer
     *
     * @return \Generated\Shared\Transfer\AddressesTransfer
     */
    protected function anonymizeCustomerAddresses(AddressesTransfer $addressesTransfer)
    {
        foreach ($addressesTransfer->getAddresses() as &$addressTransfer) {
            $addressTransfer = $this->anonymizeCustomerAddress($addressTransfer);
        }

        return $addressesTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    protected function anonymizeCustomerAddress(AddressTransfer $addressTransfer)
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

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    protected function updateCustomer(CustomerTransfer $customerTransfer)
    {
        $this->customer->update($customerTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\AddressesTransfer $addressesTransfer
     *
     * @return void
     */
    protected function updateCustomerAddresses(AddressesTransfer $addressesTransfer)
    {
        foreach ($addressesTransfer->getAddresses() as &$addressTransfer) {
            $this->address->updateAddress($addressTransfer);
        }
    }

}
