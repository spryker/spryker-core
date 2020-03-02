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

        $this->assertEquals($itemTransfer->getProductOfferReference(), $productOfferTransfer2->getProductOfferReference());
        $this->assertEquals($itemTransfer->getMerchantReference(), $merchantTransfer2->getMerchantReference());
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

        $this->assertEquals($itemTransfer->getProductOfferReference(), $productOfferTransfer->getProductOfferReference());
        $this->assertEquals($itemTransfer->getMerchantReference(), $merchantTransfer->getMerchantReference());
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
        $this->assertEquals($quoteTransfer->getMerchantReference(), $merchantTransfer->getMerchantReference());
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
}
