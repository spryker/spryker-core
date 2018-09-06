<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManagerStorage\Communication;

use Spryker\Zed\FileManagerStorage\FileManagerStorageDependencyProvider;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\FileManagerStorage\FileManagerStorageConfig getConfig()
 */
class FileManagerStorageCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\FileManagerStorage\Dependency\Facade\FileManagerStorageToEventBehaviorFacadeInterface
     */
    public function getEventBehaviorFacade()
    {
        return $this->getProvidedDependency(FileManagerStorageDependencyProvider::FACADE_EVENT_BEHAVIOR);
    }
}
