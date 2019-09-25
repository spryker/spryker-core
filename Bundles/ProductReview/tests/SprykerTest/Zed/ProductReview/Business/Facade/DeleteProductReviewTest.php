<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductReview\Business\Facade;

use Codeception\Test\Unit;
use Spryker\Shared\ProductReview\ProductReviewConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductReview
 * @group Business
 * @group Facade
 * @group DeleteProductReviewTest
 * Add your own group annotations below this line
 */
class DeleteProductReviewTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductReview\ProductReviewBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testDeleteProductReviewRemovesEntityFromDatabase()
    {
        // Arrange
        $productReviewTransfer = $this->tester->haveProductReview();
        $productReviewTransfer = $this->tester->getFacade()->createProductReview($productReviewTransfer);

        // Act
        $this->tester->getFacade()->deleteProductReview($productReviewTransfer);

        // Assert
        $productReviewTransfer = $this->tester->getFacade()->findProductReview($productReviewTransfer);
        $this->assertNull($productReviewTransfer, 'Product review should have been deleted.');
    }

    /**
     * @return void
     */
    public function testDeleteProductReviewTouchesProductReviewSearchResource()
    {
        // Arrange
        $productReviewTransfer = $this->tester->haveProductReview();
        $productReviewTransfer = $this->tester->getFacade()->createProductReview($productReviewTransfer);

        // Act
        $this->tester->getFacade()->deleteProductReview($productReviewTransfer);

        // Assert
        $this->tester->assertTouchDeleted(ProductReviewConfig::RESOURCE_TYPE_PRODUCT_REVIEW, $productReviewTransfer->getIdProductReview(), 'Product review should have been touched as deleted.');
    }

    /**
     * @return void
     */
    public function testDeleteProductReviewTouchesProductReviewAbstractSearchResource()
    {
        // Arrange
        $productReviewTransfer = $this->tester->haveProductReview();
        $productReviewTransfer = $this->tester->getFacade()->createProductReview($productReviewTransfer);

        // Act
        $this->tester->getFacade()->deleteProductReview($productReviewTransfer);

        // Assert
        $this->tester->assertTouchActive(ProductReviewConfig::RESOURCE_TYPE_PRODUCT_ABSTRACT_REVIEW, $productReviewTransfer->getFkProductAbstract(), 'Product review abstract should have been touched as active.');
    }
}
