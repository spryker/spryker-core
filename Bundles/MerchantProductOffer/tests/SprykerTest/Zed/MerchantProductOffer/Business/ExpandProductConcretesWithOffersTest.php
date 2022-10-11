<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantProductOffer\Business\Facade;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\ProductOfferTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantProductOffer
 * @group Business
 * @group Facade
 * @group ExpandProductConcretesWithOffersTest
 * Add your own group annotations below this line
 */
class ExpandProductConcretesWithOffersTest extends Test
{
    /**
     * @var \SprykerTest\Zed\MerchantProductOffer\MerchantProductOfferBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testProductConcretesWasNotExpandedByOffersWhenNoOffers(): void
    {
        // Arrange
        $productConcreteTransfers = [
            $this->tester->haveFullProduct(),
            $this->tester->haveFullProduct(),
        ];

        // Act
        $expandedProductConcreteTransfers = $this->tester->getFacade()
            ->expandProductConcretesWithOffers($productConcreteTransfers);

        // Assert
        foreach ($expandedProductConcreteTransfers as $productConcreteTransfer) {
            $this->assertSame(0, $productConcreteTransfer->getOffers()->count(), 'Offers should be empty');
        }
    }

    /**
     * @return void
     */
    public function testProductConcretesExpandedByOffers(): void
    {
        // Arrange
        $productConcreteTransfers = [
            $this->tester->haveFullProduct(),
            $this->tester->haveFullProduct(),
        ];

        $productOfferTransfer1ForProduct1 = $this->tester->haveProductOffer([
            ProductOfferTransfer::ID_PRODUCT_CONCRETE => $productConcreteTransfers[0]->getIdProductConcrete(),
            ProductOfferTransfer::CONCRETE_SKU => $productConcreteTransfers[0]->getSku(),
        ]);

        $productOfferTransfer2ForProduct1 = $this->tester->haveProductOffer([
            ProductOfferTransfer::ID_PRODUCT_CONCRETE => $productConcreteTransfers[0]->getIdProductConcrete(),
            ProductOfferTransfer::CONCRETE_SKU => $productConcreteTransfers[0]->getSku(),
        ]);

        $productOfferTransfer1ForProduct2 = $this->tester->haveProductOffer([
            ProductOfferTransfer::ID_PRODUCT_CONCRETE => $productConcreteTransfers[1]->getIdProductConcrete(),
            ProductOfferTransfer::CONCRETE_SKU => $productConcreteTransfers[1]->getSku(),
        ]);

        $offersForProduct1ToCheck = [
            $productOfferTransfer1ForProduct1->getIdProductOffer() => $productOfferTransfer1ForProduct1->toArray(),
            $productOfferTransfer2ForProduct1->getIdProductOffer() => $productOfferTransfer2ForProduct1->toArray(),
        ];

        // Act
        $expandedProductConcreteTransfers = $this->tester->getFacade()
            ->expandProductConcretesWithOffers($productConcreteTransfers);

        // Assert
        $this->assertCount(2, $expandedProductConcreteTransfers[0]->getOffers(), 'Product 1 has not enough offers');

        foreach ($expandedProductConcreteTransfers[0]->getOffers() as $offer) {
            $this->assertArrayHasKey($offer->getIdProductOffer(), $offersForProduct1ToCheck);
            $this->assertSame(
                $this->tester->removeDynamicProductOfferFields($offersForProduct1ToCheck[$offer->getIdProductOffer()]),
                $this->tester->removeDynamicProductOfferFields($offer->toArray()),
                'Product 1 has incorrect offer',
            );
        }

        $this->assertSame(
            $this->tester->removeDynamicProductOfferFields($productOfferTransfer1ForProduct2->toArray()),
            $this->tester->removeDynamicProductOfferFields(
                $expandedProductConcreteTransfers[1]->getOffers()->offsetGet(0)->toArray(),
            ),
            'Product 2 has incorrect offer',
        );
    }

    /**
     * @return void
     */
    public function testMerchantNameAddedToProductOffer(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveFullProduct();
        $merchantTransfer = $this->tester->haveMerchant();
        $productOfferTransfer = $this->tester->haveProductOffer([
            ProductOfferTransfer::FK_MERCHANT => $merchantTransfer->getIdMerchant(),
            ProductOfferTransfer::MERCHANT_REFERENCE => $merchantTransfer->getMerchantReference(),
            ProductOfferTransfer::ID_PRODUCT_CONCRETE => $productConcreteTransfer->getIdProductConcrete(),
            ProductOfferTransfer::CONCRETE_SKU => $productConcreteTransfer->getSku(),
        ]);

        // Act
        $expandedProductConcreteTransfers = $this->tester->getFacade()
            ->expandProductConcretesWithOffers([$productConcreteTransfer]);

        // Assert
        $productOfferTransfer = $expandedProductConcreteTransfers[0]->getOffers()->offsetGet(0);

        $this->assertSame($merchantTransfer->getName(), $productOfferTransfer->getMerchantName());
    }
}
