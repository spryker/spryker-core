<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductReview\Business\Facade;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\ProductReviewTransfer;
use Orm\Zed\ProductReview\Persistence\Map\SpyProductReviewTableMap;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group ProductReview
 * @group Business
 * @group Facade
 * @group CreateProductReviewTest
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
        $productReviewTransfer = $this->tester->haveProductReview();

        // Act
        $productReviewTransfer = $this->tester->getFacade()->createProductReview($productReviewTransfer);

        // Assert
        $actualProductReviewTransfer = $this->tester->getFacade()->findProductReview($productReviewTransfer);
        $this->assertNotNull($actualProductReviewTransfer, 'Product review should have been created and found in database.');
    }

    /**
     * @dataProvider statusDataProvider
     *
     * @param string $inputStatus
     *
     * @return void
     */
    public function testCreateProductReviewIsCreatedAlwaysWithPendingStatus($inputStatus)
    {
        // Arrange
        $productReviewTransfer = $this->tester->haveProductReview([
            ProductReviewTransfer::STATUS => $inputStatus,
        ]);

        // Act
        $productReviewTransfer = $this->tester->getFacade()->createProductReview($productReviewTransfer);

        // Assert
        $actualProductReviewTransfer = $this->tester->getFacade()->findProductReview($productReviewTransfer);
        $this->assertSame(SpyProductReviewTableMap::COL_STATUS_PENDING, $actualProductReviewTransfer->getStatus(), 'Product review should have been created with expected status.');
    }

    /**
     * @return array
     */
    public function statusDataProvider()
    {
        return [
            'status not defined' => [null],
            'pending status' => [SpyProductReviewTableMap::COL_STATUS_PENDING],
            'approved status' => [SpyProductReviewTableMap::COL_STATUS_APPROVED],
            'rejected status' => [SpyProductReviewTableMap::COL_STATUS_REJECTED],
        ];
    }

}
