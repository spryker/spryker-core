<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductReview\Business\Facade;

use Codeception\TestCase\Test;
use Generated\Shared\DataBuilder\ProductReviewBuilder;
use Generated\Shared\Transfer\ProductReviewTransfer;
use Spryker\Shared\ProductReview\ProductReviewConfig;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group ProductReview
 * @group Business
 * @group Facade
 * @group DeleteProductReviewTest
 * Add your own group annotations below this line
 */
class DeleteProductReviewTest extends Test
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
        $productAbstractTransfer = $this->tester->haveProductAbstract();
        $customerTransfer = $this->tester->haveCustomer();
        $productReviewTransfer = (new ProductReviewBuilder([
            ProductReviewTransfer::FK_PRODUCT_ABSTRACT => $productAbstractTransfer->getIdProductAbstract(),
            ProductReviewTransfer::CUSTOMER_REFERENCE => $customerTransfer->getCustomerReference(),
        ]))->build();
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
        $productAbstractTransfer = $this->tester->haveProductAbstract();
        $customerTransfer = $this->tester->haveCustomer();
        $productReviewTransfer = (new ProductReviewBuilder([
            ProductReviewTransfer::FK_PRODUCT_ABSTRACT => $productAbstractTransfer->getIdProductAbstract(),
            ProductReviewTransfer::CUSTOMER_REFERENCE => $customerTransfer->getCustomerReference(),
        ]))->build();
        $productReviewTransfer = $this->tester->getFacade()->createProductReview($productReviewTransfer);

        // Act
        $this->tester->getFacade()->deleteProductReview($productReviewTransfer);

        // Assert
        $this->tester->assertTouchDeleted(ProductReviewConfig::RESOURCE_TYPE_PRODUCT_REVIEW, $productReviewTransfer->getIdProductReview(), 'Product review should have been touched as deleted.');
    }

}
