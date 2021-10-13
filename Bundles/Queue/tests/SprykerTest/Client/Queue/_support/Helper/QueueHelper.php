<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\Queue\Helper;

use Codeception\Stub;
use Codeception\TestInterface;
use Generated\Shared\Transfer\QueueReceiveMessageTransfer;
use Generated\Shared\Transfer\RabbitMqConsumerOptionTransfer;
use Spryker\Client\Queue\Model\Proxy\QueueProxy;
use Spryker\Client\Queue\QueueClientInterface;
use Spryker\Client\Queue\QueueFactory;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Shared\Queue\QueueConstants;
use Spryker\Zed\Synchronization\Business\Message\QueueMessageProcessorInterface;
use Spryker\Zed\Synchronization\Dependency\Client\SynchronizationToSearchClientBridge;
use Spryker\Zed\Synchronization\Dependency\Client\SynchronizationToStorageClientBridge;
use Spryker\Zed\Synchronization\SynchronizationDependencyProvider;
use SprykerTest\Client\Search\Helper\SearchHelperTrait;
use SprykerTest\Client\Storage\Helper\StorageHelperTrait;
use SprykerTest\Client\Testify\Helper\ClientHelperTrait;
use SprykerTest\Shared\Testify\Helper\AbstractHelper;
use SprykerTest\Shared\Testify\Helper\ConfigHelperTrait;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;
use SprykerTest\Shared\Testify\Helper\StaticVariablesHelper;
use SprykerTest\Zed\Testify\Helper\Business\BusinessHelperTrait;
use SprykerTest\Zed\Testify\Helper\Business\DependencyProviderHelperTrait;

class QueueHelper extends AbstractHelper
{
    use BusinessHelperTrait;
    use ClientHelperTrait;
    use DependencyProviderHelperTrait;
    use LocatorHelperTrait;
    use StorageHelperTrait;
    use SearchHelperTrait;
    use ConfigHelperTrait;
    use StaticVariablesHelper;

    /**
     * @var \SprykerTest\Client\Queue\Helper\InMemoryAdapterInterface|null
     */
    protected $inMemoryQueueAdapter;

    /**
     * @var \Spryker\Client\Queue\QueueClientInterface|null
     */
    protected $queueClient;

    /**
     * @var \Spryker\Client\Queue\Model\Proxy\QueueProxyInterface|null
     */
    protected $queueProxy;

    /**
     * @var \Spryker\Client\Queue\Model\Proxy\QueueProxyInterface|null
     */
    protected $queueAdapterCache;

    /**
     * @param \Codeception\TestInterface $test
     *
     * @return void
     */
    public function _before(TestInterface $test): void
    {
        $this->cleanupStaticCaches();
        $this->setUpQueueConfig();
        $this->cleanupInMemoryQueue();
        $this->initializeClient();
    }

    /**
     * We have hundreds of tests that rely on the previously filled static cache, to not break them we reset them to the
     * state before using this helper.
     *
     * @param \Codeception\TestInterface $test
     *
     * @return void
     */
    public function _after(TestInterface $test): void
    {
        $this->resetStaticCaches();
    }

    /**
     * We use some static caches for performance reasons which is ok for production like environments but not for testing.
     * To be able to use our internal mocks we need to set those caches to null.
     *
     * @return void
     */
    protected function cleanupStaticCaches(): void
    {
        $this->cleanupStaticCache(QueueFactory::class, 'queueProxy', null);
        $this->cleanupStaticCache(QueueProxy::class, 'queueAdapterCache', null);
    }

    /**
     * Sets the default options, when you need other options overwrite this method.
     *
     * @return void
     */
    protected function setUpQueueConfig(): void
    {
        $rabbitMqConsumerOptionTransfer = new RabbitMqConsumerOptionTransfer();
        $rabbitMqConsumerOptionTransfer->setConsumerExclusive(false);
        $rabbitMqConsumerOptionTransfer->setNoWait(false);

        $this->getConfigHelper()->mockConfigMethod(
            'getQueueReceiverOptions',
            [
                QueueConstants::QUEUE_DEFAULT_RECEIVER => [
                    'rabbitmq' => $rabbitMqConsumerOptionTransfer,
                ],
            ],
            'Queue'
        );
    }

    /**
     * @return \Spryker\Client\Queue\QueueClientInterface
     */
    public function getQueueClient(): QueueClientInterface
    {
        return $this->queueClient;
    }

