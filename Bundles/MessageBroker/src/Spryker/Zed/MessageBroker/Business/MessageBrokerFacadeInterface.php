<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBroker\Business;

use Generated\Shared\Transfer\MessageBrokerWorkerConfigTransfer;
use Generated\Shared\Transfer\MessageResponseTransfer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Symfony\Component\Console\Output\OutputInterface;

interface MessageBrokerFacadeInterface
{
    /**
     * Specification:
     * - Adds message attributes to the transfer object.
     * - Wraps message in a Symfony Envelope and sends it through the configured transport for this message.
     * - Writes Logger::INFO level log in case of successful envelope message sending.
     * - Writes Logger::ERROR level log in case of any error during envelope message sending.
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
     * - Starts a worker process for the defined channels.
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
}
