<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Publisher\Dependency\Facade;

class PublisherToEventBehaviorFacadeBridge implements PublisherToEventBehaviorFacadeInterface
{
    /**
     * @var \Spryker\Zed\EventBehavior\Business\EventBehaviorFacadeInterface
     */
    protected $eventBehaviorFacade;

    /**
     * @param \Spryker\Zed\EventBehavior\Business\EventBehaviorFacadeInterface $eventBehaviorFacade
     */
    public function __construct($eventBehaviorFacade)
    {
        $this->eventBehaviorFacade = $eventBehaviorFacade;
    }

    /**
     * @param array $resources
     * @param array $ids
     *
     * @return void
     */
    public function executeResolvedPluginsBySources(array $resources, array $ids = []): void
    {
        $this->eventBehaviorFacade->executeResolvedPluginsBySources($resources, $ids);
    }

    /**
     * @return string[]
     */
    public function getAvailableResourceNames(): array
    {
        return $this->eventBehaviorFacade->getAvailableResourceNames();
    }
}
