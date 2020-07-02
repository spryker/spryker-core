<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\MerchantProductStorage\Business\Deleter\MerchantProductStorageDeleter;
use Spryker\Zed\MerchantProductStorage\Business\Deleter\MerchantProductStorageDeleterInterface;
use Spryker\Zed\MerchantProductStorage\Business\Writer\MerchantProductStorageWriter;
use Spryker\Zed\MerchantProductStorage\Business\Writer\MerchantProductStorageWriterInterface;
use Spryker\Zed\MerchantProductStorage\Dependency\Facade\MerchantProductStorageToEventBehaviorFacadeInterface;
use Spryker\Zed\MerchantProductStorage\MerchantProductStorageDependencyProvider;

/**
 * @method \Spryker\Zed\MerchantProductStorage\Persistence\MerchantProductStorageEntityManager getEntityManager()
 * @method \Spryker\Zed\MerchantProductStorage\Persistence\MerchantProductStorageRepository getRepository()
 * @method \Spryker\Zed\MerchantProductStorage\MerchantProductStorageConfig getConfig()
 */
class MerchantProductStorageBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\MerchantProductStorage\Business\Writer\MerchantProductStorageWriterInterface
     */
    public function createMerchantProductStorageWriter(): MerchantProductStorageWriterInterface
    {
        return new MerchantProductStorageWriter(
            $this->getEventBehaviorFacade(),
            $this->getEntityManager(),
            $this->getRepository()
        );
    }

    /**
     * @return \Spryker\Zed\MerchantProductStorage\Business\Deleter\MerchantProductStorageDeleterInterface
     */
    public function createMerchantProductStorageDeleter(): MerchantProductStorageDeleterInterface
    {
        return new MerchantProductStorageDeleter(
            $this->getEventBehaviorFacade(),
            $this->getEntityManager()
        );
    }

    /**
     * @return \Spryker\Zed\MerchantProductStorage\Dependency\Facade\MerchantProductStorageToEventBehaviorFacadeInterface
     */
    public function getEventBehaviorFacade(): MerchantProductStorageToEventBehaviorFacadeInterface
    {
        return $this->getProvidedDependency(MerchantProductStorageDependencyProvider::FACADE_EVENT_BEHAVIOR);
    }
}
