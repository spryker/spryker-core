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
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\ProductBundle\Business\ProductBundle\Availability\PreCheck\BasePreCheck;
use Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToAvailabilityInterface;
use Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToStoreFacadeInterface;
use Spryker\Zed\ProductBundle\Dependency\QueryContainer\ProductBundleToAvailabilityQueryContainerInterface;

class PreCheckMocks extends Unit
{
    const ID_STORE = 1;
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
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToAvailabilityInterface
     */
    protected function createAvailabilityFacadeMock()
    {
        return $this->getMockBuilder(ProductBundleToAvailabilityInterface::class)->getMock();
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createTestQuoteTransfer()
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
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\ProductBundle\Dependency\QueryContainer\ProductBundleToAvailabilityQueryContainerInterface
     */
    protected function createAvailabilityQueryContainerMock()
    {
        return $this->getMockBuilder(ProductBundleToAvailabilityQueryContainerInterface::class)->getMock();
    }

    /**
     * @param array $fixtures
     * @param \Spryker\Zed\ProductBundle\Business\ProductBundle\Availability\PreCheck\BasePreCheck|\PHPUnit_Framework_MockObject_MockObject $productBundleAvailabilityCheckMock
     *
     * @return void
     */
    protected function setupFindBundledProducts(array $fixtures, BasePreCheck $productBundleAvailabilityCheckMock)
    {
        $productBundleEntity = new SpyProductBundle();
        $productBundleEntity->setIdProductBundle($fixtures['idProductConcrete']);
        $productBundleEntity->setQuantity($fixtures['bundledProductQuantity']);

        $productEntity = new SpyProduct();
        $productEntity->setIdProduct($fixtures['fkBundledProduct']);
        $productEntity->setSku($fixtures['bundledProductSku']);

        $productBundleEntity->setSpyProductRelatedByFkBundledProduct($productEntity);

        $productBundleEntity->setFkBundledProduct($fixtures['fkBundledProduct']);

        $bundledProducts = new ObjectCollection();
        $bundledProducts->append($productBundleEntity);

        $productBundleAvailabilityCheckMock->expects($this->once())
            ->method('findBundledProducts')
            ->with($this->fixtures['bundle-sku'])
            ->willReturn($bundledProducts);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToStoreFacadeInterface
     */
    protected function createStoreFacadeMock()
    {
        return $this->getMockBuilder(ProductBundleToStoreFacadeInterface::class)->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToStoreFacadeInterface
     */
    protected function buildStoreFacadeMock()
    {
        $storeFacadeMock = $this->createStoreFacadeMock();
        $storeTransfer = (new StoreBuilder([
            StoreTransfer::ID_STORE => self::ID_STORE,
        ]))->build();

        $storeFacadeMock->method('getCurrentStore')->willReturn($storeTransfer);
        $storeFacadeMock->method('getStoreByName')->willReturn($storeTransfer);

        return $storeFacadeMock;
    }
}
