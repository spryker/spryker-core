<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductBundle\Business\Availability\PreCheck;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\StoreBuilder;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Product\Persistence\SpyProduct;
use Orm\Zed\ProductBundle\Persistence\SpyProductBundle;
use Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToAvailabilityFacadeInterface;
use Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToStoreFacadeInterface;

class PreCheckMocks extends Unit
{
    public const ID_STORE = 1;
    /**
     * @var array
     */
    protected $fixtures = [
        'idProductConcrete' => 1,
        'bundledProductSku' => 'sku-123',
        'fkBundledProduct' => 2,
        'bundledProductQuantity' => 5,
        'idProductBundle' => 1,
        'bundle-sku' => 'sku-321',
    ];

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToAvailabilityFacadeInterface
     */
    protected function createAvailabilityFacadeMock(): ProductBundleToAvailabilityFacadeInterface
    {
        return $this->getMockBuilder(ProductBundleToAvailabilityFacadeInterface::class)->getMock();
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createTestQuoteTransfer(): QuoteTransfer
    {
        $quoteTransfer = new QuoteTransfer();

        $quoteTransfer->setStore((new StoreTransfer())->setName('DE'));

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setSku($this->fixtures['bundle-sku']);
        $itemTransfer->setQuantity(5);

        $quoteTransfer->addItem($itemTransfer);

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setSku($this->fixtures['bundle-sku']);

        $quoteTransfer->addBundleItem($itemTransfer);

        return $quoteTransfer;
    }

    /**
     * @param array $fixtures
     * @param \Spryker\Zed\ProductBundle\Business\ProductBundle\Availability\PreCheck\ProductBundleCartAvailabilityCheckInterface|\PHPUnit\Framework\MockObject\MockObject $productBundleAvailabilityCheckMock
     *
     * @return void
     */
    protected function setupFindBundledProducts(array $fixtures, $productBundleAvailabilityCheckMock): void
    {
        $productBundleEntity = new SpyProductBundle();
        $productBundleEntity->setIdProductBundle($fixtures['idProductConcrete']);
        $productBundleEntity->setQuantity($fixtures['bundledProductQuantity']);

        $productEntity = new SpyProduct();
        $productEntity->setIdProduct($fixtures['fkBundledProduct']);
        $productEntity->setSku($fixtures['bundledProductSku']);
        $productEntity->setIsActive(true);

        $productBundleEntity->setSpyProductRelatedByFkBundledProduct($productEntity);

        $productConcreteEntity = new SpyProduct();
        $productConcreteEntity->setIdProduct($fixtures['fkBundledProduct']);
        $productConcreteEntity->setSku($fixtures['bundledProductSku']);
        $productConcreteEntity->setIsActive(true);

        $productBundleEntity->setSpyProductRelatedByFkProduct($productConcreteEntity);

        $productBundleEntity->setFkBundledProduct($fixtures['fkBundledProduct']);

        $productBundleAvailabilityCheckMock->expects($this->once())
            ->method('findBundledProducts')
            ->with($this->fixtures['bundle-sku'])
            ->willReturn([$productBundleEntity]);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToStoreFacadeInterface
     */
    protected function getStoreFacadeMock(): ProductBundleToStoreFacadeInterface
    {
        $storeFacadeMock = $this->getMockBuilder(ProductBundleToStoreFacadeInterface::class)->getMock();

        $storeTransfer = (new StoreBuilder([
            StoreTransfer::ID_STORE => self::ID_STORE,
        ]))->build();

        $storeFacadeMock->method('getCurrentStore')->willReturn($storeTransfer);
        $storeFacadeMock->method('getStoreByName')->willReturn($storeTransfer);

        return $storeFacadeMock;
    }
}
