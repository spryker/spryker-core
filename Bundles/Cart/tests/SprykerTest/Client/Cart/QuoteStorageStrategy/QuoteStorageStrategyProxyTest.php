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
    public function testAddItemShouldForwardCallToSubject(): void
    {
        $this->assertCallForwardedToSubject('addItem', [new ItemTransfer()], QuoteTransfer::class);
    }

    /**
     * @return void
     */
    public function testAddItemsShouldForwardCallToSubject(): void
    {
        $this->assertCallForwardedToSubject('addItems', [[new ItemTransfer()]], QuoteTransfer::class);
    }

    /**
     * @return void
     */
    public function testAddValidItemsShouldForwardCallToSubjectAndNotAddMessageForEditableQuote(): void
    {
        $this->assertCallForwardedToSubject('addValidItems', [new CartChangeTransfer()], QuoteTransfer::class);
    }

    /**
     * @return void
     */
    public function testValidateQuoteShouldForwardCallToSubject(): void
    {
        $this->assertCallForwardedToSubject(
            'validateQuote',
            [],
            QuoteResponseTransfer::class
        );
    }

    /**
     * @return void
     */
    public function testGetStorageStrategyShouldForwardCallToSubject(): void
    {
        $this->assertCallForwardedToSubject(
            'getStorageStrategy',
            [],
            'string'
        );
    }

    /**
     * @return void
     */
    public function testRemoveItemShouldForwardCallToSubjectAndNotAddMessageForNotLockedQuote(): void
    {
        $this->haveNotLockedQuote();
        $this->expectsErrorMessageNotAdded();
        $this->assertCallForwardedToSubject(
            'removeItem',
            ["sku"],
            QuoteTransfer::class
        );
    }

    /**
     * @return void
     */
    public function testRemoveItemShouldNotForwardCallToSubjectAndAddMessageForLockedQuote(): void
    {
        $this->haveLockedQuote();
        $this->expectsErrorMessageAdded();
        $this->assertCallNotForwardedToSubject(
            'removeItem',
            ["sku"],
            QuoteTransfer::class
        );
    }

    /**
     * @return void
     */
    public function testRemoveItemsShouldForwardCallToSubjectAndNotAddMessageForNotLockedQuote(): void
    {
        $this->haveNotLockedQuote();
        $this->expectsErrorMessageNotAdded();
        $this->assertCallForwardedToSubject(
            'removeItems',
            [new ArrayObject()],
            QuoteTransfer::class
        );
    }

    /**
     * @return void
     */
    public function testRemoveItemsShouldNotForwardCallToSubjectAndAddMessageForLockedQuote(): void
    {
        $this->haveLockedQuote();
        $this->expectsErrorMessageAdded();
        $this->assertCallNotForwardedToSubject(
            'removeItems',
            [new ArrayObject()],
            QuoteTransfer::class
        );
    }

    /**
     * @return void
     */
    public function testChangeItemQuantityShouldForwardCallToSubjectAndNotAddMessageForNotLockedQuote(): void
    {
        $this->haveNotLockedQuote();
        $this->expectsErrorMessageNotAdded();
        $this->assertCallForwardedToSubject(
            'changeItemQuantity',
            ['sku', null, 1],
            QuoteTransfer::class
        );
    }

    /**
     * @return void
     */
    public function testChangeItemQuantityShouldNotForwardCallToSubjectAndAddMessageForLockedQuote(): void
    {
        $this->haveLockedQuote();
        $this->expectsErrorMessageAdded();
        $this->assertCallNotForwardedToSubject(
            'changeItemQuantity',
            ['sku', null, 1],
            QuoteTransfer::class
        );
    }

    /**
     * @return void
     */
    public function testDecreaseItemQuantityShouldForwardCallToSubjectAndNotAddMessageForNotLockedQuote(): void
    {
        $this->haveNotLockedQuote();
        $this->expectsErrorMessageNotAdded();
        $this->assertCallForwardedToSubject(
            'decreaseItemQuantity',
            ['sku', null, 1],
            QuoteTransfer::class
        );
    }

    /**
     * @return void
     */
    public function testDecreaseItemQuantityShouldNotForwardCallToSubjectAndAddMessageForLockedQuote(): void
    {
        $this->haveLockedQuote();
        $this->expectsErrorMessageAdded();
        $this->assertCallNotForwardedToSubject(
            'decreaseItemQuantity',
            ['sku', null, 1],
            QuoteTransfer::class
        );
    }

    /**
     * @return void
     */
    public function testIncreaseItemQuantityShouldForwardCallToSubjectAndNotAddMessageForNotLockedQuote(): void
    {
        $this->haveNotLockedQuote();
        $this->expectsErrorMessageNotAdded();
        $this->assertCallForwardedToSubject(
            'increaseItemQuantity',
            ['sku', null, 1],
            QuoteTransfer::class
        );
    }

    /**
     * @return void
     */
    public function testIncreaseItemQuantityShouldNotForwardCallToSubjectAndAddMessageForLockedQuote(): void
    {
        $this->haveLockedQuote();
        $this->expectsErrorMessageAdded();
        $this->assertCallNotForwardedToSubject(
            'increaseItemQuantity',
            ['sku', null, 1],
            QuoteTransfer::class
        );
    }

    /**
     * @return void
     */
    public function testReloadItemsShouldForwardCallToSubjectAndNotAddMessageForNotLockedQuote(): void
    {
        $this->haveNotLockedQuote();
        $this->expectsErrorMessageNotAdded();
        $this->assertCallForwardedToSubject(
            'increaseItemQuantity',
            ['sku', null, 1],
            QuoteTransfer::class
        );
    }

    /**
     * @return void
     */
    public function testReloadItemsShouldNotForwardCallToSubjectAndAddMessageForLockedQuote(): void
    {
        $this->haveLockedQuote();
        $this->expectsErrorMessageAdded();
        $this->assertCallNotForwardedToSubject('reloadItems', []);
    }

    /**
     * @return void
     */
    public function testSetQuoteCurrencyShouldForwardCallToSubjectAndNotAddMessageForNotLockedQuote(): void
    {
        $this->haveNotLockedQuote();
        $this->expectsErrorMessageNotAdded();
        $this->assertCallForwardedToSubject(
            'setQuoteCurrency',
            [new CurrencyTransfer()],
            QuoteResponseTransfer::class
        );
    }

    /**
     * @return void
     */
    public function testSetQuoteCurrencyShouldNotForwardCallToSubjectAndAddMessageForLockedQuote(): void
    {
        $this->haveLockedQuote();
        $this->expectsErrorMessageAdded();
        $this->assertCallNotForwardedToSubject(
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
    protected function assertCallNotForwardedToSubject(
        string $methodName,
        $parameters,
        ?string $expectedResultType = null
    ): void {
        // Assign
        $this->quoteStorageStrategyMock->method($methodName)->willReturn(
            class_exists($expectedResultType) ? new $expectedResultType() : $expectedResultType
        );

        //Assert
        $this->quoteStorageStrategyMock->expects($this->never())->method($methodName);

        call_user_func_array([$this->quoteStorageStrategyProxy, $methodName], $parameters);
    }

    /**
     * @param string $methodName
     * @param mixed $parameters
     * @param string|null $expectedResultType
     *
     * @return void
     */
    protected function assertCallForwardedToSubject(
        string $methodName,
        $parameters,
        ?string $expectedResultType = null
    ): void {
        // Assign
        $this->quoteStorageStrategyMock->method($methodName)->willReturn(
            class_exists($expectedResultType) ? new $expectedResultType() : $expectedResultType
        );

        //Assert
        $this->quoteStorageStrategyMock->expects($this->once())->method($methodName);

        //Act
        call_user_func_array([$this->quoteStorageStrategyProxy, $methodName], $parameters);
    }

    /**
     * @return void
     */
    protected function haveLockedQuote(): void
    {
        $this->quoteClientMock->method('getQuote')
            ->willReturn(new QuoteTransfer());
        $this->quoteClientMock->method('isQuoteLocked')
            ->willReturn(true);
    }

    /**
     * @return void
     */
    protected function haveNotLockedQuote(): void
    {
        $this->quoteClientMock->method('getQuote')
            ->willReturn(new QuoteTransfer());
        $this->quoteClientMock->method('isQuoteLocked')
            ->willReturn(false);
    }
}
