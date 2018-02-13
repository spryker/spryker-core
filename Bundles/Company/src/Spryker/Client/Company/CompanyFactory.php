<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Company;

use Spryker\Client\Company\Dependency\Client\CompanyToZedRequestClientInterface;
use Spryker\Client\Company\Zed\CompanyStub;
use Spryker\Client\Company\Zed\CompanyStubInterface;
use Spryker\Client\Kernel\AbstractFactory;

class CompanyFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\Company\Zed\CompanyStubInterface
     */
    public function createZedCompanyStub(): CompanyStubInterface
    {
        return new CompanyStub($this->getZedRequestClient());
    }

    /**
     * @return \Spryker\Client\Company\Dependency\Client\CompanyToZedRequestClientInterface
     */
    protected function getZedRequestClient(): CompanyToZedRequestClientInterface
    {
        return $this->getProvidedDependency(CompanyDependencyProvider::CLIENT_ZED_REQUEST);
    }
}
