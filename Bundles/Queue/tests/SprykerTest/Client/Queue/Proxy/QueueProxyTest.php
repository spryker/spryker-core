<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\Queue\Proxy;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\QueueReceiveMessageTransfer;
use Generated\Shared\Transfer\QueueSendMessageTransfer;
use Spryker\Client\Queue\Model\Adapter\AdapterInterface;
use Spryker\Client\Queue\Model\Proxy\QueueProxy;
use Spryker\Shared\Queue\QueueConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group Queue
 * @group Proxy
 * @group QueueProxyTest
 * Add your own group annotations below this line
 */
class QueueProxyTest extends Unit
{
    public const TEST_QUEUE_NAME = 'testQueueName';

    /**
     * @var \Spryker\Client\Queue\Model\Proxy\QueueProxy
     */
    protected $queueProxy;

    /**
     * @var \Spryker\Client\Queue\Model\Adapter\AdapterInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $queueAdapterMock;

    /**
     * @return void
     */
    protected function _before()
    {
        $this->queueAdapterMock = clone $this->getMockBuilder(AdapterInterface::class)->getMock();
        $this->differentQueueAdapterMock = $this->getMockBuilder(AdapterInterface::class)->getMock();

        $this->queueProxy = new QueueProxy(
            ['testQueueEngine' => $this->queueAdapterMock],
            [
                self::TEST_QUEUE_NAME => [
                    QueueConfig::CONFIG_QUEUE_ADAPTER => get_class($this->queueAdapterMock),
                ],
            ],
            []
        );
    }

    /**
     * @return void
     */
    public function testQueueProxyTriggersCreateAndDeleteAndPurgeQueue()
    {
        $this->queueAdapterMock->expects($this->once())->method('createQueue');
        $this->queueAdapterMock->expects($this->once())->method('purgeQueue');
        $this->queueAdapterMock->expects($this->once())->method('deleteQueue');

        $this->queueProxy->createQueue(self::TEST_QUEUE_NAME);
        $this->queueProxy->purgeQueue(self::TEST_QUEUE_NAME);
        $this->queueProxy->deleteQueue(self::TEST_QUEUE_NAME);
    }

    /**
     * @return void
     */
    public function testQueueProxyTriggersSendMessageAndReceiveMessage()
    {
        $this->queueAdapterMock->expects($this->once())->method('sendMessage');
        $this->queueAdapterMock->expects($this->once())->method('receiveMessage');

        $this->queueProxy->sendMessage(self::TEST_QUEUE_NAME, new QueueSendMessageTransfer());
        $this->queueProxy->receiveMessage(self::TEST_QUEUE_NAME);
    }

    /**
     * @return void
     */
    public function testQueueProxyTriggersSendMessagesAndReceiveMessages()
    {
        $this->queueAdapterMock->expects($this->once())->method('sendMessages');
        $this->queueAdapterMock->expects($this->once())->method('receiveMessages');

        $this->queueProxy->sendMessages(self::TEST_QUEUE_NAME, [new QueueSendMessageTransfer()]);
        $this->queueProxy->receiveMessages(self::TEST_QUEUE_NAME);
    }

    /**
     * @return void
     */
    public function testQueueProxyTriggersAcknowledgeAndRejectAndHandleError()
    {
        $this->queueAdapterMock->expects($this->once())->method('acknowledge');
        $this->queueAdapterMock->expects($this->once())->method('reject');
        $this->queueAdapterMock->expects($this->once())->method('handleError');

        $queueReceiveMessageTransfer = (new QueueReceiveMessageTransfer())->setQueueName(self::TEST_QUEUE_NAME);
        $this->queueProxy->acknowledge($queueReceiveMessageTransfer);
        $this->queueProxy->reject($queueReceiveMessageTransfer);
        $this->queueProxy->handleError($queueReceiveMessageTransfer);
    }

    /**
     * @return void
     */
    public function testQueueProxyGetsRightAdapter()
    {
        $alphaQueueMessageTransfer = $this->createDummyQueueReceiveMessageTransfer('Alpha', 'alphaQueue');
        $betaQueueMessageTransfer = $this->createDummyQueueReceiveMessageTransfer('Beta', 'betaQueue');

        $alphaQueueAdapterMock = $this->getMockBuilder(AdapterInterface::class)->getMock();
        $alphaQueueAdapterMock->method('receiveMessage')
            ->willReturn($alphaQueueMessageTransfer);
        $betaQueueAdapterMock = $this->getMockBuilder(AdapterInterface::class)
            ->setMockClassName('Spryker_Client_Queue_Model_Adapter_Beta_AdapterInterface')
            ->getMock();
        $betaQueueAdapterMock->method('receiveMessage')
            ->willReturn($betaQueueMessageTransfer);

        $queueProxy = new QueueProxy(
            [
                'alphaQueueEngine' => $alphaQueueAdapterMock,
                'betaQueueEngine' => $betaQueueAdapterMock,
            ],
            [
                'alphaQueue' => [
                    QueueConfig::CONFIG_QUEUE_ADAPTER => get_class($alphaQueueAdapterMock),
                ],
                'betaQueue' => [
                    QueueConfig::CONFIG_QUEUE_ADAPTER => get_class($betaQueueAdapterMock),
                ],
            ],
            []
        );

        $alphaMessage = $queueProxy->receiveMessage('alphaQueue')->setQueueName('alphaQueue');
        $this->assertEquals($alphaQueueMessageTransfer->toArray(), $alphaMessage->toArray());

        $betaMessage = $queueProxy->receiveMessage('betaQueue')->setQueueName('betaQueue');
        $this->assertEquals($betaQueueMessageTransfer->toArray(), $betaMessage->toArray());
    }

    /**
     * @param string $body
     * @param string $queueName
     *
     * @return \Generated\Shared\Transfer\QueueReceiveMessageTransfer
     */
    protected function createDummyQueueReceiveMessageTransfer($body, $queueName)
    {
        $queueSendMessageTransfer = new QueueSendMessageTransfer();
        $queueSendMessageTransfer->setBody($body);

        $queueMessageTransfer = new QueueReceiveMessageTransfer();
        $queueMessageTransfer->setQueueMessage($queueSendMessageTransfer);
        $queueMessageTransfer->setQueueName($queueName);

        return $queueMessageTransfer;
    }
}
