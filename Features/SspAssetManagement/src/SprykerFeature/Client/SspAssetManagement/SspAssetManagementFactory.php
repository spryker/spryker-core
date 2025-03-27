<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Client\SspAssetManagement;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ZedRequest\ZedRequestClientInterface;
use SprykerFeature\Client\SspAssetManagement\Zed\SspAssetManagementStub;
use SprykerFeature\Client\SspAssetManagement\Zed\SspAssetManagementStubInterface;

class SspAssetManagementFactory extends AbstractFactory
{
    /**
     * @return \SprykerFeature\Client\SspAssetManagement\Zed\SspAssetManagementStubInterface
     */
    public function createSspAssetManagementStub(): SspAssetManagementStubInterface
    {
        return new SspAssetManagementStub(
            $this->getZedRequestClient(),
        );
    }

    /**
     * @return \Spryker\Client\ZedRequest\ZedRequestClientInterface
     */
    public function getZedRequestClient(): ZedRequestClientInterface
    {
        return $this->getProvidedDependency(SspAssetManagementDependencyProvider::CLIENT_ZED_REQUEST);
    }
}
