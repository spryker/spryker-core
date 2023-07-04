<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOfferServicePoint\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductOfferCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferServiceTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;
use SprykerTest\Zed\ProductOfferServicePoint\ProductOfferServicePointBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductOfferServicePoint
 * @group Business
 * @group Facade
 * @group ExpandProductOfferCollectionWithServicesTest
 * Add your own group annotations below this line
 */
class ExpandProductOfferCollectionWithServicesTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductOfferServicePoint\ProductOfferServicePointBusinessTester
     */
    protected ProductOfferServicePointBusinessTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->ensureProductOfferServiceTableAndRelationsAreEmpty();
    }

    /**
     * @return void
     */
    public function testShouldReturnProductOfferWithOneService(): void
    {
        // Arrange
        $persistedServiceTransfer = $this->tester->haveService();
        $productOfferTransfer = $this->tester->haveProductOffer();
        $this->tester->haveProductOfferService([
            ProductOfferServiceTransfer::ID_PRODUCT_OFFER => $productOfferTransfer->getIdProductOfferOrFail(),
            ProductOfferServiceTransfer::ID_SERVICE => $persistedServiceTransfer->getIdServiceOrFail(),
        ]);
        $productOfferCollectionTransfer = (new ProductOfferCollectionTransfer())
            ->addProductOffer($productOfferTransfer);

        // Act
        $productOfferCollectionTransfer = $this->tester
            ->getFacade()
            ->expandProductOfferCollectionWithServices($productOfferCollectionTransfer);

        // Assert
        $this->assertCount(1, $productOfferCollectionTransfer->getProductOffers());

        /** @var \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer */
        $productOfferTransfer = $productOfferCollectionTransfer->getProductOffers()->getIterator()->current();

        $this->assertCount(1, $productOfferTransfer->getServices());

        /** @var \Generated\Shared\Transfer\ServiceTransfer $serviceTransfer */
        $serviceTransfer = $productOfferTransfer->getServices()->getIterator()->current();

        $this->assertSame($serviceTransfer->getIdServiceOrFail(), $persistedServiceTransfer->getIdServiceOrFail());
    }

    /**
     * @return void
     */
    public function testShouldReturnProductOfferWithoutServices(): void
    {
        // Arrange
        $productOfferTransfer = $this->tester->haveProductOffer();
        $productOfferCollectionTransfer = (new ProductOfferCollectionTransfer())
            ->addProductOffer($productOfferTransfer);

        // Act
        $productOfferCollectionTransfer = $this->tester
            ->getFacade()
            ->expandProductOfferCollectionWithServices($productOfferCollectionTransfer);

        // Assert
        $this->assertCount(1, $productOfferCollectionTransfer->getProductOffers());

        /** @var \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer */
        $productOfferTransfer = $productOfferCollectionTransfer->getProductOffers()->getIterator()->current();

        $this->assertCount(0, $productOfferTransfer->getServices());
    }

    /**
     * @return void
     */
    public function testShouldThrowExceptionWhileIdProductOfferIsMissing(): void
    {
        // Arrange
        $productOfferTransfer = new ProductOfferTransfer();
        $productOfferCollectionTransfer = (new ProductOfferCollectionTransfer())
            ->addProductOffer($productOfferTransfer);

        // Assert
        $this->expectException(NullValueException::class);

        // Act
        $this->tester
            ->getFacade()
            ->expandProductOfferCollectionWithServices($productOfferCollectionTransfer);
    }
}
