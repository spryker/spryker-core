<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ServicePointCart\Business\Facade;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ServicePointTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use SprykerTest\Zed\ServicePointCart\ServicePointCartBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ServicePointCart
 * @group Business
 * @group Facade
 * @group ValidateCheckoutQuoteItemsTest
 * Add your own group annotations below this line
 */
class ValidateCheckoutQuoteItemsTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ServicePointCart\ServicePointCartBusinessTester
     */
    protected ServicePointCartBusinessTester $tester;

    /**
     * @return void
     */
    public function testReturnsTrueIfNoOneOfQuoteItemsHaveServicePoints(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteTransfer())->setItems(new ArrayObject([new ItemTransfer()]));
        $checkoutResponseTransfer = new CheckoutResponseTransfer();

        // Act
        $isCheckoutQuoteItemsValid = $this->tester->getFacade()->validateCheckoutQuoteItems($quoteTransfer, $checkoutResponseTransfer);

        // Assert
        $this->assertTrue($isCheckoutQuoteItemsValid);
        $this->assertEmpty($checkoutResponseTransfer->getErrors()->count());
    }

    /**
     * @return void
     */
    public function testReturnsTrueIfAllQuoteItemsHaveServicePointsThatAreActiveAndAvailableForCurrentStore(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => 'DE']);
        $servicePointTransfer1 = $this->tester->haveServicePoint(
            [
                ServicePointTransfer::IS_ACTIVE => true,
                ServicePointTransfer::STORE_RELATION => $this->tester->createStoreRelationTransfer($storeTransfer),
            ],
        );
        $servicePointTransfer2 = $this->tester->haveServicePoint(
            [
                ServicePointTransfer::IS_ACTIVE => true,
                ServicePointTransfer::STORE_RELATION => $this->tester->createStoreRelationTransfer($storeTransfer),
            ],
        );
        $itemTransfer1 = (new ItemBuilder([ItemTransfer::GROUP_KEY => 'TEST_ITEM_SKU_1']))->build()
            ->setServicePoint($servicePointTransfer1);
        $itemTransfer2 = (new ItemBuilder([ItemTransfer::GROUP_KEY => 'TEST_ITEM_SKU_2']))->build()
            ->setServicePoint($servicePointTransfer2);

        $quoteTransfer = (new QuoteTransfer())->setStore($storeTransfer)->setItems(
            new ArrayObject([$itemTransfer1, $itemTransfer2]),
        );
        $checkoutResponseTransfer = new CheckoutResponseTransfer();

        // Act
        $isCheckoutQuoteItemsValid = $this->tester->getFacade()->validateCheckoutQuoteItems(
            $quoteTransfer,
            $checkoutResponseTransfer,
        );

        // Assert
        $this->assertTrue($isCheckoutQuoteItemsValid);
        $this->assertEmpty($checkoutResponseTransfer->getErrors()->count());
    }

    /**
     * @return void
     */
    public function testReturnsFalseIfQuoteItemHasServicePointThatIsNotActive(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => 'DE']);
        $servicePointTransfer1 = $this->tester->haveServicePoint(
            [
                ServicePointTransfer::IS_ACTIVE => false,
                ServicePointTransfer::STORE_RELATION => $this->tester->createStoreRelationTransfer($storeTransfer),
            ],
        );
        $servicePointTransfer2 = $this->tester->haveServicePoint(
            [
                ServicePointTransfer::IS_ACTIVE => true,
                ServicePointTransfer::STORE_RELATION => $this->tester->createStoreRelationTransfer($storeTransfer),
            ],
        );
        $itemTransfer1 = (new ItemBuilder([ItemTransfer::GROUP_KEY => 'TEST_ITEM_SKU_1']))->build()
            ->setServicePoint($servicePointTransfer1);
        $itemTransfer2 = (new ItemBuilder([ItemTransfer::GROUP_KEY => 'TEST_ITEM_SKU_2']))->build()
            ->setServicePoint($servicePointTransfer2);

        $quoteTransfer = (new QuoteTransfer())->setStore($storeTransfer)->setItems(
            new ArrayObject([$itemTransfer1, $itemTransfer2]),
        );
        $checkoutResponseTransfer = new CheckoutResponseTransfer();

        // Act
        $isCheckoutQuoteItemsValid = $this->tester->getFacade()->validateCheckoutQuoteItems(
            $quoteTransfer,
            $checkoutResponseTransfer,
        );

        // Assert
        $this->assertFalse($isCheckoutQuoteItemsValid);
        $this->assertFalse($checkoutResponseTransfer->getIsSuccess());
        $this->assertEquals(1, $checkoutResponseTransfer->getErrors()->count());
        $this->tester->assertCheckoutErrorTransfer(
            $checkoutResponseTransfer->getErrors()->getIterator()->current(),
            $servicePointTransfer1->getUuidOrFail(),
            $storeTransfer->getNameOrFail(),
        );
    }

    /**
     * @return void
     */
    public function testReturnsFalseIfQuoteItemsHaveServicePointThatIsNotAvailableForCurrentStore(): void
    {
        // Arrange
        $storeTransfer1 = $this->tester->haveStore([StoreTransfer::NAME => 'DE']);
        $storeTransfer2 = $this->tester->haveStore([StoreTransfer::NAME => 'AT']);

        $servicePointTransfer1 = $this->tester->haveServicePoint(
            [
                ServicePointTransfer::IS_ACTIVE => true,
                ServicePointTransfer::STORE_RELATION => $this->tester->createStoreRelationTransfer($storeTransfer1),
            ],
        );
        $servicePointTransfer2 = $this->tester->haveServicePoint(
            [
                ServicePointTransfer::IS_ACTIVE => true,
                ServicePointTransfer::STORE_RELATION => $this->tester->createStoreRelationTransfer($storeTransfer2),
            ],
        );
        $itemTransfer1 = (new ItemBuilder([ItemTransfer::GROUP_KEY => 'TEST_ITEM_SKU_1']))->build()
            ->setServicePoint($servicePointTransfer1);
        $itemTransfer2 = (new ItemBuilder([ItemTransfer::GROUP_KEY => 'TEST_ITEM_SKU_2']))->build()
            ->setServicePoint($servicePointTransfer2);

        $quoteTransfer = (new QuoteTransfer())->setStore($storeTransfer1)->setItems(
            new ArrayObject([$itemTransfer1, $itemTransfer2]),
        );
        $checkoutResponseTransfer = new CheckoutResponseTransfer();

        // Act
        $isCheckoutQuoteItemsValid = $this->tester->getFacade()->validateCheckoutQuoteItems(
            $quoteTransfer,
            $checkoutResponseTransfer,
        );

        // Assert
        $this->assertFalse($isCheckoutQuoteItemsValid);
        $this->assertFalse($checkoutResponseTransfer->getIsSuccess());
        $this->assertEquals(1, $checkoutResponseTransfer->getErrors()->count());
        $this->tester->assertCheckoutErrorTransfer(
            $checkoutResponseTransfer->getErrors()->getIterator()->current(),
            $servicePointTransfer2->getUuidOrFail(),
            $storeTransfer1->getNameOrFail(),
        );
    }

    /**
     * @return void
     */
    public function testReturnsFalseIfServicePointProvidedInQuoteItemDoesNotExist(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => 'DE']);
        $servicePointTransfer = (new ServicePointTransfer())->setUuid('TEST_NOT_EXISTING_SERVICE_POINT_UUID');
        $itemTransfer = (new ItemBuilder([ItemTransfer::GROUP_KEY => 'TEST_ITEM_SKU_1']))->build()
            ->setServicePoint($servicePointTransfer);

        $quoteTransfer = (new QuoteTransfer())->setStore($storeTransfer)->setItems(
            new ArrayObject([$itemTransfer]),
        );
        $checkoutResponseTransfer = new CheckoutResponseTransfer();

        // Act
        $isCheckoutQuoteItemsValid = $this->tester->getFacade()->validateCheckoutQuoteItems(
            $quoteTransfer,
            $checkoutResponseTransfer,
        );

        // Assert
        $this->assertFalse($isCheckoutQuoteItemsValid);
        $this->assertFalse($checkoutResponseTransfer->getIsSuccess());
        $this->assertEquals(1, $checkoutResponseTransfer->getErrors()->count());
        $this->tester->assertCheckoutErrorTransfer(
            $checkoutResponseTransfer->getErrors()->getIterator()->current(),
            $servicePointTransfer->getUuidOrFail(),
            $storeTransfer->getNameOrFail(),
        );
    }
}
