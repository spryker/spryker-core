<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBroker\Business\MessageValidator;

use Generated\Shared\Transfer\MessageSendingContextTransfer;
use Spryker\Zed\MessageBroker\Business\Exception\CouldNotMapMessageToChannelNameException;
use Spryker\Zed\MessageBroker\Business\MessageChannelProvider\MessageChannelProviderInterface;
use Spryker\Zed\MessageBroker\MessageBrokerConfig;

class MessageValidator implements MessageValidatorInterface
{
    /**
     * @var \Spryker\Zed\MessageBroker\Business\MessageChannelProvider\MessageChannelProviderInterface
     */
    protected MessageChannelProviderInterface $messageChannelProvider;

    /**
     * @var \Spryker\Zed\MessageBroker\MessageBrokerConfig
     */
    protected MessageBrokerConfig $messageBrokerConfig;

    /**
     * @param \Spryker\Zed\MessageBroker\Business\MessageChannelProvider\MessageChannelProviderInterface $messageChannelProvider
     * @param \Spryker\Zed\MessageBroker\MessageBrokerConfig $messageBrokerConfig
     */
    public function __construct(
        MessageChannelProviderInterface $messageChannelProvider,
        MessageBrokerConfig $messageBrokerConfig
    ) {
        $this->messageChannelProvider = $messageChannelProvider;
        $this->messageBrokerConfig = $messageBrokerConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\MessageSendingContextTransfer $messageSendingContextTransfer
     *
     * @return bool
     */
    public function isMessageSendable(MessageSendingContextTransfer $messageSendingContextTransfer): bool
    {
        if (!$this->messageBrokerConfig->isEnabled()) {
            return false;
        }

        try {
            $channel = $this->messageChannelProvider->findChannelByMessageName(
                $messageSendingContextTransfer->getMessageNameOrFail(),
            );
        } catch (CouldNotMapMessageToChannelNameException) {
            return false;
        }

        if (!$channel) {
            return false;
        }

        return true;
    }
}
