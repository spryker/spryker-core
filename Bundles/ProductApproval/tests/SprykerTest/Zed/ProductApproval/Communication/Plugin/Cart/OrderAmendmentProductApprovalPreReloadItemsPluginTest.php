<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductApproval\Communication\Plugin\Cart;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OriginalSalesOrderItemTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\ProductApproval\ProductApprovalConfig;
use Spryker\Shared\ProductApproval\ProductApprovalConfig as ProductApprovalSharedConfig;
use Spryker\Zed\ProductApproval\Communication\Plugin\Cart\OrderAmendmentProductApprovalPreReloadItemsPlugin;
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
 * @group OrderAmendmentProductApprovalPreReloadItemsPluginTest
 * Add your own group annotations below this line
 */
class OrderAmendmentProductApprovalPreReloadItemsPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductApproval\ProductApprovalCommunicationTester
     */
    protected ProductApprovalCommunicationTester $tester;

    /**
     * @var string
     */
    protected const PRODUCT_ABSTRACT_1_SKU = 'YTP1yWAMap';

    /**
     * @var string
     */
    protected const PRODUCT_ABSTRACT_2_SKU = 'tESnx2djdn';

    /**
     * @var string
     */
    protected const PRODUCT_ABSTRACT_3_SKU = 'abstractSku3';

    /**
     * @dataProvider getNotApprovedApprovalStatuses
     *
     * @param string|null $approvalStatus
     *
     * @return void
     */
    public function testPreReloadItemsShouldFilterNotApprovedProducts(?string $approvalStatus): void
    {
        // Arrange
        $productAbstractTransfer1 = $this->tester->haveProductAbstract([
            ProductAbstractTransfer::APPROVAL_STATUS => ProductApprovalConfig::STATUS_APPROVED,
            ProductAbstractTransfer::SKU => static::PRODUCT_ABSTRACT_1_SKU,
        ]);
        $productAbstractTransfer2 = $this->tester->haveProductAbstract([
            ProductAbstractTransfer::APPROVAL_STATUS => $approvalStatus,
            ProductAbstractTransfer::SKU => static::PRODUCT_ABSTRACT_2_SKU,
        ]);
        $productConcreteTransfer1 = $this->tester->haveProduct([
            ProductConcreteTransfer::FK_PRODUCT_ABSTRACT => $productAbstractTransfer1->getIdProductAbstract(),
        ]);
        $productConcreteTransfer2 = $this->tester->haveProduct([
            ProductConcreteTransfer::FK_PRODUCT_ABSTRACT => $productAbstractTransfer2->getIdProductAbstract(),
        ]);

        $quoteTransfer = (new QuoteTransfer())
            ->addItem((new ItemTransfer())
                ->setAbstractSku($productAbstractTransfer1->getSkuOrFail())
                ->setIdProductAbstract($productAbstractTransfer1->getIdProductAbstractOrFail())
                ->setSku($productConcreteTransfer1->getSkuOrFail()))
            ->addItem((new ItemTransfer())
                ->setAbstractSku($productAbstractTransfer2->getSkuOrFail())
                ->setIdProductAbstract($productAbstractTransfer2->getIdProductAbstractOrFail())
                ->setSku($productConcreteTransfer2->getSkuOrFail()));

        // Act
        $quoteTransfer = (new OrderAmendmentProductApprovalPreReloadItemsPlugin())->preReloadItems($quoteTransfer);

        // Assert
        $this->assertCount(1, $quoteTransfer->getItems());
        $this->assertEquals(
            $quoteTransfer->getItems()->offsetGet(0)->getIdProductAbstractOrFail(),
            $productAbstractTransfer1->getIdProductAbstractOrFail(),
        );
    }

    /**
     * @return void
     */
    public function testPreReloadItemsShouldNotFilterApprovedProducts(): void
    {
        // Arrange
        $productAbstractTransfer1 = $this->tester->haveProductAbstract([
            ProductAbstractTransfer::APPROVAL_STATUS => ProductApprovalConfig::STATUS_APPROVED,
        ]);
        $productAbstractTransfer2 = $this->tester->haveProductAbstract([
            ProductAbstractTransfer::APPROVAL_STATUS => ProductApprovalConfig::STATUS_APPROVED,
        ]);
        $productConcreteTransfer1 = $this->tester->haveProduct([
            ProductConcreteTransfer::FK_PRODUCT_ABSTRACT => $productAbstractTransfer1->getIdProductAbstract(),
        ]);
        $productConcreteTransfer2 = $this->tester->haveProduct([
            ProductConcreteTransfer::FK_PRODUCT_ABSTRACT => $productAbstractTransfer2->getIdProductAbstract(),
        ]);

        $quoteTransfer = (new QuoteTransfer())
            ->addItem((new ItemTransfer())
                ->setAbstractSku($productAbstractTransfer1->getSkuOrFail())
                ->setIdProductAbstract($productAbstractTransfer1->getIdProductAbstractOrFail())
                ->setSku($productConcreteTransfer1->getSkuOrFail()))
            ->addItem((new ItemTransfer())
                ->setAbstractSku($productAbstractTransfer2->getSkuOrFail())
                ->setIdProductAbstract($productAbstractTransfer2->getIdProductAbstractOrFail())
                ->setSku($productConcreteTransfer2->getSkuOrFail()));

        // Act

        $quoteTransfer = (new OrderAmendmentProductApprovalPreReloadItemsPlugin())->preReloadItems($quoteTransfer);

        // Assert
        $this->assertCount(2, $quoteTransfer->getItems());
    }

    /**
     * @return void
     */
    public function testPreReloadItemsShouldNotFilterNotFoundItems(): void
    {
        // Arrange
        $productAbstractTransfer = $this->tester->haveProductAbstract([
            ProductAbstractTransfer::APPROVAL_STATUS => ProductApprovalConfig::STATUS_APPROVED,
        ]);
        $productAbstractTransfer2 = $this->tester->haveProductAbstract([
            ProductAbstractTransfer::APPROVAL_STATUS => ProductApprovalConfig::STATUS_APPROVED,
        ]);
        $productConcreteTransfer = $this->tester->haveProduct([
            ProductConcreteTransfer::FK_PRODUCT_ABSTRACT => $productAbstractTransfer->getIdProductAbstract(),
        ]);
        $productConcreteTransfer2 = $this->tester->haveProduct([
            ProductConcreteTransfer::FK_PRODUCT_ABSTRACT => $productAbstractTransfer2->getIdProductAbstract(),
        ]);

        $quoteTransfer = (new QuoteTransfer())
            ->addItem((new ItemTransfer())
                ->setAbstractSku(static::PRODUCT_ABSTRACT_3_SKU)
                ->setIdProductAbstract($productAbstractTransfer->getIdProductAbstractOrFail())
                ->setSku($productConcreteTransfer->getSkuOrFail()))
            ->addItem((new ItemTransfer())
                ->setAbstractSku($productAbstractTransfer2->getSkuOrFail())
                ->setIdProductAbstract($productAbstractTransfer2->getIdProductAbstractOrFail())
                ->setSku($productConcreteTransfer2->getSkuOrFail()));

        // Act
        $quoteTransfer = (new OrderAmendmentProductApprovalPreReloadItemsPlugin())->preReloadItems($quoteTransfer);

        // Assert
        $this->assertCount(2, $quoteTransfer->getItems());
    }

    /**
     * @dataProvider getNotApprovedApprovalStatuses
     *
     * @param string|null $approvalStatus
     *
     * @return void
     */
    public function testPreReloadItemsShouldNotFilterNotApprovedProductsFromOriginalOrder(?string $approvalStatus): void
    {
        // Arrange
        $productAbstractTransfer1 = $this->tester->haveProductAbstract([
            ProductAbstractTransfer::APPROVAL_STATUS => ProductApprovalConfig::STATUS_APPROVED,
            ProductAbstractTransfer::SKU => static::PRODUCT_ABSTRACT_1_SKU,
        ]);
        $productAbstractTransfer2 = $this->tester->haveProductAbstract([
            ProductAbstractTransfer::APPROVAL_STATUS => $approvalStatus,
            ProductAbstractTransfer::SKU => static::PRODUCT_ABSTRACT_2_SKU,
        ]);
        $productConcreteTransfer1 = $this->tester->haveProduct([
            ProductConcreteTransfer::FK_PRODUCT_ABSTRACT => $productAbstractTransfer1->getIdProductAbstract(),
        ]);
        $productConcreteTransfer2 = $this->tester->haveProduct([
            ProductConcreteTransfer::FK_PRODUCT_ABSTRACT => $productAbstractTransfer2->getIdProductAbstract(),
        ]);

        $quoteTransfer = (new QuoteTransfer())
            ->addItem((new ItemTransfer())
                ->setAbstractSku($productAbstractTransfer1->getSkuOrFail())
                ->setIdProductAbstract($productAbstractTransfer1->getIdProductAbstractOrFail())
                ->setSku($productConcreteTransfer1->getSkuOrFail()))
            ->addItem((new ItemTransfer())
                ->setAbstractSku($productAbstractTransfer2->getSkuOrFail())
                ->setIdProductAbstract($productAbstractTransfer2->getIdProductAbstractOrFail())
                ->setSku($productConcreteTransfer2->getSkuOrFail()))
            ->addOriginalSalesOrderItem(
                (new OriginalSalesOrderItemTransfer())->setSku($productConcreteTransfer1->getSku()),
            )
            ->addOriginalSalesOrderItem(
                (new OriginalSalesOrderItemTransfer())->setSku($productConcreteTransfer2->getSku()),
            );

        // Act
        $quoteTransfer = (new OrderAmendmentProductApprovalPreReloadItemsPlugin())->preReloadItems($quoteTransfer);

        // Assert
        $this->assertCount(2, $quoteTransfer->getItems());
        $this->assertEquals(
            $quoteTransfer->getItems()->offsetGet(0)->getIdProductAbstractOrFail(),
            $productAbstractTransfer1->getIdProductAbstractOrFail(),
        );
    }

    /**
     * @return array<int, array<int, mixed>>
     */
    protected function getNotApprovedApprovalStatuses(): array
    {
        return [
            [ProductApprovalSharedConfig::STATUS_DRAFT],
            [ProductApprovalSharedConfig::STATUS_WAITING_FOR_APPROVAL],
            [ProductApprovalSharedConfig::STATUS_DENIED],
            [null],
        ];
    }
}
