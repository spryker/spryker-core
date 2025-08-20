<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace SprykerTest\Zed\Event\Business\Dispatcher;

use Codeception\Test\Unit;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Spryker\Zed\Event\Business\Dispatcher\EventDispatcher;
use Spryker\Zed\Event\Business\Dispatcher\EventDispatcherInterface;
use Spryker\Zed\Event\Business\Logger\EventLoggerInterface;
use Spryker\Zed\Event\Business\Queue\Producer\EventQueueProducerInterface;
use Spryker\Zed\Event\Dependency\EventCollection;
use Spryker\Zed\Event\Dependency\EventCollectionInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventHandlerInterface;
use Spryker\Zed\Event\Dependency\Service\EventToUtilEncodingInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Event
 * @group Business
 * @group Dispatcher
 * @group EventDispatcherTest
 * Add your own group annotations below this line
 */
class EventDispatcherTest extends Unit
{
    /**
     * @var string
     */
    public const TEST_EVENT_NAME = 'trigger.before.save';

    /**
     * @var string
     */
    public const LISTENER_NAME = 'Test/Listener';

    /**
     * @return void
     */
    public function testTriggerWhenSynchronousEventTriggeredShouldInvokeHandle(): void
    {
        $eventCollection = $this->createEventCollection();

        $transferMock = $this->createTransferMock();

        $eventListerMock = $this->createEventListenerMock();
        $eventListerMock
            ->expects($this->once())
            ->method('handle')
            ->with($transferMock);

        $eventCollection->addListener(static::TEST_EVENT_NAME, $eventListerMock);

        $eventDispatcher = $this->createEventDispatcher($eventCollection);

        $eventDispatcher->trigger(static::TEST_EVENT_NAME, $transferMock);
    }

    /**
     * @return void
     */
    public function testTriggerWhenAsynchronousEventTriggeredShouldWriteToQueue(): void
    {
        $transferMocks = [];
        $transferMocks[] = $this->createTransferMock();
        $transferMocks[] = $this->createTransferMock();

        $eventCollection = $this->createEventCollection();
        $eventListerMock = $this->createEventListenerMock();

        $eventListerMock
            ->expects($this->never())
            ->method('handle');

        $eventCollection->addListenerQueued(static::TEST_EVENT_NAME, $eventListerMock);

        $queueProducerMock = $this->createQueueProducerMock();
        $queueProducerMock->expects($this->once())
            ->method('enqueueListenerBulk');

        $eventDispatcher = $this->createEventDispatcher($eventCollection, $queueProducerMock);

        $eventDispatcher->triggerBulk(static::TEST_EVENT_NAME, $transferMocks);
    }

    /**
     * @return void
     */
    public function testTriggerBulkWhenAsynchronousEventTriggeredShouldWriteToQueue(): void
    {
        $eventCollection = $this->createEventCollection();
        $transferMock = $this->createTransferMock();
        $eventListerMock = $this->createEventListenerMock();

        $eventListerMock
            ->expects($this->never())
            ->method('handle');

        $eventCollection->addListenerQueued(static::TEST_EVENT_NAME, $eventListerMock);

        $queueProducerMock = $this->createQueueProducerMock();
        $queueProducerMock->expects($this->once())
            ->method('enqueueListener');

        $eventDispatcher = $this->createEventDispatcher($eventCollection, $queueProducerMock);

        $eventDispatcher->trigger(static::TEST_EVENT_NAME, $transferMock);
    }

    /**
     * @return void
     */
    public function testTriggerWhenEventHandledShouldLogIt(): void
    {
        $eventCollection = $this->createEventCollection();
        $transferMock = $this->createTransferMock();

        $eventLoggerMock = $this->createEventLoggerMock();
        $eventLoggerMock->expects($this->once())
            ->method('log');

        $eventListerMock = $this->createEventListenerMock();
        $eventListerMock
            ->expects($this->once())
            ->method('handle')
            ->with($transferMock);

        $eventCollection->addListener(static::TEST_EVENT_NAME, $eventListerMock);

        $eventDispatcher = $this->createEventDispatcher($eventCollection, null, $eventLoggerMock);

        $eventDispatcher->trigger(static::TEST_EVENT_NAME, $transferMock);
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     * @param \Spryker\Zed\Event\Business\Queue\Producer\EventQueueProducerInterface|null $queueProducerMock
     * @param \Spryker\Zed\Event\Business\Logger\EventLoggerInterface|null $eventLoggerMock
     *
     * @return \Spryker\Zed\Event\Business\Dispatcher\EventDispatcherInterface
     */
    protected function createEventDispatcher(
        EventCollectionInterface $eventCollection,
        ?EventQueueProducerInterface $queueProducerMock = null,
        ?EventLoggerInterface $eventLoggerMock = null
    ): EventDispatcherInterface {
        if ($queueProducerMock === null) {
            $queueProducerMock = $this->createQueueProducerMock();
        }

        if ($eventLoggerMock === null) {
            $eventLoggerMock = $this->createEventLoggerMock();
        }

        $utilEncodingMock = $this->createUtilEncodingMock();

        return new EventDispatcher($eventCollection, $queueProducerMock, $eventLoggerMock, $utilEncodingMock);
    }

    /**
     * @return \Spryker\Zed\Event\Dependency\EventCollectionInterface
     */
    protected function createEventCollection(): EventCollectionInterface
    {
        return new EventCollection();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Event\Business\Queue\Producer\EventQueueProducerInterface
     */
    protected function createQueueProducerMock(): EventQueueProducerInterface
    {
        return $this->getMockBuilder(EventQueueProducerInterface::class)
            ->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Event\Business\Logger\EventLoggerInterface
     */
    protected function createEventLoggerMock(): EventLoggerInterface
    {
        return $this->getMockBuilder(EventLoggerInterface::class)
            ->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Event\Dependency\Plugin\EventHandlerInterface
     */
    protected function createEventListenerMock(): EventHandlerInterface
    {
        return $this->getMockBuilder(EventHandlerInterface::class)
            ->getMock();
    }

    /**
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createTransferMock(): TransferInterface
    {
        return $this->getMockBuilder(TransferInterface::class)
            ->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Event\Dependency\Service\EventToUtilEncodingInterface
     */
    protected function createUtilEncodingMock(): EventToUtilEncodingInterface
    {
        return $this->getMockBuilder(EventToUtilEncodingInterface::class)
            ->getMock();
    }
}
