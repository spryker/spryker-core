<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Client\SspFileManagement;

use Spryker\Client\Kernel\AbstractFactory;
use SprykerFeature\Client\SspFileManagement\Zed\SspFileManagementStub;
use SprykerFeature\Client\SspFileManagement\Zed\SspFileManagementStubInterface;

class SspFileManagementFactory extends AbstractFactory
{
    /**
     * @return \SprykerFeature\Client\SspFileManagement\Zed\SspFileManagementStubInterface
     */
    public function createZedSspFileManagementStub(): SspFileManagementStubInterface
    {
        return new SspFileManagementStub(
            $this->getProvidedDependency(SspFileManagementDependencyProvider::CLIENT_ZED_REQUEST),
        );
    }
}
