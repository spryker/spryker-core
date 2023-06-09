<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBroker\Communication\Plugin\MessageBroker;

use Spryker\Shared\Log\LoggerTrait;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MessageBrokerExtension\Dependency\Plugin\MessageValidatorPluginInterface;
use Spryker\Zed\MessageBrokerExtension\Dependency\Plugin\MiddlewarePluginInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\UnrecoverableMessageHandlingException;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Messenger\Stamp\ReceivedStamp;

/**
 * @method \Spryker\Zed\MessageBroker\MessageBrokerConfig getConfig()
 * @method \Spryker\Zed\MessageBroker\Business\MessageBrokerFacadeInterface getFacade()
 */
class ValidationMiddlewarePlugin extends AbstractPlugin implements MiddlewarePluginInterface
{
    use LoggerTrait;

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
            if ($envelope->all(ReceivedStamp::class)) {
                throw new UnrecoverableMessageHandlingException();
            }

            $this->getLogger()->error(sprintf('Message "%s" can not be handled. At least one of the "%s"s attached to the "%s" marked the message as invalid.', get_class($messageTransfer), MessageValidatorPluginInterface::class, static::class));

            return $envelope;
        }

        return $stack->next()->handle($envelope, $stack);
    }
}
