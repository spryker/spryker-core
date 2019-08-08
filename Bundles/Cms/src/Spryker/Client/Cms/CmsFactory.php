<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Cms;

use Spryker\Client\Cms\Zed\CmsStub;
use Spryker\Client\Cms\Zed\CmsStubInterface;
use Spryker\Client\Kernel\AbstractFactory;

class CmsFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\Cms\Zed\CmsStubInterface
     */
    public function createCmsStub(): CmsStubInterface
    {
        return new CmsStub($this->getProvidedDependency(CmsDependencyProvider::CLIENT_ZED_REQUEST));
    }
}
