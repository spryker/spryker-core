<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCategory\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\MerchantCategory\Business\Publisher\MerchantCategoryPublisher;
use Spryker\Zed\MerchantCategory\Business\Publisher\MerchantCategoryPublisherInterface;
use Spryker\Zed\MerchantCategory\Business\Reader\MerchantCategoryReader;
use Spryker\Zed\MerchantCategory\Business\Reader\MerchantCategoryReaderInterface;
use Spryker\Zed\MerchantCategory\Dependency\Facade\MerchantCategoryToEventBehaviorFacadeInterface;
use Spryker\Zed\MerchantCategory\Dependency\Facade\MerchantCategoryToEventFacadeInterface;
use Spryker\Zed\MerchantCategory\MerchantCategoryDependencyProvider;

/**
 * @method \Spryker\Zed\MerchantCategory\Persistence\MerchantCategoryRepositoryInterface getRepository()
 * @method \Spryker\Zed\MerchantCategory\MerchantCategoryConfig getConfig()
 */
class MerchantCategoryBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\MerchantCategory\Business\Reader\MerchantCategoryReaderInterface
     */
    public function createMerchantCategoryReader(): MerchantCategoryReaderInterface
    {
        return new MerchantCategoryReader(
            $this->getRepository()
        );
    }

    /**
     * @return \Spryker\Zed\MerchantCategory\Business\Publisher\MerchantCategoryPublisherInterface
     */
    public function createMerchantCategoryPublisher(): MerchantCategoryPublisherInterface
    {
        return new MerchantCategoryPublisher(
            $this->getFacadeEvent(),
            $this->getFacadeEventBehavior(),
            $this->getRepository()
        );
    }

    /**
     * @return \Spryker\Zed\MerchantCategory\Dependency\Facade\MerchantCategoryToEventFacadeInterface
     */
    public function getFacadeEvent(): MerchantCategoryToEventFacadeInterface
    {
        return $this->getProvidedDependency(MerchantCategoryDependencyProvider::FACADE_EVENT);
    }

    /**
     * @return \Spryker\Zed\MerchantCategory\Dependency\Facade\MerchantCategoryToEventBehaviorFacadeInterface
     */
    public function getFacadeEventBehavior(): MerchantCategoryToEventBehaviorFacadeInterface
    {
        return $this->getProvidedDependency(MerchantCategoryDependencyProvider::FACADE_EVENT_BEHAVIOR);
    }
}
