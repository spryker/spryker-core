<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductLabelGui\Communication\Table;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductAbstractTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductLabelGui
 * @group Communication
 * @group Table
 * @group RelatedProductTableQueryBuilderTest
 * Add your own group annotations below this line
 */
class RelatedProductTableQueryBuilderTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductLabelGui\ProductLabelGuiCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testBuildAssignedProductQueryShouldReturnProductsWithLabelGroupedByProductAbstract(): void
    {
        // Arrange
        $relatedProductTableQuery = $this->tester->getFactory()
            ->createRelatedProductTableQueryBuilder();
        $productAbstract = $this->tester->haveProductAbstract();
        $idProductAbstract = $productAbstract->getIdProductAbstract();
        $productAbstractOverride = [
            ProductAbstractTransfer::ID_PRODUCT_ABSTRACT => $idProductAbstract,
        ];
        $this->tester->haveFullProduct([], $productAbstractOverride);
        $this->tester->haveFullProduct([], $productAbstractOverride);

        $productLabelTransfer = $this->tester->haveProductLabel();
        $idProductLabel = $productLabelTransfer->getIdProductLabel();

        $this->tester->haveProductLabelToAbstractProductRelation($idProductLabel, $idProductAbstract);

        // Act
        $buildAvailableProductQuery = $relatedProductTableQuery->buildAssignedProductQuery($idProductLabel);

        // Assert
        $this->assertSame(1, $buildAvailableProductQuery->count());
    }

    /**
     * @return void
     */
    public function testBuildAssignedProductQueryShouldReturnProductsWithLabel(): void
    {
        // Arrange
        $relatedProductTableQuery = $this->tester->getFactory()
            ->createRelatedProductTableQueryBuilder();

        $productTransfer1 = $this->tester->haveFullProduct();
        $productTransfer2 = $this->tester->haveFullProduct();

        $productLabelTransfer = $this->tester->haveProductLabel();
        $idProductLabel = $productLabelTransfer->getIdProductLabel();

        $this->tester->haveProductLabelToAbstractProductRelation($idProductLabel, $productTransfer1->getFkProductAbstract());
        $this->tester->haveProductLabelToAbstractProductRelation($idProductLabel, $productTransfer2->getFkProductAbstract());

        // Act
        $buildAvailableProductQuery = $relatedProductTableQuery->buildAssignedProductQuery($idProductLabel);

        // Assert
        $this->assertSame(2, $buildAvailableProductQuery->count());
    }

    /**
     * @return void
     */
    public function testBuildAssignedProductQueryShouldReturnEmptyWhenNoProductsAssignToLabel(): void
    {
        // Arrange
        $relatedProductTableQuery = $this->tester->getFactory()
            ->createRelatedProductTableQueryBuilder();

        $productLabelTransfer = $this->tester->haveProductLabel();

        // Act
        $buildAvailableProductQuery = $relatedProductTableQuery->buildAssignedProductQuery($productLabelTransfer->getIdProductLabel());

        // Assert
        $this->assertSame(0, $buildAvailableProductQuery->count());
    }
}
