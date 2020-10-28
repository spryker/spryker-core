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
use Generated\Shared\Transfer\PersistentItemReplaceTransfer;
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
    protected const FAKE_SKU_4 = 'fake_sku_4';
    protected const FAKE_SKU_5 = 'fake_sku_5';

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
    public function testReplaceItemWillSetSuccessFalseWhenItemToBeReplacedDoesNotExistInQuote(): void
    {
        // Arrange
        $originalQuoteTransfer = $this->createCustomerQuote();

        // Act
        $persistentItemReplaceTransfer = (new PersistentItemReplaceTransfer())
            ->setCustomer($originalQuoteTransfer->getCustomer())
            ->setIdQuote($originalQuoteTransfer->getIdQuote())
            ->setNewItem((new ItemTransfer())->setQuantity(5)->setSku(static::FAKE_SKU_4))
            ->setItemToBeReplaced((new ItemTransfer())->setQuantity(5)->setSku(static::FAKE_SKU_5));

        $quoteResponseTransfer = $this->tester->getFacade()->replaceItem($persistentItemReplaceTransfer);

        // Assert
        $this->assertCount(
            3,
            $quoteResponseTransfer->getQuoteTransfer()->getItems(),
            'Expected 3 items in the quote.'
        );
        $this->assertFalse(
            $quoteResponseTransfer->getIsSuccessful(),
            'Expected that quote response isSuccessful flag will be set to false when item to be replaced does not exist in quote.'
        );
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
        $this->assertTrue(
            $quoteResponseTransfer->getIsSuccessful(),
            'Expected that quote response isSuccessful flag will be set to true when decreasing quantity for provided items.'
        );
        $this->assertSame(
            2,
            $quoteResponseTransfer->getQuoteTransfer()->getItems()->offsetGet(0)->getQuantity(),
            'Expected that quantity of the item with sku fake_sku_1 in the quote will be decreased from 5 to 2.'
        );
        $this->assertSame(
            1,
            $quoteResponseTransfer->getQuoteTransfer()->getItems()->offsetGet(1)->getQuantity(),
            'Expected that quantity of the item with sku fake_sku_2 in the quote  will be decreased from 5 to 1.'
        );
        $this->assertSame(
            5,
            $quoteResponseTransfer->getQuoteTransfer()->getItems()->offsetGet(2)->getQuantity(),
            'Expected that quantity of the item with sku fake_sku_3 in the quote wont be decreased.'
        );
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
        $this->assertTrue(
            $quoteResponseTransfer->getIsSuccessful(),
            'Expected that quote response isSuccessful flag will be set to true when increasing quantity for provided items.'
        );
        $this->assertSame(
            7,
            $quoteResponseTransfer->getQuoteTransfer()->getItems()->offsetGet(0)->getQuantity(),
            'Expected that quantity of the item with sku fake_sku_1 in the quote will be increased from 5 to 7.'
        );
        $this->assertSame(
            10,
            $quoteResponseTransfer->getQuoteTransfer()->getItems()->offsetGet(1)->getQuantity(),
            'Expected that quantity of the item with sku fake_sku_2 in the quote will be increased from 5 to 10.'
        );
        $this->assertSame(
            5,
            $quoteResponseTransfer->getQuoteTransfer()->getItems()->offsetGet(2)->getQuantity(),
            'Expected that quantity of item with sku fake_sku_3 item in the quote wont be increased.'
        );
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
        $this->assertTrue(
            $quoteResponseTransfer->getIsSuccessful(),
            'Expected that quote response isSuccessful flag will be set to true when changing quantity for provided items.'
        );
        $this->assertSame(
            1,
            $quoteResponseTransfer->getQuoteTransfer()->getItems()->offsetGet(0)->getQuantity(),
            'Expected that quantity of the item with sku fake_sku_1 in the quote will be decreased from 5 to 1.'
        );
        $this->assertSame(
            10,
            $quoteResponseTransfer->getQuoteTransfer()->getItems()->offsetGet(1)->getQuantity(),
            'Expected that quantity of the item with sku fake_sku_2 in the quote will be increased from 5 to 10.'
        );
        $this->assertSame(
            5,
            $quoteResponseTransfer->getQuoteTransfer()->getItems()->offsetGet(2)->getQuantity(),
            'Expected that quantity of the item with sku fake_sku_3 in the quote wont be changed.'
        );
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
        $this->assertTrue(
            $quoteResponseTransfer->getIsSuccessful(),
            'Expected that quote response isSuccessful flag will be set to true when updating cart items quantity with empty persistent cart change transfer .'
        );
        $this->assertSame(
            5,
            $quoteResponseTransfer->getQuoteTransfer()->getItems()->offsetGet(0)->getQuantity(),
            'Expected that quantity of the item with sku fake_sku_1 in the quote wont be changed.'
        );
        $this->assertSame(
            5,
            $quoteResponseTransfer->getQuoteTransfer()->getItems()->offsetGet(1)->getQuantity(),
            'Expected that quantity of the item with sku fake_sku_1 in the quote wont be changed.'
        );
        $this->assertSame(
            5,
            $quoteResponseTransfer->getQuoteTransfer()->getItems()->offsetGet(2)->getQuantity(),
            'Expected that quantity of the item with sku fake_sku_1 in the quote wont be changed.'
        );
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
        $this->assertTrue(
            $quoteResponseTransfer->getIsSuccessful(),
            'Expected that quote response isSuccessful flag will be set to true when updating cart items quantity with same items quantity in the persistent cart change transfer .'
        );
        $this->assertSame(
            5,
            $quoteResponseTransfer->getQuoteTransfer()->getItems()->offsetGet(0)->getQuantity(),
            'Expected that quantity of the item with sku fake_sku_1 in the quote wont be changed.'
        );
        $this->assertSame(
            5,
            $quoteResponseTransfer->getQuoteTransfer()->getItems()->offsetGet(1)->getQuantity(),
            'Expected that quantity of the item with sku fake_sku_2 in the quote wont be changed.'
        );
        $this->assertSame(
            5,
            $quoteResponseTransfer->getQuoteTransfer()->getItems()->offsetGet(2)->getQuantity(),
            'Expected that quantity of the item with sku fake_sku_3 in the quote wont be changed.'
        );
    }

    /**
     * @return void
     */
    public function testReplaceItemShouldReplaceItem(): void
    {
        // Arrange
        $originalQuoteTransfer = $this->createCustomerQuote();

        // Act
        $persistentItemReplaceTransfer = (new PersistentItemReplaceTransfer())
            ->setCustomer($originalQuoteTransfer->getCustomer())
            ->setIdQuote($originalQuoteTransfer->getIdQuote())
            ->setNewItem((new ItemTransfer())->setQuantity(5)->setSku(static::FAKE_SKU_4))
            ->setItemToBeReplaced((new ItemTransfer())->setQuantity(5)->setSku(static::FAKE_SKU_1));

        $quoteResponseTransfer = $this->tester->getFacade()->replaceItem($persistentItemReplaceTransfer);

        // Assert
        $this->assertTrue(
            $quoteResponseTransfer->getIsSuccessful(),
            'Expected that quote response isSuccessful flag will be set to true when replacing item in quote.'
        );
        $this->assertCount(
            3,
            $quoteResponseTransfer->getQuoteTransfer()->getItems(),
            'Expected that after replacing count will left the same (3).'
        );
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
        $this->assertFalse(
            $quoteResponseTransfer->getIsSuccessful(),
            'Expected that quote response isSuccessful flag will be set to false when trying to decrease more item quantity that exits in quote.'
        );
        $this->assertSame(
            5,
            $quoteResponseTransfer->getQuoteTransfer()->getItems()->offsetGet(0)->getQuantity(),
            'Expected that quantity of the item with sku fake_sku_1 in the quote wont be changed.'
        );
        $this->assertSame(
            5,
            $quoteResponseTransfer->getQuoteTransfer()->getItems()->offsetGet(1)->getQuantity(),
            'Expected that quantity of the item with sku fake_sku_2 in the quote wont be changed.'
        );
        $this->assertSame(
            5,
            $quoteResponseTransfer->getQuoteTransfer()->getItems()->offsetGet(2)->getQuantity(),
            'Expected that quantity of the item with sku fake_sku_3 in the quote wont be changed.'
        );
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
        $this->assertFalse(
            $quoteResponseTransfer->getIsSuccessful(),
            'Expected that quote response isSuccessful flag will be set to false when trying to decrease more item quantity that exits in quote.'
        );
        $this->assertSame(
            5,
            $quoteResponseTransfer->getQuoteTransfer()->getItems()->offsetGet(0)->getQuantity(),
            'Expected that quantity of the item with sku fake_sku_3 in the quote wont be changed.'
        );
        $this->assertSame(
            5,
            $quoteResponseTransfer->getQuoteTransfer()->getItems()->offsetGet(1)->getQuantity(),
            'Expected that quantity of the item with sku fake_sku_3 in the quote wont be changed.'
        );
        $this->assertSame(
            5,
            $quoteResponseTransfer->getQuoteTransfer()->getItems()->offsetGet(2)->getQuantity(),
            'Expected that quantity of the item with sku fake_sku_3 in the quote wont be changed.'
        );
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
    protected function createCartPreCheckPluginInterfaceMock(): CartPreCheckPluginInterface
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
    protected function createCartRemovalPreCheckPluginInterfaceMock(): CartRemovalPreCheckPluginInterface
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
