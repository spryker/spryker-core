<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductApproval\Communication\Plugin\Checkout;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OriginalSalesOrderItemTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\ProductApproval\ProductApprovalConfig;
use Spryker\Zed\ProductApproval\Communication\Plugin\Checkout\OrderAmendmentProductApprovalCheckoutPreConditionPlugin;
use SprykerTest\Zed\ProductApproval\ProductApprovalCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductApproval
 * @group Communication
 * @group Plugin
 * @group Checkout
 * @group OrderAmendmentProductApprovalCheckoutPreConditionPluginTest
 * Add your own group annotations below this line
 */
class OrderAmendmentProductApprovalCheckoutPreConditionPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductApproval\ProductApprovalCommunicationTester
     */
    protected ProductApprovalCommunicationTester $tester;

    /**
     * @return void
     */
    public function testCheckConditionReturnsSuccessResponseForApprovedProduct(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveFullProduct([], [
            ProductAbstractTransfer::APPROVAL_STATUS => ProductApprovalConfig::STATUS_APPROVED,
        ]);
        $quoteTransfer = (new QuoteTransfer())->addItem(
            (new ItemTransfer())
                ->setSku($productConcreteTransfer->getSku())
                ->setAbstractSku($productConcreteTransfer->getAbstractSku())
                ->setIdProductAbstract($productConcreteTransfer->getFkProductAbstract()),
        );

        // Act
        $isValid = (new OrderAmendmentProductApprovalCheckoutPreConditionPlugin())
            ->checkCondition($quoteTransfer, (new CheckoutResponseTransfer()));

        // Assert
        $this->assertTrue($isValid);
    }

    /**
     * @return void
     */
    public function testCheckConditionReturnsFailResponseWithErrorsForNotApprovedProduct(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveFullProduct([], [
            ProductAbstractTransfer::APPROVAL_STATUS => ProductApprovalConfig::STATUS_DENIED,
        ]);
        $quoteTransfer = (new QuoteTransfer())->addItem(
            (new ItemTransfer())
                ->setSku($productConcreteTransfer->getSku())
                ->setAbstractSku($productConcreteTransfer->getAbstractSku())
                ->setIdProductAbstract($productConcreteTransfer->getFkProductAbstract()),
        );
        $checkoutResponseTransfer = new CheckoutResponseTransfer();

        // Act
        $isValid = (new OrderAmendmentProductApprovalCheckoutPreConditionPlugin())
            ->checkCondition($quoteTransfer, $checkoutResponseTransfer);

        // Assert
        $this->assertFalse($isValid);
        $this->assertCount(1, $checkoutResponseTransfer->getErrors());
    }

    /**
     * @return void
     */
    public function testCheckConditionReturnsSuccessResponseForNotApprovedProductFromOriginalOrder(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveFullProduct([], [
            ProductAbstractTransfer::APPROVAL_STATUS => ProductApprovalConfig::STATUS_DENIED,
        ]);
        $quoteTransfer = (new QuoteTransfer())->addItem(
            (new ItemTransfer())
                ->setSku($productConcreteTransfer->getSku())
                ->setAbstractSku($productConcreteTransfer->getAbstractSku())
                ->setIdProductAbstract($productConcreteTransfer->getFkProductAbstract()),
        )
            ->addOriginalSalesOrderItem(
                (new OriginalSalesOrderItemTransfer())->setSku($productConcreteTransfer->getSku()),
            );

        // Act
        $isValid = (new OrderAmendmentProductApprovalCheckoutPreConditionPlugin())
            ->checkCondition($quoteTransfer, (new CheckoutResponseTransfer()));

        // Assert
        $this->assertTrue($isValid);
    }
}
