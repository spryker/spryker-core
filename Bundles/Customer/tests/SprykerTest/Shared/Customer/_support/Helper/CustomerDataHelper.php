<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Customer\Helper;

use Codeception\Exception\TestRuntimeException;
use Codeception\Module;
use Codeception\Stub;
use Generated\Shared\DataBuilder\AddressBuilder;
use Generated\Shared\DataBuilder\CustomerBuilder;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Zed\Customer\Business\CustomerFacadeInterface;
use Spryker\Zed\Customer\CustomerDependencyProvider;
use Spryker\Zed\Customer\Dependency\Facade\CustomerToMailBridge;
use Spryker\Zed\Mail\Business\MailFacadeInterface;
use SprykerTest\Service\Container\Helper\ContainerHelper;
use SprykerTest\Service\Container\Helper\ContainerHelperTrait;
use SprykerTest\Shared\Testify\Helper\ConfigHelperTrait;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;
use SprykerTest\Shared\Testify\Helper\DependencyHelperTrait;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;
use SprykerTest\Shared\Testify\Helper\ModuleHelperConfigTrait;
use SprykerTest\Zed\Testify\Helper\Business\BusinessHelper;

class CustomerDataHelper extends Module
{
    use DependencyHelperTrait;
    use LocatorHelperTrait;
    use DataCleanupHelperTrait;
    use ConfigHelperTrait;
    use ContainerHelperTrait;
    use ModuleHelperConfigTrait;

    /**
     * @var string
     */
    protected const SERVICE_STORE = 'store';

    /**
     * @var string
     */
    protected const CONFIG_KEY_IS_MAIL_FACADE_MOCK_ENABLED = 'isMailFacadeMockEnabled';

    /**
     * @param array $override
     *
     * @throws \Codeception\Exception\TestRuntimeException
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function haveCustomer(array $override = []): CustomerTransfer
    {
        /** @var \Generated\Shared\Transfer\CustomerTransfer $customerTransfer */
        $customerTransfer = (new CustomerBuilder($override))
            ->withBillingAddress()
            ->withShippingAddress()
            ->withLocale()
            ->build();

        $this->ensureCustomerWithReferenceDoesNotExist($customerTransfer);

        $customerResponseTransfer = $this->getCustomerFacade()->registerCustomer($customerTransfer);

        if (!$customerResponseTransfer->getIsSuccess() || $customerResponseTransfer->getCustomerTransfer() === null) {
            $errorString = '';
            foreach ($customerResponseTransfer->getErrors() as $error) {
                $errorString .= $error->getMessage() . ', ';
            }

            throw new TestRuntimeException(
                sprintf(
                    'Could not create customer %s due to the following errors: %s',
                    $customerTransfer->getEmail(),
                    $errorString,
                ),
            );
        }

        $this->getDataCleanupHelper()->_addCleanup(function () use ($customerResponseTransfer): void {
            $this->debug(sprintf('Deleting Customer: %s', $customerResponseTransfer->getCustomerTransfer()->getEmail()));
            $this->getCustomerFacade()->deleteCustomer($customerResponseTransfer->getCustomerTransfer());
        });

        return $customerResponseTransfer->getCustomerTransfer();
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @throws \Codeception\Exception\TestRuntimeException
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function confirmCustomer(CustomerTransfer $customerTransfer): CustomerTransfer
    {
        $customerResponseTransfer = $this->getCustomerFacade()->confirmCustomerRegistration($customerTransfer);

        if (!$customerResponseTransfer->getIsSuccess()) {
            throw new TestRuntimeException(sprintf('Could not confirm customer %s', $customerTransfer->getEmail()));
        }

        return $customerResponseTransfer->getCustomerTransfer();
    }

    /**
     * @param array<string, mixed> $seed
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    public function haveCustomerAddress(array $seed = []): AddressTransfer
    {
        $addressTransfer = $this->getCustomerFacade()
            ->createAddress((new AddressBuilder($seed))->build());

        $this->getDataCleanupHelper()->_addCleanup(function () use ($addressTransfer): void {
            $this->debug(sprintf('Deleting Customer Address: %s', $addressTransfer->getIdCustomerAddress()));
            $this->getCustomerFacade()->deleteAddress($addressTransfer);
        });

        return $addressTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    protected function ensureCustomerWithReferenceDoesNotExist(CustomerTransfer $customerTransfer): void
    {
        if (!$customerTransfer->getCustomerReference()) {
            return;
        }

        $customerTransferFound = $this->getCustomerFacade()->findByReference($customerTransfer->getCustomerReference());

        if ($customerTransferFound) {
            $this->getCustomerFacade()->deleteCustomer($customerTransferFound);
        }
    }

    /**
     * @return \Spryker\Zed\Customer\Business\CustomerFacadeInterface
     */
    public function getCustomerFacade(): CustomerFacadeInterface
    {
        if ($this->config[static::CONFIG_KEY_IS_MAIL_FACADE_MOCK_ENABLED]) {
            $customerToMailBridge = new CustomerToMailBridge($this->getMailFacadeMock());
            $this->getDependencyHelper()->setDependency(CustomerDependencyProvider::FACADE_MAIL, $customerToMailBridge);
        }

        if ($this->isDefaultCustomerFacadeSufficient()) {
            return $this->getLocatorHelper()->getLocator()->customer()->facade();
        }

        $this->getConfigHelper()->mockConfigMethod(
            'getCustomerSequenceNumberPrefix',
            'customer',
            'Customer',
            'Zed',
        );

        if ($this->hasModule('\\' . BusinessHelper::class)) {
            /** @var \Spryker\Zed\Customer\Business\CustomerFacadeInterface $customerFacade */
            $customerFacade = $this->getBusinessHelper()->getFacade('Customer');

            $this->getLocatorHelper()->addToLocatorCache('customer-facade', $customerFacade);

            return $customerFacade;
        }

        return $this->getLocatorHelper()->getLocator()->customer()->facade();
    }

    /**
     * @return bool
     */
    protected function isDefaultCustomerFacadeSufficient(): bool
    {
        return ($this->hasModule('\\' . ContainerHelper::class) && $this->getContainerHelper()->getContainer()->has(static::SERVICE_STORE))
            || $this->getLocatorHelper()->isProjectNamespaceEnabled();
    }

    /**
     * @return \SprykerTest\Zed\Testify\Helper\Business\BusinessHelper
     */
    protected function getBusinessHelper(): BusinessHelper
    {
        /** @var \SprykerTest\Zed\Testify\Helper\Business\BusinessHelper $businessHelper */
        $businessHelper = $this->getModule('\\' . BusinessHelper::class);

        return $businessHelper;
    }

    /**
     * @return \Spryker\Zed\Mail\Business\MailFacadeInterface
     */
    protected function getMailFacadeMock(): MailFacadeInterface
    {
        /** @var \Spryker\Zed\Mail\Business\MailFacadeInterface $mailFacadeMock */
        $mailFacadeMock = Stub::makeEmpty(MailFacadeInterface::class);

        return $mailFacadeMock;
    }

    /**
     * @return void
     */
    protected function setDefaultConfig(): void
    {
        $this->config = [static::CONFIG_KEY_IS_MAIL_FACADE_MOCK_ENABLED => true];
    }
}