    /**
     * We expect that every message that was read from the queue is either acknowledged, rejected or errored. If thats
     * not the case something seems to broken in the process and thus results in an unhealthy queue state we do not expect.
     *
     * @return void
     */
    protected function assertHealthyQueueState(): void
    {
        $inMemoryQueueAdapter = $this->getInMemoryQueueAdapter();

        $receivedMessages = $inMemoryQueueAdapter->getReceivedMessages();
        $unhandledMessages = [];

        foreach ($receivedMessages as $receivedMessage) {
            if ($this->isMessageIn($receivedMessage, $inMemoryQueueAdapter->getAcknowledgedMessages())) {
                continue;
            }
            if ($this->isMessageIn($receivedMessage, $inMemoryQueueAdapter->getRejectedMessages())) {
                continue;
            }
            if ($this->isMessageIn($receivedMessage, $inMemoryQueueAdapter->getErroredMessages())) {
                continue;
            }

            $unhandledMessages[] = $receivedMessage;
        }

        if (empty($unhandledMessages)) {
            return;
        }

        codecept_debug($this->format('<fg=red>There are un-handled messages:'));

        foreach ($unhandledMessages as $unhandledMessage) {
            codecept_debug($this->format(sprintf('<fg=yellow>%s</>', json_encode($unhandledMessage->toArray(), JSON_PRETTY_PRINT))));
        }

        $this->assertEmpty($unhandledMessages, $this->format(sprintf(
            '<fg=yellow>Something is wrong in the queue message processing. All received messages must be handled. One place to debug is <fg=green>%s::processMessages()</>.',
            QueueMessageProcessorInterface::class
        )));
    }

    /**
     * @param \Generated\Shared\Transfer\QueueReceiveMessageTransfer $queueReceiveMessageTransfer
     * @param array<\Generated\Shared\Transfer\QueueReceiveMessageTransfer> $queueReceiveMessageTransferCollection
     *
     * @return bool
     */
    protected function isMessageIn(QueueReceiveMessageTransfer $queueReceiveMessageTransfer, array $queueReceiveMessageTransferCollection): bool
    {
        foreach ($queueReceiveMessageTransferCollection as $handledQueueReceiveMessageTransfer) {
            if ($queueReceiveMessageTransfer === $handledQueueReceiveMessageTransfer) {
                return true;
            }
        }

        return false;
    }

    /**
     * This will clean-up the in-memory queue.
     *
     * @return void
     */
    public function cleanupInMemoryQueue(): void
    {
        $inMemoryQueueAdapter = $this->getInMemoryQueueAdapter();
        $inMemoryQueueAdapter->cleanAll();
    }

    /**
     * Creates a QueueClient with an in-memory (Queue) AdapterInterface and ensure that the locator also returns this mock
     * when used with `$locator->queue()->client()`.
     *
     * @return void
     */
    protected function initializeClient(): void
    {
        // Mock `\Spryker\Client\Queue\Model\Proxy\QueueProxy::getQueueAdapter()` to make use of the in-memory adapter only.
        $queueProxyMock = Stub::make(QueueProxy::class, [
            'getQueueAdapter' => function () {
                return $this->getInMemoryQueueAdapter();
            },
        ]);

        // Mock `\Spryker\Client\Queue\QueueFactory::createQueueProxy()` to use the mocked `QueueProxy`.
        $this->getClientHelper()->mockFactoryMethod(
            'createQueueProxy',
            $queueProxyMock,
            'Queue'
        );

        // Resolves the QueueClientInterface with all mocks from above.
        /** @var \Spryker\Client\Queue\QueueClientInterface $queueClient */
        $queueClient = $this->getClientHelper()->getClient('Queue');
        $this->queueClient = $queueClient;

        // Ensure `$locator->queue()->client()` returns always the QueueClientInterface with all mocks from above.
        $this->getLocatorHelper()->addToLocatorCache('queue-client', $this->queueClient);
    }

    /**
     * @param string $queueName
     *
     * @return void
     */
    public function assertMessagesConsumedFromEventQueue(string $queueName): void
    {
        $this->startTaskOnQueue($queueName);
    }

    /**
     * Assert that at least `$expectedMessageCount` messages found in the given `$queueName`.
     *
     * @param string $queueName
     * @param int $expectedMessageCount
     *
     * @return void
     */
    public function assertQueueMessageCount(string $queueName, int $expectedMessageCount = 1): void
    {
        $messageCount = $this->getInMemoryQueueAdapter()->getMessageCountInQueue($queueName);

        $queueInformation = $this->getInMemoryQueueAdapter()->getAll();

        $this->assertNotNull($messageCount, $this->format(
            sprintf('Whether the queue <fg=green>%s</> does not exists or is empty. Found queues: "%s"', $queueName, implode(', ', array_keys($queueInformation['queues'])))
        ));

        $this->assertTrue($messageCount >= $expectedMessageCount, $this->format(sprintf(
            'Expected at least <fg=green>%s</> message(s) in queue <fg=green>%s</> but found <fg=green>%s</>.',
            $expectedMessageCount,
            $queueName,
            $messageCount
        )));

        // Show some informative output to the use so he can follow whats going on internally
        codecept_debug($this->format(sprintf(
            'Queue <fg=green>%s</> contains <fg=green>%s</> message(s).',
            $queueName,
            $messageCount
        )));
    }

