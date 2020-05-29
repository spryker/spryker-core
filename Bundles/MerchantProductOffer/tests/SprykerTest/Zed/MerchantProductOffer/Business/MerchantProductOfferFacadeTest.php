<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantProductOffer\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MerchantProductOfferCriteriaFilterTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantProductOffer
 * @group Business
 * @group Facade
 * @group MerchantProductOfferFacadeTest
 * Add your own group annotations below this line
 */
class MerchantProductOfferFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\MerchantProductOffer\MerchantProductOfferBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGetProductOfferCollectionReturnsFilledCollection(): void
    {
        // Arrange
        $merchantTransfer = $this->tester->haveMerchant();

        $productOfferTransfer = $this->tester->haveProductOffer([
            ProductOfferTransfer::FK_MERCHANT => $merchantTransfer->getIdMerchant(),
        ]);

        $merchantProductOfferCriteriaFilterTransfer = (new MerchantProductOfferCriteriaFilterTransfer())
            ->setMerchantReference($merchantTransfer->getMerchantReference())
            ->setSkus([$productOfferTransfer->getConcreteSku()])
            ->setIsActive(true);

        // Act
        $productOfferCollectionTransfer = $this->tester->getFacade()->getProductOfferCollection($merchantProductOfferCriteriaFilterTransfer);

        // Assert
        $this->assertNotEmpty($productOfferCollectionTransfer->getProductOffers());
    }

    /**
     * @return void
     */
    public function testCheckItemProductOfferWithValidProductOfferReturnsSuccess(): void
    {
        // Arrange
        $productOfferTransfer = $this->tester->haveProductOffer();
        $itemTransfer = (new ItemTransfer())
            ->setSku($productOfferTransfer->getConcreteSku())
            ->setProductOfferReference($productOfferTransfer->getProductOfferReference());
        $cartChangeTransfer = (new CartChangeTransfer())
            ->addItem($itemTransfer)
            ->setQuote((new QuoteTransfer()));

        // Act
        $cartPreCheckResponseTransfer = $this->tester->getFacade()->checkItemProductOffer($cartChangeTransfer);

        // Assert
        $this->assertTrue($cartPreCheckResponseTransfer->getIsSuccess());
        $this->assertEmpty($cartPreCheckResponseTransfer->getMessages());
    }

    /**
     * @return void
     */
    public function testCheckItemProductOfferWithInValidProductOfferReturnsError(): void
    {
        // Arrange
        $productOfferTransfer = $this->tester->haveProductOffer();
        $productOfferTransfer2 = $this->tester->haveProductOffer();
        $itemTransfer = (new ItemTransfer())
            ->setSku($productOfferTransfer->getConcreteSku())
            ->setProductOfferReference($productOfferTransfer2->getProductOfferReference());
        $cartChangeTransfer = (new CartChangeTransfer())
            ->addItem($itemTransfer)
            ->setQuote((new QuoteTransfer()));

        // Act
        $cartPreCheckResponseTransfer = $this->tester->getFacade()->checkItemProductOffer($cartChangeTransfer);

        // Assert
        $this->assertFalse($cartPreCheckResponseTransfer->getIsSuccess());
        $this->assertNotEmpty($cartPreCheckResponseTransfer->getMessages());
    }

    /**
     * @return void
     */
    public function testCheckItemProductOfferWithoutProductOfferReturnsSuccess(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveProduct();
        $productOfferTransfer = $this->tester->haveProductOffer();

        $itemTransfer = (new ItemTransfer())
            ->setSku($productConcreteTransfer->getSku())
            ->setProductOfferReference($productOfferTransfer->getProductOfferReference());
        $cartChangeTransfer = (new CartChangeTransfer())
            ->addItem($itemTransfer)
            ->setQuote((new QuoteTransfer()));

        // Act
        $cartPreCheckResponseTransfer = $this->tester->getFacade()->checkItemProductOffer($cartChangeTransfer);

        // Assert
        $this->assertFalse($cartPreCheckResponseTransfer->getIsSuccess());
        $this->assertNotEmpty($cartPreCheckResponseTransfer->getMessages());
    }
}
