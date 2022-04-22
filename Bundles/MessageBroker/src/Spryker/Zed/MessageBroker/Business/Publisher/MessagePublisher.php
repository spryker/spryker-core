<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBroker\Business\Publisher;

use Generated\Shared\Transfer\MessageAttributesTransfer;
use Generated\Shared\Transfer\MessageResponseTransfer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Spryker\Zed\MessageBroker\Business\Exception\MessageBrokerException;
use Spryker\Zed\MessageBroker\Business\MessageAttributeProvider\MessageAttributeProviderInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class MessagePublisher implements MessagePublisherInterface
{
    /**
     * @var \Spryker\Zed\MessageBroker\Business\MessageAttributeProvider\MessageAttributeProviderInterface
     */
    protected MessageAttributeProviderInterface $messageAttributeProvider;

    /**
     * @var \Symfony\Component\Messenger\MessageBusInterface
     */
    protected MessageBusInterface $messageBus;

    /**
     * @param \Spryker\Zed\MessageBroker\Business\MessageAttributeProvider\MessageAttributeProviderInterface $messageDecorator
     * @param \Symfony\Component\Messenger\MessageBusInterface $messageBus
     */
    public function __construct(MessageAttributeProviderInterface $messageDecorator, MessageBusInterface $messageBus)
    {
        $this->messageAttributeProvider = $messageDecorator;
        $this->messageBus = $messageBus;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $messageTransfer
     *
     * @return \Generated\Shared\Transfer\MessageResponseTransfer
     */
    public function sendMessage(TransferInterface $messageTransfer): MessageResponseTransfer
    {
        $envelope = $this->messageBus->dispatch(
            $this->provideMessageAttributes($messageTransfer),
        );

        return (new MessageResponseTransfer())->setBody($envelope);
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $messageTransfer
     *
     * @throws \Spryker\Zed\MessageBroker\Business\Exception\MessageBrokerException
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    protected function provideMessageAttributes(TransferInterface $messageTransfer): TransferInterface
    {
        if (!method_exists($messageTransfer, 'setMessageAttributes') || !method_exists($messageTransfer, 'getMessageAttributes')) {
            throw new MessageBrokerException(sprintf('The passed "%s" transfer object must have an attribute "messageAttributes" but it was not found. Please add "<property name=\"messageAttributes\" type=\"MessageAttributes\"/>" to your transfer definition.', get_class($messageTransfer)));
        }

        $messageAttributes = $messageTransfer->getMessageAttributes() ?? new MessageAttributesTransfer();

        $transferName = $this->getTransferNameFromClass($messageTransfer);
        $messageAttributes->setTransferName($transferName);
        $messageAttributes->setEvent($transferName);

        $messageAttributes = $this->messageAttributeProvider->provideMessageAttributes($messageAttributes);
        $messageTransfer->setMessageAttributes($messageAttributes);

        return $messageTransfer;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $messageTransfer
     *
     * @return string
     */
    protected function getTransferNameFromClass(TransferInterface $messageTransfer): string
    {
        $messageName = get_class($messageTransfer);

        return str_replace(['Generated\Shared\Transfer\\', 'Transfer'], '', $messageName);
    }
}
