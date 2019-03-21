<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Cart\Business;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\CartChangeBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Orm\Zed\PriceProduct\Persistence\SpyPriceProductQuery;
use Orm\Zed\PriceProduct\Persistence\SpyPriceTypeQuery;
use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Spryker\Service\UtilQuantity\UtilQuantityConfig;
use Spryker\Service\UtilQuantity\UtilQuantityService;
use Spryker\Service\UtilQuantity\UtilQuantityServiceFactory;
use Spryker\Zed\Cart\CartDependencyProvider;
use Spryker\Zed\Cart\Dependency\Service\CartToUtilQuantityServiceBridge;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Cart
 * @group Business
 * @group Facade
 * @group CartFacadeTest
 * Add your own group annotations below this line
 */
class CartFacadeTest extends Unit
{
    public const PRICE_TYPE_DEFAULT = 'DEFAULT';
    public const DUMMY_1_SKU_ABSTRACT_PRODUCT = 'ABSTRACT1';
    public const DUMMY_1_SKU_CONCRETE_PRODUCT = 'CONCRETE1';
    public const DUMMY_1_PRICE = 99;
    public const DUMMY_2_SKU_ABSTRACT_PRODUCT = 'ABSTRACT2';
    public const DUMMY_2_SKU_CONCRETE_PRODUCT = 'CONCRETE2';
    public const DUMMY_2_PRICE = 100;

    /**
     * @var \Spryker\Zed\Cart\Business\CartFacadeInterface
     */
    private $cartFacade;

    /**
     * @var \SprykerTest\Zed\Cart\CartBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $utilQuantityConfigMock = $this->getMockBuilder(UtilQuantityConfig::class)
            ->setMethods(['getQuantityRoundingPrecision'])
            ->getMock();

        $utilQuantityConfigMock->method('getQuantityRoundingPrecision')
            ->will($this->returnValue(2));

        $utilQuantityServiceFactory = new UtilQuantityServiceFactory();
        $utilQuantityServiceFactory->setConfig($utilQuantityConfigMock);
        $utilQuantityService = new UtilQuantityService();
        $utilQuantityService->setFactory($utilQuantityServiceFactory);

        $utilQuantityServiceBridge = new CartToUtilQuantityServiceBridge($utilQuantityService);

        $this->tester->setDependency(CartDependencyProvider::SERVICE_UTIL_QUANTITY, $utilQuantityServiceBridge);

        $this->cartFacade = $this->tester->getFacade();

        $this->setTestData();
    }

    /**
     * @dataProvider addToCartIncreaseCartQuantityDataProvider
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     * @param float $expectedResult
     *
     * @return void
     */
    public function testAddToCartIncreaseCartQuantity(CartChangeTransfer $cartChangeTransfer, float $expectedResult): void
    {
        $resultQuoteTransfer = $this->cartFacade->add($cartChangeTransfer);

        $this->assertSame($expectedResult, $resultQuoteTransfer->getItems()[0]->getQuantity());
    }

    /**
     * @return array
     */
    public function addToCartIncreaseCartQuantityDataProvider(): array
    {
        return [
            'int stock' => $this->getDataForAddToCartIncreaseCartQuantity(1, 2, 3.0),
            'float stock' => $this->getDataForAddToCartIncreaseCartQuantity(1.1, 2.2, 3.3),
            'float stock high precision' => $this->getDataForAddToCartIncreaseCartQuantity(1.111111111, 2.100000002, 3.21),
        ];
    }

    /**
     * @param int|float $quoteQty
     * @param int|float $additionalQty
     * @param float $expectedResult
     *
     * @return array
     */
    public function getDataForAddToCartIncreaseCartQuantity($quoteQty, $additionalQty, float $expectedResult): array
    {
        $quoteTransfer = (new QuoteBuilder())
            ->withItem([
                ItemTransfer::QUANTITY => $quoteQty,
                ItemTransfer::GROUP_KEY => 'group',
                ItemTransfer::SKU => '123',
            ])
            ->build();

        $cartChangeTransfer = (new CartChangeBuilder())->withItem([
            ItemTransfer::QUANTITY => $additionalQty,
            ItemTransfer::GROUP_KEY => 'group',
            ItemTransfer::SKU => '123',
        ])->build();
        $cartChangeTransfer->setQuote($quoteTransfer);

        return [$cartChangeTransfer, $expectedResult];
    }

