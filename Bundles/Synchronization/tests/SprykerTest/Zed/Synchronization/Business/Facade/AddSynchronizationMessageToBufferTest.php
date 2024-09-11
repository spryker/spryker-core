<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Synchronization\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\SynchronizationMessageTransfer;
use Spryker\Zed\Synchronization\Business\Synchronizer\InMemoryMessageSynchronizer;
use SprykerTest\Zed\Synchronization\SynchronizationBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Synchronization
 * @group Business
 * @group Facade
 * @group AddSynchronizationMessageToBufferTest
 * Add your own group annotations below this line
 */
class AddSynchronizationMessageToBufferTest extends Unit
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
    }

    /**
     * @return void
     */
    public function testShouldAddSyncMessageToInMemoryStorage(): void
    {
        // Arrange
        $message = $this->tester->createFakeSynchronizationMessage('storage', 'sync.storage.product', 'write');
        $synchronizationMessageTransfer = (new SynchronizationMessageTransfer())->fromArray($message);

        // Act
        $this->tester->getFacade()->addSynchronizationMessageToBuffer($synchronizationMessageTransfer);

        // Assert
        $messagesInMemory = $this->tester->getStaticVariable(InMemoryMessageSynchronizer::class, 'messages');

        $this->assertMessageKey($messagesInMemory);
        $this->assertSame($message, $messagesInMemory['storage']['sync.storage.product']['write'][0]->toArray());
    }

    /**
     * @return void
     */
    public function testShouldAddDifferentSyncMessagesToInMemoryStorage(): void
    {
        // Arrange
        $message1 = $this->tester->createFakeSynchronizationMessage('storage', 'sync.storage.product', 'write');
        $synchronizationMessageTransfer1 = (new SynchronizationMessageTransfer())->fromArray($message1);

        $message2 = $this->tester->createFakeSynchronizationMessage('storage', 'sync.storage.product', 'write');
        $synchronizationMessageTransfer2 = (new SynchronizationMessageTransfer())->fromArray($message2);

        $message3 = $this->tester->createFakeSynchronizationMessage('search', 'sync.search.product', 'delete');
        $synchronizationMessageTransfer3 = (new SynchronizationMessageTransfer())->fromArray($message3);

        // Act
        $this->tester->getFacade()->addSynchronizationMessageToBuffer($synchronizationMessageTransfer1);
        $this->tester->getFacade()->addSynchronizationMessageToBuffer($synchronizationMessageTransfer2);
        $this->tester->getFacade()->addSynchronizationMessageToBuffer($synchronizationMessageTransfer3);

        // Assert
        $messagesInMemory = $this->tester->getStaticVariable(InMemoryMessageSynchronizer::class, 'messages');

        $this->assertSame($message1, $messagesInMemory['storage']['sync.storage.product']['write'][0]->toArray());
        $this->assertSame($message2, $messagesInMemory['storage']['sync.storage.product']['write'][1]->toArray());
        $this->assertSame($message3, $messagesInMemory['search']['sync.search.product']['delete'][0]->toArray());
    }

    /**
     * @return void
     */
    public function testShouldNotAddSyncMessageToInMemoryStorageWithWrongOperationType(): void
    {
        // Arrange
        $message = $this->tester->createFakeSynchronizationMessage('storage', 'sync.storage.product', 'reset');
        $synchronizationMessageTransfer = (new SynchronizationMessageTransfer())->fromArray($message);

        // Act
        $this->tester->getFacade()->addSynchronizationMessageToBuffer($synchronizationMessageTransfer);

        // Assert
        $this->assertEmpty($this->tester->getStaticVariable(InMemoryMessageSynchronizer::class, 'messages'));
    }

    /**
     * @param array<mixed> $messagesInMemory
     *
     * @return void
     */
    protected function assertMessageKey(array $messagesInMemory): void
    {
        $this->assertArrayHasKey('storage', $messagesInMemory);
        $this->assertArrayHasKey('sync.storage.product', $messagesInMemory['storage']);
        $this->assertArrayHasKey('write', $messagesInMemory['storage']['sync.storage.product']);
    }
}
