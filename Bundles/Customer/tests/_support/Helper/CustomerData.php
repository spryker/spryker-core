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
use Testify\Module\BusinessLocator;

class CustomerData extends Module
{
    /**
     * @param array $override
     */
    public function haveCustomer($override = [])
    {
        $customer = (new CustomerBuilder($override))
            ->withBillingAddress()
            ->withShippingAddress()
            ->build();
        $this->getCustomerFacade()->registerCustomer($customer);

    }

    /**
     * @return \Spryker\Zed\Customer\Business\CustomerFacadeInterface
     */
    private function getCustomerFacade()
    {
        $locator = $this->getLocator();

        $mailStub = Stub::make(MailFacadeInterface::class);
        $locator->setDependency(CustomerDependencyProvider::FACADE_MAIL, $mailStub);

        return $locator->getLocator()->customer()->facade();
    }

    /**
     * @return BusinessLocator
     */
    private function getLocator()
    {
        return $this->getModule(BusinessLocator::class);
    }
}
