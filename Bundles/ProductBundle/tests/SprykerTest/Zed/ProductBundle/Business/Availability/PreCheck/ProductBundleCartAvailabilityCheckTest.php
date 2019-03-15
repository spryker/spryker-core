<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductBundle\Business\Availability\PreCheck;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Availability\Persistence\SpyAvailability;
use Spryker\Zed\ProductBundle\Business\ProductBundle\Availability\PreCheck\ProductBundleCartAvailabilityCheck;
use Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToAvailabilityInterface;
use Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToStoreFacadeInterface;
use Spryker\Zed\ProductBundle\Dependency\QueryContainer\ProductBundleToAvailabilityQueryContainerInterface;
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
 * @group ProductBundleCartAvailabilityCheckTest
 * Add your own group annotations below this line
 */
class ProductBundleCartAvailabilityCheckTest extends PreCheckMocks
{
    public const ID_STORE = 1;

    protected const INT_QUANTITY = 3;
    protected const FLOAT_QUANTITY = 3.1;

    /**
     * @dataProvider quoteTransferWithCartChangeItemTransferDataProvider
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $cartChangeItemTransfer
     * @param float|int $expectedQuantity
     *
     * @return void
     */
    public function testCheckCartAvailabilityWhenBundledItemsAvailableShouldReturnEmptyMessageContainer(
        QuoteTransfer $quoteTransfer,
        ItemTransfer $cartChangeItemTransfer,
        $expectedQuantity
    ) {
        $availabilityFacadeMock = $this->createAvailabilityFacadeMock();
        $availabilityFacadeMock
            ->expects($this->once())
            ->method('isProductSellableForStore')
            ->withConsecutive(
                [$this->equalTo($this->fixtures['bundledProductSku']), $this->equalTo($expectedQuantity)]
            )
            ->willReturn(true);

        $productBundleAvailabilityCheckMock = $this->createProductBundleCartAvailabilityCheckMock($availabilityFacadeMock);
        $this->setupFindBundledProducts($this->fixtures, $productBundleAvailabilityCheckMock);

        $cartChangeTransfer = new CartChangeTransfer();
        $cartChangeTransfer->setQuote($quoteTransfer);

        $cartChangeTransfer->addItem($cartChangeItemTransfer);
        $cartPreCheckResponseTransfer = $productBundleAvailabilityCheckMock->checkCartAvailability($cartChangeTransfer);

        $this->assertCount(0, $cartPreCheckResponseTransfer->getMessages());
        $this->assertTrue($cartPreCheckResponseTransfer->getIsSuccess());
    }

    /**
     * @dataProvider quoteTransferWithCartChangeItemTransferDataProvider
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $cartChangeItemTransfer
     *
     * @return void
     */
    public function testCheckCartAvailabilityWhenBundledItemsNotAvailableShouldStoreErrorMessages(
        QuoteTransfer $quoteTransfer,
        ItemTransfer $cartChangeItemTransfer
    ) {
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

        $cartChangeTransfer = new CartChangeTransfer();
        $cartChangeTransfer->setQuote($quoteTransfer);
        $cartChangeTransfer->addItem($cartChangeItemTransfer);
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

        $productBundleConfig = $this->createProductBundleConfigMock();

        $productBundleCartAvailabilityCheckMock = $this->getMockBuilder(ProductBundleCartAvailabilityCheck::class)
            ->setConstructorArgs([$availabilityFacadeMock, $productBundleQueryContainerMock, $availabilityQueryContainerMock, $storeFacadeMock, $productBundleConfig])
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

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\ProductBundle\ProductBundleConfig
     */
    protected function createProductBundleConfigMock(): ProductBundleConfig
    {
        return $this->getMockBuilder(ProductBundleConfig::class)->getMock();
    }

    /**
     * @return array
     */
    public function quoteTransferWithCartChangeItemTransferDataProvider(): array
    {
        $quoteTransferWithCartChangeItemTransferDataProvider = [
            'int quantity' => $this->createQuoteTransferWithCartChangeItemTransferDataProvider(
                static::INT_QUANTITY,
                $this->fixtures['bundle-sku']
            ),
            'float quantity' => $this->createQuoteTransferWithCartChangeItemTransferDataProvider(
                static::FLOAT_QUANTITY,
                $this->fixtures['bundle-sku']
            ),
        ];

        return $quoteTransferWithCartChangeItemTransferDataProvider;
    }

    /**
     * @param float|int $quantity
     * @param string $bundleSku
     *
     * @return array
     */
    protected function createQuoteTransferWithCartChangeItemTransferDataProvider($quantity, string $bundleSku)
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

        $cartChangeItemTransfer = new ItemTransfer();
        $cartChangeItemTransfer->setSku($bundleSku);
        $cartChangeItemTransfer->setQuantity($quantity);

        return [$quoteTransfer, $cartChangeItemTransfer, $quantity * $this->fixtures['bundledProductQuantity']];
    }
}
