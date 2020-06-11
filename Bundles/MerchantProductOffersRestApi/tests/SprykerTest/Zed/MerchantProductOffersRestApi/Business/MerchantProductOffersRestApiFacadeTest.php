<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantProductOffersRestApi\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CartItemRequestTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantProductOffersRestApi
 * @group Business
 * @group Facade
 * @group MerchantProductOffersRestApiFacadeTest
 * Add your own group annotations below this line
 */
class MerchantProductOffersRestApiFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\MerchantProductOffersRestApi\MerchantProductOffersRestApiBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testMapCartItemRequestTransferToPersistentCartChangeTransferMapsDataSuccessfully(): void
    {
        // Arrange
        $cartItemRequestTransfer = $this->tester->prepareCartItemRequestTransfer([
            CartItemRequestTransfer::SKU => $this->tester::PRODUCT_CONCRETE_SKU,
        ]);
        $persistentCartChangeTransfer = $this->tester->preparePersistentCartChangeTransfer();

        // Act
        $changedPersistentCartChangeTransfer = $this->tester->getFacade()->mapCartItemRequestTransferToPersistentCartChangeTransfer(
            $cartItemRequestTransfer,
            $persistentCartChangeTransfer
        );

        // Assert
        $this->assertEquals(
            $cartItemRequestTransfer->getProductOfferReference(),
            $changedPersistentCartChangeTransfer->getItems()->getIterator()->current()->getProductOfferReference()
        );
        $this->assertEquals(
            $cartItemRequestTransfer->getMerchantReference(),
            $changedPersistentCartChangeTransfer->getItems()->getIterator()->current()->getMerchantReference()
        );
    }

    /**
     * @return void
     */
    public function testMapCartItemRequestTransferToPersistentCartChangeTransferMapsDataUnsuccessfullyWithDifferentSkus(): void
    {
        // Arrange
        $cartItemRequestTransfer = $this->tester->prepareCartItemRequestTransfer([
            CartItemRequestTransfer::SKU => $this->tester::DIFFERENT_PRODUCT_CONCRETE_SKU,
            CartItemRequestTransfer::PRODUCT_OFFER_REFERENCE => $this->tester::PRODUCT_OFFER_REFERENCE,
            CartItemRequestTransfer::MERCHANT_REFERENCE => $this->tester::MERCHANT_REFERENCE,
        ]);
        $persistentCartChangeTransfer = $this->tester->preparePersistentCartChangeTransfer();

        // Act
        $changedPersistentCartChangeTransfer = $this->tester->getFacade()->mapCartItemRequestTransferToPersistentCartChangeTransfer(
            $cartItemRequestTransfer,
            $persistentCartChangeTransfer
        );

        // Assert
        $this->assertNotEquals(
            $cartItemRequestTransfer->getProductOfferReference(),
            $changedPersistentCartChangeTransfer->getItems()->getIterator()->current()->getProductOfferReference()
        );
        $this->assertNotEquals(
            $cartItemRequestTransfer->getMerchantReference(),
            $changedPersistentCartChangeTransfer->getItems()->getIterator()->current()->getMerchantReference()
        );
    }

    /**
     * @return void
     */
    public function testMapCartItemRequestTransferToPersistentCartChangeTransferMapsDataUnsuccessfullyWithNoOfferData(): void
    {
        // Arrange
        $cartItemRequestTransfer = $this->tester->prepareCartItemRequestTransfer([
            CartItemRequestTransfer::SKU => $this->tester::PRODUCT_CONCRETE_SKU,
        ]);
        $persistentCartChangeTransfer = $this->tester->preparePersistentCartChangeTransfer();

        // Act
        $changedPersistentCartChangeTransfer = $this->tester->getFacade()->mapCartItemRequestTransferToPersistentCartChangeTransfer(
            $cartItemRequestTransfer,
            $persistentCartChangeTransfer
        );

        // Assert
        $this->assertNull(
            $changedPersistentCartChangeTransfer->getItems()->getIterator()->current()->getProductOfferReference()
        );
        $this->assertNull(
            $changedPersistentCartChangeTransfer->getItems()->getIterator()->current()->getMerchantReference()
        );
    }
}
