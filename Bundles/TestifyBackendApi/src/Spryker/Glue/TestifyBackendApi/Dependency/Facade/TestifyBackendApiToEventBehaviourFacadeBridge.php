<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\TestifyBackendApi\Dependency\Facade;

class TestifyBackendApiToEventBehaviourFacadeBridge implements TestifyBackendApiToEventBehaviourFacadeInterface
{
    /**
     * @var \Spryker\Zed\EventBehavior\Business\EventBehaviorFacadeInterface
     */
    protected $eventBehaviourFacade;

    /**
     * @param \Spryker\Zed\EventBehavior\Business\EventBehaviorFacadeInterface $eventBehaviourFacade
     */
    public function __construct($eventBehaviourFacade)
    {
        $this->eventBehaviourFacade = $eventBehaviourFacade;
    }

    /**
     * @return void
     */
    public function triggerRuntimeEvents(): void
    {
         $this->eventBehaviourFacade->triggerRuntimeEvents();
    }
}
