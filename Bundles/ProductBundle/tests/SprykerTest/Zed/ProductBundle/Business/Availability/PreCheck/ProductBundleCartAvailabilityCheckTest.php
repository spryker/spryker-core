<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductBundle\Business\Availability\PreCheck;

use Generated\Shared\DataBuilder\ProductForBundleBuilder;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer;
use Generated\Shared\Transfer\ProductForBundleTransfer;
use Orm\Zed\ProductBundle\Persistence\SpyProductBundleQuery;
use Spryker\DecimalObject\Decimal;
use Spryker\Zed\ProductBundle\Business\ProductBundle\Availability\PreCheck\ProductBundleCartAvailabilityCheck;
use Spryker\Zed\ProductBundle\Business\ProductBundle\ProductBundleReaderInterface;
use Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToAvailabilityFacadeInterface;
use Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToStoreFacadeInterface;
use Spryker\Zed\ProductBundle\Persistence\ProductBundleQueryContainerInterface;
use Spryker\Zed\ProductBundle\ProductBundleConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductBundle
 * @group Business
 * @group Availability
 * @group PreCheck
 * @group ProductBundleCartAvailabilityCheckTest
 * Add your own group annotations below this line
 */
class ProductBundleCartAvailabilityCheckTest extends PreCheckMocks
{
    public const ID_STORE = 1;

    /**
     * return void
     *
     * @return void
     */
    public function testCheckCartAvailabilityWhenBundledItemsAvailableShouldReturnEmptyMessageContainer(): void
    {
        $availabilityFacadeMock = $this->createAvailabilityFacadeMock();
        $availabilityFacadeMock
            ->expects($this->once())
            ->method('isProductSellableForStore')
            ->withConsecutive(
                [$this->equalTo($this->fixtures['bundledProductSku']), $this->equalTo(new Decimal(15))]
            )
            ->willReturn(true);

        $availabilityTransfer = new ProductConcreteAvailabilityTransfer();
        $availabilityTransfer->setAvailability(0);

        $availabilityFacadeMock->method('findOrCreateProductConcreteAvailabilityBySkuForStore')
            ->willReturn($availabilityTransfer);

        $productBundleAvailabilityCheckMock = $this->createProductBundleCartAvailabilityCheckMock($availabilityFacadeMock);

        $this->setupFindBundledProducts($this->fixtures, $productBundleAvailabilityCheckMock);

        $quoteTransfer = $this->createTestQuoteTransfer();

        $cartChangeTransfer = new CartChangeTransfer();
        $cartChangeTransfer->setQuote($quoteTransfer);

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setSku($this->fixtures['bundle-sku']);
        $itemTransfer->setQuantity(3);
        $cartChangeTransfer->addItem($itemTransfer);

        $cartPreCheckResponseTransfer = $productBundleAvailabilityCheckMock->checkCartAvailability($cartChangeTransfer);

        $this->assertCount(0, $cartPreCheckResponseTransfer->getMessages());
        $this->assertTrue($cartPreCheckResponseTransfer->getIsSuccess());
    }

    /**
     * return void
     *
     * @return void
     */
    public function testCheckCartAvailabilityWhenBundledItemsNotAvailableShouldStoreErrorMessages(): void
    {
        $availabilityFacadeMock = $this->createAvailabilityFacadeMock();
        $availabilityFacadeMock->expects($this->once())
            ->method('isProductSellableForStore')
            ->willReturn(false);

        $productBundleAvailabilityCheckMock = $this->createProductBundleCartAvailabilityCheckMock($availabilityFacadeMock);

        $availabilityTransfer = new ProductConcreteAvailabilityTransfer();
        $availabilityTransfer->setAvailability(0);

        $availabilityFacadeMock->method('findOrCreateProductConcreteAvailabilityBySkuForStore')
            ->willReturn($availabilityTransfer);

        $this->setupFindBundledProducts($this->fixtures, $productBundleAvailabilityCheckMock);

        $quoteTransfer = $this->createTestQuoteTransfer();

        $cartChangeTransfer = new CartChangeTransfer();
        $cartChangeTransfer->setQuote($quoteTransfer);

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setSku($this->fixtures['bundle-sku']);
        $itemTransfer->setQuantity(3);
        $cartChangeTransfer->addItem($itemTransfer);

        $cartPreCheckResponseTransfer = $productBundleAvailabilityCheckMock->checkCartAvailability($cartChangeTransfer);

        $this->assertCount(1, $cartPreCheckResponseTransfer->getMessages());
        $this->assertFalse($cartPreCheckResponseTransfer->getIsSuccess());
    }

    /**
     * @param \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToAvailabilityFacadeInterface|null $availabilityFacadeMock
     * @param \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToStoreFacadeInterface|null $storeFacadeMock
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ProductBundle\Business\ProductBundle\Availability\PreCheck\ProductBundleCartAvailabilityCheck
     */
    protected function createProductBundleCartAvailabilityCheckMock(
        ?ProductBundleToAvailabilityFacadeInterface $availabilityFacadeMock = null,
        ?ProductBundleToStoreFacadeInterface $storeFacadeMock = null
    ): ProductBundleCartAvailabilityCheck {
        if ($availabilityFacadeMock === null) {
            $availabilityFacadeMock = $this->createAvailabilityFacadeMock();
        }

        $productBundleQueryMock = $this->getMockBuilder(SpyProductBundleQuery::class)->getMock();
        $productBundleQueryMock
            ->method('exists')
            ->willReturn(true);

        $productBundleQueryContainerMock = $this->getMockBuilder(ProductBundleQueryContainerInterface::class)->getMock();
        $productBundleQueryContainerMock
            ->method('queryBundleProductBySku')
            ->willReturn($productBundleQueryMock);

        if ($storeFacadeMock === null) {
            $storeFacadeMock = $this->getStoreFacadeMock();
        }

        $productBundleConfig = $this->createProductBundleConfigMock();

        $productBundleReader = $this->createProductBundleReaderMock();

        $productBundleCartAvailabilityCheckMock = $this->getMockBuilder(ProductBundleCartAvailabilityCheck::class)
            ->setConstructorArgs([
                $availabilityFacadeMock,
                $productBundleQueryContainerMock,
                $storeFacadeMock,
                $productBundleConfig,
                $productBundleReader,
            ])
            ->setMethods(['findBundledProducts'])
            ->getMock();

        return $productBundleCartAvailabilityCheckMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToStoreFacadeInterface
     */
    protected function createStoreFacadeMock(): ProductBundleToStoreFacadeInterface
    {
        return $this->getMockBuilder(ProductBundleToStoreFacadeInterface::class)->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ProductBundle\ProductBundleConfig
     */
    protected function createProductBundleConfigMock(): ProductBundleConfig
    {
        return $this->getMockBuilder(ProductBundleConfig::class)->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ProductBundle\Business\ProductBundle\ProductBundleReaderInterface
     */
    protected function createProductBundleReaderMock(): ProductBundleReaderInterface
    {
        $mock = $this->getMockBuilder(ProductBundleReaderInterface::class)
            ->getMock();
        $mock->method('getProductForBundleTransfersByProductConcreteSkus')
            ->willReturn([
                $this->fixtures['bundle-sku'] => [
                    $this->createProductForBundleTransfer(['sku' => $this->fixtures['bundle-sku']]),
                ],
            ]);

        return $mock;
    }

    /**
     * @param array $seed
     *
     * @return \Generated\Shared\Transfer\ProductForBundleTransfer
     */
    protected function createProductForBundleTransfer(array $seed): ProductForBundleTransfer
    {
        return (new ProductForBundleBuilder($seed))->build();
    }
}
