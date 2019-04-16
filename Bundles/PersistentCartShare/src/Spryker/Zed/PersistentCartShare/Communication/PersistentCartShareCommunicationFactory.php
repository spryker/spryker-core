<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PersistentCartShare\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\PersistentCartShare\Dependency\Facade\PersistentCartShareToResourceShareFacadeInterface;
use Spryker\Zed\PersistentCartShare\Dependency\Service\PersistentCartShareToUtilEncodingServiceInterface;
use Spryker\Zed\PersistentCartShare\PersistentCartShareDependencyProvider;

/**
 * @method \Spryker\Zed\PersistentCartShare\Business\PersistentCartShareFacade getFacade()
 * @method \Spryker\Zed\PersistentCartShare\PersistentCartShareConfig getConfig()
 */
class PersistentCartShareCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\PersistentCartShare\Dependency\Facade\PersistentCartShareToResourceShareFacadeInterface
     */
    public function getResourceShareFacade(): PersistentCartShareToResourceShareFacadeInterface
    {
        return $this->getProvidedDependency(PersistentCartShareDependencyProvider::FACADE_RESOURCE_SHARE);
    }

    /**
     * @return \Spryker\Zed\PersistentCartShare\Dependency\Service\PersistentCartShareToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): PersistentCartShareToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(PersistentCartShareDependencyProvider::SERVICE_UTIL_ENCODING);
    }
}
