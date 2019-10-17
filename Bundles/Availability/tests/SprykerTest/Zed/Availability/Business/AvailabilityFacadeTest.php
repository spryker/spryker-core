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
use Generated\Shared\Transfer\QuoteTransfer;
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
use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStockInterface;
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
    public const DE_STORE_NAME = 'DE';

    /**
     * @var \SprykerTest\Zed\Availability\AvailabilityBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testIsProductSellableWhenNeverOutOfStockShouldReturnSuccess()
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::DE_STORE_NAME]);
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
    public function testIsProductSellableWhenStockIsEmptyShouldReturnFailure()
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::DE_STORE_NAME]);
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
    public function testIsProductSellableWhenStockFulfilledShouldReturnSuccess()
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::DE_STORE_NAME]);
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
    public function testCalculateStockForProductShouldReturnPersistedStock(Decimal $quantity)
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::DE_STORE_NAME]);
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
    public function testCheckAvailabilityPrecoditionShouldNotWriteErrorsWhenAvailabilityIsSatisfied()
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
    public function testCheckAvailabilityPrecoditionShouldWriteErrorWhenAvailabilityIsNotSatisfied()
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
    public function testUpdateAvailabilityShouldStoreNewQuantity()
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::DE_STORE_NAME]);
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
        $this->assertTrue((new Decimal($availabilityEntity->getQuantity()))->equals(50));
    }

    /**
     * @return void
     */
    public function testUpdateAvailabilityWhenItsEmptyShouldStoreNewQuantity()
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::DE_STORE_NAME]);
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
    public function testUpdateAvailabilityWhenSetToEmptyShouldStoreEmptyQuantity()
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::DE_STORE_NAME]);
        $this->createProductWithStock(
            static::ABSTRACT_SKU,
            static::CONCRETE_SKU,
            ['quantity' => 0],
            $storeTransfer
        );
        $availabilityEntity = $this->createProductAvailability(static::ABSTRACT_SKU, static::CONCRETE_SKU, new Decimal(5), $storeTransfer);

        // Assert
        $this->assertSame('5', $availabilityEntity->getQuantity());

        // Act
        $this->getAvailabilityFacade()->updateAvailability(static::CONCRETE_SKU);

        // Assert
        $availabilityEntity = SpyAvailabilityQuery::create()->findOneBySku(static::CONCRETE_SKU);
        $this->assertTrue((new Decimal($availabilityEntity->getQuantity()))->isZero());
    }

    /**
     * @return void
     */
    public function testFindProductAbstractAvailabilityForStore(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::DE_STORE_NAME]);
        $productTransfer = $this->tester->haveProduct([], ['sku' => static::ABSTRACT_SKU]);
        $this->createProductWithStock(
            static::ABSTRACT_SKU,
            static::CONCRETE_SKU,
            ['quantity' => 3],
            $storeTransfer
        );

        // Act
        $this->getAvailabilityFacade()->saveProductAvailabilityForStore(
            static::CONCRETE_SKU,
            new Decimal(2),
            $storeTransfer
        );

        // Assert
        $productAbstractAvailabilityTransfer = $this->getAvailabilityFacade()
            ->findProductAbstractAvailabilityBySkuForStore(
                $productTransfer->getAbstractSku(),
                $storeTransfer
            );

        $this->assertNotNull($productAbstractAvailabilityTransfer);
        $this->assertTrue($productAbstractAvailabilityTransfer->getAvailability()->equals(2));
    }

    /**
     * @return void
     */
    public function testSaveProductAvailabilityForStoreShouldStoreAvailability()
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::DE_STORE_NAME]);
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
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::DE_STORE_NAME]);

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
     * @return \Spryker\Zed\Availability\Business\AvailabilityFacadeInterface
     */
    protected function getAvailabilityFacade()
    {
        /** @var \Spryker\Zed\Availability\Business\AvailabilityFacade $AvailabilityFacade */
        $AvailabilityFacade = $this->tester->getFacade();

        $container = new Container();
        $container->set(AvailabilityDependencyProvider::FACADE_STOCK, function () {
            return $this->getMockBuilder(AvailabilityToStockInterface::class)
                ->getMock()
                ->method('getStoreToWarehouseMapping')
                ->willReturn([
                    static::DE_STORE_NAME => ['Warehouse1'],
                ]);
        });
        $availabilityBusinessFactory = new AvailabilityBusinessFactory();
        $dependencyProvider = new AvailabilityDependencyProvider();
        $dependencyProvider->provideBusinessLayerDependencies($container);
        $availabilityBusinessFactory->setContainer($container);
        $AvailabilityFacade->setFactory($availabilityBusinessFactory);

        return $AvailabilityFacade;
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
    protected function createQuoteTransfer()
    {
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::DE_STORE_NAME]);
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
}
