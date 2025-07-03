<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Event\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\EventQueueSendMessageBodyTransfer;
use Generated\Shared\Transfer\QueueReceiveMessageTransfer;
use Generated\Shared\Transfer\QueueSendMessageTransfer;
use Spryker\Shared\Event\EventConstants;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Spryker\Zed\Event\Business\EventBusinessFactory;
use Spryker\Zed\Event\Business\EventFacade;
use Spryker\Zed\Event\Business\Queue\Consumer\EventQueueConsumer;
use Spryker\Zed\Event\Dependency\Client\EventToQueueInterface;
use Spryker\Zed\Event\Dependency\EventCollection;
use Spryker\Zed\Event\Dependency\EventCollectionInterface;
use Spryker\Zed\Event\Dependency\EventSubscriberCollection;
use Spryker\Zed\Event\Dependency\EventSubscriberCollectionInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventBaseHandlerInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventHandlerInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventSubscriberInterface;
use Spryker\Zed\Event\EventDependencyProvider;
use Spryker\Zed\Kernel\Container;
use SprykerTest\Zed\Event\Stub\TestEventBulkListenerPluginStub;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Event
 * @group Business
 * @group Facade
 * @group EventFacadeTest
 * Add your own group annotations below this line
 */
class EventFacadeTest extends Unit
{
    /**
     * @var string
     */
    public const TEST_EVENT_NAME = 'test.event';

    /**
     * @var \SprykerTest\Zed\Event\EventBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testTriggerShouldHandleGivenListener(): void
    {
        $eventFacade = $this->createEventFacade();
        $transferObject = $this->createTransferObjectMock();

        $eventCollection = $this->createEventListenerCollection();

        $eventListenerMock = $this->createEventListenerMock();
        $eventListenerMock->expects($this->any())
            ->method('handle')
            ->with($transferObject);

        $eventCollection->addListener(static::TEST_EVENT_NAME, $eventListenerMock);

        $eventBusinessFactory = $this->createEventBusinessFactory(null, $eventCollection);

        $eventFacade->setFactory($eventBusinessFactory);
        $eventFacade->trigger(static::TEST_EVENT_NAME, $transferObject);
    }

    /**
     * @return void
     */
    public function testTriggerWhenEventProvidedWithSubscriberShouldHandleListener(): void
    {
        $eventFacade = $this->createEventFacade();
        $transferObject = $this->createTransferObjectMock();

        $eventCollection = $this->createEventListenerCollection();

        $eventListenerMock = $this->createEventListenerMock();
        $eventListenerMock->expects($this->any())
            ->method('handle')
            ->with($transferObject);

        $eventCollection->addListener(static::TEST_EVENT_NAME, $eventListenerMock);

        $eventSubscriberMock = $this->createEventSubscriberMock();

        $eventSubscriberMock->method('getSubscribedEvents')
            ->willReturn($eventCollection);

        $eventSubscriberCollection = $this->createEventSubscriberCollection();
        $eventSubscriberCollection->add($eventSubscriberMock);

        $eventBusinessFactory = $this->createEventBusinessFactory(
            null,
            null,
            $eventSubscriberCollection,
        );

        $eventFacade->setFactory($eventBusinessFactory);
        $eventFacade->trigger(static::TEST_EVENT_NAME, $transferObject);
    }

    /**
     * @return void
     */
    public function testTriggerWhenQueueUsedShouldEnqueueListener(): void
    {
        $eventFacade = $this->createEventFacade();
        $transferObject = $this->createTransferObjectMock();

        $eventCollection = $this->createEventListenerCollection();
        $eventListenerMock = $this->createEventListenerMock();

        $eventCollection->addListenerQueued(static::TEST_EVENT_NAME, $eventListenerMock);

        $mockedQueueClient = $this->createQueueClientMock();

        $mockedQueueClient->expects($this->any())
            ->method('sendMessage')
            ->with(
                EventConstants::EVENT_QUEUE,
                $this->containsOnlyInstancesOf(QueueSendMessageTransfer::class),
            );

        $eventBusinessFactory = $this->createEventBusinessFactory($mockedQueueClient, $eventCollection);

        $eventFacade->setFactory($eventBusinessFactory);
        $eventFacade->trigger(static::TEST_EVENT_NAME, $transferObject);
    }

    /**
     * @return void
     */
    public function testProcessEnqueuedMessagesShouldHandleProvidedEvents(): void
    {
        $eventFacade = $this->createEventFacade();
        $transferObject = $this->createTransferObjectMock();

        $eventCollection = $this->createEventListenerCollection();
        $eventListenerMock = $this->createEventListenerMock();

        $eventCollection->addListenerQueued(static::TEST_EVENT_NAME, $eventListenerMock);

        $messages = [
            $this->createQueueReceiveMessageTransfer($eventListenerMock, $transferObject),
        ];

        $processedMessages = $eventFacade->processEnqueuedMessages($messages);

        $processedQueueReceivedMessageTransfer = $processedMessages[0];

        $this->assertTrue($processedQueueReceivedMessageTransfer->getAcknowledge());
    }

