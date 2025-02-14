<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CustomerDataChangeRequest;

use Spryker\Client\CustomerDataChangeRequest\Zed\CustomerDataChangeRequestStub;
use Spryker\Client\CustomerDataChangeRequest\Zed\CustomerDataChangeRequestStubInterface;
use Spryker\Client\Kernel\AbstractFactory;

/**
 * @method \Spryker\Client\CustomerDataChangeRequest\CustomerDataChangeRequestConfig getConfig()
 */
class CustomerDataChangeRequestFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\CustomerDataChangeRequest\Zed\CustomerDataChangeRequestStubInterface
     */
    public function createZedCustomerDataChangeRequestStub(): CustomerDataChangeRequestStubInterface
    {
        return new CustomerDataChangeRequestStub($this->getProvidedDependency(CustomerDataChangeRequestDependencyProvider::CLIENT_ZED_REQUEST));
    }
}
