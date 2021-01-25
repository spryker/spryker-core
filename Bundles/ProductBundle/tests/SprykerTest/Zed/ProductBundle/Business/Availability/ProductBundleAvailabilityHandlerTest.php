<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductBundle\Business\Availability;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\StoreBuilder;
use Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Product\Persistence\SpyProduct;
use Orm\Zed\ProductBundle\Persistence\SpyProductBundle;
use Spryker\DecimalObject\Decimal;
use Spryker\Zed\ProductBundle\Business\ProductBundle\Availability\ProductBundleAvailabilityHandler;
use Spryker\Zed\ProductBundle\Business\ProductBundle\Availability\ProductBundleAvailabilityHandlerInterface;
use Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToAvailabilityFacadeInterface;
use Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToStockFacadeInterface;
use Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToStoreFacadeInterface;
use Spryker\Zed\ProductBundle\Persistence\ProductBundleQueryContainerInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductBundle
 * @group Business
 * @group Availability
 * @group ProductBundleAvailabilityHandlerTest
 * Add your own group annotations below this line
 */
class ProductBundleAvailabilityHandlerTest extends Unit
{
    public const ID_STORE = 1;
    protected const STORE_NAME = 'DE';

    /**
     * @return void
     */
    public function testUpdateAffectedBundlesAvailabilityShouldUpdateAffectedBundlesAvailability(): void
    {
        $bundleSku = 'sku-2';
        $bundledItemSku = 'sku-3';
        $bundleQuantity = 2;
        $bundledItemAvailability = new Decimal(15);
        $expectedBundleAvailability = new Decimal(7); // floor(15 / 2)

        $availabilityFacadeMock = $this->createAvailabilityFacadeMock();
        $productBundleAvailabilityHandlerMock = $this->createProductBundleAvailabilityHandler($availabilityFacadeMock);

        $availabilityFacadeMock->method('findOrCreateProductConcreteAvailabilityBySkuForStore')
            ->willReturn(
                (new ProductConcreteAvailabilityTransfer())
                    ->setSku($bundledItemSku)
                    ->setAvailability($bundledItemAvailability)
            );

        $bundledProducts = [];
        $productBundleEntity = new SpyProductBundle();
        $productEntity = new SpyProduct();

        $productEntity->setSku($bundleSku);
        $productBundleEntity->setSpyProductRelatedByFkProduct($productEntity);
        $bundledProducts[] = $productBundleEntity;

        $productBundleAvailabilityHandlerMock->method('getBundlesUsingProductBySku')
            ->willReturn($bundledProducts);

        $this->setupGetBundleItemsByIdProduct($bundleQuantity, $bundledItemSku, $productBundleAvailabilityHandlerMock);
        $this->setupProductBundleAvailability($bundledItemAvailability, $productBundleAvailabilityHandlerMock);

        $availabilityFacadeMock->expects($this->once())
            ->method('saveProductAvailabilityForStore')
            ->with($bundleSku, $expectedBundleAvailability);

        $productBundleAvailabilityHandlerMock->updateAffectedBundlesAvailability('sku-1');
    }

    /**
     * @return void
     */
    public function testUpdateBundleAvailabilityShouldUpdateGivenBundleAvailability(): void
    {
        $bundleSku = 'sku-2';
        $bundledItemSku = 'sku-3';
        $bundleQuantity = 2;
        $bundledItemAvailability = new Decimal(15);
        $expectedBundleAvailability = new Decimal(7); // floor(15 / 2)

        $availabilityFacadeMock = $this->createAvailabilityFacadeMock();
        $productBundleAvailabilityHandlerMock = $this->createProductBundleAvailabilityHandler($availabilityFacadeMock);

        $availabilityFacadeMock->method('findOrCreateProductConcreteAvailabilityBySkuForStore')
            ->willReturn(
                (new ProductConcreteAvailabilityTransfer())
                    ->setSku($bundledItemSku)
                    ->setAvailability($bundledItemAvailability)
            );

        $bundleProductEntity = new SpyProductBundle();

        $productBundleAvailabilityHandlerMock->method('findBundleProductEntityBySku')->willReturn($bundleProductEntity);

        $availabilityFacadeMock->expects($this->once())
            ->method('saveProductAvailabilityForStore')
            ->with($bundleSku, $expectedBundleAvailability);

        $this->setupProductBundleAvailability($bundledItemAvailability, $productBundleAvailabilityHandlerMock);
        $this->setupGetBundleItemsByIdProduct($bundleQuantity, $bundledItemSku, $productBundleAvailabilityHandlerMock);

        $productBundleAvailabilityHandlerMock->updateBundleAvailability($bundleSku);
    }

