<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Queue\Communication\Console;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\QueueDumpRequestTransfer;
use Generated\Shared\Transfer\QueueReceiveMessageTransfer;
use Generated\Shared\Transfer\RabbitMqConsumerOptionTransfer;
use Spryker\Client\Queue\QueueClient;
use Spryker\Shared\Event\EventConstants;
use Spryker\Shared\Queue\QueueConstants;
use Spryker\Zed\Event\Communication\Plugin\Queue\EventQueueMessageProcessorPlugin;
use Spryker\Zed\Queue\Business\QueueBusinessFactory;
use Spryker\Zed\Queue\Business\QueueFacade;
use Spryker\Zed\Queue\Business\QueueFacadeInterface;
use Spryker\Zed\Queue\QueueConfig;
use Spryker\Zed\Queue\QueueDependencyProvider;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Queue
 * @group Communication
 * @group Console
 * @group Facade
 * @group QueueFacadeTest
 * Add your own group annotations below this line
 */
class QueueFacadeTest extends Unit
{
    protected const REGISTERED_QUEUE_NAME = 'event';
    protected const UNREGISTERED_QUEUE_NAME = 'wrongQueueName';

    protected const LIMIT_OPTION = 1;
    protected const FORMAT_OPTION = 'json';
    protected const NO_ACK_OPTION = 0;

    /**
     * @var \Spryker\Zed\Queue\Business\QueueFacadeInterface
     */
    protected $queueFacade;

    /**
     * @var \SprykerTest\Zed\Queue\QueueBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function _before()
    {
        $this->tester->setDependency(QueueDependencyProvider::QUEUE_MESSAGE_PROCESSOR_PLUGINS, [
            EventConstants::EVENT_QUEUE => new EventQueueMessageProcessorPlugin(),
        ]);

        $this->tester->setDependency(QueueDependencyProvider::CLIENT_QUEUE, $this->createQueueClientMock());
    }

    /**
     * @return void
     */
    public function testQueueDumpWithAcknowledge(): void
    {
        $queueFacade = $this->getFacade(static::REGISTERED_QUEUE_NAME);
        $queueDumpRequestTransfer = $this->createQueueDumpRequestTransfer(static::REGISTERED_QUEUE_NAME);
        $queueDumpResponseTransfer = $queueFacade->queueDump($queueDumpRequestTransfer);

        $this->assertInstanceOf('\Generated\Shared\Transfer\QueueDumpResponseTransfer', $queueDumpResponseTransfer);
    }

    /**
     * @expectedException \Spryker\Zed\Queue\Business\Exception\MissingQueuePluginException
     * @expectedExceptionMessage There is no queue registered with this queue: wrongQueueName. Please check the queue name and try again.
     *
     * @return void
     */
    public function testQueueDumpWithNonExistingQueue(): void
    {
        $queueFacade = $this->getFacade(static::UNREGISTERED_QUEUE_NAME);
        $queueDumpRequestTransfer = $this->createQueueDumpRequestTransfer(static::UNREGISTERED_QUEUE_NAME);
        $queueFacade->queueDump($queueDumpRequestTransfer);
    }

    /**
     * @param string $queueName
     *
     * @return \Generated\Shared\Transfer\QueueDumpRequestTransfer
     */
    protected function createQueueDumpRequestTransfer(string $queueName): QueueDumpRequestTransfer
    {
        return (new QueueDumpRequestTransfer())
            ->setQueueName($queueName)
            ->setLimit(static::LIMIT_OPTION)
            ->setFormat(static::FORMAT_OPTION)
            ->setAcknowledge(static::NO_ACK_OPTION);
    }

    /**
     * @param string $queueName
     *
     * @return \Spryker\Zed\Queue\Business\QueueFacadeInterface
     */
    protected function getFacade(string $queueName): QueueFacadeInterface
    {
        $configMock = $this->getMockBuilder(QueueConfig::class)->getMock();
        $configMock
            ->method('getQueueReceiverOption')
            ->willReturn($this->getQueueReceiverOptions($queueName));

        $factory = new QueueBusinessFactory();
        $factory->setConfig($configMock);

        $facade = new QueueFacade();
        $facade->setFactory($factory);

        return $facade;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\PHPUnit\Framework\MockObject\MockBuilder
     */
    protected function createQueueClientMock()
    {
        $queueClientMock = $this->getMockBuilder(QueueClient::class)
            ->getMock();

        $queueClientMock
            ->method('receiveMessages')
            ->willReturn([(new QueueReceiveMessageTransfer())]);

        return $queueClientMock;
    }

    /**
     * @param string $queueName
     *
     * @return array
     */
    protected function getQueueReceiverOptions(string $queueName): array
    {
        $queueReceiverOptions = [
            QueueConstants::QUEUE_DEFAULT_RECEIVER => [
                'rabbitmq' => $this->getRabbitMqQueueConsumerOptions(),
            ],
        ];

        if (isset($queueReceiverOptions[$queueName])) {
            return $queueReceiverOptions[$queueName];
        }

        if (array_key_exists(QueueConstants::QUEUE_DEFAULT_RECEIVER, $queueReceiverOptions)) {
            return $queueReceiverOptions[QueueConstants::QUEUE_DEFAULT_RECEIVER];
        }

        return null;
    }

    /**
     * @return \Generated\Shared\Transfer\RabbitMqConsumerOptionTransfer
     */
    protected function getRabbitMqQueueConsumerOptions(): RabbitMqConsumerOptionTransfer
    {
        $queueOptionTransfer = new RabbitMqConsumerOptionTransfer();
        $queueOptionTransfer->setConsumerExclusive(false);
        $queueOptionTransfer->setNoWait(false);

        return $queueOptionTransfer;
    }
}
