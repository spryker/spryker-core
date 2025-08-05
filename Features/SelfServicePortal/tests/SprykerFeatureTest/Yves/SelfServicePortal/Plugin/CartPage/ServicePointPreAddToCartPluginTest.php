<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeatureTest\Yves\SelfServicePortal\Plugin\CartPage;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductOfferStorageTransfer;
use Generated\Shared\Transfer\ServicePointStorageTransfer;
use Generated\Shared\Transfer\ServiceStorageTransfer;
use Spryker\Client\ProductOfferStorage\ProductOfferStorageClientInterface;
use SprykerFeature\Yves\SelfServicePortal\Plugin\CartPage\ServicePointPreAddToCartPlugin;
use SprykerFeatureTest\Yves\SelfServicePortal\SelfServicePortalYvesTester;

/**
 * @group SprykerFeatureTest
 * @group Yves
 * @group SelfServicePortal
 * @group Plugin
 * @group CartPage
 * @group ServicePointPreAddToCartPluginTest
 */
class ServicePointPreAddToCartPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const PARAM_SERVICE_POINT_UUID = 'service_point_uuid';

    /**
     * @var string
     */
    protected const PARAM_PRODUCT_OFFER_REFERENCE = 'product_offer_reference';

    /**
     * @var string
     */
    protected const TEST_SERVICE_POINT_UUID = 'test-service-point-uuid';

    /**
     * @var string
     */
    protected const TEST_PRODUCT_OFFER_REFERENCE = 'test-product-offer-reference';

    /**
     * @var string
     */
    protected const TEST_SERVICE_POINT_NAME = 'Test Service Point';

    /**
     * @var int
     */
    protected const TEST_SERVICE_POINT_ID = 1;

    /**
     * @var \SprykerFeatureTest\Yves\SelfServicePortal\SelfServicePortalYvesTester
     */
    protected SelfServicePortalYvesTester $tester;

    public function testPreAddToCartExpandsItemWithServicePointWhenValidParametersProvided(): void
    {
        // Arrange
        $servicePointStorageTransfer = (new ServicePointStorageTransfer())
            ->setUuid(static::TEST_SERVICE_POINT_UUID)
            ->setName(static::TEST_SERVICE_POINT_NAME)
            ->setIdServicePoint(static::TEST_SERVICE_POINT_ID);

        $serviceStorageTransfer = (new ServiceStorageTransfer())
            ->setServicePoint($servicePointStorageTransfer);

        $productOfferStorageTransfer = (new ProductOfferStorageTransfer())
            ->setProductOfferReference(static::TEST_PRODUCT_OFFER_REFERENCE)
            ->setServices(new ArrayObject([$serviceStorageTransfer]));

        $productOfferStorageClientMock = $this->createProductOfferStorageClientMock();
        $productOfferStorageClientMock
            ->expects($this->once())
            ->method('findProductOfferStorageByReference')
            ->with(static::TEST_PRODUCT_OFFER_REFERENCE)
            ->willReturn($productOfferStorageTransfer);

        $this->tester->mockFactoryMethod('getProductOfferStorageClient', $productOfferStorageClientMock);

        $itemTransfer = new ItemTransfer();
        $params = [
            static::PARAM_SERVICE_POINT_UUID => static::TEST_SERVICE_POINT_UUID,
            static::PARAM_PRODUCT_OFFER_REFERENCE => static::TEST_PRODUCT_OFFER_REFERENCE,
        ];

        // Act
        $resultItemTransfer = (new ServicePointPreAddToCartPlugin())
            ->setFactory($this->tester->getFactory())
            ->preAddToCart($itemTransfer, $params);

        // Assert
        $this->assertNotNull($resultItemTransfer->getServicePoint());
        $this->assertSame(static::TEST_SERVICE_POINT_UUID, $resultItemTransfer->getServicePointOrFail()->getUuid());
        $this->assertSame(static::TEST_SERVICE_POINT_NAME, $resultItemTransfer->getServicePointOrFail()->getName());
        $this->assertSame(static::TEST_SERVICE_POINT_ID, $resultItemTransfer->getServicePointOrFail()->getIdServicePoint());
    }

    public function testPreAddToCartDoesNotExpandItemWhenParametersNotProvided(): void
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
        $resultItemTransfer = (new ServicePointPreAddToCartPlugin())
            ->setFactory($this->tester->getFactory())
            ->preAddToCart($itemTransfer, $params);

        // Assert
        $this->assertNull($resultItemTransfer->getServicePoint());
    }

    public function testPreAddToCartDoesNotExpandItemWhenServicePointUuidIsEmpty(): void
    {
        // Arrange
        $productOfferStorageClientMock = $this->createProductOfferStorageClientMock();
        $productOfferStorageClientMock
            ->expects($this->never())
            ->method('findProductOfferStorageByReference');

        $this->tester->mockFactoryMethod('getProductOfferStorageClient', $productOfferStorageClientMock);

        $itemTransfer = new ItemTransfer();
        $params = [
            static::PARAM_SERVICE_POINT_UUID => '',
            static::PARAM_PRODUCT_OFFER_REFERENCE => static::TEST_PRODUCT_OFFER_REFERENCE,
        ];

        // Act
        $resultItemTransfer = (new ServicePointPreAddToCartPlugin())
            ->setFactory($this->tester->getFactory())
            ->preAddToCart($itemTransfer, $params);

        // Assert
        $this->assertNull($resultItemTransfer->getServicePoint());
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
            static::PARAM_SERVICE_POINT_UUID => static::TEST_SERVICE_POINT_UUID,
            static::PARAM_PRODUCT_OFFER_REFERENCE => '',
        ];

        // Act
        $resultItemTransfer = (new ServicePointPreAddToCartPlugin())
            ->setFactory($this->tester->getFactory())
            ->preAddToCart($itemTransfer, $params);

        // Assert
        $this->assertNull($resultItemTransfer->getServicePoint());
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
            static::PARAM_SERVICE_POINT_UUID => static::TEST_SERVICE_POINT_UUID,
            static::PARAM_PRODUCT_OFFER_REFERENCE => static::TEST_PRODUCT_OFFER_REFERENCE,
        ];

        // Act
        $resultItemTransfer = (new ServicePointPreAddToCartPlugin())
            ->setFactory($this->tester->getFactory())
            ->preAddToCart($itemTransfer, $params);

        // Assert
        $this->assertNull($resultItemTransfer->getServicePoint());
    }

    public function testPreAddToCartDoesNotExpandItemWhenProductOfferHasNoServices(): void
    {
        // Arrange
        $productOfferStorageTransfer = (new ProductOfferStorageTransfer())
            ->setProductOfferReference(static::TEST_PRODUCT_OFFER_REFERENCE)
            ->setServices(new ArrayObject([]));

        $productOfferStorageClientMock = $this->createProductOfferStorageClientMock();
        $productOfferStorageClientMock
            ->expects($this->once())
            ->method('findProductOfferStorageByReference')
            ->with(static::TEST_PRODUCT_OFFER_REFERENCE)
            ->willReturn($productOfferStorageTransfer);

        $this->tester->mockFactoryMethod('getProductOfferStorageClient', $productOfferStorageClientMock);

        $itemTransfer = new ItemTransfer();
        $params = [
            static::PARAM_SERVICE_POINT_UUID => static::TEST_SERVICE_POINT_UUID,
            static::PARAM_PRODUCT_OFFER_REFERENCE => static::TEST_PRODUCT_OFFER_REFERENCE,
        ];

        // Act
        $resultItemTransfer = (new ServicePointPreAddToCartPlugin())
            ->setFactory($this->tester->getFactory())
            ->preAddToCart($itemTransfer, $params);

        // Assert
        $this->assertNull($resultItemTransfer->getServicePoint());
    }

    public function testPreAddToCartDoesNotExpandItemWhenServicePointNotFoundInProductOfferServices(): void
    {
        // Arrange
        $differentServicePointUuid = 'different-service-point-uuid';

        $servicePointStorageTransfer = (new ServicePointStorageTransfer())
            ->setUuid($differentServicePointUuid)
            ->setName(static::TEST_SERVICE_POINT_NAME)
            ->setIdServicePoint(static::TEST_SERVICE_POINT_ID);

        $serviceStorageTransfer = (new ServiceStorageTransfer())
            ->setServicePoint($servicePointStorageTransfer);

        $productOfferStorageTransfer = (new ProductOfferStorageTransfer())
            ->setProductOfferReference(static::TEST_PRODUCT_OFFER_REFERENCE)
            ->setServices(new ArrayObject([$serviceStorageTransfer]));

        $productOfferStorageClientMock = $this->createProductOfferStorageClientMock();
        $productOfferStorageClientMock
            ->expects($this->once())
            ->method('findProductOfferStorageByReference')
            ->with(static::TEST_PRODUCT_OFFER_REFERENCE)
            ->willReturn($productOfferStorageTransfer);

        $this->tester->mockFactoryMethod('getProductOfferStorageClient', $productOfferStorageClientMock);

        $itemTransfer = new ItemTransfer();
        $params = [
            static::PARAM_SERVICE_POINT_UUID => static::TEST_SERVICE_POINT_UUID,
            static::PARAM_PRODUCT_OFFER_REFERENCE => static::TEST_PRODUCT_OFFER_REFERENCE,
        ];

        // Act
        $resultItemTransfer = (new ServicePointPreAddToCartPlugin())
            ->setFactory($this->tester->getFactory())
            ->preAddToCart($itemTransfer, $params);

        // Assert
        $this->assertNull($resultItemTransfer->getServicePoint());
    }

    public function testPreAddToCartDoesNotExpandItemWhenServicePointIsNullInProductOfferService(): void
    {
        // Arrange
        $serviceStorageTransfer = (new ServiceStorageTransfer())
            ->setServicePoint(null);

        $productOfferStorageTransfer = (new ProductOfferStorageTransfer())
            ->setProductOfferReference(static::TEST_PRODUCT_OFFER_REFERENCE)
            ->setServices(new ArrayObject([$serviceStorageTransfer]));

        $productOfferStorageClientMock = $this->createProductOfferStorageClientMock();
        $productOfferStorageClientMock
            ->expects($this->once())
            ->method('findProductOfferStorageByReference')
            ->with(static::TEST_PRODUCT_OFFER_REFERENCE)
            ->willReturn($productOfferStorageTransfer);

        $this->tester->mockFactoryMethod('getProductOfferStorageClient', $productOfferStorageClientMock);

        $itemTransfer = new ItemTransfer();
        $params = [
            static::PARAM_SERVICE_POINT_UUID => static::TEST_SERVICE_POINT_UUID,
            static::PARAM_PRODUCT_OFFER_REFERENCE => static::TEST_PRODUCT_OFFER_REFERENCE,
        ];

        // Act
        $resultItemTransfer = (new ServicePointPreAddToCartPlugin())
            ->setFactory($this->tester->getFactory())
            ->preAddToCart($itemTransfer, $params);

        // Assert
        $this->assertNull($resultItemTransfer->getServicePoint());
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
