<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductBundle\Business;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\StoreBuilder;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductForBundleTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Availability\Persistence\SpyAvailability;
use Spryker\Zed\ProductBundle\Business\ProductBundle\ProductBundleReader;
use Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToStoreFacadeInterface;
use Spryker\Zed\ProductBundle\Dependency\QueryContainer\ProductBundleToAvailabilityQueryContainerInterface;
use Spryker\Zed\ProductBundle\Persistence\ProductBundleRepositoryInterface;

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
        $productBundleRepositoryMock = $this->createProductBundleRepositoryMock();
        $this->setupFindBundledProducts($this->fixtures, $productBundleRepositoryMock);
        $productBundleReaderMock = $this->createProductBundleReader($productBundleRepositoryMock);

        $bundledProductsTransferCollection = $productBundleReaderMock->findBundledProductsByIdProductConcrete($this->fixtures['idProductConcrete']);

        $productForBundleTransfer = $bundledProductsTransferCollection[0];
        $this->assertSame($this->fixtures['bundledProductSku'], $productForBundleTransfer->getSku());
        $this->assertSame($this->fixtures['fkBundledProduct'], $productForBundleTransfer->getIdProductConcrete());
        $this->assertSame($this->fixtures['bundledProductQuantity'], $productForBundleTransfer->getQuantity());
        $this->assertSame($this->fixtures['idProductBundle'], $productForBundleTransfer->getIdProductBundle());
    }

    /**
     * @return void
     */
    public function testAssignBundledProductsToProductConcreteShouldAssignBundledProductsAndAvailability()
    {
        $bundleAvailability = 5;

        $productBundleRepositoryMock = $this->createProductBundleRepositoryMock();
        $this->setupFindBundledProducts($this->fixtures, $productBundleRepositoryMock);
        $productBundleReaderMock = $this->createProductBundleReader($productBundleRepositoryMock);

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
        $this->assertEquals($bundleAvailability, $productBundleTransfer->getAvailability());
        $this->assertCount(1, $productBundleTransfer->getBundledProducts());
    }

    /**
     * @param \Spryker\Zed\ProductBundle\Persistence\ProductBundleRepositoryInterface|null $productBundleRepositoryMock
     * @param \Spryker\Zed\ProductBundle\Dependency\QueryContainer\ProductBundleToAvailabilityQueryContainerInterface|null $productBundleToAvailabilityQueryContainerMock
     * @param \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToStoreFacadeInterface|null $storeFacadeMock
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ProductBundle\Business\ProductBundle\ProductBundleReader
     */
    protected function createProductBundleReader(
        ?ProductBundleRepositoryInterface $productBundleRepositoryMock = null,
        ?ProductBundleToAvailabilityQueryContainerInterface $productBundleToAvailabilityQueryContainerMock = null,
        ?ProductBundleToStoreFacadeInterface $storeFacadeMock = null
    ) {

        if ($productBundleRepositoryMock === null) {
            $productBundleRepositoryMock = $this->createProductBundleRepositoryMock();
        }

        if ($productBundleToAvailabilityQueryContainerMock === null) {
            $productBundleToAvailabilityQueryContainerMock = $this->createAvailabilityQueryContainerMock();
        }

        if ($storeFacadeMock === null) {
            $storeFacadeMock = $this->createStoreFacadeMock();
            $storeTransfer = (new StoreBuilder([
                StoreTransfer::ID_STORE => self::ID_STORE,
            ]))->build();
            $storeFacadeMock->method('getCurrentStore')->willReturn($storeTransfer);
            $storeFacadeMock->method('getStoreByName')->willReturn($storeTransfer);
        }

        $productBundleReaderMock = $this->getMockBuilder(ProductBundleReader::class)
            ->setConstructorArgs([$productBundleRepositoryMock, $productBundleToAvailabilityQueryContainerMock, $storeFacadeMock ])
            ->setMethods(['findBundledProducts', 'findOrCreateProductBundleAvailabilityEntity'])
            ->getMock();

        return $productBundleReaderMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ProductBundle\Persistence\ProductBundleRepositoryInterface
     */
    protected function createProductBundleRepositoryMock()
    {
        return $this->getMockBuilder(ProductBundleRepositoryInterface::class)->getMock();
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
     * @param \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ProductBundle\Persistence\ProductBundleRepositoryInterface $productBundleRepositoryMock
     *
     * @return void
     */
    protected function setupFindBundledProducts(array $fixtures, ProductBundleRepositoryInterface $productBundleRepositoryMock)
    {
        $productForBundleTransfer = new ProductForBundleTransfer();
        $productForBundleTransfer->setIdProductBundle($fixtures['idProductConcrete']);
        $productForBundleTransfer->setQuantity($fixtures['bundledProductQuantity']);
        $productForBundleTransfer->setIdProductConcrete($fixtures['fkBundledProduct']);
        $productForBundleTransfer->setSku($fixtures['bundledProductSku']);

        $productBundleRepositoryMock->expects($this->once())
            ->method('findBundledProductsByIdProductConcrete')
            ->with($fixtures['idProductConcrete'])
            ->willReturn([$productForBundleTransfer]);
    }
}
