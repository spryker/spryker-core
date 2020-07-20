<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Customer\Helper;

use Codeception\Exception\TestRuntimeException;
use Codeception\Module;
use Codeception\Util\Stub;
use Generated\Shared\DataBuilder\CustomerBuilder;
use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Zed\Customer\Business\CustomerFacadeInterface;
use Spryker\Zed\Customer\CustomerDependencyProvider;
use Spryker\Zed\Customer\Dependency\Facade\CustomerToMailBridge;
use Spryker\Zed\Mail\Business\MailFacadeInterface;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;
use SprykerTest\Shared\Testify\Helper\DependencyHelperTrait;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;
use SprykerTest\Zed\Testify\Helper\BusinessHelper;

class CustomerDataHelper extends Module
{
    use DependencyHelperTrait;
    use LocatorHelperTrait;
    use DataCleanupHelperTrait;

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
            ->build();

        $this->ensureCustomerWithReferenceDoesNotExist($customerTransfer);

        $customerResponseTransfer = $this->getCustomerFacade()->registerCustomer($customerTransfer);

        if (!$customerResponseTransfer->getIsSuccess() || $customerResponseTransfer->getCustomerTransfer() === null) {
            throw new TestRuntimeException(sprintf('Could not create customer %s', $customerTransfer->getEmail()));
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
    protected function getCustomerFacade(): CustomerFacadeInterface
    {
        $customerToMailBridge = new CustomerToMailBridge($this->getMailFacadeMock());
        $this->getDependencyHelper()->setDependency(CustomerDependencyProvider::FACADE_MAIL, $customerToMailBridge);

        if ($this->hasModule('\\' . BusinessHelper::class)) {
            /** @var \Spryker\Zed\Customer\Business\CustomerFacadeInterface $customerFacade */
            $customerFacade = $this->getBusinessHelper()->getFacade();

            return $customerFacade;
        }

        return $this->getLocatorHelper()->getLocator()->customer()->facade();
    }

    /**
     * @return \SprykerTest\Zed\Testify\Helper\BusinessHelper
     */
    protected function getBusinessHelper(): BusinessHelper
    {
        /** @var \SprykerTest\Zed\Testify\Helper\BusinessHelper $businessHelper */
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
}
