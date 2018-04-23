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
    use DataCleanupHelperTrait;
    use DependencyHelperTrait;
    use LocatorHelperTrait;

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

        $this->getCustomerFacade()->registerCustomer($customerTransfer);

        $this->getDataCleanupHelper()->_addCleanup(function () use ($customerTransfer) {
            $this->getCustomerFacade()->deleteCustomer($customerTransfer);
        });

        return $customerTransfer;
    }

    /**
     * @return \Spryker\Zed\Customer\Business\CustomerFacadeInterface
     */
    private function getCustomerFacade(): CustomerFacadeInterface
    {
        $customerToMailBridge = new CustomerToMailBridge($this->getMailFacadeMock());
        $this->getDependencyHelper()->setDependency(CustomerDependencyProvider::FACADE_MAIL, $customerToMailBridge);

        return $this->getLocatorHelper()->getLocator()->customer()->facade();
    }

    /**
     * @return \Spryker\Zed\Mail\Business\MailFacadeInterface|object
     */
    private function getMailFacadeMock()
    {
        return Stub::makeEmpty(MailFacadeInterface::class);
    }
}
