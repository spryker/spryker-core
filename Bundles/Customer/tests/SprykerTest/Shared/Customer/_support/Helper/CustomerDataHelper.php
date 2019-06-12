<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Customer\Helper;

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

class CustomerDataHelper extends Module
{
    use DependencyHelperTrait;
    use LocatorHelperTrait;
    use DataCleanupHelperTrait;

    /**
     * @param array $override
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function haveCustomer(array $override = []): CustomerTransfer
    {
        $customerTransfer = (new CustomerBuilder($override))
            ->withBillingAddress()
            ->withShippingAddress()
            ->build();

        $this->ensureCustomerWithReferenceDoesNotExist($customerTransfer);

        $customerResponseTransfer = $this->getCustomerFacade()->registerCustomer($customerTransfer);

        $this->getDataCleanupHelper()->_addCleanup(function () use ($customerResponseTransfer) {
            if (!$customerResponseTransfer->getIsSuccess()) {
                return;
            }
            $this->getCustomerFacade()
                ->deleteCustomer($customerResponseTransfer->getCustomerTransfer());
        });

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

        return $this->getLocatorHelper()->getLocator()->customer()->facade();
    }

    /**
     * @return \Spryker\Zed\Mail\Business\MailFacadeInterface
     */
    protected function getMailFacadeMock()
    {
        /** @var \Spryker\Zed\Mail\Business\MailFacadeInterface $mailFacadeMock */
        $mailFacadeMock = Stub::makeEmpty(MailFacadeInterface::class);

        return $mailFacadeMock;
    }
}
