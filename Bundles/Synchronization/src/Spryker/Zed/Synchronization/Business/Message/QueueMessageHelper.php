<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Synchronization\Business\Message;

use Generated\Shared\Transfer\QueueReceiveMessageTransfer;
use Spryker\Shared\Synchronization\SynchronizationConfig;
use Spryker\Zed\Synchronization\Dependency\Service\SynchronizationToUtilEncodingServiceInterface;

class QueueMessageHelper implements QueueMessageHelperInterface
{
    /**
     * @var \Spryker\Zed\Synchronization\Dependency\Service\SynchronizationToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Zed\Synchronization\Dependency\Service\SynchronizationToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(SynchronizationToUtilEncodingServiceInterface $utilEncodingService)
    {
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param \Generated\Shared\Transfer\QueueReceiveMessageTransfer $queueMessageTransfer
     * @param string $errorMessage
     *
     * @return \Generated\Shared\Transfer\QueueReceiveMessageTransfer
     */
    public function markMessageAsFailed(QueueReceiveMessageTransfer $queueMessageTransfer, string $errorMessage = ''): QueueReceiveMessageTransfer
    {
        $this->setMessageError($queueMessageTransfer, $errorMessage);

        $queueMessageTransfer->setAcknowledge(false);
        $queueMessageTransfer->setReject(true);
        $queueMessageTransfer->setHasError(true);
        $queueMessageTransfer->setRoutingKey(SynchronizationConfig::MESSAGE_ROUTING_KEY_ERROR);

        return $queueMessageTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QueueReceiveMessageTransfer $queueMessageTransfer
     * @param string $errorMessage
     *
     * @return void
     */
    protected function setMessageError(QueueReceiveMessageTransfer $queueMessageTransfer, string $errorMessage = ''): void
    {
        $queueMessageBody = $this->decodeJson($queueMessageTransfer->getQueueMessage()->getBody(), true);
        $queueMessageBody['errorMessage'] = $errorMessage;
        $queueMessageTransfer->getQueueMessage()->setBody($this->encodeJson($queueMessageBody));
    }

    /**
     * @param string $jsonValue
     * @param bool $assoc
     * @param int|null $depth
     * @param int|null $options
     *
     * @return mixed|null
     */
    public function decodeJson($jsonValue, $assoc = false, $depth = null, $options = null)
    {
        return $this->utilEncodingService->decodeJson($jsonValue, $assoc, $depth, $options);
    }

    /**
     * @param array $value
     * @param int|null $options
     * @param int|null $depth
     *
     * @return string
     */
    public function encodeJson(array $value, ?int $options = null, ?int $depth = null): string
    {
        return $this->utilEncodingService->encodeJson($value, $options, $depth);
    }
}
