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
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

class CustomerAnonymizer implements CustomerAnonymizerInterface
{
    use DatabaseTransactionHandlerTrait;

    /**
     * @var \Spryker\Zed\Customer\Persistence\CustomerQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\Customer\Business\Customer\CustomerInterface
     */
    protected $customerModel;

    /**
     * @var \Spryker\Zed\Customer\Business\Customer\AddressInterface
     */
    protected $addressModel;

    /**
     * @var \Spryker\Zed\Customer\Dependency\Plugin\CustomerAnonymizerPluginInterface[]
     */
    protected $plugins;

    /**
     * @param \Spryker\Zed\Customer\Persistence\CustomerQueryContainerInterface $customerQueryContainer
     * @param \Spryker\Zed\Customer\Business\Customer\CustomerInterface $customerModel
     * @param \Spryker\Zed\Customer\Business\Customer\AddressInterface $addressModel
     * @param array $customerAnonymizerPlugins
     */
    public function __construct(
        CustomerQueryContainerInterface $customerQueryContainer,
        CustomerInterface $customerModel,
        AddressInterface $addressModel,
        array $customerAnonymizerPlugins
    ) {
        $this->queryContainer = $customerQueryContainer;
        $this->customerModel = $customerModel;
        $this->addressModel = $addressModel;
        $this->plugins = $customerAnonymizerPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    public function process(CustomerTransfer $customerTransfer)
    {
        $customerTransfer->requireIdCustomer();
        $customerTransfer = $this->getCustomer($customerTransfer);

        $this->handleDatabaseTransaction(function () use ($customerTransfer) {
            $this->processTransaction($customerTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    protected function processTransaction(CustomerTransfer $customerTransfer)
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
    protected function getCustomer(CustomerTransfer $customerTransfer)
    {
        return $this->customerModel->get($customerTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function anonymizeCustomer(CustomerTransfer $customerTransfer)
    {
        $customerTransfer
            ->setAnonymizedAt((new DateTime())->format("Y-m-d H:i:s.u"))
            ->setFirstName(null)
            ->setLastName(null)
            ->setSalutation(null)
            ->setGender(null)
            ->setDateOfBirth(null)
            ->setPhone(null)
            ->setEmail($this->generateRandomEmail());

        return $customerTransfer;
    }

    /**
     * @return string
     */
    protected function generateRandomEmail()
    {
        do {
            $randomEmail = sprintf(
                '%s@%s.%s',
                strtolower(md5((string)mt_rand())),
                strtolower(md5((string)mt_rand())),
                strtolower(md5((string)mt_rand()))
            );
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
        foreach ($addressesTransfer->getAddresses() as $addressTransfer) {
            $this->anonymizeCustomerAddress($addressTransfer);
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
        $addressTransfer
            ->setAnonymizedAt((new DateTime())->format("Y-m-d H:i:s.u"))
            ->setIsDeleted(true)
            ->setFirstName('')
            ->setLastName('')
            ->setSalutation(null)
            ->setAddress1(null)
            ->setAddress2(null)
            ->setAddress3(null)
            ->setCompany(null)
            ->setCity(null)
            ->setZipCode(null)
            ->setPhone(null);

        return $addressTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    protected function updateCustomer(CustomerTransfer $customerTransfer)
    {
        $this->customerModel->update($customerTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\AddressesTransfer $addressesTransfer
     *
     * @return void
     */
    protected function updateCustomerAddresses(AddressesTransfer $addressesTransfer)
    {
        foreach ($addressesTransfer->getAddresses() as $addressTransfer) {
            $this->addressModel->updateAddress($addressTransfer);
        }
    }
}
