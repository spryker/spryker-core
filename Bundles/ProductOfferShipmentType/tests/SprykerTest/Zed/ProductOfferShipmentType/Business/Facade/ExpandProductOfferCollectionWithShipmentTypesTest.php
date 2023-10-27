<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOfferShipmentType\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductOfferCollectionTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;
use SprykerTest\Zed\ProductOfferShipmentType\ProductOfferShipmentTypeBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductOfferShipmentType
 * @group Business
 * @group Facade
 * @group ExpandProductOfferCollectionWithShipmentTypesTest
 * Add your own group annotations below this line
 */
class ExpandProductOfferCollectionWithShipmentTypesTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductOfferShipmentType\ProductOfferShipmentTypeBusinessTester
     */
    protected ProductOfferShipmentTypeBusinessTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->ensureDatabaseTableIsEmpty($this->tester->getProductOfferShipmentTypeQuery());
    }

    /**
     * @return void
     */
    public function testExpandsOneProductOfferWithOneRelatedShipmentType(): void
    {
        // Arrange
        $productOfferTransfer = $this->tester->haveProductOffer();
        $shipmentTypeTransfer = $this->tester->haveShipmentType();

        $this->tester->haveProductOfferShipmentType($productOfferTransfer, $shipmentTypeTransfer);
        $this->tester->haveProductOfferShipmentType($this->tester->haveProductOffer(), $this->tester->haveShipmentType());

        $productOfferCollectionTransfer = (new ProductOfferCollectionTransfer())->addProductOffer($productOfferTransfer);

        // Act
        $productOfferCollectionTransfer = $this->tester->getFacade()->expandProductOfferCollectionWithShipmentTypes(
            $productOfferCollectionTransfer,
        );
        $expandedProductOfferTransfer = $productOfferCollectionTransfer->getProductOffers()->getIterator()->current();

        // Assert
        $this->assertCount(1, $expandedProductOfferTransfer->getShipmentTypes());
        $this->assertEquals($shipmentTypeTransfer, $expandedProductOfferTransfer->getShipmentTypes()->offsetGet(0));
    }

    /**
     * @return void
     */
    public function testExpandsFewProductOffersWithFewRelatedShipmentTypes(): void
    {
        // Arrange
        $productOfferTransfer1 = $this->tester->haveProductOffer();
        $productOfferTransfer2 = $this->tester->haveProductOffer();
        $shipmentTypeTransfer1 = $this->tester->haveShipmentType();
        $shipmentTypeTransfer2 = $this->tester->haveShipmentType();
        $shipmentTypeTransfer3 = $this->tester->haveShipmentType();

        $this->tester->haveProductOfferShipmentType($productOfferTransfer1, $shipmentTypeTransfer1);
        $this->tester->haveProductOfferShipmentType($productOfferTransfer1, $shipmentTypeTransfer2);
        $this->tester->haveProductOfferShipmentType($productOfferTransfer2, $shipmentTypeTransfer3);
        $this->tester->haveProductOfferShipmentType($this->tester->haveProductOffer(), $this->tester->haveShipmentType());

        $productOfferCollectionTransfer = (new ProductOfferCollectionTransfer())
            ->addProductOffer($productOfferTransfer1)
            ->addProductOffer($productOfferTransfer2);

        // Act
        $productOfferCollectionTransfer = $this->tester->getFacade()->expandProductOfferCollectionWithShipmentTypes(
            $productOfferCollectionTransfer,
        );
        $expandedProductOfferTransfer1 = $productOfferCollectionTransfer->getProductOffers()->offsetGet(0);
        $expandedProductOfferTransfer2 = $productOfferCollectionTransfer->getProductOffers()->offsetGet(1);

        // Assert
        $this->assertCount(2, $expandedProductOfferTransfer1->getShipmentTypes());
        $this->assertCount(1, $expandedProductOfferTransfer2->getShipmentTypes());
        $this->assertEquals($shipmentTypeTransfer1, $expandedProductOfferTransfer1->getShipmentTypes()->offsetGet(0));
        $this->assertEquals($shipmentTypeTransfer2, $expandedProductOfferTransfer1->getShipmentTypes()->offsetGet(1));
        $this->assertEquals($shipmentTypeTransfer3, $expandedProductOfferTransfer2->getShipmentTypes()->offsetGet(0));
    }

    /**
     * @return void
     */
    public function testDoesNotExpandProductOffersWhenNoRelatedShipmentTypesExist(): void
    {
        // Arrange
        $this->tester->haveProductOfferShipmentType($this->tester->haveProductOffer(), $this->tester->haveShipmentType());
        $productOfferTransfer = $this->tester->haveProductOffer();

        $productOfferCollectionTransfer = (new ProductOfferCollectionTransfer())->addProductOffer($productOfferTransfer);

        // Act
        $productOfferCollectionTransfer = $this->tester->getFacade()->expandProductOfferCollectionWithShipmentTypes(
            $productOfferCollectionTransfer,
        );
        $expandedProductOfferTransfer = $productOfferCollectionTransfer->getProductOffers()->getIterator()->current();

        // Assert
        $this->assertEmpty($expandedProductOfferTransfer->getShipmentTypes());
    }

    /**
     * @return void
     */
    public function testThrowsNullValueExceptionWhenIdProductOfferIsNotSet(): void
    {
        // Arrange
        $productOfferTransfer = $this->tester->haveProductOffer();
        $this->tester->haveProductOfferShipmentType($productOfferTransfer, $this->tester->haveShipmentType());
        $productOfferTransfer->setIdProductOffer(null);

        $productOfferCollectionTransfer = (new ProductOfferCollectionTransfer())->addProductOffer($productOfferTransfer);

        // Assert
        $this->expectException(NullValueException::class);

        // Act
        $this->tester->getFacade()->expandProductOfferCollectionWithShipmentTypes($productOfferCollectionTransfer);
    }
}
