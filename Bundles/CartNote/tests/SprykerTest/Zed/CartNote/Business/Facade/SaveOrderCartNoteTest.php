<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CartNote\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;
use SprykerTest\Zed\CartNote\CartNoteBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CartNote
 * @group Business
 * @group Facade
 * @group SaveOrderCartNoteTest
 * Add your own group annotations below this line
 */
class SaveOrderCartNoteTest extends Unit
{
    /**
     * @var string
     */
    protected const DEFAULT_OMS_PROCESS_NAME = 'Test01';

    /**
     * @var string
     */
    protected const TEST_CART_NOTE_1 = 'test cart note 1';

    /**
     * @var string
     */
    protected const TEST_CART_NOTE_2 = 'test cart note 2';

    /**
     * @var \SprykerTest\Zed\CartNote\CartNoteBusinessTester
     */
    protected CartNoteBusinessTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->configureTestStateMachine([static::DEFAULT_OMS_PROCESS_NAME]);
    }

    /**
     * @return void
     */
    public function testThrowsNullValueExceptionWhenIdSalesOrderIsNotSetInSaveOrderTransfer(): void
    {
        // Assert
        $this->expectException(NullValueException::class);
        $this->expectExceptionMessage('Property "idSalesOrder" of transfer `Generated\Shared\Transfer\SaveOrderTransfer` is null.');

        // Act
        $this->tester->getFacade()->saveOrderCartNote(new QuoteTransfer(), new SaveOrderTransfer(), true);
    }

    /**
     * @dataProvider updatesCartNoteInDatabaseDataProvider
     *
     * @param string|null $cartNoteToUpdate
     * @param string|null $savedCartNote
     * @param string $expectedCartNote
     *
     * @return void
     */
    public function testUpdatesCartNoteInDatabase(
        ?string $cartNoteToUpdate,
        ?string $savedCartNote,
        string $expectedCartNote
    ): void {
        // Arrange
        $quoteTransfer = (new QuoteTransfer())->setCartNote($cartNoteToUpdate);
        $saveOrderTransfer = $this->tester->haveOrder([], static::DEFAULT_OMS_PROCESS_NAME);
        $this->tester->updateCartNote($saveOrderTransfer->getIdSalesOrderOrFail(), $savedCartNote);

        // Act
        $this->tester->getFacade()->saveOrderCartNote($quoteTransfer, $saveOrderTransfer, true);

        // Assert
        $this->assertSame(
            $expectedCartNote,
            $this->tester->findOrderCartNote($saveOrderTransfer->getIdSalesOrderOrFail()),
        );
    }

    /**
     * @return array<string, list<string|null>>
     */
    protected function updatesCartNoteInDatabaseDataProvider(): array
    {
        return [
            'Cart note: is updated with string, null in DB' => [
                static::TEST_CART_NOTE_1,
                null,
                static::TEST_CART_NOTE_1,
            ],
            'Cart note: is updated with null, null in DB' => [
                null,
                null,
                '',
            ],
            'Cart note: is updated with empty string, null in DB' => [
                '',
                null,
                '',
            ],
            'Cart note: is updated with string, string in DB' => [
                static::TEST_CART_NOTE_1,
                static::TEST_CART_NOTE_2,
                static::TEST_CART_NOTE_1,
            ],
            'Cart note: is updated with null, string in DB' => [
                null,
                static::TEST_CART_NOTE_2,
                '',
            ],
            'Cart note: is updated with empty string, string in DB' => [
                '',
                static::TEST_CART_NOTE_2,
                '',
            ],
            'Cart note: is updated with string, empty string in DB' => [
                static::TEST_CART_NOTE_1,
                '',
                static::TEST_CART_NOTE_1,
            ],
            'Cart note: is updated with null, empty string in DB' => [
                null,
                '',
                '',
            ],
            'Cart note: is updated with empty string, empty string in DB' => [
                '',
                '',
                '',
            ],
        ];
    }
}
