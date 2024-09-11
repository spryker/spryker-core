<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Synchronization\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\SynchronizationMessageTransfer;
use Spryker\Zed\Synchronization\Business\Search\SynchronizationSearch;
use Spryker\Zed\Synchronization\Business\Storage\SynchronizationStorage;
use Spryker\Zed\Synchronization\Business\SynchronizationBusinessFactory;
use Spryker\Zed\Synchronization\Business\Synchronizer\InMemoryMessageSynchronizer;
use Spryker\Zed\Synchronization\Dependency\Client\SynchronizationToQueueClientInterface;
use SprykerTest\Zed\Synchronization\SynchronizationBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Synchronization
 * @group Business
 * @group Facade
 * @group FlushSynchronizationMessagesFromBufferTest
 * Add your own group annotations below this line
 */
class FlushSynchronizationMessagesFromBufferTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Synchronization\SynchronizationBusinessTester
     */
    protected SynchronizationBusinessTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->clearStaticVariable(InMemoryMessageSynchronizer::class, 'messages');
        $this->tester->clearStaticVariable(SynchronizationBusinessFactory::class, 'inMemoryMessageSynchronizer');
    }

    /**
     * @return void
     */
    public function testShouldWriteBulkStorageAndSearchMessages(): void
    {
        // Assert
        $this->assertBulkMessageProcessing('write');

        // Arrange
        $synchronizationMessageTransfer = (new SynchronizationMessageTransfer())
            ->fromArray($this->tester->createFakeSynchronizationMessage('storage', 'sync.storage.product', 'write'));

        $this->tester->getFacade()->addSynchronizationMessageToBuffer($synchronizationMessageTransfer);

        $synchronizationMessageTransfer = (new SynchronizationMessageTransfer())
            ->fromArray($this->tester->createFakeSynchronizationMessage('search', 'sync.search.product', 'write'));

        $this->tester->getFacade()->addSynchronizationMessageToBuffer($synchronizationMessageTransfer);

        // Act
        $this->tester->getFacade()->flushSynchronizationMessagesFromBuffer();
    }

    /**
     * @return void
     */
    public function testShouldDeleteBulkStorageAndSearchMessages(): void
    {
        // Assert
        $this->assertBulkMessageProcessing('delete');

        // Arrange
        $synchronizationMessageTransfer = (new SynchronizationMessageTransfer())
            ->fromArray($this->tester->createFakeSynchronizationMessage('storage', 'sync.storage.product', 'delete'));

        $this->tester->getFacade()->addSynchronizationMessageToBuffer($synchronizationMessageTransfer);

        $synchronizationMessageTransfer = (new SynchronizationMessageTransfer())
            ->fromArray($this->tester->createFakeSynchronizationMessage('search', 'sync.search.product', 'delete'));

        $this->tester->getFacade()->addSynchronizationMessageToBuffer($synchronizationMessageTransfer);

        // Act
        $this->tester->getFacade()->flushSynchronizationMessagesFromBuffer();
    }

    /**
     * @return void
     */
    public function testShouldDeleteAndWriteBulkStorageAndSearchMessages(): void
    {
        // Assert
        $this->assertBulkMessageProcessing('write-delete');

        // Arrange
        $synchronizationMessageTransfer = (new SynchronizationMessageTransfer())
            ->fromArray($this->tester->createFakeSynchronizationMessage('storage', 'sync.storage.product', 'delete'));

        $this->tester->getFacade()->addSynchronizationMessageToBuffer($synchronizationMessageTransfer);

        $synchronizationMessageTransfer = (new SynchronizationMessageTransfer())
            ->fromArray($this->tester->createFakeSynchronizationMessage('storage', 'sync.storage.product', 'write'));

        $this->tester->getFacade()->addSynchronizationMessageToBuffer($synchronizationMessageTransfer);

        $synchronizationMessageTransfer = (new SynchronizationMessageTransfer())
            ->fromArray($this->tester->createFakeSynchronizationMessage('search', 'sync.search.product', 'delete'));

        $this->tester->getFacade()->addSynchronizationMessageToBuffer($synchronizationMessageTransfer);

        $synchronizationMessageTransfer = (new SynchronizationMessageTransfer())
            ->fromArray($this->tester->createFakeSynchronizationMessage('search', 'sync.search.product', 'write'));

        $this->tester->getFacade()->addSynchronizationMessageToBuffer($synchronizationMessageTransfer);

        // Act
        $this->tester->getFacade()->flushSynchronizationMessagesFromBuffer();
    }

    /**
     * @return void
     */
    public function testShouldSkipSynchronizationWhenMessagesAreEmpty(): void
    {
        // Assert
        $this->assertBulkMessageProcessing('skip');

        // Act
        $this->tester->getFacade()->flushSynchronizationMessagesFromBuffer();
    }

    /**
     * @return void
     */
    public function testShouldCatchExceptionWhenSynchronizationForDestinationTypeNotFound(): void
    {
        // Assert
        $this->assertBulkMessageProcessing('skip', true);

        // Arrange
        $synchronizationMessageTransfer = (new SynchronizationMessageTransfer())
            ->fromArray($this->tester->createFakeSynchronizationMessage('unknown', 'sync.unknown.product', 'write'));

        $this->tester->getFacade()->addSynchronizationMessageToBuffer($synchronizationMessageTransfer);

        // Act
        $this->tester->getFacade()->flushSynchronizationMessagesFromBuffer();
    }

    /**
     * @return void
     */
    public function testShouldSkipSynchronizationForNotFoundOperationType(): void
    {
        // Assert
        $this->assertBulkMessageProcessing('skip');

        // Arrange
        $synchronizationMessageTransfer = (new SynchronizationMessageTransfer())
            ->fromArray($this->tester->createFakeSynchronizationMessage('storage', 'sync.storage.product', 'unknown'));

        $this->tester->getFacade()->addSynchronizationMessageToBuffer($synchronizationMessageTransfer);

        // Act
        $this->tester->getFacade()->flushSynchronizationMessagesFromBuffer();
    }

    /**
     * @param string $operationType
     * @param bool $expectSendToQueue
     *
     * @return void
     */
    protected function assertBulkMessageProcessing(
        string $operationType,
        bool $expectSendToQueue = false
    ): void {
        $storageManagerMock = $this->createMock(SynchronizationStorage::class);
        $storageManagerMock->method('isDestinationTypeApplicable')
            ->willReturnCallback(function (string $destinationType) {
                return $destinationType === 'storage';
            });

        $searchManagerMock = $this->createMock(SynchronizationSearch::class);
        $searchManagerMock->method('isDestinationTypeApplicable')
            ->willReturnCallback(function (string $destinationType) {
                return $destinationType === 'search';
            });

        $queueClientMock = $this->createMock(SynchronizationToQueueClientInterface::class);

        $operationMethods = [
            'write' => ['write' => true, 'delete' => false],
            'delete' => ['write' => false, 'delete' => true],
            'write-delete' => ['write' => true, 'delete' => true],
            'skip' => ['write' => false, 'delete' => false],
        ];

        $methods = $operationMethods[$operationType];
        $storageManagerMock->expects($methods['write'] ? $this->once() : $this->never())->method('writeBulk');
        $storageManagerMock->expects($methods['delete'] ? $this->once() : $this->never())->method('deleteBulk');
        $searchManagerMock->expects($methods['write'] ? $this->once() : $this->never())->method('writeBulk');
        $searchManagerMock->expects($methods['delete'] ? $this->once() : $this->never())->method('deleteBulk');
        $queueClientMock->expects($expectSendToQueue ? $this->once() : $this->never())->method('sendMessages');

        $this->tester->mockFactoryMethod('createStorageManager', $storageManagerMock);
        $this->tester->mockFactoryMethod('createSearchManager', $searchManagerMock);
        $this->tester->mockFactoryMethod('getQueueClient', $queueClientMock);
    }
}
