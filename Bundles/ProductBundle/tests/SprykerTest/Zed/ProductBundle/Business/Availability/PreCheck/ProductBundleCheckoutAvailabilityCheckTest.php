<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductBundle\Business\Availability\PreCheck;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Spryker\Zed\ProductBundle\Business\ProductBundle\Availability\PreCheck\ProductBundleCheckoutAvailabilityCheck;
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
 * @group ProductBundleCheckoutAvailabilityCheckTest
 * Add your own group annotations below this line
 */
class ProductBundleCheckoutAvailabilityCheckTest extends PreCheckMocks
{
    /**
     * @var int
     */
    public const ID_STORE = 1;

    /**
     * @return void
     */
    public function testCheckCheckoutAvailabilityWhenAvailabilityExistingShouldReturnEmptyErrorContainer(): void
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
            $checkoutResponseTransfer,
        );

        $this->assertCount(0, $checkoutResponseTransfer->getErrors());
        $this->assertTrue($checkoutResponseTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testCheckCheckoutAvailabilityWhenAvailabilityNonExistingShouldStoreErrorMessage(): void
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
            $checkoutResponseTransfer,
        );

        $this->assertCount(1, $checkoutResponseTransfer->getErrors());
        $this->assertFalse($checkoutResponseTransfer->getIsSuccess());
    }

    /**
     * @param \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToAvailabilityFacadeInterface|null $availabilityFacadeMock
     * @param \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToStoreFacadeInterface|null $storeFacadeMock
     *
     * @return \Spryker\Zed\ProductBundle\Business\ProductBundle\Availability\PreCheck\ProductBundleCheckoutAvailabilityCheck|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createProductBundleCheckoutAvailabilityCheckMock(
        ?ProductBundleToAvailabilityFacadeInterface $availabilityFacadeMock = null,
        ?ProductBundleToStoreFacadeInterface $storeFacadeMock = null
    ): ProductBundleCheckoutAvailabilityCheck {
        if ($availabilityFacadeMock === null) {
            $availabilityFacadeMock = $this->createAvailabilityFacadeMock();
        }

        if ($storeFacadeMock === null) {
            $storeFacadeMock = $this->getStoreFacadeMock();
        }

        $productBundleQueryContainerMock = $this->getMockBuilder(ProductBundleQueryContainerInterface::class)->getMock();
        $productBundleConfig = $this->createProductBundleConfigMock();

        $productBundleCartAvailabilityCheckMock = $this->getMockBuilder(ProductBundleCheckoutAvailabilityCheck::class)
            ->setConstructorArgs([$availabilityFacadeMock, $productBundleQueryContainerMock, $storeFacadeMock, $productBundleConfig])
            ->onlyMethods(['findBundledProducts'])
            ->getMock();

        return $productBundleCartAvailabilityCheckMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ProductBundle\ProductBundleConfig
     */
    protected function createProductBundleConfigMock(): ProductBundleConfig
    {
        return $this->getMockBuilder(ProductBundleConfig::class)->getMock();
    }
}
