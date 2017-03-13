<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Client\Queue\Proxy;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\QueueMessageTransfer;
use Generated\Shared\Transfer\QueueOptionTransfer;
use Spryker\Client\Queue\Model\Adapter\AdapterInterface;
use Spryker\Client\Queue\Model\Proxy\QueueProxy;

/**
 * @group Unit
 * @group Spryker
 * @group Client
 * @group Queue
 * @group Proxy
 * @group QueueProxyTest
 */
class QueueProxyTest extends Test
{

    /**
     * @var \Spryker\Client\Queue\Model\Proxy\QueueProxy
     */
    protected $queueProxy;

    /**
     * @var \Spryker\Client\Queue\Model\Adapter\AdapterInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $queueAdapterMock;

    /**
     * @return void
     */
    protected function _before()
    {
        $this->queueAdapterMock = $this->getMockBuilder(AdapterInterface::class)->getMock();
        $this->differentQueueAdapterMock = $this->getMockBuilder(AdapterInterface::class)->getMock();

        $this->queueProxy = new QueueProxy(
            ['testQueueEngine' => $this->queueAdapterMock],
            'testQueueEngine',
            ['testQueue' => 'testQueueEngine']
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

        $this->queueProxy->createQueue(new QueueOptionTransfer());
        $this->queueProxy->purgeQueue('testingQueue');
        $this->queueProxy->deleteQueue('testingQueue');
    }

    /**
     * @return void
     */
    public function testQueueProxyTriggersSendMessageAndReceiveMessage()
    {
        $this->queueAdapterMock->expects($this->once())->method('sendMessage');
        $this->queueAdapterMock->expects($this->once())->method('receiveMessage');

        $this->queueProxy->sendMessage(new QueueMessageTransfer());
        $this->queueProxy->receiveMessage(new QueueOptionTransfer());
    }

    /**
     * @return void
     */
    public function testQueueProxyTriggersSendMessagesAndReceiveMessages()
    {
        $this->queueAdapterMock->expects($this->once())->method('sendMessages');
        $this->queueAdapterMock->expects($this->once())->method('receiveMessages');

        $this->queueProxy->sendMessages('testQueue', [new QueueMessageTransfer()]);
        $this->queueProxy->receiveMessages(new QueueOptionTransfer());
    }

    /**
     * @return void
     */
    public function testQueueProxyTriggersAcknowledgeAndRejectAndHandleError()
    {
        $this->queueAdapterMock->expects($this->once())->method('acknowledge');
        $this->queueAdapterMock->expects($this->once())->method('reject');
        $this->queueAdapterMock->expects($this->once())->method('handleErrorMessage');

        $this->queueProxy->acknowledge(new QueueMessageTransfer());
        $this->queueProxy->reject(new QueueMessageTransfer());
        $this->queueProxy->handleErrorMessage(new QueueMessageTransfer());
    }

    /**
     * @return void
     */
    public function testQueueProxyGetsRightAdapter()
    {
        $alphaQueueMessageTransfer = $this->createDummyQueueMessageTransfer('Alpha', 'alphaQueue');
        $betaQueueMessageTransfer = $this->createDummyQueueMessageTransfer('Beta', 'betaQueue');

        $alphaQueueAdapterMock = $this->getMockBuilder(AdapterInterface::class)->getMock();
        $alphaQueueAdapterMock->method('receiveMessage')
            ->willReturn($alphaQueueMessageTransfer);

        $betaQueueAdapterMock = $this->getMockBuilder(AdapterInterface::class)->getMock();
        $betaQueueAdapterMock->method('receiveMessage')
            ->willReturn($betaQueueMessageTransfer);

        $queueProxy = new QueueProxy(
            [
                'alphaQueueEngine' => $alphaQueueAdapterMock,
                'betaQueueEngine' => $betaQueueAdapterMock,
            ],
            'alphaQueueEngine',
            [
                'alphaQueue' => 'alphaQueueEngine',
                'betaQueue' => 'betaQueueEngine',
            ]
        );

        $alphaMessage = $queueProxy->receiveMessage((new QueueOptionTransfer())->setQueueName('alphaQueue'));
        $this->assertEquals($alphaQueueMessageTransfer->toArray(), $alphaMessage->toArray());

        $betaMessage = $queueProxy->receiveMessage((new QueueOptionTransfer())->setQueueName('betaQueue'));
        $this->assertEquals($betaQueueMessageTransfer->toArray(), $betaMessage->toArray());
    }

    /**
     * @return \Generated\Shared\Transfer\QueueMessageTransfer
     */
    protected function createDummyQueueMessageTransfer($body, $queueName)
    {
        $queueMessageTransfer = new QueueMessageTransfer();
        $queueMessageTransfer->setBody($body);
        $queueMessageTransfer->setQueueName($queueName);

        return $queueMessageTransfer;
    }

}
