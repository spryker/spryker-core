<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\EventBehavior\Business;

use Spryker\Zed\EventBehavior\Business\Model\EventEntityTransferFilter;
use Spryker\Zed\EventBehavior\Business\Model\TriggerManager;
use Spryker\Zed\EventBehavior\EventBehaviorDependencyProvider;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\EventBehavior\EventBehaviorConfig getConfig()
 * @method \Spryker\Zed\EventBehavior\Persistence\EventBehaviorQueryContainerInterface getQueryContainer()
 */
class EventBehaviorBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\EventBehavior\Business\Model\TriggerManagerInterface
     */
    public function createTriggerManager()
    {
        return new TriggerManager(
            $this->getEventFacade(),
            $this->getUtilEncodingService(),
            $this->getQueryContainer(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\EventBehavior\Dependency\Facade\EventBehaviorToEventInterface
     */
    public function getEventFacade()
    {
        return $this->getProvidedDependency(EventBehaviorDependencyProvider::FACADE_EVENT);
    }

    /**
     * @return \Spryker\Zed\EventBehavior\Dependency\Service\EventBehaviorToUtilEncodingInterface
     */
    public function getUtilEncodingService()
    {
        return $this->getProvidedDependency(EventBehaviorDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return \Spryker\Zed\EventBehavior\Business\Model\EventEntityTransferFilterInterface
     */
    public function createEventEntityTransferFilter()
    {
        return new EventEntityTransferFilter();
    }
}
