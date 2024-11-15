<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PersistentCart\Communication\Plugin\CartReorder;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CartReorderTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\PersistentCart\Communication\Plugin\CartReorder\UpdateQuoteCartPostReorderPlugin;
use SprykerTest\Zed\PersistentCart\PersistentCartCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PersistentCart
 * @group Communication
 * @group Plugin
 * @group CartReorder
 * @group UpdateQuoteCartPostReorderPluginTest
 * Add your own group annotations below this line
 */
class UpdateQuoteCartPostReorderPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const CART_NAME = 'CART_NAME';

    /**
     * @var int
     */
    protected const FAKE_ID_QUOTE = 3141512;

    /**
     * @var \SprykerTest\Zed\PersistentCart\PersistentCartCommunicationTester
     */
    protected PersistentCartCommunicationTester $tester;

    /**
     * @return void
     */
    public function testShouldPersistChangesForReorderedCart(): void
    {
        // Arrange
        $quoteTransfer = $this->createCustomerQuote();
        $idQuote = $quoteTransfer->getIdQuoteOrFail();

        $quoteTransfer->setName(static::CART_NAME);

        // Act
        $cartReorderTransfer = (new UpdateQuoteCartPostReorderPlugin())->postReorder(
            (new CartReorderTransfer())->setQuote($quoteTransfer),
        );

        // Assert
        $this->assertSame(static::CART_NAME, $cartReorderTransfer->getQuoteOrFail()->getName());
        $this->assertSame(static::CART_NAME, $this->tester->getQuoteFromPersistenceByIdQuote($idQuote)->getName());
    }

    /**
     * @return void
     */
    public function testShouldNotPersistChangesForReorderedCart(): void
    {
        // Arrange
        $quoteTransfer = $this->createCustomerQuote();
        $idQuote = $quoteTransfer->getIdQuoteOrFail();

        $quoteTransfer
            ->setIdQuote(null)
            ->setName(static::CART_NAME);

        // Act
        $cartReorderTransfer = (new UpdateQuoteCartPostReorderPlugin())->postReorder(
            (new CartReorderTransfer())->setQuote($quoteTransfer),
        );

        // Assert
        $this->assertSame(static::CART_NAME, $cartReorderTransfer->getQuoteOrFail()->getName());
        $this->assertNotSame(static::CART_NAME, $this->tester->getQuoteFromPersistenceByIdQuote($idQuote)->getName());
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createCustomerQuote(): QuoteTransfer
    {
        return $this->tester->havePersistentQuote([
            QuoteTransfer::CUSTOMER => $this->tester->haveCustomer(),
            QuoteTransfer::STORE => [StoreTransfer::NAME => 'DE'],
            QuoteTransfer::ITEMS => [
                [ItemTransfer::SKU => 'fake_sku_1', ItemTransfer::GROUP_KEY => 'fake_sku_1', ItemTransfer::QUANTITY => 5],
                [ItemTransfer::SKU => 'fake_sku_2', ItemTransfer::GROUP_KEY => 'fake_sku_2', ItemTransfer::QUANTITY => 5],
                [ItemTransfer::SKU => 'fake_sku_3', ItemTransfer::GROUP_KEY => 'fake_sku_3', ItemTransfer::QUANTITY => 5],
            ],
        ]);
    }
}
