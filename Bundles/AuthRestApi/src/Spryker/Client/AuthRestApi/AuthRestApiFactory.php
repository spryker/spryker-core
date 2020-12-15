<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\AuthRestApi;

use Spryker\Client\AuthRestApi\Dependency\Client\AuthRestApiToZedRequestClientInterface;
use Spryker\Client\AuthRestApi\Zed\AuthRestApiZedStub;
use Spryker\Client\AuthRestApi\Zed\AuthRestApiZedStubInterface;
use Spryker\Client\Kernel\AbstractFactory;

class AuthRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\AuthRestApi\Zed\AuthRestApiZedStubInterface
     */
    public function createAuthRestApiZedStub(): AuthRestApiZedStubInterface
    {
        return new AuthRestApiZedStub($this->getZedRequestClient());
    }

    /**
     * @return \Spryker\Client\AuthRestApi\Dependency\Client\AuthRestApiToZedRequestClientInterface
     */
    public function getZedRequestClient(): AuthRestApiToZedRequestClientInterface
    {
        return $this->getProvidedDependency(AuthRestApiDependencyProvider::CLIENT_ZED_REQUEST);
    }
}
