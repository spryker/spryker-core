<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CompanyUserStorage;

use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\CompanyUserStorage\CompanyUserStorageFactory getFactory()
 */
class CompanyUserStorageClient extends AbstractClient implements CompanyUserStorageClientInterface
{
    /**
     * @return \Spryker\Client\CompanyUserStorage\Zed\CompanyUserStorageStubInterface
     */
    protected function getZedStub()
    {
        return $this->getFactory()->createZedStub();
    }
}
