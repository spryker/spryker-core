<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantProductOffersRestApi\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CartItemRequestTransfer;
use Generated\Shared\Transfer\ItemTransfer;

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
    protected const TEST_SKU = 'test123';

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
        $cartItem = $this->tester->prepareItemTransfer([
            ItemTransfer::SKU => static::TEST_SKU,
        ]);
        $cartItemRequestTransfer = $this->tester->prepareCartItemRequestTransfer([
            CartItemRequestTransfer::SKU => static::TEST_SKU,
        ]);
        $persistentCartChangeTransfer = $this->tester->createPersistentCartChangeTransfer();
        $persistentCartChangeTransfer->addItem($cartItem);

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
        $cartItem = $this->tester->prepareItemTransfer();
        $cartItemRequestTransfer = $this->tester->prepareCartItemRequestTransfer();
        $persistentCartChangeTransfer = $this->tester->createPersistentCartChangeTransfer();
        $persistentCartChangeTransfer->addItem($cartItem);

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
        $cartItem = $this->tester->prepareItemTransfer([
            ItemTransfer::SKU => static::TEST_SKU,
        ]);
        $cartItemRequestTransfer = $this->tester->prepareCartItemRequestTransfer([
            CartItemRequestTransfer::SKU => static::TEST_SKU,
            ItemTransfer::PRODUCT_OFFER_REFERENCE => null,
            ItemTransfer::MERCHANT_REFERENCE => null,
        ]);
        $persistentCartChangeTransfer = $this->tester->createPersistentCartChangeTransfer();
        $persistentCartChangeTransfer->addItem($cartItem);

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
