<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Customer;

use Spryker\Client\Customer\Session\CustomerSession;
use Spryker\Client\Customer\Zed\CustomerStub;
use Spryker\Client\Kernel\AbstractFactory;

class CustomerFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\Customer\Zed\CustomerStubInterface
     */
    public function createZedCustomerStub()
    {
        return new CustomerStub(
            $this->getProvidedDependency(CustomerDependencyProvider::SERVICE_ZED)
        );
    }

    /**
     * @return \Spryker\Client\Customer\Session\CustomerSessionInterface
     */
    public function createSessionCustomerSession()
    {
        return new CustomerSession(
            $this->getProvidedDependency(CustomerDependencyProvider::SERVICE_SESSION)
        );
    }
}
