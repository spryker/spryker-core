<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductBundle\Business\Availability\PreCheck;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Spryker\Zed\ProductBundle\Business\ProductBundle\Availability\PreCheck\ProductBundleCheckoutAvailabilityCheck;
use Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToAvailabilityInterface;
use Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToStoreFacadeInterface;
use Spryker\Zed\ProductBundle\Persistence\ProductBundleQueryContainerInterface;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group ProductBundle
 * @group Business
 * @group Availability
 * @group PreCheck
 * @group ProductBundleCheckoutAvailabilityCheckTest
 * Add your own group annotations below this line
 */
class ProductBundleCheckoutAvailabilityCheckTest extends PreCheckMocks
{
    const ID_STORE = 1;

    /**
     * @return void
     */
    public function testCheckCheckoutAvailabilityWhenAvailabilityExistingShouldReturnEmptyErrorContainer()
    {
        $availabilityFacadeMock = $this->createAvailabilityFacadeMock();
        $availabilityFacadeMock->expects($this->once())
            ->method('isProductSellableForStore')
            ->willReturn(true);

        $productBundleAvailabilityCheckMock = $this->createProductBundleCheckoutAvailabilityCheckMock($availabilityFacadeMock);

        $this->setupFindBundledProducts($this->fixtures, $productBundleAvailabilityCheckMock);

        $quoteTransfer = $this->createTestQuoteTransfer();

        $checkoutResponseTransfer = new CheckoutResponseTransfer();
        $checkoutResponseTransfer->setIsSuccess(true);

        $productBundleAvailabilityCheckMock->checkCheckoutAvailability(
            $quoteTransfer,
            $checkoutResponseTransfer
        );

        $this->assertCount(0, $checkoutResponseTransfer->getErrors());
        $this->assertTrue($checkoutResponseTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testCheckCheckoutAvailabilityWhenAvailabilityNonExistingShouldStoreErrorMessage()
    {
        $availabilityFacadeMock = $this->createAvailabilityFacadeMock();
        $availabilityFacadeMock->expects($this->once())
            ->method('isProductSellableForStore')
            ->willReturn(false);

        $productBundleAvailabilityCheckMock = $this->createProductBundleCheckoutAvailabilityCheckMock($availabilityFacadeMock);

        $this->setupFindBundledProducts($this->fixtures, $productBundleAvailabilityCheckMock);

        $quoteTransfer = $this->createTestQuoteTransfer();

        $checkoutResponseTransfer = new CheckoutResponseTransfer();
        $checkoutResponseTransfer->setIsSuccess(true);

        $productBundleAvailabilityCheckMock->checkCheckoutAvailability(
            $quoteTransfer,
            $checkoutResponseTransfer
        );

        $this->assertCount(1, $checkoutResponseTransfer->getErrors());
        $this->assertFalse($checkoutResponseTransfer->getIsSuccess());
    }

    /**
     * @param \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToAvailabilityInterface|null $availabilityFacadeMock
     * @param \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToStoreFacadeInterface|null $storeFacadeMock
     *
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function createProductBundleCheckoutAvailabilityCheckMock(
        ?ProductBundleToAvailabilityInterface $availabilityFacadeMock = null,
        ?ProductBundleToStoreFacadeInterface $storeFacadeMock = null
    ) {

        if ($availabilityFacadeMock === null) {
            $availabilityFacadeMock = $this->createAvailabilityFacadeMock();
        }

        if ($storeFacadeMock === null) {
            $storeFacadeMock = $this->buildStoreFacadeMock();
        }

        $productBundleQueryContainerMock = $this->getMockBuilder(ProductBundleQueryContainerInterface::class)->getMock();

        $productBundleCartAvailabilityCheckMock = $this->getMockBuilder(ProductBundleCheckoutAvailabilityCheck::class)
            ->setConstructorArgs([$availabilityFacadeMock, $productBundleQueryContainerMock, $storeFacadeMock])
            ->setMethods(['findBundledProducts'])
            ->getMock();

        return $productBundleCartAvailabilityCheckMock;
    }
}
