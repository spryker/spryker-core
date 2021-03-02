<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantSwitcher\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MerchantSwitchRequestTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SingleMerchantQuoteValidationRequestTransfer;
use Generated\Shared\Transfer\WishlistItemTransfer;
use Generated\Shared\Transfer\WishlistTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantSwitcher
 * @group Business
 * @group Facade
 * @group MerchantSwitcherFacadeTest
 *
 * Add your own group annotations below this line
 */
class MerchantSwitcherFacadeTest extends Unit
{
    protected const OLD_PRODUCT_OFFER_REFERENCE = 'old_product_offer_reference';

    /**
     * @var \SprykerTest\Zed\MerchantSwitcher\MerchantSwitcherBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testSwitchMerchantInQuoteItemsReplacesProductOffersInQuote(): void
    {
        // Arrange
        $merchantTransfer1 = $this->tester->haveMerchant();
        $merchantTransfer2 = $this->tester->haveMerchant();

        $productOfferTransfer1 = $this->tester->haveProductOffer([
            ProductOfferTransfer::FK_MERCHANT => $merchantTransfer1->getIdMerchant(),

        ]);

        $productOfferTransfer2 = $this->tester->haveProductOffer([
            ProductOfferTransfer::FK_MERCHANT => $merchantTransfer2->getIdMerchant(),
            ProductOfferTransfer::CONCRETE_SKU => $productOfferTransfer1->getConcreteSku(),
        ]);

        $quoteTransfer = $this->tester->havePersistentQuote([
            QuoteTransfer::CUSTOMER => $this->tester->haveCustomer(),
            QuoteTransfer::MERCHANT_REFERENCE => $merchantTransfer1->getMerchantReference(),
            QuoteTransfer::ITEMS => [
                [
                    ItemTransfer::SKU => $productOfferTransfer1->getConcreteSku(),
                    ItemTransfer::UNIT_PRICE => 1,
                    ItemTransfer::QUANTITY => 1,
                    ItemTransfer::MERCHANT_REFERENCE => $merchantTransfer1->getMerchantReference(),
                    ItemTransfer::PRODUCT_OFFER_REFERENCE => $productOfferTransfer1->getProductOfferReference(),
                ],
            ],
        ]);

        $merchantSwitchRequestTransfer = (new MerchantSwitchRequestTransfer())
            ->setQuote($quoteTransfer)
            ->setMerchantReference($merchantTransfer2->getMerchantReference());

        // Act
        $quoteTransfer = $this->tester->getFacade()
            ->switchMerchantInQuoteItems($merchantSwitchRequestTransfer)
            ->getQuote();

        //Assert
        /** @var \Generated\Shared\Transfer\ItemTransfer $itemTransfer */
        $itemTransfer = $quoteTransfer->getItems()->getIterator()->current();

