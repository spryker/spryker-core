<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\MerchantProductOption\Business;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MerchantProductOptionGroupCriteriaTransfer;
use Generated\Shared\Transfer\MerchantProductOptionGroupTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\ProductOptionGroupTransfer;
use Generated\Shared\Transfer\ProductOptionTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\MerchantProductOption\MerchantProductOptionConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Shared
 * @group MerchantProductOption
 * @group Business
 * @group Facade
 * @group MerchantProductOptionFacadeTest
 * Add your own group annotations below this line
 */
class MerchantProductOptionFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\MerchantProductOption\MerchantProductOptionBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGetGrpoupsEmptyCollectionSuccess(): void
    {
        // Arrange
        $this->tester->ensureMerchantProductOptionGroupTableEmpty();

        // Act
        $merchantProductOptionGroupCollectionTransfer = $this->tester
            ->getFacade()
            ->getGroups(new MerchantProductOptionGroupCriteriaTransfer());

        // Assert
        $this->assertCount(0, $merchantProductOptionGroupCollectionTransfer->getMerchantProductOptionGroups());
    }

    /**
     * @return void
     */
    public function testGetGroupsNotEmptyCollectionSuccess(): void
    {
        // Arrange
        $this->tester->ensureMerchantProductOptionGroupTableEmpty();
        $this->tester->haveMerchantProductOptionGroup([
            MerchantProductOptionGroupTransfer::APPROVAL_STATUS => MerchantProductOptionConfig::STATUS_APPROVED,
        ]);
        $this->tester->haveMerchantProductOptionGroup([
            MerchantProductOptionGroupTransfer::APPROVAL_STATUS => MerchantProductOptionConfig::STATUS_APPROVED,
        ]);

        // Act
        $merchantProductOptionGroupCollectionTransfer = $this->tester
            ->getFacade()
            ->getGroups(new MerchantProductOptionGroupCriteriaTransfer());

        // Assert
        $this->assertCount(2, $merchantProductOptionGroupCollectionTransfer->getMerchantProductOptionGroups());
    }

    /**
     * @return void
     */
    public function testValidateMerchantProductOptionsInCartSuccess(): void
    {
        // Arrange
        $this->tester->ensureMerchantProductOptionGroupTableEmpty();
        $merchantProductOptionGroupTransfer = $this->tester->haveMerchantProductOptionGroup([
            MerchantProductOptionGroupTransfer::APPROVAL_STATUS => MerchantProductOptionConfig::STATUS_APPROVED,
        ]);
        $productOptionTransfer = (new ProductOptionTransfer())->setIdGroup($merchantProductOptionGroupTransfer->getFkProductOptionGroup());
        $itemTransfer = (new ItemTransfer())->setProductOptions(new ArrayObject([$productOptionTransfer]));
        $cartChangeTransfer = (new CartChangeTransfer())
            ->setItems(new ArrayObject([$itemTransfer]));

        // Act
        $cartPreCheckResponseTransfer = $this->tester->getFacade()->validateMerchantProductOptionsInCart($cartChangeTransfer);

        // Assert
        $this->assertTrue($cartPreCheckResponseTransfer->getIsSuccess());
        $this->assertCount(0, $cartPreCheckResponseTransfer->getMessages());
    }

    /**
     * @return void
     */
    public function testValidateMerchantProductOptionsInCartFailed(): void
    {
        // Arrange
        $this->tester->ensureMerchantProductOptionGroupTableEmpty();
        $merchantProductOptionGroupTransfer = $this->tester->haveMerchantProductOptionGroup([
            MerchantProductOptionGroupTransfer::APPROVAL_STATUS => MerchantProductOptionConfig::STATUS_WAITING_FOR_APPROVAL,
        ]);
        $productOptionTransfer = (new ProductOptionTransfer())->setIdGroup($merchantProductOptionGroupTransfer->getFkProductOptionGroup());
        $itemTransfer = (new ItemTransfer())->setProductOptions(new ArrayObject([$productOptionTransfer]));
        $cartChangeTransfer = (new CartChangeTransfer())
            ->setItems(new ArrayObject([$itemTransfer]));

        // Act
        $cartPreCheckResponseTransfer = $this->tester->getFacade()->validateMerchantProductOptionsInCart($cartChangeTransfer);

        // Assert
        $this->assertFalse($cartPreCheckResponseTransfer->getIsSuccess());
        $this->assertCount(1, $cartPreCheckResponseTransfer->getMessages());
    }

    /**
     * @return void
     */
    public function testValidateMerchantProductOptionsOnCheckoutReturnsTrueForApprovedGroup(): void
    {
        // Arrange
        $this->tester->ensureMerchantProductOptionGroupTableEmpty();
        $merchantProductOptionGroupTransfer = $this->tester->haveMerchantProductOptionGroup([
            MerchantProductOptionGroupTransfer::APPROVAL_STATUS => MerchantProductOptionConfig::STATUS_APPROVED,
        ]);
        $productOptionTransfer = (new ProductOptionTransfer())->setIdGroup($merchantProductOptionGroupTransfer->getFkProductOptionGroup());
        $itemTransfer = (new ItemTransfer())->setProductOptions(new ArrayObject([$productOptionTransfer]));
        $quoteTransfer = (new QuoteTransfer())
            ->setItems(new ArrayObject([$itemTransfer]));
        $checkoutResponseTransfer = new CheckoutResponseTransfer();

        // Act
        $checkoutResponseTransfer = $this->tester->getFacade()->validateMerchantProductOptionsOnCheckout(
            $quoteTransfer,
            $checkoutResponseTransfer
        );

        // Assert
        $this->assertTrue($checkoutResponseTransfer->getIsSuccess());
        $this->assertCount(0, $checkoutResponseTransfer->getErrors());
    }

    /**
     * @return void
     */
    public function testValidateMerchantProductOptionsOnCheckoutReturnsFalseForNotApprovedGroup(): void
    {
        // Arrange
        $this->tester->ensureMerchantProductOptionGroupTableEmpty();
        $merchantProductOptionGroupTransfer = $this->tester->haveMerchantProductOptionGroup([
            MerchantProductOptionGroupTransfer::APPROVAL_STATUS => MerchantProductOptionConfig::STATUS_WAITING_FOR_APPROVAL,
        ]);
        $productOptionTransfer = (new ProductOptionTransfer())->setIdGroup($merchantProductOptionGroupTransfer->getFkProductOptionGroup());
        $itemTransfer = (new ItemTransfer())->setProductOptions(new ArrayObject([$productOptionTransfer]));
        $quoteTransfer = (new QuoteTransfer())
            ->setItems(new ArrayObject([$itemTransfer]));
        $checkoutResponseTransfer = new CheckoutResponseTransfer();

        // Act
        $checkoutResponseTransfer = $this->tester->getFacade()->validateMerchantProductOptionsOnCheckout(
            $quoteTransfer,
            $checkoutResponseTransfer
        );

        // Assert
        $this->assertFalse($checkoutResponseTransfer->getIsSuccess());
        $this->assertCount(1, $checkoutResponseTransfer->getErrors());
    }

    /**
     * @return void
     */
    public function testExpandProductOptionGroupWithMerchantData(): void
    {
        // Arrange
        $this->tester->ensureMerchantProductOptionGroupTableEmpty();
        $merchantTransfer = $this->tester->haveMerchant();
        $merchantProductOptionGroupTransfer = $this->tester->haveMerchantProductOptionGroup([
            MerchantProductOptionGroupTransfer::APPROVAL_STATUS => MerchantProductOptionConfig::STATUS_WAITING_FOR_APPROVAL,
            MerchantProductOptionGroupTransfer::MERCHANT_REFERENCE => $merchantTransfer->getMerchantReference(),
            ProductOptionGroupTransfer::KEY => 'test',
        ]);
        $productOptionGroupTransfer = (new ProductOptionGroupTransfer())
            ->setIdProductOptionGroup($merchantProductOptionGroupTransfer->getFkProductOptionGroup());

        // Act
        $productOptionGroupTransfer = $this->tester->getFacade()->expandProductOptionGroup(
            $productOptionGroupTransfer
        );

        // Assert
        $this->assertInstanceOf(MerchantTransfer::class, $productOptionGroupTransfer->getMerchant());
        $this->assertSame($productOptionGroupTransfer->getMerchant()->getMerchantReference(), $merchantProductOptionGroupTransfer->getMerchantReference());
    }

    /**
     * @return void
     */
    public function testValidateMerchantProductOptionsOnCheckoutReturnsFalseWhenErrorProvidedAtTheBeginning(): void
    {
        // Arrange
        $this->tester->ensureMerchantProductOptionGroupTableEmpty();
        $merchantProductOptionGroupTransfer = $this->tester->haveMerchantProductOptionGroup([
            MerchantProductOptionGroupTransfer::APPROVAL_STATUS => MerchantProductOptionConfig::STATUS_APPROVED,
        ]);
        $productOptionTransfer = (new ProductOptionTransfer())->setIdGroup($merchantProductOptionGroupTransfer->getFkProductOptionGroup());
        $itemTransfer = (new ItemTransfer())->setProductOptions(new ArrayObject([$productOptionTransfer]));
        $quoteTransfer = (new QuoteTransfer())
            ->setItems(new ArrayObject([$itemTransfer]));
        $checkoutResponseTransfer = new CheckoutResponseTransfer();
        $checkoutErrorTransfer = new CheckoutErrorTransfer();
        $checkoutResponseTransfer->addError($checkoutErrorTransfer);
        $checkoutResponseTransfer->setIsSuccess(false);

        // Act
        $checkoutResponseTransfer = $this->tester->getFacade()->validateMerchantProductOptionsOnCheckout(
            $quoteTransfer,
            $checkoutResponseTransfer
        );

        // Assert
        $this->assertFalse($checkoutResponseTransfer->getIsSuccess());
    }
}
