<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManagerStorage\Dependency\Facade;

class FileManagerStorageToEventBehaviorFacadeBridge implements FileManagerStorageToEventBehaviorFacadeInterface
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
    public function triggerRuntimeEvents()
    {
        $this->eventBehaviourFacade->triggerRuntimeEvents();
    }

    /**
     * @return void
     */
    public function triggerLostEvents()
    {
        $this->eventBehaviourFacade->triggerLostEvents();
    }

    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return array
     */
    public function getEventTransferIds(array $eventTransfers)
    {
        return $this->eventBehaviourFacade->getEventTransferIds($eventTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     * @param string $foreignKeyColumnName
     *
     * @return array
     */
    public function getEventTransferForeignKeys(array $eventTransfers, $foreignKeyColumnName)
    {
        return $this->eventBehaviourFacade->getEventTransferForeignKeys($eventTransfers, $foreignKeyColumnName);
    }

    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     * @param array $columns
     *
     * @return \Generated\Shared\Transfer\EventEntityTransfer[]
     */
    public function getEventTransfersByModifiedColumns(array $eventTransfers, array $columns)
    {
        return $this->eventBehaviourFacade->getEventTransfersByModifiedColumns($eventTransfers, $columns);
    }
}
