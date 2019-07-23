<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Synchronization\Business\Message;

use Generated\Shared\Transfer\QueueSendMessageTransfer;
use Generated\Shared\Transfer\SynchronizationQueueMessageTransfer;
use Spryker\Zed\Synchronization\Business\Exception\SynchronizationQueuePoolNotFoundException;
use Spryker\Zed\Synchronization\Dependency\Service\SynchronizationToUtilEncodingServiceInterface;
use Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataPluginInterface;

class QueueMessageCreator implements QueueMessageCreatorInterface
{
    protected const WRITE = 'write';

    /**
     * @var \Spryker\Zed\Synchronization\Dependency\Service\SynchronizationToUtilEncodingServiceInterface
     */
    protected $encodingService;

    /**
     * @param \Spryker\Zed\Synchronization\Dependency\Service\SynchronizationToUtilEncodingServiceInterface $encodingService
     */
    public function __construct(SynchronizationToUtilEncodingServiceInterface $encodingService)
    {
        $this->encodingService = $encodingService;
    }

    /**
     * @param \Generated\Shared\Transfer\SynchronizationQueueMessageTransfer $synchronizationQueueMessageTransfer
     * @param \Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataPluginInterface $plugin
     * @param string|null $store
     *
     * @return \Generated\Shared\Transfer\QueueSendMessageTransfer
     */
    public function createQueueMessage(
        SynchronizationQueueMessageTransfer $synchronizationQueueMessageTransfer,
        SynchronizationDataPluginInterface $plugin,
        $store = null
    ): QueueSendMessageTransfer {
        $message = [];
        $message[static::WRITE] = $synchronizationQueueMessageTransfer->toArray();

        return $this->createQueueSendMessageTransfer($message, $plugin, $store);
    }

    /**
     * @param array $message
     * @param \Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataPluginInterface $plugin
     * @param string|null $store
     *
     * @throws \Spryker\Zed\Synchronization\Business\Exception\SynchronizationQueuePoolNotFoundException
     *
     * @return \Generated\Shared\Transfer\QueueSendMessageTransfer
     */
    protected function createQueueSendMessageTransfer(array $message, SynchronizationDataPluginInterface $plugin, $store = null): QueueSendMessageTransfer
    {
        $queueSendTransfer = new QueueSendMessageTransfer();
        $queueSendTransfer->setBody(
            $this->encodingService->encodeJson($message)
        );
        $queuePoolName = $plugin->getSynchronizationQueuePoolName();

        if ($store) {
            $queueSendTransfer->setStoreName($store);

            return $queueSendTransfer;
        }

        if (!$queuePoolName) {
            throw new SynchronizationQueuePoolNotFoundException(
                sprintf('You must specify either store column or `SynchronizationQueuePoolName` for %s resource.', $plugin->getResourceName())
            );
        }
        $queueSendTransfer->setQueuePoolName($queuePoolName);

        return $queueSendTransfer;
    }
}
