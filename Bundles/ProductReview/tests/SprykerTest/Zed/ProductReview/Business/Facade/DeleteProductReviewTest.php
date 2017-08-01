<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductReview\Business\Facade;

use Codeception\TestCase\Test;
use Generated\Shared\DataBuilder\ProductReviewBuilder;
use Generated\Shared\Transfer\ProductReviewTransfer;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group ProductReview
 * @group Business
 * @group Facade
 * @group UpdateProductReviewTest
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
        $customerTransfer = $this->tester->haveCustomer();
        $productReviewTransfer = (new ProductReviewBuilder([
            ProductReviewTransfer::CUSTOMER_REFERENCE => $customerTransfer->getCustomerReference(),
        ]))->build();
        $productReviewTransfer = $this->tester->getFacade()->createProductReview($productReviewTransfer);

        // Act
        $this->tester->getFacade()->deleteProductReview($productReviewTransfer);

        // Assert
        $this->tester->getFacade()->findProductReview($productReviewTransfer);
        $this->assertNull($productReviewTransfer, 'Product review should have been deleted.');
    }

    /**
     * @return void
     */
    public function testDeleteProductReviewTouchesProductReviewSearchResource()
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer();
        $productReviewTransfer = (new ProductReviewBuilder([
            ProductReviewTransfer::CUSTOMER_REFERENCE => $customerTransfer->getCustomerReference(),
        ]))->build();
        $productReviewTransfer = $this->tester->getFacade()->createProductReview($productReviewTransfer);

        // Act
        $this->tester->getFacade()->deleteProductReview($productReviewTransfer);

        // Assert
        $this->tester->assertTouchDeleted(ProductReviewConfig::RESOURCE_TYPE_PRODUCT_REVIEW, $productReviewTransfer->getIdProductReview(), 'Product review should have been touched as deleted.');
    }

}
