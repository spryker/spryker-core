<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Queue\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\QueueDumpRequestTransfer;
use Generated\Shared\Transfer\QueueDumpResponseTransfer;
use Generated\Shared\Transfer\QueueReceiveMessageTransfer;
use Generated\Shared\Transfer\RabbitMqConsumerOptionTransfer;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Queue\QueueClient;
use Spryker\Client\Queue\QueueDependencyProvider as SprykerQueueDependencyProvider;
use Spryker\Shared\Queue\QueueConfig as SharedQueueConfig;
use Spryker\Shared\Queue\QueueConstants;
use Spryker\Zed\Queue\Business\Exception\MissingQueuePluginException;
use Spryker\Zed\Queue\Business\QueueBusinessFactory;
use Spryker\Zed\Queue\Business\QueueFacade;
use Spryker\Zed\Queue\Business\QueueFacadeInterface;
use Spryker\Zed\Queue\Business\Worker\Worker;
use Spryker\Zed\Queue\Business\Worker\WorkerInterface;
use Spryker\Zed\Queue\QueueConfig;
use Spryker\Zed\Queue\QueueDependencyProvider;
use Spryker\Zed\QueueExtension\Dependency\Plugin\QueueMessageCheckerPluginInterface;
use Symfony\Component\Console\Output\ConsoleOutput;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Queue
 * @group Business
 * @group Facade
 * @group QueueFacadeTest
 * Add your own group annotations below this line
 */
class QueueFacadeTest extends Unit
{
    /**
     * @var string
     */
    protected const REGISTERED_QUEUE_NAME = 'event';

    /**
     * @var string
     */
    protected const UNREGISTERED_QUEUE_NAME = 'wrongQueueName';

    /**
     * @var int
     */
    protected const LIMIT_OPTION = 1;

    /**
     * @var string
     */
    protected const FORMAT_OPTION = 'json';

    /**
     * @var int
     */
    protected const NO_ACK_OPTION = 0;

    /**
     * @var int
     */
    protected const MESSAGE_AMOUNT = 500;

    /**
     * @var int
     */
    protected const QUEUE_WORKER_MAX_THRESHOLD_SECONDS = 59;

    /**
     * @var int
     */
    protected const QUEUE_WORKER_INTERVAL_MILLISECONDS = 1000;

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
    protected function _before(): void
    {
        $this->tester->setDependency(SprykerQueueDependencyProvider::QUEUE_ADAPTERS, function (Container $container) {
            return [
                $container->getLocator()->rabbitMq()->client()->createQueueAdapter(),
            ];
        });
    }

    /**
     * @return void
     */
    public function testQueueWorkerShouldStopIfQueuesDontHaveMessages(): void
    {
        // Arrange
        $queueWorkerMock = $this->getQueueWorkerMock();

        $queueWorkerMock->method('areQueuesEmpty')
            ->willReturn(true);

        // Assert
        $queueWorkerMock->expects($this->exactly(1))
            ->method('executeOperation');

        // Act
        $queueWorkerMock->start(
            $this->tester->getCommandSignature(),
            [SharedQueueConfig::CONFIG_WORKER_STOP_WHEN_EMPTY => true],
        );
    }

    /**
     * @return void
     */
    public function testQueueWorkerDoesNotStopIfThresholdIsReachedAndStopWhenEmptyIsEnabled(): void
    {
        // Arrange
        $queueWorkerMock = $this->getQueueWorkerMock(true);

        $queueWorkerMock->method('areQueuesEmpty')
            ->willReturnOnConsecutiveCalls(false, true);

        // Assert
        $queueWorkerMock->expects($this->exactly(2))
            ->method('executeOperation');

        // Act
        $queueWorkerMock->start(
            $this->tester->getCommandSignature(),
            [SharedQueueConfig::CONFIG_WORKER_STOP_WHEN_EMPTY => true],
        );
    }

    /**
     * @return void
     */
    public function testQueueWorkerDoesNotStopIfThresholdIsReachedAndStopWhenEmptyIsDisabled(): void
    {
        // Arrange
        $queueWorkerMock = $this->getQueueWorkerMock(true);

        $queueWorkerMock->method('areQueuesEmpty')
            ->willReturn(false);

        // Assert
        $queueWorkerMock->expects($this->exactly(0))
            ->method('executeOperation');

        // Act
        $queueWorkerMock->start(
            $this->tester->getCommandSignature(),
            [SharedQueueConfig::CONFIG_WORKER_STOP_WHEN_EMPTY => false],
        );
    }

    /**
     * @return void
     */
    public function testQueueWorkerShouldRestartIfQueuesHaveMessages(): void
    {
        // Arrange
        $queueWorkerMock = $this->getQueueWorkerMock();

        $queueWorkerMock->method('areQueuesEmpty')
            ->willReturnOnConsecutiveCalls(false, true);

        // Assert
        $queueWorkerMock->expects($this->exactly(2))
            ->method('executeOperation');

        // Act
        $queueWorkerMock->start(
            $this->tester->getCommandSignature(),
            [SharedQueueConfig::CONFIG_WORKER_STOP_WHEN_EMPTY => true],
        );
    }

