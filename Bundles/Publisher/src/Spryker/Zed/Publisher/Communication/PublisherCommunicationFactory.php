<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Publisher\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Publisher\Dependency\Facade\PublisherToEventBehaviorFacadeInterface;
use Spryker\Zed\Publisher\PublisherDependencyProvider;

/**
 * @method \Spryker\Zed\Publisher\PublisherConfig getConfig()
 * @method \Spryker\Zed\Publisher\Business\PublisherFacadeInterface getFacade()
 */
class PublisherCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\Publisher\Dependency\Facade\PublisherToEventBehaviorFacadeInterface
     */
    public function getEventBehaviorFacade(): PublisherToEventBehaviorFacadeInterface
    {
        return $this->getProvidedDependency(PublisherDependencyProvider::FACADE_EVENT_BEHAVIOR);
    }

    /**
     * @return \Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherTriggerPluginInterface[]
     */
    public function getPublisherTriggerPlugins(): array
    {
        return $this->getProvidedDependency(PublisherDependencyProvider::PLUGINS_PUBLISHER_TRIGGER);
    }
}
