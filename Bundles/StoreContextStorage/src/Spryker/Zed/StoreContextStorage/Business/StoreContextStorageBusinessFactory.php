<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StoreContextStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\StoreContextStorage\Business\Writer\StoreContextStorageWriter;
use Spryker\Zed\StoreContextStorage\Business\Writer\StoreContextStorageWriterInterface;
use Spryker\Zed\StoreContextStorage\Dependency\Facade\StoreContextStorageToEventBehaviorFacadeInterface;
use Spryker\Zed\StoreContextStorage\Dependency\Facade\StoreContextStorageToStoreStorageFacadeInterface;
use Spryker\Zed\StoreContextStorage\StoreContextStorageDependencyProvider;

/**
 * @method \Spryker\Zed\StoreContextStorage\StoreContextStorageConfig getConfig()
 */
class StoreContextStorageBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\StoreContextStorage\Business\Writer\StoreContextStorageWriterInterface
     */
    public function createStoreContextStorageWriter(): StoreContextStorageWriterInterface
    {
        return new StoreContextStorageWriter(
            $this->getEventBehaviorFacade(),
            $this->getStoreStorageFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\StoreContextStorage\Dependency\Facade\StoreContextStorageToEventBehaviorFacadeInterface
     */
    public function getEventBehaviorFacade(): StoreContextStorageToEventBehaviorFacadeInterface
    {
        return $this->getProvidedDependency(StoreContextStorageDependencyProvider::FACADE_EVENT_BEHAVIOR);
    }

    /**
     * @return \Spryker\Zed\StoreContextStorage\Dependency\Facade\StoreContextStorageToStoreStorageFacadeInterface
     */
    public function getStoreStorageFacade(): StoreContextStorageToStoreStorageFacadeInterface
    {
        return $this->getProvidedDependency(StoreContextStorageDependencyProvider::FACADE_STORE_STORAGE);
    }
}
