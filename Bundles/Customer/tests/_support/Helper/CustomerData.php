<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Customer\Helper;

use Codeception\Module;
use Codeception\Util\Stub;
use Generated\Shared\DataBuilder\CustomerBuilder;
use Spryker\Zed\Customer\CustomerDependencyProvider;
use Spryker\Zed\Customer\Dependency\Facade\CustomerToMailBridge;
use Spryker\Zed\Mail\Business\MailFacadeInterface;
use Testify\Helper\Dependency;
use Testify\Helper\Locator;

class CustomerData extends Module
{

    /**
     * @param array $override
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function haveCustomer($override = [])
    {
        $customer = (new CustomerBuilder($override))
            ->withBillingAddress()
            ->withShippingAddress()
            ->build();

        $this->getCustomerFacade()->registerCustomer($customer);

        return $customer;
    }

    /**
     * @return \Spryker\Zed\Customer\Business\CustomerFacadeInterface
     */
    private function getCustomerFacade()
    {
        $customerToMailBridge = new CustomerToMailBridge($this->getMailFacadeMock());
        $this->getDependencyHelper()->setDependency(CustomerDependencyProvider::FACADE_MAIL, $customerToMailBridge);

        return $this->getLocatorHelper()->getLocator()->customer()->facade();
    }

    /**
     * @return MailFacadeInterface|object
     */
    private function getMailFacadeMock()
    {
        return Stub::makeEmpty(MailFacadeInterface::class);
    }

    /**
     * @return \Testify\Helper\Locator|\Codeception\Module
     */
    private function getLocatorHelper()
    {
        return $this->getModule('\\' . Locator::class);
    }

    /**
     * @return \Testify\Helper\Dependency|\Codeception\Module
     */
    private function getDependencyHelper()
    {
        return $this->getModule('\\' . Dependency::class);
    }

}
