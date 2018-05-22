<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductBundle\Business\Availability\PreCheck;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Orm\Zed\Availability\Persistence\SpyAvailability;
use Spryker\Zed\ProductBundle\Business\ProductBundle\Availability\PreCheck\ProductBundleCartAvailabilityCheck;
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
 * @group PreCheck
 * @group ProductBundleCartAvailabilityCheckTest
 * Add your own group annotations below this line
 */
class ProductBundleCartAvailabilityCheckTest extends PreCheckMocks
{
    const ID_STORE = 1;

    /**
     * return void
     *
     * @return void
     */
    public function testCheckCartAvailabilityWhenBundledItemsAvailableShouldReturnEmptyMessageContainer()
    {
        $availabilityFacadeMock = $this->createAvailabilityFacadeMock();
        $availabilityFacadeMock
            ->expects($this->once())
            ->method('isProductSellableForStore')
            ->withConsecutive(
                [$this->equalTo($this->fixtures['bundledProductSku']), $this->equalTo(15)]
            )
            ->willReturn(true);

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
    public function testCheckCartAvailabilityWhenBundledItemsNotAvailableShouldStoreErrorMessages()
    {
        $availabilityFacadeMock = $this->createAvailabilityFacadeMock();
        $availabilityFacadeMock->expects($this->once())
            ->method('isProductSellableForStore')
            ->willReturn(false);

        $productBundleAvailabilityCheckMock = $this->createProductBundleCartAvailabilityCheckMock($availabilityFacadeMock);

        $availabilityEntity = new SpyAvailability();
        $availabilityEntity->setQuantity(0);

        $productBundleAvailabilityCheckMock->method('findAvailabilityEntityBySku')
            ->willReturn($availabilityEntity);

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
     * @param \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToAvailabilityInterface|null $availabilityFacadeMock
     * @param \Spryker\Zed\ProductBundle\Dependency\QueryContainer\ProductBundleToAvailabilityQueryContainerInterface|null $availabilityQueryContainerMock
     * @param \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToStoreFacadeInterface|null $storeFacadeMock
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\ProductBundle\Business\ProductBundle\Availability\PreCheck\ProductBundleCartAvailabilityCheckInterface
     */
    protected function createProductBundleCartAvailabilityCheckMock(
        ?ProductBundleToAvailabilityInterface $availabilityFacadeMock = null,
        ?ProductBundleToAvailabilityQueryContainerInterface $availabilityQueryContainerMock = null,
        ?ProductBundleToStoreFacadeInterface $storeFacadeMock = null
    ) {

        if ($availabilityFacadeMock === null) {
            $availabilityFacadeMock = $this->createAvailabilityFacadeMock();
        }

        $productBundleQueryContainerMock = $this->getMockBuilder(ProductBundleQueryContainerInterface::class)->getMock();

        if ($availabilityQueryContainerMock === null) {
            $availabilityQueryContainerMock = $this->createAvailabilityQueryContainerMock();
        }

        if ($storeFacadeMock === null) {
            $storeFacadeMock = $this->buildStoreFacadeMock();
        }

        $productBundleCartAvailabilityCheckMock = $this->getMockBuilder(ProductBundleCartAvailabilityCheck::class)
            ->setConstructorArgs([$availabilityFacadeMock, $productBundleQueryContainerMock, $availabilityQueryContainerMock, $storeFacadeMock])
            ->setMethods(['findBundledProducts', 'findAvailabilityEntityBySku'])
            ->getMock();

        return $productBundleCartAvailabilityCheckMock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToStoreFacadeInterface
     */
    protected function createStoreFacadeMock()
    {
        return $this->getMockBuilder(ProductBundleToStoreFacadeInterface::class)->getMock();
    }
}
