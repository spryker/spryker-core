<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductReview\Business\Facade;

use Codeception\TestCase\Test;
use Generated\Shared\DataBuilder\ProductReviewBuilder;
use Generated\Shared\Transfer\ProductReviewTransfer;
use SprykerTest\Zed\ProductReview\Business\ProductReviewConfig;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group ProductReview
 * @group Business
 * @group ProductReviewTest
 * Add your own group annotations below this line
 */
class CreateProductReviewTest extends Test
{

    /**
     * @var \SprykerTest\Zed\ProductReview\ProductReviewBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testCreateProductReviewPersistsToDatabase()
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer();
        $productReviewTransfer = (new ProductReviewBuilder([
            ProductReviewTransfer::CUSTOMER_REFERENCE => $customerTransfer->getCustomerReference(),
        ]))->build();

        // Act
        $productReviewTransfer = $this->tester->getFacade()->createProductReview($productReviewTransfer);

        // Assert
        $actualProductReviewTransfer = $this->tester->getFacade()->findProductReview($productReviewTransfer);
        $this->assertNotNull($actualProductReviewTransfer, 'Product review should have been created and found in database.');
    }

    /**
     * @dataProvider statusDataProvider
     *
     * @param mixed $inputStatus
     *
     * @return void
     */
    public function testCreateProductReviewIsCreatedAlwaysWithPendingStatus($inputStatus)
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer();
        $productReviewTransfer = (new ProductReviewBuilder([
            ProductReviewTransfer::CUSTOMER_REFERENCE => $customerTransfer->getCustomerReference(),
            ProductReviewTransfer::STATUS => $inputStatus,
        ]))->build();

        // Act
        $productReviewTransfer = $this->tester->getFacade()->createProductReview($productReviewTransfer);

        // Assert
        $actualProductReviewTransfer = $this->tester->getFacade()->findProductReview($productReviewTransfer);
        // TODO: this must come from propel enum
        $this->assertSame(0, $actualProductReviewTransfer->getStatus(), 'Product review should have been created with expected status.');
    }

    /**
     * @return void
     */
    public function testCreateProductReviewTouchesProductReviewSearchResource()
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer();
        $productReviewTransfer = (new ProductReviewBuilder([
            ProductReviewTransfer::CUSTOMER_REFERENCE => $customerTransfer->getCustomerReference(),
        ]))->build();

        // Act
        $productReviewTransfer = $this->tester->getFacade()->createProductReview($productReviewTransfer);

        // Assert
        $this->tester->assertTouchActive(ProductReviewConfig::RESOURCE_TYPE_PRODUCT_Review, $productReviewTransfer->getIdProductReview(), 'Product review should have been touched as active.');
    }

    /**
     * @return array
     */
    public function statusDataProvider()
    {
        return [
            'status not defined' => [null],
            'pending status' => [0],  // TODO: this must come from propel enum
            'approved status' => [1],  // TODO: this must come from propel enum
            'rejected status' => [2],  // TODO: this must come from propel enum
        ];
    }

}
