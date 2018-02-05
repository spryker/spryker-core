<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CompanyBusinessUnit;

use Spryker\Client\CompanyBusinessUnit\Dependency\Client\CompanyBusinessUnitToZedRequestClientInterface;
use Spryker\Client\CompanyBusinessUnit\Zed\CompanyBusinessUnitStub;
use Spryker\Client\CompanyBusinessUnit\Zed\CompanyBusinessUnitStubInterface;
use Spryker\Client\Kernel\AbstractFactory;

class CompanyBusinessUnitFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\CompanyBusinessUnit\Zed\CompanyBusinessUnitStubInterface
     */
    public function createZedCompanyBusinessUnitStub(): CompanyBusinessUnitStubInterface
    {
        return new CompanyBusinessUnitStub($this->getZedRequestClient());
    }

    /**
     * @return \Spryker\Client\CompanyBusinessUnit\Dependency\Client\CompanyBusinessUnitToZedRequestClientInterface
     */
    protected function getZedRequestClient(): CompanyBusinessUnitToZedRequestClientInterface
    {
        return $this->getProvidedDependency(CompanyBusinessUnitDependencyProvider::CLIENT_ZED_REQUEST);
    }
}
