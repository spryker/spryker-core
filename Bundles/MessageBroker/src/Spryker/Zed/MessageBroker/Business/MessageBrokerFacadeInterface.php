<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBroker\Business;

use Generated\Shared\Transfer\MessageBrokerWorkerConfigTransfer;
use Generated\Shared\Transfer\MessageResponseTransfer;
use Generated\Shared\Transfer\MessageSendingContextTransfer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Symfony\Component\Console\Output\OutputInterface;

interface MessageBrokerFacadeInterface
{
    /**
     * Specification:
     * - Adds message attributes to the transfer object.
     * - Wraps message in a Symfony Envelope and adds a channel timestamp.
     * - Throws `MissingMessageSenderException` if no message sender is found for the current message channel.
     * - Sends the message through the configured transport for this message.
     * - Writes Logger::INFO level log in case of successful envelope message sending.
     * - Writes Logger::ERROR level log in case of any error during envelope message sending.
     * - Will not send message if {@link \Spryker\Zed\MessageBroker\MessageBrokerConfig::isEnabled()} is `false`.
     *
     * @api
     *
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $messageTransfer
     *
     * @return \Generated\Shared\Transfer\MessageResponseTransfer
     */
    public function sendMessage(TransferInterface $messageTransfer): MessageResponseTransfer;

    /**
     * Specification:
     * - Will not start worker if {@link \Spryker\Zed\MessageBroker\MessageBrokerConfig::isEnabled()} is `false`.
     * - Starts a worker process using data form `MessageBrokerWorkerConfig` transfer.
     * - If `MessageBrokerWorkerConfig.channels` is empty, it will use all available channels specified in the module's config.
     * - Uses message receiver plugins to get messages from the channels.
     * - Iterates over the messages and sends them to the message handler plugins.
     * - Adds `ChannelNameStamp` stamp with message channel to the message.
     * - Disables Propel instance pooling on message handling to avoid data inconsistency on long-running processes.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MessageBrokerWorkerConfigTransfer $messageBrokerWorkerConfigTransfer
     *
     * @return void
     */
    public function startWorker(MessageBrokerWorkerConfigTransfer $messageBrokerWorkerConfigTransfer): void;

    /**
     * Specification:
     * - Prints debug information to the console.
     *
     * @api
     *
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param string|null $pathToAsyncApiFile
     *
     * @return void
     */
    public function printDebug(OutputInterface $output, ?string $pathToAsyncApiFile = null): void;

    /**
     * Specification:
     * - Checks if message can be handled.
     * - Returns false if can\'t be handled and logs the reason.
     *
     * @api
     *
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $message
     *
     * @return bool
     */
    public function canHandleMessage(TransferInterface $message): bool;

    /**
     * Specification:
     * - Checks if the message can be sent.
     * - Requires `MessageSendingContext.messageName` to be set.
     * - Returns `false` if message broker is disabled.
     * - Returns `false` if the message name is not found in message to channel mapping.
     * - Returns `false` if the channel found by message name was filtered out by a stack of `FilterMessageChannelPluginInterface`.
     * - Returns `true` otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MessageSendingContextTransfer $messageSendingContextTransfer
     *
     * @return bool
     */
    public function isMessageSendable(MessageSendingContextTransfer $messageSendingContextTransfer): bool;
}
