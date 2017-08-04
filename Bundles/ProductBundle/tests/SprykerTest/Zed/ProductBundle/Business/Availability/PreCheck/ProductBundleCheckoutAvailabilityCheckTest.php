<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductBundle\Business\Availability\PreCheck;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Spryker\Zed\ProductBundle\Business\ProductBundle\Availability\PreCheck\ProductBundleCheckoutAvailabilityCheck;
use Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToAvailabilityInterface;
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

    /**
     * @return void
     */
    public function testCheckCheckoutAvailabilityWhenAvailabilityExistingShouldReturnEmptyErrorContainer()
    {
        $availabilityFacadeMock = $this->createAvailabilityFacadeMock();
        $availabilityFacadeMock->expects($this->once())
            ->method('isProductSellable')
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
            ->method('isProductSellable')
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
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\ProductBundle\Business\ProductBundle\Availability\ProductBundleAvailabilityCheck
     */
    protected function createProductBundleCheckoutAvailabilityCheckMock(
        ProductBundleToAvailabilityInterface $availabilityFacadeMock = null
    ) {

        if ($availabilityFacadeMock === null) {
            $availabilityFacadeMock = $this->createAvailabilityFacadeMock();
        }

        $productBundleQueryContainerMock = $this->getMockBuilder(ProductBundleQueryContainerInterface::class)->getMock();

        $productBundleCartAvailabilityCheckMock = $this->getMockBuilder(ProductBundleCheckoutAvailabilityCheck::class)
            ->setConstructorArgs([$availabilityFacadeMock, $productBundleQueryContainerMock])
            ->setMethods(['findBundledProducts'])
            ->getMock();

        return $productBundleCartAvailabilityCheckMock;
    }

}