    /**
     * @param string $queueName
     *
     * @return void
     */
    public function assertMessagesConsumedFromQueueAndSyncedToStorage(string $queueName): void
    {
        $this->setStorageClientMock();
        $this->startTaskOnQueue($queueName);
    }

    /**
     * @param string $queueName
     *
     * @return void
     */
    public function assertMessagesConsumedFromQueueAndUpdatedInStorage(string $queueName): void
    {
        $this->setStorageClientMock();
        $this->startTaskOnQueue($queueName);
    }

    /**
     * @param string $queueName
     *
     * @return void
     */
    public function assertMessagesConsumedFromQueueAndRemovedFromStorage(string $queueName): void
    {
        $this->setStorageClientMock();
        $this->startTaskOnQueue($queueName);
    }

    /**
     * @return void
     */
    protected function setStorageClientMock(): void
    {
        $storageClientMock = $this->getStorageHelper()->getStorageClient();

        $this->getDependencyProviderHelper()->setDependency(
            SynchronizationDependencyProvider::CLIENT_STORAGE,
            new SynchronizationToStorageClientBridge($storageClientMock)
        );
    }

    /**
     * @param string $queueName
     *
     * @return void
     */
    public function assertMessagesConsumedFromQueueAndSyncedToSearch(string $queueName): void
    {
        $this->setupSearchClientMock();
        $this->startTaskOnQueue($queueName);
    }

    /**
     * @param string $queueName
     *
     * @return void
     */
    public function assertMessagesConsumedFromQueueAndUpdatedInSearch(string $queueName): void
    {
        $this->setupSearchClientMock();
        $this->startTaskOnQueue($queueName);
    }

    /**
     * @param string $queueName
     *
     * @return void
     */
    public function assertMessagesConsumedFromQueueAndRemovedFromSearch(string $queueName): void
    {
        $this->setupSearchClientMock();
        $this->startTaskOnQueue($queueName);
    }

    /**
     * @return void
     */
    protected function setupSearchClientMock(): void
    {
        $searchClient = $this->getSearchHelper()->getSearchClient();

        $this->getDependencyProviderHelper()->setDependency(
            SynchronizationDependencyProvider::CLIENT_SEARCH,
            new SynchronizationToSearchClientBridge($searchClient)
        );
    }

    /**
     * @param array $collection
     *
     * @return array
     */
    protected function transfersToArray(array $collection): array
    {
        $formattedData = [];

        foreach ($collection as $entry) {
            if ($entry instanceof AbstractTransfer) {
                $formattedData[] = $entry->toArray();

                continue;
            }

            $formattedData[] = $entry;
        }

        return $formattedData;
    }

    /**
     * @param string $queueName
     *
     * @return void
     */
    protected function startTaskOnQueue(string $queueName): void
    {
        /** @var \Spryker\Zed\Queue\Business\QueueFacadeInterface $queueFacade */
        $queueFacade = $this->getBusinessHelper()->getFacade('Queue');
        $queueTaskResponseTransfer = $queueFacade->startTaskWithReport($queueName);

        codecept_debug($this->format(sprintf('Task status: %s', $queueTaskResponseTransfer->getIsSuccesful() ? '<fg=green>success</>' : '<fg=red>failed</>')));
        codecept_debug($this->format(sprintf('Message: <fg=yellow>%s</>', $queueTaskResponseTransfer->getMessage())));
        codecept_debug($this->format(sprintf('Received messages: <fg=yellow>%s</>', $queueTaskResponseTransfer->getReceivedMessageCount())));
        codecept_debug($this->format(sprintf('Processed messages: <fg=yellow>%s</>', $queueTaskResponseTransfer->getProcessedMessageCount())));

        $this->assertHealthyQueueState();
    }

    /**
     * @return \SprykerTest\Client\Queue\Helper\InMemoryAdapterInterface
     */
    public function getInMemoryQueueAdapter(): InMemoryAdapterInterface
    {
        if ($this->inMemoryQueueAdapter === null) {
            $this->inMemoryQueueAdapter = new InMemoryQueueAdapter();
        }

        return $this->inMemoryQueueAdapter;
    }
}