    /**
     * @param \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToAvailabilityFacadeInterface|null $availabilityFacadeMock
     * @param \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToStoreFacadeInterface|null $storeFacadeMock
     * @param \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToStockFacadeInterface|null $stockFacadeMock
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ProductBundle\Business\ProductBundle\Availability\ProductBundleAvailabilityHandlerInterface
     */
    protected function createProductBundleAvailabilityHandler(
        ?ProductBundleToAvailabilityFacadeInterface $availabilityFacadeMock = null,
        ?ProductBundleToStoreFacadeInterface $storeFacadeMock = null,
        ?ProductBundleToStockFacadeInterface $stockFacadeMock = null
    ): ProductBundleAvailabilityHandlerInterface {
        $productBundleQueryContainerMock = $this->createProductBundleQueryContainerMock();

        if ($availabilityFacadeMock === null) {
            $availabilityFacadeMock = $this->createAvailabilityFacadeMock();
        }

        if ($stockFacadeMock === null) {
            $stockFacadeMock = $this->createStockFacadeMock();
            $storeTransfer = (new StoreBuilder([
                StoreTransfer::ID_STORE => static::ID_STORE,
                StoreTransfer::NAME => static::STORE_NAME,
            ]))->build();
            $stockFacadeMock->method('getStoresWhereProductStockIsDefined')
                ->willReturn([$storeTransfer]);
        }

        return $this->getMockBuilder(ProductBundleAvailabilityHandler::class)
            ->setConstructorArgs([$availabilityFacadeMock, $productBundleQueryContainerMock, $stockFacadeMock])
            ->setMethods(['getBundleItemsByIdProduct', 'getBundlesUsingProductBySku', 'findBundleProductEntityBySku', 'findBundledItemAvailabilityEntityBySku'])
            ->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToAvailabilityFacadeInterface
     */
    protected function createAvailabilityFacadeMock(): ProductBundleToAvailabilityFacadeInterface
    {
        return $this->getMockBuilder(ProductBundleToAvailabilityFacadeInterface::class)->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ProductBundle\Persistence\ProductBundleQueryContainerInterface
     */
    protected function createProductBundleQueryContainerMock(): ProductBundleQueryContainerInterface
    {
        return $this->getMockBuilder(ProductBundleQueryContainerInterface::class)->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToStockFacadeInterface
     */
    protected function createStockFacadeMock(): ProductBundleToStockFacadeInterface
    {
        return $this->getMockBuilder(ProductBundleToStockFacadeInterface::class)->getMock();
    }

    /**
     * @param int $bundleQuantity
     * @param string $bundledItemSku
     * @param \Spryker\Zed\ProductBundle\Business\ProductBundle\Availability\ProductBundleAvailabilityHandlerInterface|\PHPUnit\Framework\MockObject\MockObject $productBundleAvailabilityHandlerMock
     *
     * @return void
     */
    protected function setupGetBundleItemsByIdProduct(
        int $bundleQuantity,
        string $bundledItemSku,
        ProductBundleAvailabilityHandlerInterface $productBundleAvailabilityHandlerMock
    ): void {
        $bundleItems = [];
        $productBundleEntity = new SpyProductBundle();
        $productBundleEntity->setQuantity($bundleQuantity);
        $productEntity = new SpyProduct();

        $productEntity->setSku($bundledItemSku);
        $productBundleEntity->setSpyProductRelatedByFkBundledProduct($productEntity);
        $bundleItems[] = $productBundleEntity;

        $productBundleAvailabilityHandlerMock->method('getBundleItemsByIdProduct')
            ->willReturn($bundleItems);
    }

    /**
     * @param \Spryker\DecimalObject\Decimal $bundledItemAvailability
     * @param \Spryker\Zed\ProductBundle\Business\ProductBundle\Availability\ProductBundleAvailabilityHandlerInterface|\PHPUnit\Framework\MockObject\MockObject $productBundleAvailabilityHandlerMock
     *
     * @return void
     */
    protected function setupProductBundleAvailability(
        Decimal $bundledItemAvailability,
        ProductBundleAvailabilityHandlerInterface $productBundleAvailabilityHandlerMock
    ): void {
        $availabilityTransfer = new ProductConcreteAvailabilityTransfer();
        $availabilityTransfer->setAvailability($bundledItemAvailability);

        $productBundleAvailabilityHandlerMock->method('findBundledItemAvailabilityEntityBySku')
            ->willReturn($availabilityTransfer);
    }
}
