<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductBundle\Business\Availability;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\StoreBuilder;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Availability\Persistence\SpyAvailability;
use Orm\Zed\Product\Persistence\SpyProduct;
use Orm\Zed\ProductBundle\Persistence\SpyProductBundle;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\ProductBundle\Business\ProductBundle\Availability\ProductBundleAvailabilityHandler;
use Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToAvailabilityInterface;
use Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToStoreFacadeInterface;
use Spryker\Zed\ProductBundle\Dependency\QueryContainer\ProductBundleToAvailabilityQueryContainerInterface;
use Spryker\Zed\ProductBundle\Persistence\ProductBundleQueryContainerInterface;

/**
 * Auto-generated group annotations
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
        $bundledItemAvailability = 10;
        $expectedBundleAvailability = $bundledItemAvailability / $bundleQuantity; //5

        $availabilityFacadeMock = $this->createAvailabilityFacadeMock();
        $productBundleAvailabilityHandlerMock = $this->createProductBundleAvailabilityHandler($availabilityFacadeMock);

        $bundledProducts = new ObjectCollection();
        $productBundleEntity = new SpyProductBundle();
        $productEntity = new SpyProduct();

        $productEntity->setSku($bundleSku);
        $productBundleEntity->setSpyProductRelatedByFkProduct($productEntity);
        $bundledProducts->append($productBundleEntity);

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
        $bundledItemAvailability = 10;
        $expectedBundleAvailability = $bundledItemAvailability / $bundleQuantity; //5

        $availabilityFacadeMock = $this->createAvailabilityFacadeMock();
        $productBundleAvailabilityHandlerMock = $this->createProductBundleAvailabilityHandler($availabilityFacadeMock);

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
     * @param \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToAvailabilityInterface|null $availabilityFacadeMock
     * @param \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToStoreFacadeInterface|null $storeFacadeMock
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ProductBundle\Business\ProductBundle\Availability\ProductBundleAvailabilityHandler
     */
    protected function createProductBundleAvailabilityHandler(
        ?ProductBundleToAvailabilityInterface $availabilityFacadeMock = null,
        ?ProductBundleToStoreFacadeInterface $storeFacadeMock = null
    ) {
        $productBundleQueryContainerMock = $this->createProductBundleQueryContainerMock();
        $availabilityQueryContainerMock = $this->createAvailabilityQueryContainerMock();

        if ($availabilityFacadeMock === null) {
            $availabilityFacadeMock = $this->createAvailabilityFacadeMock();
        }

        if ($storeFacadeMock === null) {
            $storeFacadeMock = $this->createStoreFacadeMock();
            $storeTransfer = (new StoreBuilder([
                StoreTransfer::ID_STORE => self::ID_STORE,
            ]))
            ->build();
            $storeFacadeMock->method('getCurrentStore')->willReturn($storeTransfer);
            $storeFacadeMock->method('getStoreByName')->willReturn($storeTransfer);
        }

        return $this->getMockBuilder(ProductBundleAvailabilityHandler::class)
            ->setConstructorArgs([$availabilityQueryContainerMock, $availabilityFacadeMock, $productBundleQueryContainerMock, $storeFacadeMock])
            ->setMethods(['getBundleItemsByIdProduct', 'getBundlesUsingProductBySku', 'findBundleProductEntityBySku', 'findBundledItemAvailabilityEntityBySku'])
            ->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ProductBundle\Dependency\QueryContainer\ProductBundleToAvailabilityQueryContainerInterface
     */
    protected function createAvailabilityQueryContainerMock()
    {
        return $this->getMockBuilder(ProductBundleToAvailabilityQueryContainerInterface::class)->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToAvailabilityInterface
     */
    protected function createAvailabilityFacadeMock()
    {
        return $this->getMockBuilder(ProductBundleToAvailabilityInterface::class)->getMock();
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
     * @param \Spryker\Zed\ProductBundle\Business\ProductBundle\Availability\ProductBundleAvailabilityHandler $productBundleAvailabilityHandlerMock
     *
     * @return void
     */
    protected function setupGetBundleItemsByIdProduct($bundleQuantity, $bundledItemSku, ProductBundleAvailabilityHandler $productBundleAvailabilityHandlerMock)
    {
        $bundleItems = new ObjectCollection();
        $productBundleEntity = new SpyProductBundle();
        $productBundleEntity->setQuantity($bundleQuantity);
        $productEntity = new SpyProduct();

        $productEntity->setSku($bundledItemSku);
        $productBundleEntity->setSpyProductRelatedByFkBundledProduct($productEntity);
        $bundleItems->append($productBundleEntity);

        $productBundleAvailabilityHandlerMock->method('getBundleItemsByIdProduct')
            ->willReturn($bundleItems);
    }

    /**
     * @param int $bundledItemAvailability
     * @param \Spryker\Zed\ProductBundle\Business\ProductBundle\Availability\ProductBundleAvailabilityHandler $productBundleAvailabilityHandlerMock
     *
     * @return void
     */
    protected function setupProductBundleAvailability($bundledItemAvailability, ProductBundleAvailabilityHandler $productBundleAvailabilityHandlerMock)
    {
        $availabilityEntity = new SpyAvailability();
        $availabilityEntity->setQuantity($bundledItemAvailability);

        $productBundleAvailabilityHandlerMock->method('findBundledItemAvailabilityEntityBySku')
            ->willReturn($availabilityEntity);
    }
}
