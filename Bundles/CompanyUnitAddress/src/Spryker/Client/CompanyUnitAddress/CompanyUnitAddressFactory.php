<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CompanyUnitAddress;

use Spryker\Client\CompanyUnitAddress\Dependency\Client\CompanyUnitAddressToZedRequestClientInterface;
use Spryker\Client\CompanyUnitAddress\Zed\CompanyUnitAddressStub;
use Spryker\Client\CompanyUnitAddress\Zed\CompanyUnitAddressStubInterface;
use Spryker\Client\Kernel\AbstractFactory;

class CompanyUnitAddressFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\CompanyUnitAddress\Zed\CompanyUnitAddressStubInterface
     */
    public function createZedCompanyUnitAddressStub(): CompanyUnitAddressStubInterface
    {
        return new CompanyUnitAddressStub($this->getZedRequestClient());
    }

    /**
     * @return \Spryker\Client\CompanyUnitAddress\Dependency\Client\CompanyUnitAddressToZedRequestClientInterface
     */
    protected function getZedRequestClient(): CompanyUnitAddressToZedRequestClientInterface
    {
        return $this->getProvidedDependency(CompanyUnitAddressDependencyProvider::CLIENT_ZED_REQUEST);
    }
}