    /**
     * @return void
     */
    public function testProcessEnqueuedMessagesWithBulkShouldHandleProvidedEventsForTheSameEntity(): void
    {
        // Arrange
        $eventCollection = $this->createEventListenerCollection();
        $eventListenerMock = $this->createEventBulkListenerMock();

        // Assert
        $eventListenerMock
            ->expects($this->once())
            ->method('handleBulk')
            ->with(
                $this->callback(function ($arg) {
                    return is_array($arg) && count($arg) === 1;
                }),
                $this->anything(),
            );

        // Arrange
        $eventQueueConsumerMock = $this->createEventQueueConsumerMock();
        $eventQueueConsumerMock->method('createEventListener')->willReturn($eventListenerMock);

        $this->tester->mockFactoryMethod('createEventQueueConsumer', $eventQueueConsumerMock);
        $eventCollection->addListenerQueued(static::TEST_EVENT_NAME, $eventListenerMock);

        $messages = [
            $this->createQueueReceiveMessageTransfer($eventListenerMock, $this->createTransferObjectMock(1)),
            $this->createQueueReceiveMessageTransfer($eventListenerMock, $this->createTransferObjectMock(1)),
        ];

        // Act
        $processedMessages = $this->tester->getFacade()->processEnqueuedMessages($messages);

        $processedQueueReceivedMessageTransfer = $processedMessages[0];

        // Assert
        $this->assertTrue($processedQueueReceivedMessageTransfer->getAcknowledge());
    }

    /**
     * @return void
     */
    public function testProcessEnqueuedMessagesWithBulkShouldHandleProvidedEvents(): void
    {
        // Arrange
        $eventCollection = $this->createEventListenerCollection();
        $eventListenerMock = $this->createEventBulkListenerMock();

        // Assert
        $eventListenerMock
            ->expects($this->once())
            ->method('handleBulk')
            ->with(
                $this->callback(function ($arg) {
                    return is_array($arg) && count($arg) === 2;
                }),
                $this->anything(),
            );

        // Arrange
        $eventQueueConsumerMock = $this->createEventQueueConsumerMock();
        $eventQueueConsumerMock->method('createEventListener')->willReturn($eventListenerMock);

        $this->tester->mockFactoryMethod('createEventQueueConsumer', $eventQueueConsumerMock);
        $eventCollection->addListenerQueued(static::TEST_EVENT_NAME, $eventListenerMock);

        $messages = [
            $this->createQueueReceiveMessageTransfer($eventListenerMock, $this->createTransferObjectMock(1)),
            $this->createQueueReceiveMessageTransfer($eventListenerMock, $this->createTransferObjectMock(2)),
        ];

        // Act
        $processedMessages = $this->tester->getFacade()->processEnqueuedMessages($messages);

        $processedQueueReceivedMessageTransfer = $processedMessages[0];

        // Assert
        $this->assertTrue($processedQueueReceivedMessageTransfer->getAcknowledge());
    }

    /**
     * @return void
     */
    public function testProcessEnqueuedMessagesShouldMarkAsFailedWhenDataIsMissing(): void
    {
        $eventFacade = $this->createEventFacade();

        $eventCollection = $this->createEventListenerCollection();
        $eventListenerMock = $this->createEventListenerMock();

        $eventCollection->addListenerQueued(static::TEST_EVENT_NAME, $eventListenerMock);

        $queueReceivedMessageTransfer = $this->createQueueReceiveMessageTransfer();

        $messages = [
            $queueReceivedMessageTransfer,
        ];

        $processedMessages = $eventFacade->processEnqueuedMessages($messages);

        $processedQueueReceivedMessageTransfer = $processedMessages[0];

        $this->assertFalse($processedQueueReceivedMessageTransfer->getAcknowledge());
        $this->assertTrue($processedQueueReceivedMessageTransfer->getReject());
        $this->assertTrue($processedQueueReceivedMessageTransfer->getHasError());
    }

