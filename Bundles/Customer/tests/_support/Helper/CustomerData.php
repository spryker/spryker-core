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
use Spryker\Zed\Mail\Business\MailFacadeInterface;
use Testify\Helper\BusinessHelper;

class CustomerData extends Module
{
    /**
     * @param array $override
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
        $locator = $this->getLocator();

        $mailStub = Stub::makeEmpty(MailFacadeInterface::class);
        $locator->setDependency(CustomerDependencyProvider::FACADE_MAIL, $mailStub);

        return $locator->getLocator()->customer()->facade();
    }

    /**
     * @return BusinessHelper
     */
    private function getLocator()
    {
        return $this->getModule('\\' . BusinessHelper::class);
    }
}
