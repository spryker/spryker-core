<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeatureTest\Yves\SelfServicePortal\Plugin\CartPage;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductOfferStorageTransfer;
use Spryker\Client\ProductOfferStorage\ProductOfferStorageClientInterface;
use SprykerFeature\Yves\SelfServicePortal\Plugin\CartPage\ProductOfferPreAddToCartPlugin;
use SprykerFeatureTest\Yves\SelfServicePortal\SelfServicePortalYvesTester;

/**
 * @group SprykerFeatureTest
 * @group Yves
 * @group SelfServicePortal
 * @group Plugin
 * @group CartPage
 * @group ProductOfferPreAddToCartPluginTest
 */
class ProductOfferPreAddToCartPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const PARAM_PRODUCT_OFFER_REFERENCE = 'product_offer_reference';

    /**
     * @var string
     */
    protected const TEST_PRODUCT_OFFER_REFERENCE = 'test-product-offer-reference';

    /**
     * @var \SprykerFeatureTest\Yves\SelfServicePortal\SelfServicePortalYvesTester
     */
    protected SelfServicePortalYvesTester $tester;

    public function testPreAddToCartExpandsItemWithProductOfferReferenceWhenValidReferenceProvided(): void
    {
        // Arrange
        $productOfferStorageTransfer = (new ProductOfferStorageTransfer())
            ->setProductOfferReference(static::TEST_PRODUCT_OFFER_REFERENCE);

        $productOfferStorageClientMock = $this->createProductOfferStorageClientMock();
        $productOfferStorageClientMock
            ->expects($this->once())
            ->method('findProductOfferStorageByReference')
            ->with(static::TEST_PRODUCT_OFFER_REFERENCE)
            ->willReturn($productOfferStorageTransfer);

        $this->tester->mockFactoryMethod('getProductOfferStorageClient', $productOfferStorageClientMock);

        $itemTransfer = new ItemTransfer();
        $params = [
            static::PARAM_PRODUCT_OFFER_REFERENCE => static::TEST_PRODUCT_OFFER_REFERENCE,
        ];

        // Act
        $resultItemTransfer = (new ProductOfferPreAddToCartPlugin())
            ->setFactory($this->tester->getFactory())
            ->preAddToCart($itemTransfer, $params);

        // Assert
        $this->assertSame(static::TEST_PRODUCT_OFFER_REFERENCE, $resultItemTransfer->getProductOfferReference());
    }

    public function testPreAddToCartDoesNotExpandItemWhenProductOfferReferenceParamNotProvided(): void
    {
        // Arrange
        $productOfferStorageClientMock = $this->createProductOfferStorageClientMock();
        $productOfferStorageClientMock
            ->expects($this->never())
            ->method('findProductOfferStorageByReference');

        $this->tester->mockFactoryMethod('getProductOfferStorageClient', $productOfferStorageClientMock);

        $itemTransfer = new ItemTransfer();
        $params = [];

        // Act
        $resultItemTransfer = (new ProductOfferPreAddToCartPlugin())
            ->setFactory($this->tester->getFactory())
            ->preAddToCart($itemTransfer, $params);

        // Assert
        $this->assertNull($resultItemTransfer->getProductOfferReference());
    }

    public function testPreAddToCartDoesNotExpandItemWhenProductOfferReferenceIsEmpty(): void
    {
        // Arrange
        $productOfferStorageClientMock = $this->createProductOfferStorageClientMock();
        $productOfferStorageClientMock
            ->expects($this->never())
            ->method('findProductOfferStorageByReference');

        $this->tester->mockFactoryMethod('getProductOfferStorageClient', $productOfferStorageClientMock);

        $itemTransfer = new ItemTransfer();
        $params = [
            static::PARAM_PRODUCT_OFFER_REFERENCE => '',
        ];

        // Act
        $resultItemTransfer = (new ProductOfferPreAddToCartPlugin())
            ->setFactory($this->tester->getFactory())
            ->preAddToCart($itemTransfer, $params);

        // Assert
        $this->assertNull($resultItemTransfer->getProductOfferReference());
    }

    public function testPreAddToCartDoesNotExpandItemWhenProductOfferNotFound(): void
    {
        // Arrange
        $productOfferStorageClientMock = $this->createProductOfferStorageClientMock();
        $productOfferStorageClientMock
            ->expects($this->once())
            ->method('findProductOfferStorageByReference')
            ->with(static::TEST_PRODUCT_OFFER_REFERENCE)
            ->willReturn(null);

        $this->tester->mockFactoryMethod('getProductOfferStorageClient', $productOfferStorageClientMock);

        $itemTransfer = new ItemTransfer();
        $params = [
            static::PARAM_PRODUCT_OFFER_REFERENCE => static::TEST_PRODUCT_OFFER_REFERENCE,
        ];

        // Act
        $resultItemTransfer = (new ProductOfferPreAddToCartPlugin())
            ->setFactory($this->tester->getFactory())
            ->preAddToCart($itemTransfer, $params);

        // Assert
        $this->assertNull($resultItemTransfer->getProductOfferReference());
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\ProductOfferStorage\ProductOfferStorageClientInterface
     */
    protected function createProductOfferStorageClientMock(): ProductOfferStorageClientInterface
    {
        return $this->getMockBuilder(ProductOfferStorageClientInterface::class)
            ->getMock();
    }
}
