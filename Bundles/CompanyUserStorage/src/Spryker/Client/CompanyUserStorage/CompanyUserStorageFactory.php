<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CompanyUserStorage;

use Spryker\Client\CompanyUserStorage\Zed\CompanyUserStorageStub;
use Spryker\Client\Kernel\AbstractFactory;

class CompanyUserStorageFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\CompanyUserStorage\Zed\CompanyUserStorageStubInterface
     */
    public function createZedStub()
    {
        return new CompanyUserStorageStub($this->getZedRequestClient());
    }

    /**
     * @return \Spryker\Client\ZedRequest\ZedRequestClientInterface
     */
    protected function getZedRequestClient()
    {
        return $this->getProvidedDependency(CompanyUserStorageDependencyProvider::CLIENT_ZED_REQUEST);
    }
}
