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
use PHPUnit\Framework\MockObject\MockObject;
use Spryker\DecimalObject\Decimal;
use Spryker\Zed\ProductBundle\Business\ProductBundle\Availability\ProductBundleAvailabilityHandler;
use Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToAvailabilityFacadeInterface;
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

    /**
     * @return void
     */
    public function testUpdateAffectedBundlesAvailabilityShouldUpdateAffectedBundlesAvailability()
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
    public function testUpdateBundleAvailabilityShouldUpdateGivenBundleAvailability()
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
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ProductBundle\Business\ProductBundle\Availability\ProductBundleAvailabilityHandlerInterface
     */
    protected function createProductBundleAvailabilityHandler(
        ?ProductBundleToAvailabilityFacadeInterface $availabilityFacadeMock = null,
        ?ProductBundleToStoreFacadeInterface $storeFacadeMock = null
    ) {
        $productBundleQueryContainerMock = $this->createProductBundleQueryContainerMock();

        if ($availabilityFacadeMock === null) {
            $availabilityFacadeMock = $this->createAvailabilityFacadeMock();
        }

        if ($storeFacadeMock === null) {
            $storeFacadeMock = $this->createStoreFacadeMock();
            $storeTransfer = (new StoreBuilder([
                StoreTransfer::ID_STORE => self::ID_STORE,
            ]))->build();
            $storeFacadeMock->method('getCurrentStore')->willReturn($storeTransfer);
            $storeFacadeMock->method('getStoreByName')->willReturn($storeTransfer);
        }

        return $this->getMockBuilder(ProductBundleAvailabilityHandler::class)
            ->setConstructorArgs([$availabilityFacadeMock, $productBundleQueryContainerMock, $storeFacadeMock])
            ->setMethods(['getBundleItemsByIdProduct', 'getBundlesUsingProductBySku', 'findBundleProductEntityBySku', 'findBundledItemAvailabilityEntityBySku'])
            ->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToAvailabilityFacadeInterface
     */
    protected function createAvailabilityFacadeMock()
    {
        return $this->getMockBuilder(ProductBundleToAvailabilityFacadeInterface::class)->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ProductBundle\Persistence\ProductBundleQueryContainerInterface
     */
    protected function createProductBundleQueryContainerMock()
    {
        return $this->getMockBuilder(ProductBundleQueryContainerInterface::class)->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToStoreFacadeInterface
     */
    protected function createStoreFacadeMock()
    {
        return $this->getMockBuilder(ProductBundleToStoreFacadeInterface::class)->getMock();
    }

    /**
     * @param int $bundleQuantity
     * @param string $bundledItemSku
     * @param \PHPUnit\Framework\MockObject\MockObject $productBundleAvailabilityHandlerMock
     *
     * @return void
     */
    protected function setupGetBundleItemsByIdProduct($bundleQuantity, $bundledItemSku, MockObject $productBundleAvailabilityHandlerMock)
    {
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
     * @param \PHPUnit\Framework\MockObject\MockObject $productBundleAvailabilityHandlerMock
     *
     * @return void
     */
    protected function setupProductBundleAvailability(Decimal $bundledItemAvailability, MockObject $productBundleAvailabilityHandlerMock): void
    {
        $availabilityTransfer = new ProductConcreteAvailabilityTransfer();
        $availabilityTransfer->setAvailability($bundledItemAvailability);

        $productBundleAvailabilityHandlerMock->method('findBundledItemAvailabilityEntityBySku')
            ->willReturn($availabilityTransfer);
    }
}
