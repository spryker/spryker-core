<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CompanyUser;

use Spryker\Client\CompanyUser\Zed\CompanyUserStub;
use Spryker\Client\CompanyUser\Zed\CompanyUserStubInterface;
use Spryker\Client\Kernel\AbstractFactory;

class CompanyUserFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\CompanyUser\Zed\CompanyUserStubInterface
     */
    public function createZedCompanyUserStub(): CompanyUserStubInterface
    {
        return new CompanyUserStub($this->getProvidedDependency(CompanyUserDependencyProvider::CLIENT_ZED_REQUEST));
    }
}
