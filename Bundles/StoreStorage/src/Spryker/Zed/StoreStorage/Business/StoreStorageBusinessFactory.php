<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StoreStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\StoreStorage\Business\Writer\StoreStorageWriter;
use Spryker\Zed\StoreStorage\Business\Writer\StoreStorageWriterInterface;
use Spryker\Zed\StoreStorage\Dependency\Facade\StoreStorageToEventBehaviorFacadeInterface;
use Spryker\Zed\StoreStorage\Dependency\Facade\StoreStorageToStoreFacadeInterface;
use Spryker\Zed\StoreStorage\StoreStorageDependencyProvider;

/**
 * @method \Spryker\Zed\StoreStorage\StoreStorageConfig getConfig()
 * @method \Spryker\Zed\StoreStorage\Persistence\StoreStorageEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\StoreStorage\Persistence\StoreStorageRepositoryInterface getRepository()
 */
class StoreStorageBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\StoreStorage\Business\Writer\StoreStorageWriterInterface
     */
    public function createStoreStorageWriter(): StoreStorageWriterInterface
    {
        return new StoreStorageWriter(
            $this->getStoreFacade(),
            $this->getEventBehaviorFacade(),
            $this->getEntityManager(),
        );
    }

    /**
     * @return \Spryker\Zed\StoreStorage\Dependency\Facade\StoreStorageToStoreFacadeInterface
     */
    public function getStoreFacade(): StoreStorageToStoreFacadeInterface
    {
        return $this->getProvidedDependency(StoreStorageDependencyProvider::FACADE_STORE);
    }

    /**
     * @return \Spryker\Zed\StoreStorage\Dependency\Facade\StoreStorageToEventBehaviorFacadeInterface
     */
    public function getEventBehaviorFacade(): StoreStorageToEventBehaviorFacadeInterface
    {
        return $this->getProvidedDependency(StoreStorageDependencyProvider::FACADE_EVENT_BEHAVIOR);
    }
}
