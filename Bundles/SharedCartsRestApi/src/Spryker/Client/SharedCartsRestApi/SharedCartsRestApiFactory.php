<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SharedCartsRestApi;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\SharedCartsRestApi\Dependency\Client\SharedCartsRestApiToZedRequestClientInterface;
use Spryker\Client\SharedCartsRestApi\Zed\SharedCartsRestApiStub;
use Spryker\Client\SharedCartsRestApi\Zed\SharedCartsRestApiStubInterface;

class SharedCartsRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\SharedCartsRestApi\Zed\SharedCartsRestApiStubInterface
     */
    public function createSharedCartsRestApiStub(): SharedCartsRestApiStubInterface
    {
        return new SharedCartsRestApiStub($this->getZedRequestClient());
    }

    /**
     * @return \Spryker\Client\SharedCartsRestApi\Dependency\Client\SharedCartsRestApiToZedRequestClientInterface
     */
    public function getZedRequestClient(): SharedCartsRestApiToZedRequestClientInterface
    {
        return $this->getProvidedDependency(SharedCartsRestApiDependencyProvider::CLIENT_ZED_REQUEST);
    }
}
