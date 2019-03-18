<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductBundle\Business\Availability\PreCheck;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\ProductBundle\Business\ProductBundle\Availability\PreCheck\ProductBundleCheckoutAvailabilityCheck;
use Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToAvailabilityInterface;
use Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToStoreFacadeInterface;
use Spryker\Zed\ProductBundle\Dependency\Service\ProductBundleToUtilQuantityServiceBridge;
use Spryker\Zed\ProductBundle\Dependency\Service\ProductBundleToUtilQuantityServiceInterface;
use Spryker\Zed\ProductBundle\Persistence\ProductBundleQueryContainerInterface;
use Spryker\Zed\ProductBundle\ProductBundleConfig;

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
    public const ID_STORE = 1;

    protected const INT_QUANTITY = 5;
    protected const FLOAT_QUANTITY = 5.1;

    /**
     * @dataProvider quoteTransferDataProvider
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function testCheckCheckoutAvailabilityWhenAvailabilityExistingShouldReturnEmptyErrorContainer(
        QuoteTransfer $quoteTransfer
    ): void {
        $availabilityFacadeMock = $this->createAvailabilityFacadeMock();
        $availabilityFacadeMock->expects($this->once())
            ->method('isProductSellableForStore')
            ->willReturn(true);

        $productBundleAvailabilityCheckMock = $this->createProductBundleCheckoutAvailabilityCheckMock($availabilityFacadeMock);

        $this->setupFindBundledProducts($this->fixtures, $productBundleAvailabilityCheckMock);

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
     * @dataProvider quoteTransferDataProvider
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function testCheckCheckoutAvailabilityWhenAvailabilityNonExistingShouldStoreErrorMessage(
        QuoteTransfer $quoteTransfer
    ): void {
        $availabilityFacadeMock = $this->createAvailabilityFacadeMock();
        $availabilityFacadeMock->expects($this->once())
            ->method('isProductSellableForStore')
            ->willReturn(false);

        $productBundleAvailabilityCheckMock = $this->createProductBundleCheckoutAvailabilityCheckMock($availabilityFacadeMock);

        $this->setupFindBundledProducts($this->fixtures, $productBundleAvailabilityCheckMock);

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
        $productBundleConfig = $this->createProductBundleConfigMock();

        $productBundleCartAvailabilityCheckMock = $this->getMockBuilder(ProductBundleCheckoutAvailabilityCheck::class)
            ->setConstructorArgs([
                $availabilityFacadeMock,
                $productBundleQueryContainerMock,
                $storeFacadeMock,
                $productBundleConfig,
                $this->createUtilQuantityService(),
            ])
            ->setMethods(['findBundledProducts'])
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

    /**
     * @return \Spryker\Zed\ProductBundle\Dependency\Service\ProductBundleToUtilQuantityServiceInterface
     */
    protected function createUtilQuantityService(): ProductBundleToUtilQuantityServiceInterface
    {
        return new ProductBundleToUtilQuantityServiceBridge($this->tester->getLocator()->utilQuantity()->service());
    }

    /**
     * @return array
     */
    public function quoteTransferDataProvider(): array
    {
        return [
            'int quantity' => $this->getDataForQuoteTransfer(
                static::INT_QUANTITY,
                $this->fixtures['bundle-sku']
            ),
            'float quantity' => $this->getDataForQuoteTransfer(
                static::FLOAT_QUANTITY,
                $this->fixtures['bundle-sku']
            ),
        ];
    }

    /**
     * @param float|int $quantity
     * @param string $bundleSku
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer[]
     */
    protected function getDataForQuoteTransfer($quantity, string $bundleSku): array
    {
        $quoteTransfer = new QuoteTransfer();
        $quoteTransfer->setStore((new StoreTransfer())->setName('DE'));

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setSku($bundleSku);
        $itemTransfer->setQuantity($quantity);

        $quoteTransfer->addItem($itemTransfer);

        $bundleItemTransfer = new ItemTransfer();
        $bundleItemTransfer->setSku($bundleSku);

        $quoteTransfer->addBundleItem($bundleItemTransfer);

        return [$quoteTransfer];
    }
}
