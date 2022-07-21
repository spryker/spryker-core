<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Cart\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;
use SprykerTest\Zed\Cart\CartBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Cart
 * @group Business
 * @group Facade
 * @group ExpandItemGroupKeysWithCartIdentifierTest
 * Add your own group annotations below this line
 */
class ExpandItemGroupKeysWithCartIdentifierTest extends Unit
{
    /**
     * @var int
     */
    protected const FAKE_ID_QUOTE = 12345;

    /**
     * @var string
     */
    protected const FAKE_GROUP_KEY = 'FAKE_GROUP_KEY';

    /**
     * @var \SprykerTest\Zed\Cart\CartBusinessTester
     */
    protected CartBusinessTester $tester;

    /**
     * @return void
     */
    public function testExpandItemGroupKeysWithCartIdentifierExpandsGroupKeyWithQuoteIdentifier(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteTransfer())->setIdQuote(static::FAKE_ID_QUOTE);
        $itemTransfer = (new ItemTransfer())->setGroupKey(static::FAKE_GROUP_KEY);

        $cartChangeTrnafer = (new CartChangeTransfer())
            ->setQuote($quoteTransfer)
            ->addItem($itemTransfer);

        // Act
        $cartChangeTrnafer = $this->tester->getFacade()->expandItemGroupKeysWithCartIdentifier($cartChangeTrnafer);

        // Assert
        $this->assertNotSame(static::FAKE_GROUP_KEY, $cartChangeTrnafer->getItems()->offsetGet(0)->getGroupKey());
        $this->assertSame(
            sprintf('%s_%s', static::FAKE_GROUP_KEY, hash('md5', (string)static::FAKE_ID_QUOTE)),
            $cartChangeTrnafer->getItems()->offsetGet(0)->getGroupKey(),
        );
    }

    /**
     * @return void
     */
    public function testExpandItemGroupKeysWithCartIdentifierDoesNotExpandGroupKeysForQuoteWithoutId(): void
    {
        // Arrange
        $itemTransfer = (new ItemTransfer())->setGroupKey(static::FAKE_GROUP_KEY);

        $cartChangeTrnafer = (new CartChangeTransfer())
            ->setQuote(new QuoteTransfer())
            ->addItem($itemTransfer);

        // Act
        $cartChangeTrnafer = $this->tester->getFacade()->expandItemGroupKeysWithCartIdentifier($cartChangeTrnafer);

        // Assert
        $this->assertSame(static::FAKE_GROUP_KEY, $cartChangeTrnafer->getItems()->offsetGet(0)->getGroupKey());
    }

    /**
     * @return void
     */
    public function testExpandItemGroupKeysWithCartIdentifierDoesNotExpandGroupKeysWithoutQuote(): void
    {
        // Arrange
        $itemTransfer = (new ItemTransfer())->setGroupKey(static::FAKE_GROUP_KEY);

        $cartChangeTrnafer = (new CartChangeTransfer())
            ->setQuote(null)
            ->addItem($itemTransfer);

        // Act
        $cartChangeTrnafer = $this->tester->getFacade()->expandItemGroupKeysWithCartIdentifier($cartChangeTrnafer);

        // Assert
        $this->assertSame(static::FAKE_GROUP_KEY, $cartChangeTrnafer->getItems()->offsetGet(0)->getGroupKey());
    }

    /**
     * @return void
     */
    public function testExpandItemGroupKeysWithCartIdentifierThrowsNullValueExceptionForItemWithoutGroupKey(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteTransfer())->setIdQuote(static::FAKE_ID_QUOTE);

        $cartChangeTrnafer = (new CartChangeTransfer())
            ->setQuote($quoteTransfer)
            ->addItem(new ItemTransfer());

        // Assert
        $this->expectException(NullValueException::class);

        // Act
        $this->tester->getFacade()->expandItemGroupKeysWithCartIdentifier($cartChangeTrnafer);
    }
}
