<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBroker\Communication\Plugin\MessageBroker;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MessageBrokerExtension\Dependency\Plugin\MiddlewarePluginInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\UnrecoverableMessageHandlingException;
use Symfony\Component\Messenger\Middleware\StackInterface;

/**
 * @method \Spryker\Zed\MessageBroker\MessageBrokerConfig getConfig()
 * @method \Spryker\Zed\MessageBroker\Business\MessageBrokerFacadeInterface getFacade()
 */
class ValidationMiddlewarePlugin extends AbstractPlugin implements MiddlewarePluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Symfony\Component\Messenger\Envelope $envelope
     * @param \Symfony\Component\Messenger\Middleware\StackInterface $stack
     *
     * @throws \Symfony\Component\Messenger\Exception\UnrecoverableMessageHandlingException
     *
     * @return \Symfony\Component\Messenger\Envelope
     */
    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        /** @var \Spryker\Shared\Kernel\Transfer\TransferInterface $messageTransfer */
        $messageTransfer = $envelope->getMessage();
        if (!$this->getFacade()->canHandleMessage($messageTransfer)) {
            throw new UnrecoverableMessageHandlingException();
        }

        return $stack->next()->handle($envelope, $stack);
    }
}
