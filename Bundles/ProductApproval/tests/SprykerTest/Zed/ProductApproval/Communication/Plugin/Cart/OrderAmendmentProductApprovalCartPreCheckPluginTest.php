<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductApproval\Communication\Plugin\Cart;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OriginalSalesOrderItemTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\ProductApproval\ProductApprovalConfig;
use Spryker\Zed\ProductApproval\Communication\Plugin\Cart\OrderAmendmentProductApprovalCartPreCheckPlugin;
use SprykerTest\Zed\ProductApproval\ProductApprovalCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductApproval
 * @group Communication
 * @group Plugin
 * @group Cart
 * @group OrderAmendmentProductApprovalCartPreCheckPluginTest
 * Add your own group annotations below this line
 */
class OrderAmendmentProductApprovalCartPreCheckPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductApproval\ProductApprovalCommunicationTester
     */
    protected ProductApprovalCommunicationTester $tester;

    /**
     * @return void
     */
    public function testCheckShouldNotFailForApprovedProduct(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveFullProduct([], [
            ProductAbstractTransfer::APPROVAL_STATUS => ProductApprovalConfig::STATUS_APPROVED,
        ]);
        $cartChangeTransfer = (new CartChangeTransfer())
            ->setQuote((new QuoteTransfer()))
            ->addItem((new ItemTransfer())
                    ->setSku($productConcreteTransfer->getSku())
                    ->setIdProductAbstract($productConcreteTransfer->getFkProductAbstract()));

        // Act
        $cartPreCheckResponseTransfer = (new OrderAmendmentProductApprovalCartPreCheckPlugin())->check($cartChangeTransfer);

        // Assert
        $this->assertTrue($cartPreCheckResponseTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testCheckShouldFailForUnapprovedProduct(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveFullProduct([], [
            ProductAbstractTransfer::APPROVAL_STATUS => ProductApprovalConfig::STATUS_DENIED,
        ]);
        $cartChangeTransfer = (new CartChangeTransfer())
            ->setQuote((new QuoteTransfer()))
            ->addItem((new ItemTransfer())
                ->setSku($productConcreteTransfer->getSku())
                ->setIdProductAbstract($productConcreteTransfer->getFkProductAbstract()));

        // Act
        $cartPreCheckResponseTransfer = (new OrderAmendmentProductApprovalCartPreCheckPlugin())->check($cartChangeTransfer);

        // Assert
        $this->assertFalse($cartPreCheckResponseTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testCheckShouldNotFailForUnapprovedProductFromOriginalOrder(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveFullProduct([], [
            ProductAbstractTransfer::APPROVAL_STATUS => ProductApprovalConfig::STATUS_DENIED,
        ]);
        $quoteTransfer = (new QuoteTransfer())
            ->addOriginalSalesOrderItem(
                (new OriginalSalesOrderItemTransfer())->setSku($productConcreteTransfer->getSku()),
            );
        $cartChangeTransfer = (new CartChangeTransfer())
            ->setQuote($quoteTransfer)
            ->addItem(
                (new ItemTransfer())
                    ->setSku($productConcreteTransfer->getSku())
                    ->setIdProductAbstract($productConcreteTransfer->getFkProductAbstract()),
            );

        // Act
        $cartPreCheckResponseTransfer = (new OrderAmendmentProductApprovalCartPreCheckPlugin())->check($cartChangeTransfer);

        // Assert
        $this->assertTrue($cartPreCheckResponseTransfer->getIsSuccess());
    }
}