        $this->assertSame($itemTransfer->getProductOfferReference(), $productOfferTransfer2->getProductOfferReference());
        $this->assertSame($itemTransfer->getMerchantReference(), $merchantTransfer2->getMerchantReference());
    }

    /**
     * @return void
     */
    public function testSwitchMerchantInQuoteItemsDoesNothingIfNoReplacementFound(): void
    {
        // Arrange
        $merchantTransfer = $this->tester->haveMerchant();

        $productOfferTransfer = $this->tester->haveProductOffer([
            ProductOfferTransfer::FK_MERCHANT => $merchantTransfer->getIdMerchant(),
        ]);

        $quoteTransfer = $this->tester->havePersistentQuote([
            QuoteTransfer::CUSTOMER => $this->tester->haveCustomer(),
            QuoteTransfer::MERCHANT_REFERENCE => uniqid(),
            QuoteTransfer::ITEMS => [
                [
                    ItemTransfer::SKU => $productOfferTransfer->getConcreteSku(),
                    ItemTransfer::UNIT_PRICE => 1,
                    ItemTransfer::QUANTITY => 1,
                    ItemTransfer::MERCHANT_REFERENCE => $merchantTransfer->getMerchantReference(),
                    ItemTransfer::PRODUCT_OFFER_REFERENCE => $productOfferTransfer->getProductOfferReference(),
                ],
            ],
        ]);

        $merchantSwitchRequestTransfer = (new MerchantSwitchRequestTransfer())
            ->setQuote($quoteTransfer)
            ->setMerchantReference($merchantTransfer->getMerchantReference());

        // Act
        $quoteTransfer = $this->tester->getFacade()
            ->switchMerchantInQuoteItems($merchantSwitchRequestTransfer)
            ->getQuote();

        // Assert
        /** @var \Generated\Shared\Transfer\ItemTransfer $itemTransfer */
        $itemTransfer = $quoteTransfer->getItems()->getIterator()->current();

        $this->assertSame($itemTransfer->getProductOfferReference(), $productOfferTransfer->getProductOfferReference());
        $this->assertSame($itemTransfer->getMerchantReference(), $merchantTransfer->getMerchantReference());
    }

    /**
     * @return void
     */
    public function testSwitchMerchantInQuoteReplacesMerchantReferenceValue(): void
    {
        // Arrange
        $merchantTransfer = $this->tester->haveMerchant();

        $productOfferTransfer = $this->tester->haveProductOffer([
            ProductOfferTransfer::FK_MERCHANT => $merchantTransfer->getIdMerchant(),
        ]);

        $quoteTransfer = $this->tester->havePersistentQuote([
            QuoteTransfer::CUSTOMER => $this->tester->haveCustomer(),
            QuoteTransfer::ITEMS => [
                [
                    ItemTransfer::SKU => $productOfferTransfer->getConcreteSku(),
                    ItemTransfer::UNIT_PRICE => 1,
                    ItemTransfer::QUANTITY => 1,
                    ItemTransfer::MERCHANT_REFERENCE => $merchantTransfer->getMerchantReference(),
                    ItemTransfer::PRODUCT_OFFER_REFERENCE => $productOfferTransfer->getProductOfferReference(),
                ],
            ],
        ]);

        $merchantSwitchRequestTransfer = (new MerchantSwitchRequestTransfer())
            ->setQuote($quoteTransfer)
            ->setMerchantReference($merchantTransfer->getMerchantReference());

        // Act
        $quoteTransfer = $this->tester->getFacade()->switchMerchantInQuote($merchantSwitchRequestTransfer)->getQuote();

        // Assert
        $this->assertSame($quoteTransfer->getMerchantReference(), $merchantTransfer->getMerchantReference());
    }

    /**
     * @return void
     */
    public function testValidateMerchantInQuoteReturnsSuccessfulResponse(): void
    {
        // Arrange
        $merchantTransfer = $this->tester->haveMerchant();

        $productOfferTransfer = $this->tester->haveProductOffer([
            ProductOfferTransfer::FK_MERCHANT => $merchantTransfer->getIdMerchant(),
        ]);

        $quoteTransfer = $this->tester->havePersistentQuote([
            QuoteTransfer::CUSTOMER => $this->tester->haveCustomer(),
            QuoteTransfer::MERCHANT_REFERENCE => $merchantTransfer->getMerchantReference(),
            QuoteTransfer::ITEMS => [
                [
                    ItemTransfer::SKU => $productOfferTransfer->getConcreteSku(),
                    ItemTransfer::UNIT_PRICE => 1,
                    ItemTransfer::QUANTITY => 1,
                    ItemTransfer::MERCHANT_REFERENCE => $merchantTransfer->getMerchantReference(),
                    ItemTransfer::PRODUCT_OFFER_REFERENCE => $productOfferTransfer->getProductOfferReference(),
                ],
            ],
        ]);

        $singleMerchantQuoteValidationRequestTransfer = (new SingleMerchantQuoteValidationRequestTransfer())
            ->setMerchantReference($quoteTransfer->getMerchantReference())
            ->setItems($quoteTransfer->getItems());

        // Act
        $singleMerchantQuoteValidationResponseTransfer = $this->tester->getFacade()->validateMerchantInQuoteItems($singleMerchantQuoteValidationRequestTransfer);

        // Assert
        $this->assertTrue($singleMerchantQuoteValidationResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testValidateMerchantInQuoteReturnsFailureResponse(): void
    {
        // Arrange
        $merchantTransfer = $this->tester->haveMerchant();

        $productOfferTransfer = $this->tester->haveProductOffer([
            ProductOfferTransfer::FK_MERCHANT => $merchantTransfer->getIdMerchant(),
        ]);

        $quoteTransfer = $this->tester->havePersistentQuote([
            QuoteTransfer::CUSTOMER => $this->tester->haveCustomer(),
            QuoteTransfer::MERCHANT_REFERENCE => uniqid(),
            QuoteTransfer::ITEMS => [
                [
                    ItemTransfer::SKU => $productOfferTransfer->getConcreteSku(),
                    ItemTransfer::UNIT_PRICE => 1,
                    ItemTransfer::QUANTITY => 1,
                    ItemTransfer::MERCHANT_REFERENCE => $merchantTransfer->getMerchantReference(),
                    ItemTransfer::PRODUCT_OFFER_REFERENCE => $productOfferTransfer->getProductOfferReference(),
                ],
            ],
        ]);

        $singleMerchantQuoteValidationRequestTransfer = (new SingleMerchantQuoteValidationRequestTransfer())
            ->setMerchantReference($quoteTransfer->getMerchantReference())
            ->setItems($quoteTransfer->getItems());

        // Act
        $singleMerchantQuoteValidationResponseTransfer = $this->tester->getFacade()->validateMerchantInQuoteItems($singleMerchantQuoteValidationRequestTransfer);

        // Assert
        $this->assertFalse($singleMerchantQuoteValidationResponseTransfer->getIsSuccessful());
        $this->assertNotEmpty($singleMerchantQuoteValidationResponseTransfer->getErrors()->count());
    }

    /**
     * @return void
     */
    public function testSwitchMerchantInWishlistItemsSuccessful(): void
    {
        // Arrange
        $oldMerchantTransfer = $this->tester->haveMerchant();
        $newMerchantTransfer = $this->tester->haveMerchant();

        $productOfferTransfer1 = $this->tester->haveProductOffer([
            ProductOfferTransfer::FK_MERCHANT => $newMerchantTransfer->getIdMerchant(),
            ProductOfferTransfer::MERCHANT_REFERENCE => $newMerchantTransfer->getMerchantReference(),
        ]);

        $productOfferTransfer2 = $this->tester->haveProductOffer([
            ProductOfferTransfer::FK_MERCHANT => $newMerchantTransfer->getIdMerchant(),
            ProductOfferTransfer::MERCHANT_REFERENCE => $newMerchantTransfer->getMerchantReference(),
        ]);

        $customerTransfer = $this->tester->haveCustomer();

        $wishlistTransfer = $this->tester->haveWishlist([
            WishlistTransfer::MERCHANT_REFERENCE => $newMerchantTransfer->getMerchantReference(),
            WishlistTransfer::FK_CUSTOMER => $customerTransfer->getIdCustomer(),
        ]);

        $wishlistItemTransfer1 = $this->tester->haveItemInWishlist([
            WishlistItemTransfer::FK_WISHLIST => $wishlistTransfer->getIdWishlist(),
            WishlistItemTransfer::FK_CUSTOMER => $customerTransfer->getIdCustomer(),
            WishlistItemTransfer::MERCHANT_REFERENCE => $oldMerchantTransfer->getMerchantReference(),
            WishlistItemTransfer::SKU => $productOfferTransfer1->getConcreteSku(),
        ]);

        $wishlistItemTransfer2 = $this->tester->haveItemInWishlist([
            WishlistItemTransfer::FK_WISHLIST => $wishlistTransfer->getIdWishlist(),
            WishlistItemTransfer::FK_CUSTOMER => $customerTransfer->getIdCustomer(),
            WishlistItemTransfer::MERCHANT_REFERENCE => $oldMerchantTransfer->getMerchantReference(),
            WishlistItemTransfer::SKU => $productOfferTransfer2->getConcreteSku(),
        ]);

        $wishlistTransfer->addWishlistItem($wishlistItemTransfer1);
        $wishlistTransfer->addWishlistItem($wishlistItemTransfer2);

        $merchantSwitchRequestTransfer = (new MerchantSwitchRequestTransfer())
            ->setMerchantReference($newMerchantTransfer->getMerchantReference())
            ->setWishlist($wishlistTransfer);

        // Act
        $merchantSwitchResponseTransfer = $this->tester
            ->getFacade()
            ->switchMerchantInWishlistItems($merchantSwitchRequestTransfer);

        // Assert
        $wishlistItemTransfers = $merchantSwitchResponseTransfer->getWishlist()->getWishlistItems();

        /** @var \Generated\Shared\Transfer\WishlistItemTransfer $actualWishlistItemTransfer1 */
        $actualWishlistItemTransfer1 = $wishlistItemTransfers->offsetGet(0);

        $newMerchantReference = $newMerchantTransfer->getMerchantReference();
        $productOfferReference1 = $productOfferTransfer1->getProductOfferReference();
        $productOfferReference2 = $productOfferTransfer2->getProductOfferReference();

        $this->assertEquals($newMerchantReference, $actualWishlistItemTransfer1->getMerchantReference());
        $this->assertEquals($productOfferReference1, $actualWishlistItemTransfer1->getProductOfferReference());

        /** @var \Generated\Shared\Transfer\WishlistItemTransfer $actualWishlistItemTransfer2 */
        $actualWishlistItemTransfer2 = $wishlistItemTransfers->offsetGet(1);

        $this->assertEquals($newMerchantReference, $actualWishlistItemTransfer2->getMerchantReference());
        $this->assertEquals($productOfferReference2, $actualWishlistItemTransfer2->getProductOfferReference());
    }

    /**
     * @return void
     */
    public function testSwitchMerchantInWishlistItemsDoesNothingNoMatchingProductOffer(): void
    {
        // Arrange
        $oldMerchantTransfer = $this->tester->haveMerchant();
        $newMerchantTransfer = $this->tester->haveMerchant();

        $customerTransfer = $this->tester->haveCustomer();

        $wishlistTransfer = $this->tester->haveWishlist([
            WishlistTransfer::MERCHANT_REFERENCE => $newMerchantTransfer->getMerchantReference(),
            WishlistTransfer::FK_CUSTOMER => $customerTransfer->getIdCustomer(),
        ]);

        $wishlistItemTransfer1 = $this->tester->haveItemInWishlist([
            WishlistItemTransfer::FK_WISHLIST => $wishlistTransfer->getIdWishlist(),
            WishlistItemTransfer::FK_CUSTOMER => $customerTransfer->getIdCustomer(),
            WishlistItemTransfer::MERCHANT_REFERENCE => $oldMerchantTransfer->getMerchantReference(),
        ]);

        $wishlistItemTransfer2 = $this->tester->haveItemInWishlist([
            WishlistItemTransfer::FK_WISHLIST => $wishlistTransfer->getIdWishlist(),
            WishlistItemTransfer::FK_CUSTOMER => $customerTransfer->getIdCustomer(),
            WishlistItemTransfer::MERCHANT_REFERENCE => $oldMerchantTransfer->getMerchantReference(),
        ]);

        $wishlistTransfer->addWishlistItem($wishlistItemTransfer1);
        $wishlistTransfer->addWishlistItem($wishlistItemTransfer2);

        $merchantSwitchRequestTransfer = (new MerchantSwitchRequestTransfer())
            ->setMerchantReference($newMerchantTransfer->getMerchantReference())
            ->setWishlist($wishlistTransfer);

        // Act
        $merchantSwitchResponseTransfer = $this->tester
            ->getFacade()
            ->switchMerchantInWishlistItems($merchantSwitchRequestTransfer);

        // Assert
        $wishlistItemTransfers = $merchantSwitchResponseTransfer->getWishlist()->getWishlistItems();

        /** @var \Generated\Shared\Transfer\WishlistItemTransfer $actualWishlistItemTransfer1 */
        $actualWishlistItemTransfer1 = $wishlistItemTransfers->offsetGet(0);

        $oldMerchantReference = $oldMerchantTransfer->getMerchantReference();
        $productOfferReference1 = $wishlistItemTransfer1->getProductOfferReference();
        $productOfferReference2 = $wishlistItemTransfer2->getProductOfferReference();

        $this->assertEquals($oldMerchantReference, $actualWishlistItemTransfer1->getMerchantReference());
        $this->assertEquals($productOfferReference1, $actualWishlistItemTransfer1->getProductOfferReference());

        /** @var \Generated\Shared\Transfer\WishlistItemTransfer $actualWishlistItemTransfer2 */
        $actualWishlistItemTransfer2 = $wishlistItemTransfers->offsetGet(1);

        $this->assertEquals($oldMerchantReference, $actualWishlistItemTransfer2->getMerchantReference());
        $this->assertEquals($productOfferReference2, $actualWishlistItemTransfer2->getProductOfferReference());
    }

    /**
     * @return void
     */
    public function testValidateWishlistItemsSuccessfully(): void
    {
        // Arrange
        $merchantTransfer = $this->tester->haveMerchant();
        $customerTransfer = $this->tester->haveCustomer();

        $wishlistTransfer = $this->tester->haveWishlist([
            WishlistTransfer::MERCHANT_REFERENCE => $merchantTransfer->getMerchantReference(),
            WishlistTransfer::FK_CUSTOMER => $customerTransfer->getIdCustomer(),
        ]);

        $wishlistItemTransfer1 = $this->tester->haveItemInWishlist([
            WishlistItemTransfer::FK_WISHLIST => $wishlistTransfer->getIdWishlist(),
            WishlistItemTransfer::FK_CUSTOMER => $customerTransfer->getIdCustomer(),
            WishlistItemTransfer::MERCHANT_REFERENCE => $merchantTransfer->getMerchantReference(),
        ]);

        $wishlistItemTransfer2 = $this->tester->haveItemInWishlist([
            WishlistItemTransfer::FK_WISHLIST => $wishlistTransfer->getIdWishlist(),
            WishlistItemTransfer::FK_CUSTOMER => $customerTransfer->getIdCustomer(),
            WishlistItemTransfer::MERCHANT_REFERENCE => $merchantTransfer->getMerchantReference(),
        ]);

        $wishlistTransfer->addWishlistItem($wishlistItemTransfer1);
        $wishlistTransfer->addWishlistItem($wishlistItemTransfer2);

        // Act
        $validationResponseTransfer = $this->tester
            ->getFacade()
            ->validateWishlistItems($wishlistTransfer);

        // Assert
        $this->assertTrue($validationResponseTransfer->getIsSuccessful());
        $this->assertEquals(0, $validationResponseTransfer->getErrors()->count());
    }

    /**
     * @return void
     */
    public function testValidateWishlistItemsFailedWrongMerchantReference(): void
    {
        // Arrange
        $merchantTransfer = $this->tester->haveMerchant();
        $customerTransfer = $this->tester->haveCustomer();

        $wishlistTransfer = $this->tester->haveWishlist([
            WishlistTransfer::MERCHANT_REFERENCE => $merchantTransfer->getMerchantReference(),
            WishlistTransfer::FK_CUSTOMER => $customerTransfer->getIdCustomer(),
        ]);

        $wishlistItemTransfer1 = $this->tester->haveItemInWishlist([
            WishlistItemTransfer::FK_WISHLIST => $wishlistTransfer->getIdWishlist(),
            WishlistItemTransfer::FK_CUSTOMER => $customerTransfer->getIdCustomer(),
        ]);

        $wishlistItemTransfer2 = $this->tester->haveItemInWishlist([
            WishlistItemTransfer::FK_WISHLIST => $wishlistTransfer->getIdWishlist(),
            WishlistItemTransfer::FK_CUSTOMER => $customerTransfer->getIdCustomer(),
        ]);

        $wishlistTransfer->addWishlistItem($wishlistItemTransfer1);
        $wishlistTransfer->addWishlistItem($wishlistItemTransfer2);

        // Act
        $validationResponseTransfer = $this->tester
            ->getFacade()
            ->validateWishlistItems($wishlistTransfer);

        // Assert
        $this->assertFalse($validationResponseTransfer->getIsSuccessful());
        $this->assertEquals(2, $validationResponseTransfer->getErrors()->count());
    }
}
