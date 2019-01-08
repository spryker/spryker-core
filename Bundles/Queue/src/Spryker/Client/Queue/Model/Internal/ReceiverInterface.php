<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Queue\Model\Internal;

use Generated\Shared\Transfer\QueueReceiveMessageTransfer;

interface ReceiverInterface
{
    /**
     * Specification
     *  - Returns messages from the queue
     *
     * @api
     *
     * @param string $queueName
     * @param int $chunkSize
     * @param array $options
     *
     * @return \Generated\Shared\Transfer\QueueReceiveMessageTransfer[]
     */
    public function receiveMessages($queueName, $chunkSize = 100, array $options = []);

    /**
     * Specification
     *  - Return a message from the queue
     *
     * @api
     *
     * @param string $queueName
     * @param array $options
     *
     * @return \Generated\Shared\Transfer\QueueReceiveMessageTransfer
     */
    public function receiveMessage($queueName, array $options = []);

    /**
     * Specification
     *  - Sends acknowledgement for a specific message to queue
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QueueReceiveMessageTransfer $queueReceiveMessageTransfer
     *
     * @return void
     */
    public function acknowledge(QueueReceiveMessageTransfer $queueReceiveMessageTransfer);

    /**
     * Specification
     *  - Sends reject for a specific message to queue
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QueueReceiveMessageTransfer $queueReceiveMessageTransfer
     *
     * @return void
     */
    public function reject(QueueReceiveMessageTransfer $queueReceiveMessageTransfer);

    /**
     * Specification
     *  - Manages error handling for the queue
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QueueReceiveMessageTransfer $queueReceiveMessageTransfer
     *
     * @return bool
     */
    public function handleError(QueueReceiveMessageTransfer $queueReceiveMessageTransfer);
}
