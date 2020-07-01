<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Availability\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductConcreteAvailabilityRequestTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\StockProductTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Availability\Persistence\SpyAvailability;
use Orm\Zed\Availability\Persistence\SpyAvailabilityAbstractQuery;
use Orm\Zed\Availability\Persistence\SpyAvailabilityQuery;
use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Orm\Zed\Stock\Persistence\SpyStockProduct;
use Orm\Zed\Stock\Persistence\SpyStockProductQuery;
use Orm\Zed\Stock\Persistence\SpyStockQuery;
use Spryker\DecimalObject\Decimal;
use Spryker\Zed\Availability\AvailabilityDependencyProvider;
use Spryker\Zed\Availability\Business\AvailabilityBusinessFactory;
use Spryker\Zed\Availability\Business\AvailabilityFacade;
use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStockFacadeInterface;
use Spryker\Zed\Kernel\Container;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Availability
 * @group Business
 * @group Facade
 * @group AvailabilityFacadeTest
 * Add your own group annotations below this line
 */
class AvailabilityFacadeTest extends Unit
{
    public const ABSTRACT_SKU = '123_availability_test';
    public const CONCRETE_SKU = '123_availability_test-concrete';
    public const ID_STORE = 1;
    public const STORE_NAME_DE = 'DE';