    /**
     * @return void
     */
    public function testProcessEnqueuedMessageWillSendOnlyErroredMessageFromBulkToRetry(): void
    {
        //Arrange
        $eventCollection = $this->createEventListenerCollection();
        $eventBulkListenerStub = new TestEventBulkListenerPluginStub();
        $eventCollection->addListenerQueued(static::TEST_EVENT_NAME, $eventBulkListenerStub);
        $message = $this->createQueueReceiveMessageTransfer($eventBulkListenerStub, $this->createTransferObjectMock());

        $messages = [
            $this->createQueueReceiveMessageTransfer($eventBulkListenerStub, $this->createTransferObjectMock()),
            $this->createQueueReceiveMessageTransfer($eventBulkListenerStub, $this->createTransferObjectMock()),
        ];

        //Act
        $processedMessages = $this->createEventFacade()->processEnqueuedMessages($messages);

        //Assert
        $this->assertTrue($processedMessages[0]->getAcknowledge());
        $this->assertSame('retry', $processedMessages[0]->getRoutingKey());
        $this->assertTrue($processedMessages[1]->getAcknowledge());
        $this->assertNull($processedMessages[1]->getRoutingKey());
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Event\Dependency\Client\EventToQueueInterface
     */
    protected function createQueueClientMock(): EventToQueueInterface
    {
        return $this->getMockBuilder(EventToQueueInterface::class)
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
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface
     */
    protected function createEventBulkListenerMock(): EventBulkHandlerInterface
    {
        return $this->getMockBuilder(EventBulkHandlerInterface::class)
            ->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|(\Spryker\Zed\Event\Business\Queue\Consumer\EventQueueConsumer&\PHPUnit\Framework\MockObject\MockObject)
     */
    protected function createEventQueueConsumerMock(): EventQueueConsumer
    {
        return $this->getMockBuilder(EventQueueConsumer::class)
            ->onlyMethods(['createEventListener'])
            ->setConstructorArgs([
                $this->tester->getFactory()->createEventLogger(),
                $this->tester->getFactory()->getUtilEncodingService(),
                $this->tester->getFactory()->getConfig(),
            ])
            ->getMock();
    }

    /**
     * @return \Spryker\Zed\Event\Dependency\EventCollectionInterface
     */
    protected function createEventListenerCollection(): EventCollectionInterface
    {
        return new EventCollection();
    }

    /**
     * @return \Spryker\Zed\Event\Business\EventFacade
     */
    protected function createEventFacade(): EventFacade
    {
        return new EventFacade();
    }

    /**
     * @return \Spryker\Zed\Event\Dependency\EventSubscriberCollectionInterface
     */
    protected function createEventSubscriberCollection(): EventSubscriberCollectionInterface
    {
        return new EventSubscriberCollection();
    }

    /**
     * @param int|null $id
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    protected function createTransferObjectMock(?int $id = null): TransferInterface
    {
        return (new EventEntityTransfer())->setId($id);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Event\Dependency\Plugin\EventSubscriberInterface
     */
    protected function createEventSubscriberMock(): EventSubscriberInterface
    {
        return $this->getMockBuilder(EventSubscriberInterface::class)
            ->getMock();
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\Client\EventToQueueInterface|null $queueClientMock
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface|null $eventCollection
     * @param \Spryker\Zed\Event\Dependency\EventSubscriberCollectionInterface|null $eventSubscriberCollection
     *
     * @return \Spryker\Zed\Event\Business\EventBusinessFactory
     */
    protected function createEventBusinessFactory(
        ?EventToQueueInterface $queueClientMock = null,
        ?EventCollectionInterface $eventCollection = null,
        ?EventSubscriberCollectionInterface $eventSubscriberCollection = null
    ): EventBusinessFactory {
        if ($queueClientMock === null) {
            $queueClientMock = $this->createQueueClientMock();
        }

        if ($eventCollection === null) {
            $eventCollection = $this->createEventListenerCollection();
        }

        if ($eventSubscriberCollection === null) {
            $eventSubscriberCollection = $this->createEventSubscriberCollection();
        }

        $eventDependencyProvider = new EventDependencyProvider();

        $container = new Container();

        $businessLayerDependencies = $eventDependencyProvider->provideBusinessLayerDependencies($container);

        $container[EventDependencyProvider::CLIENT_QUEUE] = function () use ($queueClientMock) {
            return $queueClientMock;
        };

        $container[EventDependencyProvider::EVENT_LISTENERS] = function () use ($eventCollection) {
            return $eventCollection;
        };

        $container[EventDependencyProvider::EVENT_SUBSCRIBERS] = function () use ($eventSubscriberCollection) {
            return $eventSubscriberCollection;
        };

        $eventBusinessFactory = new EventBusinessFactory();
        $eventBusinessFactory->setContainer($businessLayerDependencies);

        return $eventBusinessFactory;
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\Plugin\EventBaseHandlerInterface|null $eventListenerMock
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface|null $transferObject
     *
     * @return \Generated\Shared\Transfer\QueueReceiveMessageTransfer
     */
    protected function createQueueReceiveMessageTransfer(
        ?EventBaseHandlerInterface $eventListenerMock = null,
        ?TransferInterface $transferObject = null
    ): QueueReceiveMessageTransfer {
        $message = [
            EventQueueSendMessageBodyTransfer::LISTENER_CLASS_NAME => ($eventListenerMock) ? get_class($eventListenerMock) : null,
            EventQueueSendMessageBodyTransfer::TRANSFER_CLASS_NAME => ($transferObject) ? get_class($transferObject) : null,
            EventQueueSendMessageBodyTransfer::TRANSFER_DATA => $transferObject !== null ? $transferObject->toArray() : ['1', '2', '3'],
            EventQueueSendMessageBodyTransfer::EVENT_NAME => static::TEST_EVENT_NAME,
        ];

        $queueMessageTransfer = new QueueSendMessageTransfer();
        $queueMessageTransfer->setBody((string)json_encode($message));

        $queueReceivedMessageTransfer = new QueueReceiveMessageTransfer();
        $queueReceivedMessageTransfer->setQueueMessage($queueMessageTransfer);

        return $queueReceivedMessageTransfer;
    }
}
