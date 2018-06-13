<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Event\Business;

use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Event\Business\EventBusinessFactory getFactory()
 */
class EventFacade extends AbstractFacade implements EventFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $eventName
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $eventTransfer
     *
     * @return void
     */
    public function trigger($eventName, TransferInterface $eventTransfer)
    {
        $this->getFactory()
            ->createEventDispatcher()
            ->trigger($eventName, $eventTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $eventName
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface[] $eventTransfers
     *
     * @return void
     */
    public function triggerBulk($eventName, array $eventTransfers): void
    {
        $this->getFactory()
            ->createEventDispatcher()
            ->triggerBulk($eventName, $eventTransfers);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QueueReceiveMessageTransfer[] $queueMessageTransfers
     *
     * @return \Generated\Shared\Transfer\QueueReceiveMessageTransfer[]
     */
    public function processEnqueuedMessages(array $queueMessageTransfers)
    {
        return $this->getFactory()
            ->createEventQueueConsumer()
            ->processMessages($queueMessageTransfers);
    }
}
