<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\MerchantStorage\Business\Writer\MerchantStorageWriter;
use Spryker\Zed\MerchantStorage\Business\Writer\MerchantStorageWriterInterface;
use Spryker\Zed\MerchantStorage\Dependency\Facade\MerchantStorageToEventBehaviorFacadeInterface;
use Spryker\Zed\MerchantStorage\Dependency\Facade\MerchantStorageToMerchantFacadeInterface;
use Spryker\Zed\MerchantStorage\Dependency\Facade\MerchantStorageToStoreFacadeInterface;
use Spryker\Zed\MerchantStorage\MerchantStorageDependencyProvider;

/**
 * @method \Spryker\Zed\MerchantStorage\MerchantStorageConfig getConfig()
 * @method \Spryker\Zed\MerchantStorage\Persistence\MerchantStorageEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\MerchantStorage\Persistence\MerchantStorageRepositoryInterface getRepository()
 */
class MerchantStorageBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\MerchantStorage\Business\Writer\MerchantStorageWriterInterface
     */
    public function createMerchantStorageWriter(): MerchantStorageWriterInterface
    {
        return new MerchantStorageWriter(
            $this->getEventBehaviorFacade(),
            $this->getMerchantFacade(),
            $this->getStoreFacade(),
            $this->getEntityManager(),
            $this->getRepository()
        );
    }

    /**
     * @return \Spryker\Zed\MerchantStorage\Dependency\Facade\MerchantStorageToEventBehaviorFacadeInterface
     */
    public function getEventBehaviorFacade(): MerchantStorageToEventBehaviorFacadeInterface
    {
        return $this->getProvidedDependency(MerchantStorageDependencyProvider::FACADE_EVENT_BEHAVIOR);
    }

    /**
     * @return \Spryker\Zed\MerchantStorage\Dependency\Facade\MerchantStorageToMerchantFacadeInterface
     */
    public function getMerchantFacade(): MerchantStorageToMerchantFacadeInterface
    {
        return $this->getProvidedDependency(MerchantStorageDependencyProvider::FACADE_MERCHANT);
    }

    /**
     * @return \Spryker\Zed\MerchantStorage\Dependency\Facade\MerchantStorageToStoreFacadeInterface
     */
    public function getStoreFacade(): MerchantStorageToStoreFacadeInterface
    {
        return $this->getProvidedDependency(MerchantStorageDependencyProvider::FACADE_STORE);
    }
}