    /**
     * @dataProvider addToCartDecreaseCartQuantityDataProvider
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     * @param float $expectedResult
     *
     * @return void
     */
    public function testAddToCartDecreaseCartQuantity(CartChangeTransfer $cartChangeTransfer, float $expectedResult): void
    {
        $resultQuoteTransfer = $this->cartFacade->remove($cartChangeTransfer);

        $this->assertSame($expectedResult, $resultQuoteTransfer->getItems()[0]->getQuantity());
    }

    /**
     * @return array
     */
    public function addToCartDecreaseCartQuantityDataProvider(): array
    {
        return [
            'int stock' => $this->getDataForAddToCartDecreaseCartQuantity(3, 1, 2.0),
            'float stock' => $this->getDataForAddToCartDecreaseCartQuantity(3.1, 2.2, 0.9),
            'float stock high precision' => $this->getDataForAddToCartDecreaseCartQuantity(3.111111111, 2.000000001, 1.11),
        ];
    }

    /**
     * @param int|float $quoteQty
     * @param int|float $additionalQty
     * @param float $expectedResult
     *
     * @return array
     */
    public function getDataForAddToCartDecreaseCartQuantity($quoteQty, $additionalQty, float $expectedResult): array
    {
        $quoteTransfer = (new QuoteBuilder())
            ->withItem([
                ItemTransfer::QUANTITY => $quoteQty,
                ItemTransfer::GROUP_KEY => 'group',
                ItemTransfer::SKU => '123',
            ])
            ->build();

        $cartChangeTransfer = (new CartChangeBuilder())->withItem([
            ItemTransfer::QUANTITY => $additionalQty,
            ItemTransfer::GROUP_KEY => 'group',
            ItemTransfer::SKU => '123',
        ])->build();
        $cartChangeTransfer->setQuote($quoteTransfer);

        return [$cartChangeTransfer, $expectedResult];
    }

    /**
     * @return void
     */
    public function testAddToCart()
    {
        $this->markTestSkipped('Tried to retrieve a concrete product with sku CONCRETE2, but it does not exist');
        $quoteTransfer = new QuoteTransfer();
        $cartItem = new ItemTransfer();
        $cartItem->setSku(self::DUMMY_1_SKU_CONCRETE_PRODUCT);
        $cartItem->setQuantity(3);
        $cartItem->setUnitGrossPrice(1);

        $quoteTransfer->addItem($cartItem);

        $newItem = new ItemTransfer();
        $newItem->setSku(self::DUMMY_2_SKU_CONCRETE_PRODUCT);
        $newItem->setQuantity(1);
        $newItem->setUnitGrossPrice(1);

        $cartChange = new CartChangeTransfer();
        $cartChange->setQuote($quoteTransfer);
        $cartChange->addItem($newItem);

        $changedCart = $this->cartFacade->addToCart($cartChange);

        $this->assertCount(2, $changedCart->getItems());

        /** @var \Generated\Shared\Transfer\ItemTransfer $item */
        foreach ($quoteTransfer->getItems() as $item) {
            if ($item->getSku() === $cartItem->getSku()) {
                $this->assertEquals($cartItem->getQuantity(), $item->getQuantity());
            } elseif ($newItem->getSku() === $item->getSku()) {
                $this->assertEquals($newItem->getQuantity(), $item->getQuantity());
            } else {
                $this->fail('Cart has a unknown item inside');
            }
        }
    }

