<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\ProductBundle\Business\Availability;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Orm\Zed\Availability\Persistence\SpyAvailability;
use Orm\Zed\ProductBundle\Persistence\SpyProductBundle;
use Orm\Zed\Product\Persistence\SpyProduct;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\ProductBundle\Business\ProductBundle\Availability\ProductBundleAvailabilityCheck;
use Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToAvailabilityInterface;
use Spryker\Zed\ProductBundle\Dependency\QueryContainer\ProductBundleToAvailabilityQueryContainerInterface;
use Spryker\Zed\ProductBundle\Persistence\ProductBundleQueryContainerInterface;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group ProductBundle
 * @group Business
 * @group Availability
 * @group ProductBundleAvailabilityCheckTest
 */
class ProductBundleAvailabilityCheckTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var array
     */
    protected $fixtures = [
        'idProductConcrete' => 1,
        'bundledProductSku' => 'sku-123',
        'fkBundledProduct' => 2,
        'bundledProductQuantity' => 5,
        'idProductBundle' => 1,
        'bundle-sku' => 'sku-321',
    ];

    /**
     * @return void
     */
    public function testCheckCheckoutAvailabilityWhenAvailabilityExistingShouldReturnEmptyErrorContainer()
    {
        $availabilityFacadeMock = $this->createAvailabilityFacadeMock();
        $availabilityFacadeMock->expects($this->once())
            ->method('isProductSellable')
            ->willReturn(true);

        $productBundleAvailabilityCheckMock = $this->createProductBundleAvailabilityCheckMock($availabilityFacadeMock);

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

        $productBundleAvailabilityCheckMock = $this->createProductBundleAvailabilityCheckMock($availabilityFacadeMock);

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
     * return void
     *
     * @return void
     */
    public function testCheckCartAvailabilityWhenBundledItemsAvailableShouldReturnEmptyMessageContainer()
    {
        $availabilityFacadeMock = $this->createAvailabilityFacadeMock();
        $availabilityFacadeMock->expects($this->once())
            ->method('isProductSellable')
            ->willReturn(true);

        $productBundleAvailabilityCheckMock = $this->createProductBundleAvailabilityCheckMock($availabilityFacadeMock);

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
    public function testCheckCartAvailabilityWhenBundledItemsAvailableShouldStoreErrorMessages()
    {
        $availabilityFacadeMock = $this->createAvailabilityFacadeMock();
        $availabilityFacadeMock->expects($this->once())
            ->method('isProductSellable')
            ->willReturn(false);

        $productBundleAvailabilityCheckMock = $this->createProductBundleAvailabilityCheckMock($availabilityFacadeMock);

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
     * @param array $fixtures
     * @param \Spryker\Zed\ProductBundle\Business\ProductBundle\Availability\ProductBundleAvailabilityCheck|\PHPUnit_Framework_MockObject_MockObject $productBundleAvailabilityCheckMock
     *
     * @return void
     */
    protected function setupFindBundledProducts(array $fixtures, ProductBundleAvailabilityCheck $productBundleAvailabilityCheckMock)
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

        $productBundleAvailabilityCheckMock->expects($this->once())
            ->method('findBundledProducts')
            ->with($this->fixtures['bundle-sku'])
            ->willReturn($bundledProducts);
    }

    /**
     * @param \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToAvailabilityInterface|null $availabilityFacadeMock
     * @param \Spryker\Zed\ProductBundle\Dependency\QueryContainer\ProductBundleToAvailabilityQueryContainerInterface|null $availabilityQueryContainerMock
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\ProductBundle\Business\ProductBundle\Availability\ProductBundleAvailabilityCheck
     */
    protected function createProductBundleAvailabilityCheckMock(
        ProductBundleToAvailabilityInterface $availabilityFacadeMock = null,
        ProductBundleToAvailabilityQueryContainerInterface $availabilityQueryContainerMock = null
    ) {

        if ($availabilityFacadeMock === null) {
            $availabilityFacadeMock = $this->createAvailabilityFacadeMock();
        }

        $productBundleQueryContainerMock = $this->getMockBuilder(ProductBundleQueryContainerInterface::class)->getMock();

        if ($availabilityQueryContainerMock === null) {
            $availabilityQueryContainerMock = $this->createAvailabilityQueryContainerMock();
        }

        $productBundleAvailabilityCheckMock = $this->getMockBuilder(ProductBundleAvailabilityCheck::class)
            ->setConstructorArgs([$availabilityFacadeMock, $productBundleQueryContainerMock, $availabilityQueryContainerMock])
            ->setMethods(['findBundledProducts', 'findAvailabilityEntityBySku'])
            ->getMock();

        return $productBundleAvailabilityCheckMock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToAvailabilityInterface
     */
    protected function createAvailabilityFacadeMock()
    {
        return $this->getMockBuilder(ProductBundleToAvailabilityInterface::class)->getMock();
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createTestQuoteTransfer()
    {
        $quoteTransfer = new QuoteTransfer();

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setSku($this->fixtures['bundle-sku']);
        $itemTransfer->setQuantity(5);

        $quoteTransfer->addItem($itemTransfer);

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setSku($this->fixtures['bundle-sku']);

        $quoteTransfer->addBundleItem($itemTransfer);
        return $quoteTransfer;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\ProductBundle\Dependency\QueryContainer\ProductBundleToAvailabilityQueryContainerInterface
     */
    protected function createAvailabilityQueryContainerMock()
    {
        return $this->getMockBuilder(ProductBundleToAvailabilityQueryContainerInterface::class)->getMock();
    }

}
