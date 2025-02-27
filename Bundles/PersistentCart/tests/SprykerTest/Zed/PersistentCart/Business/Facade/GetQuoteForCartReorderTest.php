<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PersistentCart\Business\Facade;

use Codeception\Stub;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\CartReorderRequestTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;
use Spryker\Zed\Quote\QuoteDependencyProvider;
use Spryker\Zed\QuoteExtension\Dependency\Plugin\QuoteWritePluginInterface;
use SprykerTest\Zed\PersistentCart\PersistentCartBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PersistentCart
 * @group Business
 * @group Facade
 * @group GetQuoteForCartReorderTest
 * Add your own group annotations below this line
 */
class GetQuoteForCartReorderTest extends Unit
{
    /**
     * @var string
     */
    protected const FAKE_SKU_1 = 'FAKE_SKU_1';

    /**
     * @var string
     */
    protected const FAKE_SKU_2 = 'FAKE_SKU_2';

    /**
     * @var \SprykerTest\Zed\PersistentCart\PersistentCartBusinessTester
     */
    protected PersistentCartBusinessTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->setDependency(
            QuoteDependencyProvider::PLUGINS_QUOTE_CREATE_BEFORE,
            [$this->getAddDefaultNameBeforeQuoteSavePluginMock()],
        );
    }

    /**
     * @return void
     */
    public function testShouldReturnQuoteForCartReorderWhenCustomerDoesNotHaveQuote(): void
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer();
        $cartReorderRequestTransfer = (new CartReorderRequestTransfer())
            ->setCustomerReference($customerTransfer->getCustomerReference());

        // Act
        $quoteTransfer = $this->tester->getFacade()->getQuoteForCartReorder($cartReorderRequestTransfer);

        // Assert
        $this->assertNotNull($quoteTransfer->getIdQuote());
        $this->assertEmpty($quoteTransfer->getItems());
    }

    /**
     * @return void
     */
    public function testShouldThrowExceptionWhenCustomerReferenceNotSet(): void
    {
        // Assert
        $this->expectException(NullValueException::class);
        $this->expectExceptionMessage('Property "customerReference" of transfer `Generated\Shared\Transfer\CartReorderRequestTransfer` is null.');

        // Act
        $this->tester->getFacade()->getQuoteForCartReorder(new CartReorderRequestTransfer());
    }

    /**
     * @return void
     */
    public function testShouldReturnQuoteForCartReorderWhenCustomerHasDefaultQuote(): void
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer();
        $defaultQuote = $this->tester->havePersistentQuote([
            QuoteTransfer::IS_DEFAULT => true,
            QuoteTransfer::CUSTOMER => $customerTransfer,
            QuoteTransfer::STORE => [StoreTransfer::NAME => 'DE'],
            QuoteTransfer::ITEMS => [
                [ItemTransfer::SKU => static::FAKE_SKU_1, ItemTransfer::GROUP_KEY => static::FAKE_SKU_1, ItemTransfer::QUANTITY => 1],
                [ItemTransfer::SKU => static::FAKE_SKU_2, ItemTransfer::GROUP_KEY => static::FAKE_SKU_2, ItemTransfer::QUANTITY => 2],
            ],
        ]);

        $cartReorderRequestTransfer = (new CartReorderRequestTransfer())
            ->setCustomerReference($customerTransfer->getCustomerReference());

        // Act
        $quoteTransfer = $this->tester->getFacade()->getQuoteForCartReorder($cartReorderRequestTransfer);

        // Assert
        $this->assertSame($defaultQuote->getIdQuote(), $quoteTransfer->getIdQuote());
        $this->assertEmpty($quoteTransfer->getItems());
    }

    /**
     * @return void
     */
    public function testShouldReturnQuoteForCartReorderWhenCustomerHasQuote(): void
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer();
        $persistedQuote = $this->tester->havePersistentQuote([
            QuoteTransfer::IS_DEFAULT => false,
            QuoteTransfer::CUSTOMER => $customerTransfer,
            QuoteTransfer::STORE => [StoreTransfer::NAME => 'DE'],
            QuoteTransfer::ITEMS => [
                [ItemTransfer::SKU => static::FAKE_SKU_1, ItemTransfer::GROUP_KEY => static::FAKE_SKU_1, ItemTransfer::QUANTITY => 1],
                [ItemTransfer::SKU => static::FAKE_SKU_2, ItemTransfer::GROUP_KEY => static::FAKE_SKU_2, ItemTransfer::QUANTITY => 2],
            ],
        ]);

        $cartReorderRequestTransfer = (new CartReorderRequestTransfer())
            ->setCustomerReference($customerTransfer->getCustomerReference());

        // Act
        $quoteTransfer = $this->tester->getFacade()->getQuoteForCartReorder($cartReorderRequestTransfer);

        // Assert
        $this->assertSame($persistedQuote->getIdQuote(), $quoteTransfer->getIdQuote());
        $this->assertEmpty($quoteTransfer->getItems());
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\QuoteExtension\Dependency\Plugin\QuoteWritePluginInterface
     */
    protected function getAddDefaultNameBeforeQuoteSavePluginMock(): QuoteWritePluginInterface
    {
        return Stub::makeEmpty(QuoteWritePluginInterface::class, [
            'execute' => function (QuoteTransfer $quoteTransfer) {
                if (!$quoteTransfer->getName()) {
                    $quoteTransfer->setName('Shopping Cart Test');
                }

                return $quoteTransfer;
            },
        ]);
    }
}