    /**
     * @return void
     */
    public function testIncreaseCartQuantity()
    {
        $this->markTestSkipped('Tried to retrieve a concrete product with sku CONCRETE1, but it does not exist');
        $quoteTransfer = new QuoteTransfer();
        $cartItem = new ItemTransfer();
        $cartItem->setSku(self::DUMMY_1_SKU_CONCRETE_PRODUCT);
        $cartItem->setQuantity(3);
        $cartItem->setUnitGrossPrice(1);

        $quoteTransfer->addItem($cartItem);

        $newItem = new ItemTransfer();
        $newItem->setId(self::DUMMY_1_SKU_CONCRETE_PRODUCT);
        $newItem->setSku(self::DUMMY_1_SKU_CONCRETE_PRODUCT);
        $newItem->setQuantity(1);
        $newItem->setUnitGrossPrice(1);

        $cartChange = new CartChangeTransfer();
        $cartChange->setQuote($quoteTransfer);
        $cartChange->addItem($newItem);

        $changedCart = $this->cartFacade->increaseQuantity($cartChange);
        $cartItems = $changedCart->getItems();
        $this->assertCount(2, $cartItems);

        /** @var \Generated\Shared\Transfer\ItemTransfer $changedItem */
        $changedItem = $cartItems[1];
        $this->assertEquals(3, $changedItem->getQuantity());

        $changedItem = $cartItems[2];
        $this->assertEquals(1, $changedItem->getQuantity());
    }

    /**
     * @return void
     */
    public function testRemoveFromCart()
    {
        $this->markTestSkipped('Tried to retrieve a concrete product with sku CONCRETE2, but it does not exist');
        $quoteTransfer = new QuoteTransfer();
        $cartItem = new ItemTransfer();
        $cartItem->setId(self::DUMMY_2_SKU_CONCRETE_PRODUCT);
        $cartItem->setSku(self::DUMMY_2_SKU_CONCRETE_PRODUCT);
        $cartItem->setQuantity(1);
        $cartItem->setUnitGrossPrice(1);

        $quoteTransfer->addItem($cartItem);

        $newItem = new ItemTransfer();
        $newItem->setId(self::DUMMY_2_SKU_CONCRETE_PRODUCT);
        $newItem->setSku(self::DUMMY_2_SKU_CONCRETE_PRODUCT);
        $newItem->setQuantity(1);
        $newItem->setUnitGrossPrice(1);

        $cartChange = new CartChangeTransfer();
        $cartChange->setQuote($quoteTransfer);
        $cartChange->addItem($newItem);

        $changedCart = $this->cartFacade->removeFromCart($cartChange);

        $this->assertCount(0, $changedCart->getItems());
    }

    /**
     * @return void
     */
    public function testDecreaseCartItem()
    {
        $this->markTestSkipped('Tried to retrieve a concrete product with sku CONCRETE1, but it does not exist');
        $quoteTransfer = new QuoteTransfer();
        $cartItem = new ItemTransfer();
        $cartItem->setSku(self::DUMMY_1_SKU_CONCRETE_PRODUCT);
        $cartItem->setQuantity(3);
        $cartItem->setUnitGrossPrice(1);

        $quoteTransfer->addItem($cartItem);

        $newItem = new ItemTransfer();
        $newItem->setSku(self::DUMMY_1_SKU_CONCRETE_PRODUCT);
        $newItem->setQuantity(1);
        $newItem->setUnitGrossPrice(1);

        $cartChange = new CartChangeTransfer();
        $cartChange->setQuote($quoteTransfer);
        $cartChange->addItem($newItem);

        $changedCart = $this->cartFacade->decreaseQuantity($cartChange);
        $cartItems = $changedCart->getItems();
        $this->assertCount(1, $cartItems);
        /** @var \Generated\Shared\Transfer\ItemTransfer $changedItem */
        $changedItem = $cartItems[0];
        $this->assertEquals(2, $changedItem->getQuantity());
    }

    /**
     * @dataProvider quoteOneItemQuantityDataProvider
     *
     * @param int|float $quantity
     *
     * @return void
     */
    public function testCleanUpItemsRemoveKeyGroupPrefixFromQuoteItem($quantity): void
    {
        // Arrange
        $quoteTransfer = new QuoteTransfer();
        $cartItem = (new ItemTransfer())->setSku(self::DUMMY_1_SKU_CONCRETE_PRODUCT)
            ->setQuantity($quantity)
            ->setUnitGrossPrice(1)
            ->setGroupKeyPrefix(uniqid('', true));

        $quoteTransfer->addItem($cartItem);

        // Act
        $this->cartFacade->cleanUpItems($quoteTransfer);

        // Assert
        $this->assertNull($quoteTransfer->getItems()[0]->getGroupKeyPrefix());
    }

