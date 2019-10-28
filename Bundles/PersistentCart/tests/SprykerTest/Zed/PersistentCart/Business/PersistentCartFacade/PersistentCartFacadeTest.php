<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PersistentCart\Business\PersistentCartFacade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CartPreCheckResponseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\PersistentCartChangeTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Cart\CartDependencyProvider;
use Spryker\Zed\CartExtension\Dependency\Plugin\CartPreCheckPluginInterface;
use Spryker\Zed\CartExtension\Dependency\Plugin\CartRemovalPreCheckPluginInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PersistentCart
 * @group Business
 * @group PersistentCartFacade
 * @group Facade
 * @group PersistentCartFacadeTest
 * Add your own group annotations below this line
 */
class PersistentCartFacadeTest extends Unit
{
    protected const FAKE_SKU_1 = 'fake_sku_1';
    protected const FAKE_SKU_2 = 'fake_sku_2';
    protected const FAKE_SKU_3 = 'fake_sku_3';

    /**
     * @var \SprykerTest\Zed\PersistentCart\PersistentCartBusinessTester
     */
    protected $tester;

    /**
     * @var \Generated\Shared\Transfer\QuoteTransfer
     */
    protected $originalQuoteTransfer;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->tester->haveProducts([
            static::FAKE_SKU_1,
            static::FAKE_SKU_2,
            static::FAKE_SKU_3,
        ]);
    }

    /**
     * @return void
     */
    public function testUpdateQuantityDecreasesQuantityForProvidedItems(): void
    {
        // Arrange
        $originalQuoteTransfer = $this->createCustomerQuote();

        $persistentCartChangeTransfer = (new PersistentCartChangeTransfer())
            ->setIdQuote($originalQuoteTransfer->getIdQuote())
            ->setCustomer($originalQuoteTransfer->getCustomer())
            ->addItem((new ItemTransfer())->setQuantity(2)->setSku(static::FAKE_SKU_1))
            ->addItem((new ItemTransfer())->setQuantity(1)->setSku(static::FAKE_SKU_2));

        // Act
        $quoteResponseTransfer = $this->tester->getFacade()->updateQuantity($persistentCartChangeTransfer);

        // Assert
        $this->assertTrue($quoteResponseTransfer->getIsSuccessful());
        $this->assertSame(2, $quoteResponseTransfer->getQuoteTransfer()->getItems()->offsetGet(0)->getQuantity());
        $this->assertSame(1, $quoteResponseTransfer->getQuoteTransfer()->getItems()->offsetGet(1)->getQuantity());
        $this->assertSame(5, $quoteResponseTransfer->getQuoteTransfer()->getItems()->offsetGet(2)->getQuantity());
    }

    /**
     * @return void
     */
    public function testUpdateQuantityIncreasesQuantityForProvidedItems(): void
    {
        // Arrange
        $originalQuoteTransfer = $this->createCustomerQuote();

        $persistentCartChangeTransfer = (new PersistentCartChangeTransfer())
            ->setIdQuote($originalQuoteTransfer->getIdQuote())
            ->setCustomer($originalQuoteTransfer->getCustomer())
            ->addItem((new ItemTransfer())->setQuantity(7)->setSku(static::FAKE_SKU_1))
            ->addItem((new ItemTransfer())->setQuantity(10)->setSku(static::FAKE_SKU_2));

        // Act
        $quoteResponseTransfer = $this->tester->getFacade()->updateQuantity($persistentCartChangeTransfer);

        // Assert
        $this->assertTrue($quoteResponseTransfer->getIsSuccessful());
        $this->assertSame(7, $quoteResponseTransfer->getQuoteTransfer()->getItems()->offsetGet(0)->getQuantity());
        $this->assertSame(10, $quoteResponseTransfer->getQuoteTransfer()->getItems()->offsetGet(1)->getQuantity());
        $this->assertSame(5, $quoteResponseTransfer->getQuoteTransfer()->getItems()->offsetGet(2)->getQuantity());
    }

    /**
     * @return void
     */
    public function testUpdateQuantityChangesQuantityForProvidedItems(): void
    {
        // Arrange
        $originalQuoteTransfer = $this->createCustomerQuote();

        $persistentCartChangeTransfer = (new PersistentCartChangeTransfer())
            ->setIdQuote($originalQuoteTransfer->getIdQuote())
            ->setCustomer($originalQuoteTransfer->getCustomer())
            ->addItem((new ItemTransfer())->setQuantity(1)->setSku(static::FAKE_SKU_1))
            ->addItem((new ItemTransfer())->setQuantity(10)->setSku(static::FAKE_SKU_2))
            ->addItem((new ItemTransfer())->setQuantity(5)->setSku(static::FAKE_SKU_3));

        // Act
        $quoteResponseTransfer = $this->tester->getFacade()->updateQuantity($persistentCartChangeTransfer);

        // Assert
        $this->assertTrue($quoteResponseTransfer->getIsSuccessful());
        $this->assertSame(1, $quoteResponseTransfer->getQuoteTransfer()->getItems()->offsetGet(0)->getQuantity());
        $this->assertSame(10, $quoteResponseTransfer->getQuoteTransfer()->getItems()->offsetGet(1)->getQuantity());
        $this->assertSame(5, $quoteResponseTransfer->getQuoteTransfer()->getItems()->offsetGet(2)->getQuantity());
    }

    /**
     * @return void
     */
    public function testUpdateQuantityChangesQuantityForEmptyPersistentCartChangeTransfer(): void
    {
        // Arrange
        $originalQuoteTransfer = $this->createCustomerQuote();

        $persistentCartChangeTransfer = (new PersistentCartChangeTransfer())
            ->setIdQuote($originalQuoteTransfer->getIdQuote())
            ->setCustomer($originalQuoteTransfer->getCustomer());

        // Act
        $quoteResponseTransfer = $this->tester->getFacade()->updateQuantity($persistentCartChangeTransfer);

        // Assert
        $this->assertTrue($quoteResponseTransfer->getIsSuccessful());
        $this->assertSame(5, $quoteResponseTransfer->getQuoteTransfer()->getItems()->offsetGet(0)->getQuantity());
        $this->assertSame(5, $quoteResponseTransfer->getQuoteTransfer()->getItems()->offsetGet(1)->getQuantity());
        $this->assertSame(5, $quoteResponseTransfer->getQuoteTransfer()->getItems()->offsetGet(2)->getQuantity());
    }

    /**
     * @return void
     */
    public function testUpdateQuantityChangesQuantityForSamePersistentCartChangeTransfer(): void
    {
        // Arrange
        $originalQuoteTransfer = $this->createCustomerQuote();

        $persistentCartChangeTransfer = (new PersistentCartChangeTransfer())
            ->setIdQuote($originalQuoteTransfer->getIdQuote())
            ->setCustomer($originalQuoteTransfer->getCustomer())
            ->addItem((new ItemTransfer())->setQuantity(5)->setSku(static::FAKE_SKU_1))
            ->addItem((new ItemTransfer())->setQuantity(5)->setSku(static::FAKE_SKU_2))
            ->addItem((new ItemTransfer())->setQuantity(5)->setSku(static::FAKE_SKU_3));

        // Act
        $quoteResponseTransfer = $this->tester->getFacade()->updateQuantity($persistentCartChangeTransfer);

        // Assert
        $this->assertTrue($quoteResponseTransfer->getIsSuccessful());
        $this->assertSame(5, $quoteResponseTransfer->getQuoteTransfer()->getItems()->offsetGet(0)->getQuantity());
        $this->assertSame(5, $quoteResponseTransfer->getQuoteTransfer()->getItems()->offsetGet(1)->getQuantity());
        $this->assertSame(5, $quoteResponseTransfer->getQuoteTransfer()->getItems()->offsetGet(2)->getQuantity());
    }

    /**
     * @return void
     */
    public function testUpdateQuantityUpdatesQuantityWithoutIncreasing(): void
    {
        // Arrange
        $customerQuoteTransfer = $this->createCustomerQuote();
        $this->tester->setDependency(CartDependencyProvider::CART_PRE_CHECK_PLUGINS, [$this->createCartPreCheckPluginInterfaceMock()]);

        $persistentCartChangeTransfer = (new PersistentCartChangeTransfer())
            ->setIdQuote($customerQuoteTransfer->getIdQuote())
            ->setCustomer($customerQuoteTransfer->getCustomer())
            ->addItem((new ItemTransfer())->setQuantity(9)->setSku(static::FAKE_SKU_1));

        // Act
        $quoteResponseTransfer = $this->tester->getFacade()->updateQuantity($persistentCartChangeTransfer);

        // Assert
        $this->assertFalse($quoteResponseTransfer->getIsSuccessful());
        $this->assertSame(5, $quoteResponseTransfer->getQuoteTransfer()->getItems()->offsetGet(0)->getQuantity());
        $this->assertSame(5, $quoteResponseTransfer->getQuoteTransfer()->getItems()->offsetGet(1)->getQuantity());
        $this->assertSame(5, $quoteResponseTransfer->getQuoteTransfer()->getItems()->offsetGet(2)->getQuantity());
    }

    /**
     * @return void
     */
    public function testUpdateQuantityUpdatesQuantityWithoutDecreasing(): void
    {
        // Arrange
        $customerQuoteTransfer = $this->createCustomerQuote();
        $this->tester->setDependency(CartDependencyProvider::CART_REMOVAL_PRE_CHECK_PLUGINS, [$this->createCartRemovalPreCheckPluginInterfaceMock()]);

        $persistentCartChangeTransfer = (new PersistentCartChangeTransfer())
            ->setIdQuote($customerQuoteTransfer->getIdQuote())
            ->setCustomer($customerQuoteTransfer->getCustomer())
            ->addItem((new ItemTransfer())->setQuantity(1)->setSku(static::FAKE_SKU_1))
            ->addItem((new ItemTransfer())->setQuantity(10)->setSku(static::FAKE_SKU_2));

        // Act
        $quoteResponseTransfer = $this->tester->getFacade()->updateQuantity($persistentCartChangeTransfer);

        // Assert
        $this->assertFalse($quoteResponseTransfer->getIsSuccessful());
        $this->assertSame(5, $quoteResponseTransfer->getQuoteTransfer()->getItems()->offsetGet(0)->getQuantity());
        $this->assertSame(5, $quoteResponseTransfer->getQuoteTransfer()->getItems()->offsetGet(1)->getQuantity());
        $this->assertSame(5, $quoteResponseTransfer->getQuoteTransfer()->getItems()->offsetGet(2)->getQuantity());
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
                [ItemTransfer::SKU => static::FAKE_SKU_1, ItemTransfer::GROUP_KEY => static::FAKE_SKU_1, ItemTransfer::QUANTITY => 5],
                [ItemTransfer::SKU => static::FAKE_SKU_2, ItemTransfer::GROUP_KEY => static::FAKE_SKU_2, ItemTransfer::QUANTITY => 5],
                [ItemTransfer::SKU => static::FAKE_SKU_3, ItemTransfer::GROUP_KEY => static::FAKE_SKU_3, ItemTransfer::QUANTITY => 5],
            ],
        ]);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\CartExtension\Dependency\Plugin\CartPreCheckPluginInterface
     */
    protected function createCartPreCheckPluginInterfaceMock()
    {
        $cartPreCheckPluginInterfaceMock = $this
            ->getMockBuilder(CartPreCheckPluginInterface::class)
            ->setMethods([
                'check',
            ])
            ->disableOriginalConstructor()
            ->getMock();

        $cartPreCheckPluginInterfaceMock
            ->method('check')
            ->willReturn((new CartPreCheckResponseTransfer())->setIsSuccess(false));

        return $cartPreCheckPluginInterfaceMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\CartExtension\Dependency\Plugin\CartRemovalPreCheckPluginInterface
     */
    protected function createCartRemovalPreCheckPluginInterfaceMock()
    {
        $cartPreCheckPluginInterfaceMock = $this
            ->getMockBuilder(CartRemovalPreCheckPluginInterface::class)
            ->setMethods([
                'check',
            ])
            ->disableOriginalConstructor()
            ->getMock();

        $cartPreCheckPluginInterfaceMock
            ->method('check')
            ->willReturn((new CartPreCheckResponseTransfer())->setIsSuccess(false));

        return $cartPreCheckPluginInterfaceMock;
    }
}
