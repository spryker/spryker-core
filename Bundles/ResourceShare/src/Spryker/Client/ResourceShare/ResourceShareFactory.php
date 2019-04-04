<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ResourceShare;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ResourceShare\Dependency\Client\ResourceShareToZedRequestClientInterface;
use Spryker\Client\ResourceShare\ResourceShareActivator\ResourceShareActivator;
use Spryker\Client\ResourceShare\ResourceShareActivator\ResourceShareActivatorInterface;
use Spryker\Client\ResourceShare\Zed\ResourceShareStub;
use Spryker\Client\ResourceShare\Zed\ResourceShareStubInterface;

class ResourceShareFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\ResourceShare\ResourceShareActivator\ResourceShareActivatorInterface
     */
    public function createResourceShareActivator(): ResourceShareActivatorInterface
    {
        return new ResourceShareActivator(
            $this->createZedResourceShareStub()
        );
    }

    /**
     * @return \Spryker\Client\ResourceShare\Zed\ResourceShareStubInterface
     */
    public function createZedResourceShareStub(): ResourceShareStubInterface
    {
        return new ResourceShareStub($this->getZedRequestClient());
    }

    /**
     * @return \Spryker\Client\ResourceShare\Dependency\Client\ResourceShareToZedRequestClientInterface
     */
    protected function getZedRequestClient(): ResourceShareToZedRequestClientInterface
    {
        return $this->getProvidedDependency(ResourceShareDependencyProvider::CLIENT_ZED_REQUEST);
    }
}