    /**
     * @return array
     */
    public function quoteOneItemQuantityDataProvider(): array
    {
        return [
            'int stock' => [1],
            'float stock' => [1.1],
        ];
    }

    /**
     * @dataProvider quoteTwoItemQuantityDataProvider
     *
     * @param int|float $quantity1
     * @param int|float $quantity2
     *
     * @return void
     */
    public function testCleanUpItemsRemoveKeyGroupPrefixFromQuoteItemIfMoreThanOne($quantity1, $quantity2): void
    {
        // Arrange
        $quoteTransfer = new QuoteTransfer();
        $cartItem = (new ItemTransfer())->setSku(self::DUMMY_1_SKU_CONCRETE_PRODUCT)
            ->setQuantity($quantity1)
            ->setUnitGrossPrice(1)
            ->setGroupKeyPrefix(uniqid('', true));

        $newItem = (new ItemTransfer())->setSku(self::DUMMY_1_SKU_CONCRETE_PRODUCT)
            ->setQuantity($quantity2)
            ->setUnitGrossPrice(1);

        $quoteTransfer->addItem($cartItem);
        $quoteTransfer->addItem($newItem);

        // Act
        $this->cartFacade->cleanUpItems($quoteTransfer);

        // Assert
        $this->assertNotNull($quoteTransfer->getItems()[0]->getGroupKeyPrefix());
    }

    /**
     * @return array
     */
    public function quoteTwoItemQuantityDataProvider(): array
    {
        return [
            'int stock' => [1, 2],
            'float stock' => [1.1, 2.2],
        ];
    }

    /**
     * @return void
     */
    protected function setTestData()
    {
        $defaultPriceType = SpyPriceTypeQuery::create()->filterByName(self::PRICE_TYPE_DEFAULT)->findOneOrCreate();
        $defaultPriceType->setName(self::PRICE_TYPE_DEFAULT)->save();

        $abstractProduct1 = SpyProductAbstractQuery::create()
            ->filterBySku(self::DUMMY_1_SKU_ABSTRACT_PRODUCT)
            ->findOneOrCreate();

        $abstractProduct1->setSku(self::DUMMY_1_SKU_ABSTRACT_PRODUCT)
            ->setAttributes('{}')
            ->save();

        $concreteProduct1 = SpyProductQuery::create()
            ->filterBySku(self::DUMMY_1_SKU_CONCRETE_PRODUCT)
            ->findOneOrCreate();

        $concreteProduct1
            ->setSku(self::DUMMY_1_SKU_CONCRETE_PRODUCT)
            ->setSpyProductAbstract($abstractProduct1)
            ->setAttributes('{}')
            ->save();

        $abstractProduct2 = SpyProductAbstractQuery::create()
            ->filterBySku(self::DUMMY_2_SKU_ABSTRACT_PRODUCT)
            ->findOneOrCreate();

        $abstractProduct2
            ->setSku(self::DUMMY_2_SKU_ABSTRACT_PRODUCT)
            ->setAttributes('{}')
            ->save();

        $concreteProduct2 = SpyProductQuery::create()
            ->filterBySku(self::DUMMY_2_SKU_CONCRETE_PRODUCT)
            ->findOneOrCreate();

        $concreteProduct2
            ->setSku(self::DUMMY_2_SKU_CONCRETE_PRODUCT)
            ->setSpyProductAbstract($abstractProduct2)
            ->setAttributes('{}')
            ->save();

        SpyPriceProductQuery::create()
            ->filterByProduct($concreteProduct1)
            ->filterBySpyProductAbstract($abstractProduct1)
            ->filterByPriceType($defaultPriceType)
            ->findOneOrCreate()
            ->setPrice(100)
            ->save();

        SpyPriceProductQuery::create()
            ->filterByProduct($concreteProduct2)
            ->filterBySpyProductAbstract($abstractProduct2)
            ->filterByPriceType($defaultPriceType)
            ->findOneOrCreate()
            ->setPrice(100)
            ->save();
    }
}
