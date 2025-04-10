<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MultiFactorAuth;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\MultiFactorAuth\Dependency\Client\MultiFactorAuthToZedRequestClientInterface;
use Spryker\Client\MultiFactorAuth\Zed\Customer\CustomerMultiFactorAuthStub;
use Spryker\Client\MultiFactorAuth\Zed\Customer\CustomerMultiFactorAuthStubInterface;

class MultiFactorAuthFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\MultiFactorAuth\Zed\Customer\CustomerMultiFactorAuthStubInterface
     */
    public function createCustomerMultiFactorAuthStub(): CustomerMultiFactorAuthStubInterface
    {
        return new CustomerMultiFactorAuthStub(
            $this->getZedRequestClient(),
        );
    }

    /**
     * @return \Spryker\Client\MultiFactorAuth\Dependency\Client\MultiFactorAuthToZedRequestClientInterface
     */
    public function getZedRequestClient(): MultiFactorAuthToZedRequestClientInterface
    {
        return $this->getProvidedDependency(MultiFactorAuthDependencyProvider::CLIENT_ZED_REQUEST);
    }
}
