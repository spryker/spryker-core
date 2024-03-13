<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\TestifyBackendApi\Processor\Synchronizer;

use Spryker\Glue\TestifyBackendApi\Dependency\Facade\TestifyBackendApiToEventBehaviourFacadeInterface;
use Spryker\Glue\TestifyBackendApi\Dependency\Facade\TestifyBackendApiToQueueFacadeInterface;
use Symfony\Component\Console\Output\NullOutput;

class OperationSynchronizer implements OperationSynchronizerInterface
{
    /**
     * @var string
     */
    protected const QUEUE_RUNNER_COMMAND = APPLICATION_VENDOR_DIR . '/bin/console queue:task:start';

    /**
     * @var \Spryker\Glue\TestifyBackendApi\Dependency\Facade\TestifyBackendApiToEventBehaviourFacadeInterface $eventBehaviourFacade
     */
    protected TestifyBackendApiToEventBehaviourFacadeInterface $eventBehaviourFacade;

    /**
     * @var \Spryker\Glue\TestifyBackendApi\Dependency\Facade\TestifyBackendApiToQueueFacadeInterface $queueFacade
     */
    protected TestifyBackendApiToQueueFacadeInterface $queueFacade;

    /**
     * @param \Spryker\Glue\TestifyBackendApi\Dependency\Facade\TestifyBackendApiToEventBehaviourFacadeInterface $eventBehaviourFacade
     * @param \Spryker\Glue\TestifyBackendApi\Dependency\Facade\TestifyBackendApiToQueueFacadeInterface $queueFacade
     */
    public function __construct(
        TestifyBackendApiToEventBehaviourFacadeInterface $eventBehaviourFacade,
        TestifyBackendApiToQueueFacadeInterface $queueFacade
    ) {
        $this->eventBehaviourFacade = $eventBehaviourFacade;
        $this->queueFacade = $queueFacade;
    }

    /**
     * @return void
     */
    public function synchronize(): void
    {
        $this->eventBehaviourFacade->triggerRuntimeEvents();
        $this->queueFacade->startWorker(static::QUEUE_RUNNER_COMMAND, new NullOutput(), ['stop_when_empty' => true]);
    }
}
