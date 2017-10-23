<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\EventBehavior\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\EventBehavior\Business\EventBehaviorBusinessFactory getFactory()
 */
class EventBehaviorFacade extends AbstractFacade implements EventBehaviorFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return void
     */
    public function triggerRuntimeEvents()
    {
        $this->getFactory()->createTriggerManager()->triggerRuntimeEvents();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return void
     */
    public function triggerLostEvents()
    {
        $this->getFactory()->createTriggerManager()->triggerLostEvents();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return array
     */
    public function getEventTransferIds(array $eventTransfers)
    {
        return $this->getFactory()->createEventEntityTransferFilter()->getEventTransferIds($eventTransfers);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     * @param string $foreignKeyColumnName
     *
     * @return array
     */
    public function getEventTransferForeignKeys(array $eventTransfers, $foreignKeyColumnName)
    {
        return $this->getFactory()->createEventEntityTransferFilter()->getEventTransferForeignKeys($eventTransfers, $foreignKeyColumnName);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param array $eventTransfers
     * @param array $columns
     *
     * @return \Generated\Shared\Transfer\EventEntityTransfer[]
     */
    public function getEventTransfersByModifiedColumns(array $eventTransfers, array $columns)
    {
        return $this->getFactory()->createEventEntityTransferFilter()->getEventTransfersByModifiedColumns($eventTransfers, $columns);
    }
}