    /**
     * @var \SprykerTest\Zed\Availability\AvailabilityBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testIsProductSellableWhenNeverOutOfStockShouldReturnSuccess(): void
    {
        // Arrange
        $this->tester->haveProduct([ProductConcreteTransfer::SKU => static::CONCRETE_SKU]);
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_DE]);
        $this->createProductWithStock(
            static::ABSTRACT_SKU,
            static::CONCRETE_SKU,
            ['is_never_out_of_stock' => true],
            $storeTransfer
        );

        // Act
        $isProductSellable = $this->getAvailabilityFacade()
            ->isProductSellableForStore(static::CONCRETE_SKU, new Decimal(1), $storeTransfer);

        // Assert
        $this->assertTrue($isProductSellable);
    }

    /**
     * @return void
     */
    public function testIsProductSellableWhenStockIsEmptyShouldReturnFailure(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_DE]);
        $this->createProductWithStock(
            static::ABSTRACT_SKU,
            static::CONCRETE_SKU,
            ['quantity' => 0],
            $storeTransfer
        );

        // Act
        $isProductSellable = $this->getAvailabilityFacade()
            ->isProductSellableForStore(static::CONCRETE_SKU, new Decimal(1), $storeTransfer);

        // Assert
        $this->assertFalse($isProductSellable);
    }

    /**
     * @return void
     */
    public function testIsProductSellableWhenStockFulfilledShouldReturnSuccess(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_DE]);
        $this->createProductWithStock(
            static::ABSTRACT_SKU,
            static::CONCRETE_SKU,
            ['quantity' => 5],
            $storeTransfer
        );

        // Act
        $isProductSellable = $this->getAvailabilityFacade()
            ->isProductSellableForStore(static::CONCRETE_SKU, new Decimal(1), $storeTransfer);

        // Assert
        $this->assertTrue($isProductSellable);
    }

    /**
     * @dataProvider provideTestDecimalQuantity
     *
     * @param \Spryker\DecimalObject\Decimal $quantity
     *
     * @return void
     */
    public function testCalculateStockForProductShouldReturnPersistedStock(Decimal $quantity): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_DE]);
        $this->createProductWithStock(
            static::ABSTRACT_SKU,
            static::CONCRETE_SKU,
            ['quantity' => $quantity->toString()],
            $storeTransfer
        );

        // Act
        $calculatedQuantity = $this->getAvailabilityFacade()
            ->calculateAvailabilityForProductWithStore(static::CONCRETE_SKU, $storeTransfer);

        // Assert
        $this->assertTrue($calculatedQuantity->equals($quantity));
    }

    /**
     * @return array
     */
    public function provideTestDecimalQuantity(): array
    {
        return [
            'int stock' => [new Decimal(5)],
            'float stock' => [new Decimal(5.5)],
        ];
    }

    /**
     * @return void
     */
    public function testCheckAvailabilityPrecoditionShouldNotWriteErrorsWhenAvailabilityIsSatisfied(): void
    {
        // Arrange
        $checkoutResponseTransfer = new CheckoutResponseTransfer();
        $quoteTransfer = $this->createQuoteTransfer();
        $this->createProductWithStock(
            static::ABSTRACT_SKU,
            static::CONCRETE_SKU,
            ['quantity' => 5],
            $quoteTransfer->getStore()
        );

        // Act
        $this->getAvailabilityFacade()
            ->checkoutAvailabilityPreCondition($quoteTransfer, $checkoutResponseTransfer);

        // Assert
        $this->assertEmpty($checkoutResponseTransfer->getErrors());
    }

    /**
     * @return void
     */
    public function testCheckAvailabilityPrecoditionShouldWriteErrorWhenAvailabilityIsNotSatisfied(): void
    {
        // Arrange
        $checkoutResponseTransfer = new CheckoutResponseTransfer();
        $quoteTransfer = $this->createQuoteTransfer();
        $this->createProductWithStock(
            static::ABSTRACT_SKU,
            static::CONCRETE_SKU,
            ['quantity' => 0],
            $quoteTransfer->getStore()
        );

        // Act
        $this->getAvailabilityFacade()
            ->checkoutAvailabilityPreCondition($quoteTransfer, $checkoutResponseTransfer);

        // Assert
        $this->assertNotEmpty($checkoutResponseTransfer->getErrors());
    }

    /**
     * @return void
     */
    public function testUpdateAvailabilityShouldStoreNewQuantity(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_DE]);
        $stockProductEntity = $this->createProductWithStock(
            static::ABSTRACT_SKU,
            static::CONCRETE_SKU,
            ['quantity' => 5],
            $storeTransfer
        );

        $stockProductEntity->setQuantity(50);
        $stockProductEntity->save();

        // Act
        $this->getAvailabilityFacade()->updateAvailability(static::CONCRETE_SKU);

        // Assert
        $availabilityEntity = SpyAvailabilityQuery::create()->findOneBySku(static::CONCRETE_SKU);
        $this->assertNotNull($availabilityEntity);
        $this->assertEquals(50, (new Decimal($availabilityEntity->getQuantity()))->toString());

        $availabilityAbstractEntity = SpyAvailabilityAbstractQuery::create()->findOneByAbstractSku(static::ABSTRACT_SKU);
        $this->assertNotNull($availabilityAbstractEntity);
        $this->assertEquals(50, (new Decimal($availabilityAbstractEntity->getQuantity()))->toString());
    }

    /**
     * @return void
     */
    public function testUpdateAvailabilityWhenItsEmptyShouldStoreNewQuantity(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_DE]);
        $this->createProductWithStock(
            static::ABSTRACT_SKU,
            static::CONCRETE_SKU,
            ['quantity' => 50],
            $storeTransfer
        );
        $this->createProductAvailability(static::ABSTRACT_SKU, static::CONCRETE_SKU, new Decimal(0), $storeTransfer);

        // Act
        $this->getAvailabilityFacade()->updateAvailability(static::CONCRETE_SKU);

        // Assert
        $availabilityEntity = SpyAvailabilityQuery::create()->findOneBySku(static::CONCRETE_SKU);
        $this->assertTrue((new Decimal($availabilityEntity->getQuantity()))->equals(50));
    }

    /**
     * @return void
     */
    public function testUpdateAvailabilityWhenSetToEmptyShouldStoreEmptyQuantity(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_DE]);
        $productTransfer = $this->tester->haveProduct();
        $this->tester->haveProductInStockForStore($storeTransfer, [
            StockProductTransfer::SKU => $productTransfer->getSku(),
            StockProductTransfer::QUANTITY => 0,
            StockProductTransfer::IS_NEVER_OUT_OF_STOCK => false,
        ]);

        // Act
        $this->getAvailabilityFacade()->updateAvailability($productTransfer->getSku());

        // Assert
        $availabilityEntity = SpyAvailabilityQuery::create()->findOneBySku($productTransfer->getSku());
        $this->assertNotNull($availabilityEntity);
        $this->assertEquals(0, (new Decimal($availabilityEntity->getQuantity()))->toString());
        $availabilityAbstractEntity = SpyAvailabilityAbstractQuery::create()->findOneByAbstractSku($productTransfer->getAbstractSku());
        $this->assertNotNull($availabilityAbstractEntity);
        $this->assertEquals(0, (new Decimal($availabilityAbstractEntity->getQuantity()))->toString());
    }

    /**
     * @return void
     */
    public function testFindProductAbstractAvailabilityForStoreWithCachedAvailability(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_DE]);
        $productTransfer = $this->tester->haveProduct([], ['sku' => static::ABSTRACT_SKU]);
        $this->tester->haveAvailabilityAbstract($productTransfer, new Decimal(2));

        // Act
        $productAbstractAvailabilityTransfer = $this->getAvailabilityFacade()
            ->findOrCreateProductAbstractAvailabilityBySkuForStore(
                $productTransfer->getAbstractSku(),
                $storeTransfer
            );

        // Assert
        $this->assertNotNull($productAbstractAvailabilityTransfer);
        $this->assertEquals($productAbstractAvailabilityTransfer->getAvailability()->trim()->toString(), 2);
    }

    /**
     * @return void
     */
    public function testFindProductAbstractAvailabilityForStoreWithInvalidSku(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_DE]);

        // Act
        $productAbstractAvailabilityTransfer = $this->getAvailabilityFacade()
            ->findOrCreateProductAbstractAvailabilityBySkuForStore(
                'xyz' . rand(100, 1000),
                $storeTransfer
            );

        // Assert
        $this->assertNull($productAbstractAvailabilityTransfer);
    }

    /**
     * @return void
     */
    public function testFindProductAbstractAvailabilityForStoreWithStockAndNoCachedAvailability(): void
    {
        // Arrange
        $abstractSku = 'testFindProductAbstractAvailabilityForStoreAbstract';
        $concreteSku1 = 'testFindProductAbstractAvailabilityForStore1';
        $concreteSku2 = 'testFindProductAbstractAvailabilityForStore2';
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_DE]);
        $productQuantity1 = rand(1, 10);
        $productQuantity2 = rand(1, 10);
        $this->createProductWithStock(
            $abstractSku,
            $concreteSku1,
            ['quantity' => $productQuantity1],
            $storeTransfer
        );

        $this->createProductWithStock(
            $abstractSku,
            $concreteSku2,
            ['quantity' => $productQuantity2],
            $storeTransfer
        );

        // Act
        $productAbstractAvailabilityTransfer = $this->getAvailabilityFacade()
            ->findOrCreateProductAbstractAvailabilityBySkuForStore(
                $abstractSku,
                $storeTransfer
            );

        // Assert
        $this->assertNotNull($productAbstractAvailabilityTransfer);
        $this->assertEquals(
            $productAbstractAvailabilityTransfer->getAvailability()->trim()->toString(),
            ($productQuantity1 + $productQuantity2)
        );
    }

    /**
     * @return void
     */
    public function testFindProductConcreteAvailabilityBySkuForStoreWithCachedAvailability(): void
    {
        // Arrange
        $productQuantity = 6;
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_DE]);
        $productTransfer = $this->tester->haveProduct(['sku' => static::CONCRETE_SKU], ['sku' => static::ABSTRACT_SKU]);
        $this->tester->haveAvailabilityConcrete($productTransfer->getSku(), $storeTransfer, new Decimal($productQuantity));

        // Act
        $productConcreteAvailabilityTransfer = $this->getAvailabilityFacade()
            ->findOrCreateProductConcreteAvailabilityBySkuForStore(
                $productTransfer->getSku(),
                $storeTransfer
            );

        // Assert
        $this->assertNotNull($productConcreteAvailabilityTransfer);
        $this->assertEquals($productConcreteAvailabilityTransfer->getAvailability()->trim()->toString(), $productQuantity);
    }

    /**
     * @return void
     */
    public function testFindProductConcreteAvailabilityBySkuForStoreWithStockAndNoCachedAvailability(): void
    {
        // Arrange
        $productQuantity = 13;
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_DE]);
        $stockProductEntity = $this->createProductWithStock(
            static::ABSTRACT_SKU,
            static::CONCRETE_SKU,
            ['quantity' => $productQuantity],
            $storeTransfer
        );

        // Act
        $productConcreteAvailabilityTransfer = $this->getAvailabilityFacade()
            ->findOrCreateProductConcreteAvailabilityBySkuForStore(
                static::CONCRETE_SKU,
                $storeTransfer
            );

        // Assert
        $this->assertNotNull($productConcreteAvailabilityTransfer);
        $this->assertEquals($productQuantity, $productConcreteAvailabilityTransfer->getAvailability()->trim()->toString());
    }

    /**
     * @return void
     */
    public function testFindProductConcreteAvailabilityBySkuForStoreWithInvalidSku(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_DE]);

        // Act
        $productConcreteAvailabilityTransfer = $this->getAvailabilityFacade()
            ->findOrCreateProductConcreteAvailabilityBySkuForStore(
                'xyz' . rand(100, 1000),
                $storeTransfer
            );

        // Assert
        $this->assertNull($productConcreteAvailabilityTransfer);
    }

    /**
     * @return void
     */
    public function testSaveProductAvailabilityForStoreShouldStoreAvailability(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_DE]);
        $this->createProductWithStock(
            static::ABSTRACT_SKU,
            static::CONCRETE_SKU,
            ['quantity' => 0],
            $storeTransfer
        );

        // Act
        $this->getAvailabilityFacade()
            ->saveProductAvailabilityForStore(static::CONCRETE_SKU, new Decimal(2), $storeTransfer);

        // Assert
        $productConcreteAvailabilityTransfer = $this->getAvailabilityFacade()
            ->findProductConcreteAvailability(
                (new ProductConcreteAvailabilityRequestTransfer())
                    ->setSku(static::CONCRETE_SKU)
            );

        $this->assertTrue($productConcreteAvailabilityTransfer->getAvailability()->equals(2));
    }

    /**
     * @return void
     */
    public function testIsProductConcreteAvailable(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveProduct();
        $productConcreteTransfer2 = $this->tester->haveProduct();
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_DE]);

        $this->getAvailabilityFacade()->saveProductAvailabilityForStore(
            $productConcreteTransfer->getSku(),
            new Decimal('1.1'),
            $storeTransfer
        );

        // Act
        $productAvailable = $this->getAvailabilityFacade()
            ->isProductConcreteAvailable($productConcreteTransfer->getIdProductConcrete());

        $productAvailable2 = $this->getAvailabilityFacade()
            ->isProductConcreteAvailable($productConcreteTransfer2->getIdProductConcrete());

        // Assert
        $this->assertTrue($productAvailable);
        $this->assertFalse($productAvailable2);
    }

    /**
     * @return void
     */
    public function testFilterAvailableProductsWithNeverOutOfStock(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveProduct([ProductConcreteTransfer::SKU => static::CONCRETE_SKU]);
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_DE]);
        $this->createProductWithStock(
            static::ABSTRACT_SKU,
            static::CONCRETE_SKU,
            ['is_never_out_of_stock' => true],
            $storeTransfer
        );

        // Act
        $productConcreteTransfers = $this->getAvailabilityFacade()
            ->filterAvailableProducts([$productConcreteTransfer]);

        // Assert
        $this->assertCount(1, $productConcreteTransfers);
    }

    /**
     * @return void
     */
    public function testFilterAvailableProductsWithQuantity(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveProduct([ProductConcreteTransfer::SKU => static::CONCRETE_SKU]);
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_DE]);
        $this->createProductWithStock(
            static::ABSTRACT_SKU,
            static::CONCRETE_SKU,
            ['quantity' => 2],
            $storeTransfer
        );

        // Act
        $productConcreteTransfers = $this->getAvailabilityFacade()
            ->filterAvailableProducts([$productConcreteTransfer]);

        // Assert
        $this->assertCount(1, $productConcreteTransfers);
    }

    /**
     * @return void
     */
    public function testFilterAvailableProductsWithZeroQuantity(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveProduct([ProductConcreteTransfer::SKU => static::CONCRETE_SKU]);
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_DE]);
        $this->createProductWithStock(
            static::ABSTRACT_SKU,
            static::CONCRETE_SKU,
            ['quantity' => 0],
            $storeTransfer
        );

        // Act
        $productConcreteTransfers = $this->getAvailabilityFacade()
            ->filterAvailableProducts([$productConcreteTransfer]);

        // Assert
        $this->assertCount(0, $productConcreteTransfers);
    }

    /**
     * @return void
     */
    public function testFilterAvailableProductsWithoutStock(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveProduct([ProductConcreteTransfer::SKU => static::CONCRETE_SKU]);
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_DE]);

        // Act
        $productConcreteTransfers = $this->getAvailabilityFacade()
            ->filterAvailableProducts([$productConcreteTransfer]);

        // Assert
        $this->assertCount(0, $productConcreteTransfers);
    }

    /**
     * @return void
     */
    public function testFilterAvailableProductsWithSeveralItems(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_DE]);

        $firstProductConcreteTransfer = $this->tester->haveProduct();
        $this->createProductWithStock(
            $firstProductConcreteTransfer->getAbstractSku(),
            $firstProductConcreteTransfer->getSku(),
            ['quantity' => 0],
            $storeTransfer
        );

        $secondProductConcreteTransfer = $this->tester->haveProduct();
        $this->createProductWithStock(
            $firstProductConcreteTransfer->getAbstractSku(),
            $firstProductConcreteTransfer->getSku(),
            ['quantity' => 2],
            $storeTransfer
        );

        // Act
        $productConcreteTransfers = $this->getAvailabilityFacade()
            ->filterAvailableProducts([$firstProductConcreteTransfer, $secondProductConcreteTransfer]);

        // Assert
        $this->assertCount(1, $productConcreteTransfers);
    }

    /**
     * @return \Spryker\Zed\Availability\Business\AvailabilityFacade
     */
    protected function getAvailabilityFacade(): AvailabilityFacade
    {
        /** @var \Spryker\Zed\Availability\Business\AvailabilityFacade $availabilityFacade */
        $availabilityFacade = $this->tester->getFacade();

        $container = new Container();
        $container->set(AvailabilityDependencyProvider::FACADE_STOCK, function () {
            return $this->createStockFacadeMock();
        });
        $availabilityBusinessFactory = new AvailabilityBusinessFactory();
        $dependencyProvider = new AvailabilityDependencyProvider();
        $dependencyProvider->provideBusinessLayerDependencies($container);
        $availabilityBusinessFactory->setContainer($container);
        $availabilityFacade->setFactory($availabilityBusinessFactory);

        return $availabilityFacade;
    }

    /**
     * @param string $abstractSku
     * @param string $concreteSku
     * @param array $stockData
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Orm\Zed\Stock\Persistence\SpyStockProduct
     */
    protected function createProductWithStock(
        string $abstractSku,
        string $concreteSku,
        array $stockData,
        StoreTransfer $storeTransfer
    ): SpyStockProduct {
        $productAbstractEntity = (new SpyProductAbstractQuery())
            ->filterBySku($abstractSku)
            ->findOneOrCreate();
        $productAbstractEntity->setAttributes('');
        $productAbstractEntity->save();

        $productEntity = (new SpyProductQuery())
            ->filterBySku($concreteSku)
            ->findOneOrCreate();

        $productEntity->setAttributes('');
        $productEntity->setIsActive(true);
        $productEntity->setFkProductAbstract($productAbstractEntity->getIdProductAbstract());
        $productEntity->save();

        $stockEntity = (new SpyStockQuery())
            ->filterByName('Warehouse1')
            ->findOneOrCreate();

        $stockEntity->save();

        $stockProductEntity = (new SpyStockProductQuery())
            ->filterByFkProduct($productEntity->getIdProduct())
            ->filterByFkStock($stockEntity->getIdStock())
            ->findOneOrCreate();

        $stockProductEntity->fromArray($stockData);
        $stockProductEntity->save();

        $this->getAvailabilityFacade()->updateAvailabilityForStore($concreteSku, $storeTransfer);

        return $stockProductEntity;
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createQuoteTransfer(): QuoteTransfer
    {
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_DE]);
        $quoteTransfer = new QuoteTransfer();
        $quoteTransfer->setStore($storeTransfer);
        $itemTransfer = new ItemTransfer();
        $itemTransfer->setSku(static::CONCRETE_SKU);
        $itemTransfer->setQuantity(1);
        $quoteTransfer->addItem($itemTransfer);

        return $quoteTransfer;
    }

    /**
     * @param string $abstractSku
     * @param string $concreteSku
     * @param \Spryker\DecimalObject\Decimal $quantity
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Orm\Zed\Availability\Persistence\SpyAvailability
     */
    protected function createProductAvailability(
        string $abstractSku,
        string $concreteSku,
        Decimal $quantity,
        StoreTransfer $storeTransfer
    ): SpyAvailability {
        $availabilityAbstractEntity = (new SpyAvailabilityAbstractQuery())
            ->filterByAbstractSku($abstractSku)
            ->filterByFkStore($storeTransfer->getIdStore())
            ->findOneOrCreate();

        $availabilityAbstractEntity->setQuantity($quantity)->save();

        $availabilityEntity = (new SpyAvailabilityQuery())
            ->filterByFkAvailabilityAbstract($availabilityAbstractEntity->getIdAvailabilityAbstract())
            ->filterByFkStore($storeTransfer->getIdStore())
            ->filterBySku($concreteSku)
            ->findOneOrCreate();

        $availabilityEntity->setQuantity($quantity)->save();

        return $availabilityEntity;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStockFacadeInterface
     */
    protected function createStockFacadeMock(): AvailabilityToStockFacadeInterface
    {
        $mock = $this->getMockBuilder(AvailabilityToStockFacadeInterface::class)->getMock();
        $mock->method('getStoreToWarehouseMapping')
            ->willReturn([static::STORE_NAME_DE => ['Warehouse1']]);
        $mock->method('getStoresWhereProductStockIsDefined')
            ->willReturn((new StoreTransfer())->setName(static::STORE_NAME_DE));

        return $mock;
    }
}
