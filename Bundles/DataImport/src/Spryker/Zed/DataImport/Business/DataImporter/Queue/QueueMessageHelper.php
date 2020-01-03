<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Business\DataImporter\Queue;

use Generated\Shared\Transfer\QueueReceiveMessageTransfer;
use Spryker\Zed\DataImport\Dependency\Service\DataImportToUtilEncodingServiceInterface;

class QueueMessageHelper implements QueueMessageHelperInterface
{
    protected const ERROR_MESSAGE = 'errorMessage';
    protected const MESSAGE_ROUTING_KEY_ERROR = 'error';

    /**
     * @var \Spryker\Zed\DataImport\Dependency\Service\DataImportToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Zed\DataImport\Dependency\Service\DataImportToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(DataImportToUtilEncodingServiceInterface $utilEncodingService)
    {
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param \Generated\Shared\Transfer\QueueReceiveMessageTransfer $queueReceiveMessageTransfer
     *
     * @return mixed|null
     */
    public function getDecodedMessageBody(QueueReceiveMessageTransfer $queueReceiveMessageTransfer)
    {
        return $this->utilEncodingService->decodeJson($queueReceiveMessageTransfer->getQueueMessage()->getBody(), true);
    }

    /**
     * @param \Generated\Shared\Transfer\QueueReceiveMessageTransfer $queueReceiveMessageTransfer
     *
     * @return \Generated\Shared\Transfer\QueueReceiveMessageTransfer
     */
    public function handleSuccessMessage(QueueReceiveMessageTransfer $queueReceiveMessageTransfer): QueueReceiveMessageTransfer
    {
        return $queueReceiveMessageTransfer->setAcknowledge(true);
    }

    /**
     * @param \Generated\Shared\Transfer\QueueReceiveMessageTransfer $queueReceiveMessageTransfer
     * @param string $errorMessage
     *
     * @return \Generated\Shared\Transfer\QueueReceiveMessageTransfer
     */
    public function handleFailedMessage(QueueReceiveMessageTransfer $queueReceiveMessageTransfer, string $errorMessage): QueueReceiveMessageTransfer
    {
        $queueReceiveMessageTransfer = $this->addErrorMessageToQueueReceiveMessageBody($queueReceiveMessageTransfer, $errorMessage);
        $queueReceiveMessageTransfer->setAcknowledge(false);
        $queueReceiveMessageTransfer->setReject(true);
        $queueReceiveMessageTransfer->setHasError(true);
        $queueReceiveMessageTransfer->setRoutingKey(static::MESSAGE_ROUTING_KEY_ERROR);

        return $queueReceiveMessageTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QueueReceiveMessageTransfer $queueReceiveMessageTransfer
     * @param string $message
     *
     * @return \Generated\Shared\Transfer\QueueReceiveMessageTransfer
     */
    protected function addErrorMessageToQueueReceiveMessageBody(QueueReceiveMessageTransfer $queueReceiveMessageTransfer, string $message): QueueReceiveMessageTransfer
    {
        $queueMessageBody = $this->getDecodedMessageBody($queueReceiveMessageTransfer);
        $queueMessageBody[static::ERROR_MESSAGE] = $message;
        $queueReceiveMessageTransfer->getQueueMessage()->setBody($this->utilEncodingService->encodeJson($queueMessageBody));

        return $queueReceiveMessageTransfer;
    }
}
