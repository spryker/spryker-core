<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductBundle\Business;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\StoreBuilder;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Availability\Persistence\SpyAvailability;
use Orm\Zed\Product\Persistence\SpyProduct;
use Orm\Zed\ProductBundle\Persistence\SpyProductBundle;
use PHPUnit\Framework\MockObject\MockObject;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\DecimalObject\Decimal;
use Spryker\Zed\ProductBundle\Business\ProductBundle\ProductBundleReader;
use Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToStoreFacadeInterface;
use Spryker\Zed\ProductBundle\Dependency\QueryContainer\ProductBundleToAvailabilityQueryContainerInterface;
use Spryker\Zed\ProductBundle\Persistence\ProductBundleQueryContainerInterface;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group ProductBundle
 * @group Business
 * @group ProductBundleReaderTest
 * Add your own group annotations below this line
 */
class ProductBundleReaderTest extends Unit
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
    ];

    /**
     * @return void
     */
    public function testFindBundledProductsByIdProductConcreteShouldBuildTransferCollectionFromPersistence()
    {
        $productBundleReaderMock = $this->createProductBundleReader();

        $this->setupFindBundledProducts($this->fixtures, $productBundleReaderMock);

        $bundledProductsTransferCollection = $productBundleReaderMock->findBundledProductsByIdProductConcrete($this->fixtures['idProductConcrete']);

        $productForBundleTransfer = $bundledProductsTransferCollection[0];
        $this->assertSame($this->fixtures['bundledProductSku'], $productForBundleTransfer->getSku());
        $this->assertSame($this->fixtures['fkBundledProduct'], $productForBundleTransfer->getIdProductConcrete());
        $this->assertTrue((new Decimal($this->fixtures['bundledProductQuantity']))->equals($productForBundleTransfer->getQuantity()));
        $this->assertSame($this->fixtures['idProductBundle'], $productForBundleTransfer->getIdProductBundle());
    }

    /**
     * @return void
     */
    public function testAssignBundledProductsToProductConcreteShouldAssignBundledProductsAndAvailability()
    {
        $bundleAvailability = 5;

        $productBundleReaderMock = $this->createProductBundleReader();

        $this->setupFindBundledProducts($this->fixtures, $productBundleReaderMock);

        $productConcreteTransfer = new ProductConcreteTransfer();
        $productConcreteTransfer->setIdProductConcrete($this->fixtures['idProductConcrete']);
        $productConcreteTransfer->setSku('sku-2');

        $availabilityEntity = new SpyAvailability();
        $availabilityEntity->setQuantity($bundleAvailability);

        $productBundleReaderMock->method('findOrCreateProductBundleAvailabilityEntity')
            ->willReturn($availabilityEntity);

        $productConcreteTransfer = $productBundleReaderMock->assignBundledProductsToProductConcrete($productConcreteTransfer);

        $productBundleTransfer = $productConcreteTransfer->getProductBundle();

        $this->assertNotNull($productBundleTransfer);
        $this->assertTrue($productBundleTransfer->getAvailability()->equals($bundleAvailability));
        $this->assertCount(1, $productBundleTransfer->getBundledProducts());
    }

    /**
     * @param \Spryker\Zed\ProductBundle\Persistence\ProductBundleQueryContainerInterface|null $productBundleQueryContainerMock
     * @param \Spryker\Zed\ProductBundle\Dependency\QueryContainer\ProductBundleToAvailabilityQueryContainerInterface|null $productBundleToAvailabilityQueryContainerMock
     * @param \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToStoreFacadeInterface|null $storeFacadeMock
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ProductBundle\Business\ProductBundleFacadeInterface
     */
    protected function createProductBundleReader(
        ?ProductBundleQueryContainerInterface $productBundleQueryContainerMock = null,
        ?ProductBundleToAvailabilityQueryContainerInterface $productBundleToAvailabilityQueryContainerMock = null,
        ?ProductBundleToStoreFacadeInterface $storeFacadeMock = null
    ) {
        if ($productBundleQueryContainerMock === null) {
            $productBundleQueryContainerMock = $this->createProductQueryContainerMock();
        }

        if ($productBundleToAvailabilityQueryContainerMock === null) {
            $productBundleToAvailabilityQueryContainerMock = $this->createAvailabilityQueryContainerMock();
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

        $productBundleReaderMock = $this->getMockBuilder(ProductBundleReader::class)
            ->setConstructorArgs([$productBundleQueryContainerMock, $productBundleToAvailabilityQueryContainerMock, $storeFacadeMock ])
            ->setMethods(['findBundledProducts', 'findOrCreateProductBundleAvailabilityEntity'])
            ->getMock();

        return $productBundleReaderMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ProductBundle\Persistence\ProductBundleQueryContainerInterface
     */
    protected function createProductQueryContainerMock()
    {
        return $this->getMockBuilder(ProductBundleQueryContainerInterface::class)->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ProductBundle\Dependency\QueryContainer\ProductBundleToAvailabilityQueryContainerInterface
     */
    protected function createAvailabilityQueryContainerMock()
    {
        return $this->getMockBuilder(ProductBundleToAvailabilityQueryContainerInterface::class)->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToStoreFacadeInterface
     */
    protected function createStoreFacadeMock()
    {
        return $this->getMockBuilder(ProductBundleToStoreFacadeInterface::class)->getMock();
    }

    /**
     * @param array $fixtures
     * @param \Spryker\Zed\ProductBundle\Business\ProductBundle\ProductBundleReaderInterface|\PHPUnit\Framework\MockObject\MockObject $productBundleReaderMock
     *
     * @return void
     */
    protected function setupFindBundledProducts(array $fixtures, MockObject $productBundleReaderMock): void
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

        $productBundleReaderMock->expects($this->once())
            ->method('findBundledProducts')
            ->with($fixtures['idProductConcrete'])
            ->willReturn($bundledProducts);
    }
}