    /**
     * @param bool $returnZeroThreshold
     *
     * @return \Spryker\Zed\Queue\Business\Worker\WorkerInterface
     */
    protected function getQueueWorkerMock(bool $returnZeroThreshold = false): WorkerInterface
    {
        $queueBusinessFactory = new QueueBusinessFactory();

        if ($returnZeroThreshold === true) {
            $queueConfigMock = $this->getMockBuilder(QueueConfig::class)
                ->getMock();
            $queueConfigMock
                ->method('getQueueWorkerMaxThreshold')
                ->willReturn(0);
            $queueBusinessFactory->setConfig($queueConfigMock);
        }

        $queueWorkerMock = $this->getMockBuilder(Worker::class)
            ->setConstructorArgs([
                $queueBusinessFactory->createProcessManager(),
                $queueBusinessFactory->getConfig(),
                $queueBusinessFactory->createWorkerProgressbar(new ConsoleOutput()),
                $queueBusinessFactory->getQueueClient(),
                $queueBusinessFactory->getQueueNames(),
                $queueBusinessFactory->createQueueWorkerSignalDispatcher(),
                $queueBusinessFactory->createQueueConfigReader(),
                $queueBusinessFactory->getQueueMessageCheckerPlugins(),
            ])
            ->onlyMethods(['areQueuesEmpty', 'getPendingProcesses', 'executeOperation'])
            ->getMock();

        $queueWorkerMock
            ->method('getPendingProcesses')
            ->willReturn([]);

        return $queueWorkerMock;
    }

    /**
     * @return void
     */
    public function testQueueDumpWithAcknowledge(): void
    {
        $this->tester->setDependency(QueueDependencyProvider::CLIENT_QUEUE, $this->createQueueClientMock());

        $queueFacade = $this->getFacade(static::REGISTERED_QUEUE_NAME);
        $queueDumpRequestTransfer = $this->createQueueDumpRequestTransfer(static::REGISTERED_QUEUE_NAME);
        $queueDumpResponseTransfer = $queueFacade->queueDump($queueDumpRequestTransfer);

        $this->assertInstanceOf(QueueDumpResponseTransfer::class, $queueDumpResponseTransfer);
    }

    /**
     * @return void
     */
    public function testQueueDumpWithNonExistingQueue(): void
    {
        $this->tester->setDependency(QueueDependencyProvider::CLIENT_QUEUE, $this->createQueueClientMock());

        $queueFacade = $this->getFacade(static::UNREGISTERED_QUEUE_NAME);
        $queueDumpRequestTransfer = $this->createQueueDumpRequestTransfer(static::UNREGISTERED_QUEUE_NAME);

        $this->expectException(MissingQueuePluginException::class);
        $this->expectExceptionMessage('There is no queue registered with this queue: wrongQueueName. Please check the queue name and try again.');

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
     * @param string|null $queueName
     *
     * @return \Spryker\Zed\Queue\Business\QueueFacadeInterface
     */
    protected function getFacade($queueName = null): QueueFacadeInterface
    {
        $queueConfigMock = $this->getMockBuilder(QueueConfig::class)->getMock();

        if ($queueName) {
            $queueConfigMock
                ->method('getQueueReceiverOption')
                ->willReturn($this->getQueueReceiverOptions($queueName));
        }

        $queueConfigMock
            ->method('getWorkerMessageCheckOption')
            ->willReturn($this->tester->getQueueReceiverOptions());
        $queueConfigMock
            ->method('getQueueWorkerMaxThreshold')
            ->willReturn(static::QUEUE_WORKER_MAX_THRESHOLD_SECONDS);
        $queueConfigMock
            ->method('getQueueWorkerInterval')
            ->willReturn(static::QUEUE_WORKER_INTERVAL_MILLISECONDS);
        $queueConfigMock
            ->method('getQueueServerId')
            ->willReturn($this->tester->getServerName());

        $queueBusinessFactory = new QueueBusinessFactory();
        $queueBusinessFactory->setConfig($queueConfigMock);

        $queueFacade = new QueueFacade();
        $queueFacade->setFactory($queueBusinessFactory);

        return $queueFacade;
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

        return [];
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

    /**
     * @return void
     */
    protected function setQueueMessageCheckerPluginDependency(): void
    {
        $queueCheckerPluginInterfaceMock = $this
            ->getMockBuilder(QueueMessageCheckerPluginInterface::class)
            ->getMock();

        $queueCheckerPluginInterfaceMock
            ->method('isApplicable')
            ->willReturn(true);

        $this->tester->setDependency(QueueDependencyProvider::PLUGINS_QUEUE_MESSAGE_CHECKER, [$queueCheckerPluginInterfaceMock]);
    }
}
