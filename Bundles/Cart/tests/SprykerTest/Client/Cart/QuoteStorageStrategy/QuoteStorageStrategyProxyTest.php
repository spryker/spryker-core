<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\Cart\QuoteStorageStrategy;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Cart\Dependency\Client\CartToMessengerClientInterface;
use Spryker\Client\Cart\Dependency\Client\CartToQuoteInterface;
use Spryker\Client\Cart\QuoteStorageStrategy\QuoteStorageStrategyProxy;
use Spryker\Client\CartExtension\Dependency\Plugin\QuoteStorageStrategyPluginInterface;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Client
 * @group Cart
 * @group QuoteStorageStrategy
 * @group QuoteStorageStrategyProxyTest
 * Add your own group annotations below this line
 */
class QuoteStorageStrategyProxyTest extends Unit
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $quoteClientMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $messengerClientMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $quoteStorageStrategyMock;

    /**
     * @var \Spryker\Client\Cart\QuoteStorageStrategy\QuoteStorageStrategyProxy
     */
    protected $quoteStorageStrategyProxy;

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->messengerClientMock = $this->createMock(CartToMessengerClientInterface::class);
        $this->quoteClientMock = $this->createMock(CartToQuoteInterface::class);
        $this->quoteStorageStrategyMock = $quoteStorageStrategy = $this->createMock(QuoteStorageStrategyPluginInterface::class);

        $this->quoteStorageStrategyProxy = new QuoteStorageStrategyProxy(
            $this->messengerClientMock,
            $this->quoteClientMock,
            $this->quoteStorageStrategyMock
        );
    }

    /**
     * @return void
     */
    public function testAddItemShouldForwardCallToSubjectAndNotAddMessageForEditableQuote(): void
    {
        $this->expectsErrorMessageNotAdded();
        $this->assertCallForwaredAndMessageNotAddedForEditableQuote(
            'addItem',
            [new ItemTransfer()],
            QuoteTransfer::class
        );
    }

    /**
     * @return void
     */
    public function testAddItemShouldNotForwardCallToSubjectAndAddMessageForNotEditableQuote(): void
    {
        $this->expectsErrorMessageAdded();
        $this->assertCallNotForwaredAndMessageAddedForNotEditableQuote(
            'addItem',
            [new ItemTransfer()],
            QuoteTransfer::class
        );
    }

    /**
     * @return void
     */
    public function testAddItemsShouldForwardCallToSubjectAndNotAddMessageForEditableQuote(): void
    {
        $this->expectsErrorMessageNotAdded();
        $this->assertCallForwaredAndMessageNotAddedForEditableQuote(
            'addItems',
            [[new ItemTransfer()]],
            QuoteTransfer::class
        );
    }

    /**
     * @return void
     */
    public function testAddItemsShouldNotForwardCallToSubjectAndAddMessageForNotEditableQuote(): void
    {
        $this->expectsErrorMessageAdded();
        $this->assertCallNotForwaredAndMessageAddedForNotEditableQuote(
            'addItems',
            [[new ItemTransfer()]],
            QuoteTransfer::class
        );
    }

    /**
     * @return void
     */
    public function testAddValidItemsShouldForwardCallToSubjectAndNotAddMessageForEditableQuote(): void
    {
        $this->expectsErrorMessageNotAdded();
        $this->assertCallForwaredAndMessageNotAddedForEditableQuote(
            'addValidItems',
            [new CartChangeTransfer()],
            QuoteTransfer::class
        );
    }

    /**
     * @return void
     */
    public function testAddValidItemsShouldNotForwardCallToSubjectAndAddMessageForNotEditableQuote(): void
    {
        $this->expectsErrorMessageAdded();
        $this->assertCallNotForwaredAndMessageAddedForNotEditableQuote(
            'addValidItems',
            [new CartChangeTransfer()],
            QuoteTransfer::class
        );
    }

    /**
     * @return void
     */
    public function testRemoveItemShouldForwardCallToSubjectAndNotAddMessageForEditableQuote(): void
    {
        $this->expectsErrorMessageNotAdded();
        $this->assertCallForwaredAndMessageNotAddedForEditableQuote(
            'removeItem',
            ["sku"],
            QuoteTransfer::class
        );
    }

    /**
     * @return void
     */
    public function testRemoveItemShouldNotForwardCallToSubjectAndAddMessageForNotEditableQuote(): void
    {
        $this->expectsErrorMessageAdded();
        $this->assertCallNotForwaredAndMessageAddedForNotEditableQuote(
            'removeItem',
            ["sku"],
            QuoteTransfer::class
        );
    }

    /**
     * @return void
     */
    public function testRemoveItemsShouldForwardCallToSubjectAndNotAddMessageForEditableQuote(): void
    {
        $this->expectsErrorMessageNotAdded();
        $this->assertCallForwaredAndMessageNotAddedForEditableQuote(
            'removeItems',
            [new ArrayObject()],
            QuoteTransfer::class
        );
    }

    /**
     * @return void
     */
    public function testRemoveItemsShouldNotForwardCallToSubjectAndAddMessageForNotEditableQuote(): void
    {
        $this->expectsErrorMessageAdded();
        $this->assertCallNotForwaredAndMessageAddedForNotEditableQuote(
            'removeItems',
            [new ArrayObject()],
            QuoteTransfer::class
        );
    }

    /**
     * @return void
     */
    public function testChangeItemQuantityShouldForwardCallToSubjectAndNotAddMessageForEditableQuote(): void
    {
        $this->expectsErrorMessageNotAdded();
        $this->assertCallForwaredAndMessageNotAddedForEditableQuote(
            'changeItemQuantity',
            ['sku', null, 1],
            QuoteTransfer::class
        );
    }

    /**
     * @return void
     */
    public function testChangeItemQuantityShouldNotForwardCallToSubjectAndAddMessageForNotEditableQuote(): void
    {
        $this->expectsErrorMessageAdded();
        $this->assertCallNotForwaredAndMessageAddedForNotEditableQuote(
            'changeItemQuantity',
            ['sku', null, 1],
            QuoteTransfer::class
        );
    }

    /**
     * @return void
     */
    public function testDecreaseItemQuantityShouldForwardCallToSubjectAndNotAddMessageForEditableQuote(): void
    {
        $this->expectsErrorMessageNotAdded();
        $this->assertCallForwaredAndMessageNotAddedForEditableQuote(
            'decreaseItemQuantity',
            ['sku', null, 1],
            QuoteTransfer::class
        );
    }

    /**
     * @return void
     */
    public function testDecreaseItemQuantityShouldNotForwardCallToSubjectAndAddMessageForNotEditableQuote(): void
    {
        $this->expectsErrorMessageAdded();
        $this->assertCallNotForwaredAndMessageAddedForNotEditableQuote(
            'decreaseItemQuantity',
            ['sku', null, 1],
            QuoteTransfer::class
        );
    }

    /**
     * @return void
     */
    public function testIncreaseItemQuantityShouldForwardCallToSubjectAndNotAddMessageForEditableQuote(): void
    {
        $this->expectsErrorMessageNotAdded();
        $this->assertCallForwaredAndMessageNotAddedForEditableQuote(
            'increaseItemQuantity',
            ['sku', null, 1],
            QuoteTransfer::class
        );
    }

    /**
     * @return void
     */
    public function testIncreaseItemQuantityShouldNotForwardCallToSubjectAndAddMessageForNotEditableQuote(): void
    {
        $this->expectsErrorMessageAdded();
        $this->assertCallNotForwaredAndMessageAddedForNotEditableQuote(
            'increaseItemQuantity',
            ['sku', null, 1],
            QuoteTransfer::class
        );
    }

    /**
     * @return void
     */
    public function testReloadItemsShouldForwardCallToSubjectAndNotAddMessageForEditableQuote(): void
    {
        $this->expectsErrorMessageNotAdded();
        $this->assertCallForwaredAndMessageNotAddedForEditableQuote(
            'increaseItemQuantity',
            ['sku', null, 1],
            QuoteTransfer::class
        );
    }

    /**
     * @return void
     */
    public function testReloadItemsShouldNotForwardCallToSubjectAndAddMessageForNotEditableQuote(): void
    {
        $this->expectsErrorMessageAdded();
        $this->assertCallNotForwaredAndMessageAddedForNotEditableQuote('reloadItems', []);
    }

    /**
     * @return void
     */
    public function testValidateQuoteShouldForwardCallToSubjectAndNotAddMessageForEditableQuote(): void
    {
        $this->expectsErrorMessageNotAdded();
        $this->assertCallForwaredAndMessageNotAddedForEditableQuote(
            'validateQuote',
            [],
            QuoteResponseTransfer::class
        );
    }

    /**
     * @return void
     */
    public function testValidateQuoteShouldNotForwardCallToSubjectAndAddMessageForNotEditableQuote(): void
    {
        $this->expectsErrorMessageNotAdded();
        $this->assertCallNotForwaredAndMessageAddedForNotEditableQuote(
            'validateQuote',
            [],
            QuoteResponseTransfer::class
        );
    }

    /**
     * @return void
     */
    public function testSetQuoteCurrencyShouldForwardCallToSubjectAndNotAddMessageForEditableQuote(): void
    {
        $this->expectsErrorMessageNotAdded();
        $this->assertCallForwaredAndMessageNotAddedForEditableQuote(
            'setQuoteCurrency',
            [new CurrencyTransfer()],
            QuoteResponseTransfer::class
        );
    }

    /**
     * @return void
     */
    public function testSetQuoteCurrencyShouldNotForwardCallToSubjectAndAddMessageForNotEditableQuote(): void
    {
        $this->expectsErrorMessageAdded();
        $this->assertCallNotForwaredAndMessageAddedForNotEditableQuote(
            'setQuoteCurrency',
            [new CurrencyTransfer()],
            QuoteResponseTransfer::class
        );
    }

    /**
     * @return void
     */
    protected function expectsErrorMessageNotAdded(): void
    {
        $this->messengerClientMock->expects($this->never())->method($this->anything());
    }

    /**
     * @return void
     */
    protected function expectsErrorMessageAdded(): void
    {
        $this->messengerClientMock->expects($this->once())->method('addErrorMessage');
    }

    /**
     * @param string $methodName
     * @param mixed $parameters
     * @param string|null $expectedResultType
     *
     * @return void
     */
    protected function assertCallForwaredAndMessageNotAddedForEditableQuote(
        string $methodName,
        $parameters,
        ?string $expectedResultType = null
    ): void {
        // Assign
        $this->quoteStorageStrategyMock->method($methodName)->willReturn(
            $expectedResultType === null ?: (new $expectedResultType())
        );

        $quoteTransfer = new QuoteTransfer();
        $quoteTransfer->setIsLocked(false);

        $this->quoteClientMock->method('getQuote')->willReturn($quoteTransfer);

        //Assert
        $this->quoteStorageStrategyMock->expects($this->once())->method($methodName);

        //Act
        $result = call_user_func_array([$this->quoteStorageStrategyProxy, $methodName], $parameters);

        //Assert
        if ($expectedResultType !== null) {
            $this->assertInstanceOf($expectedResultType, $result);
        }
    }

    /**
     * @param string $methodName
     * @param mixed $parameters
     * @param string|null $expectedResultType
     *
     * @return void
     */
    protected function assertCallNotForwaredAndMessageAddedForNotEditableQuote(
        string $methodName,
        $parameters,
        ?string $expectedResultType = null
    ): void {
        // Assign

        $quoteTransfer = new QuoteTransfer();
        $quoteTransfer->setCustomer(new CustomerTransfer());
        $quoteTransfer->setIsLocked(true);

        $this->quoteClientMock->method('getQuote')
            ->willReturn($quoteTransfer);

        //Assert
        $this->quoteStorageStrategyMock->expects($this->never())->method($methodName);

        //Act
        $result = call_user_func_array([$this->quoteStorageStrategyProxy, $methodName], $parameters);

        //Assert
        if ($expectedResultType !== null) {
            $this->assertInstanceOf($expectedResultType, $result);
        }
    }
}
