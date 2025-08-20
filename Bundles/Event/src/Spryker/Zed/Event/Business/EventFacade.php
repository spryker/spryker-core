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
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $eventName
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $transfer
     *
     * @return void
     */
    public function trigger($eventName, TransferInterface $transfer)
    {
        $this->getFactory()
            ->createEventDispatcher()
            ->trigger($eventName, $transfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $eventName
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $transfers
     *
     * @return void
     */
    public function triggerBulk($eventName, array $transfers): void
    {
        $this->getFactory()
            ->createEventDispatcher()
            ->triggerBulk($eventName, $transfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $listenerName
     * @param string $eventName
     * @param array<\Spryker\Shared\Kernel\Transfer\TransferInterface> $transfers
     *
     * @return void
     */
    public function triggerByListenerName(string $listenerName, string $eventName, array $transfers): void
    {
        $this->getFactory()
            ->createEventDispatcher()
            ->triggerByListenerName($listenerName, $eventName, $transfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\QueueReceiveMessageTransfer> $queueMessageTransfers
     *
     * @return array<\Generated\Shared\Transfer\QueueReceiveMessageTransfer>
     */
    public function processEnqueuedMessages(array $queueMessageTransfers)
    {
        return $this->getFactory()
            ->createEventQueueConsumer()
            ->processMessages($queueMessageTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\QueueReceiveMessageTransfer> $queueMessageTransfers
     *
     * @return array<\Generated\Shared\Transfer\QueueReceiveMessageTransfer>
     */
    public function forwardMessages(array $queueMessageTransfers): array
    {
        return $this->getFactory()
            ->createMessageForwarder()
            ->forwardMessages($queueMessageTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return array<string, array<string, array<string, mixed>>>
     */
    public function dumpEventListener(): array
    {
        return $this->getFactory()->createEventListenerDumper()->dump();
    }
}
