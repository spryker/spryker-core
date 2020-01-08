<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductBundle\Business;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ProductForBundleBuilder;
use Generated\Shared\DataBuilder\StoreBuilder;
use Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductForBundleTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use PHPUnit\Framework\MockObject\MockObject;
use Spryker\DecimalObject\Decimal;
use Spryker\Zed\ProductBundle\Business\ProductBundle\ProductBundleReader;
use Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToAvailabilityFacadeInterface;
use Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToStoreFacadeInterface;
use Spryker\Zed\ProductBundle\Persistence\ProductBundleRepositoryInterface;

/**
 * Auto-generated group annotations
 *
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
    public function testFindBundledProductsByIdProductConcreteShouldBuildTransferCollectionFromPersistence(): void
    {
        $productBundleRepositoryMock = $this->createProductBundleRepositoryMock();
        $productBundleReaderMock = $this->createProductBundleReader($productBundleRepositoryMock);
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
    public function testAssignBundledProductsToProductConcreteShouldAssignBundledProductsAndAvailability(): void
    {
        $bundleAvailability = 5;

        $productBundleRepositoryMock = $this->createProductBundleRepositoryMock();
        $productBundleReaderMock = $this->createProductBundleReader($productBundleRepositoryMock);
        $this->setupFindBundledProducts($this->fixtures, $productBundleReaderMock);

        $productConcreteTransfer = new ProductConcreteTransfer();
        $productConcreteTransfer->setIdProductConcrete($this->fixtures['idProductConcrete']);
        $productConcreteTransfer->setSku('sku-2');

        $availabilityTransfer = new ProductConcreteAvailabilityTransfer();
        $availabilityTransfer->setAvailability($bundleAvailability);

        $productBundleReaderMock->method('findProductConcreteAvailabilityBySkuForStore')
            ->willReturn($availabilityTransfer);

        $productConcreteTransfer = $productBundleReaderMock->assignBundledProductsToProductConcrete($productConcreteTransfer);

        $productBundleTransfer = $productConcreteTransfer->getProductBundle();

        $this->assertNotNull($productBundleTransfer);
        $this->assertEquals($bundleAvailability, $productBundleTransfer->getAvailability()->toString());
        $this->assertCount(1, $productBundleTransfer->getBundledProducts());
    }

    /**
     * @param \Spryker\Zed\ProductBundle\Persistence\ProductBundleRepositoryInterface|null $productBundleRepositoryMock
     * @param \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToAvailabilityFacadeInterface|null $productBundleToAvailabilityFacadeMock
     * @param \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToStoreFacadeInterface|null $storeFacadeMock
     *
     * @return \Spryker\Zed\ProductBundle\Business\ProductBundle\ProductBundleReader|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createProductBundleReader(
        ?ProductBundleRepositoryInterface $productBundleRepositoryMock = null,
        ?ProductBundleToAvailabilityFacadeInterface $productBundleToAvailabilityFacadeMock = null,
        ?ProductBundleToStoreFacadeInterface $storeFacadeMock = null
    ) {
        if ($productBundleRepositoryMock === null) {
            $productBundleRepositoryMock = $this->createProductBundleRepositoryMock();
        }

        if ($productBundleToAvailabilityFacadeMock === null) {
            $productBundleToAvailabilityFacadeMock = $this->createAvailabilityFacadeMock();
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
            ->setConstructorArgs([$productBundleRepositoryMock, $productBundleToAvailabilityFacadeMock, $storeFacadeMock ])
            ->setMethods(['findBundledProductsByIdProductConcrete', 'findProductConcreteAvailabilityBySkuForStore'])
            ->getMock();

        return $productBundleReaderMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ProductBundle\Persistence\ProductBundleRepositoryInterface
     */
    protected function createProductBundleRepositoryMock(): MockObject
    {
        return $this->getMockBuilder(ProductBundleRepositoryInterface::class)->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToAvailabilityFacadeInterface
     */
    protected function createAvailabilityFacadeMock()
    {
        return $this->getMockBuilder(ProductBundleToAvailabilityFacadeInterface::class)->getMock();
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
     * @param \PHPUnit\Framework\MockObject\MockObject $productBundleReaderMock
     *
     * @return void
     */
    protected function setupFindBundledProducts(array $fixtures, MockObject $productBundleReaderMock): void
    {
        $productForBundleTransfer = (new ProductForBundleBuilder([
            ProductForBundleTransfer::QUANTITY => $fixtures['bundledProductQuantity'],
            ProductForBundleTransfer::SKU => $fixtures['bundledProductSku'],
            ProductForBundleTransfer::ID_PRODUCT_CONCRETE => $fixtures['fkBundledProduct'],
            ProductForBundleTransfer::ID_PRODUCT_BUNDLE => $fixtures['idProductBundle'],
        ]))->build();

        $productForBundleTransferCollection = new ArrayObject();
        $productForBundleTransferCollection->append($productForBundleTransfer);

        $productBundleReaderMock->expects($this->once())
            ->method('findBundledProductsByIdProductConcrete')
            ->with($fixtures['idProductConcrete'])
            ->willReturn($productForBundleTransferCollection);
    }
}
