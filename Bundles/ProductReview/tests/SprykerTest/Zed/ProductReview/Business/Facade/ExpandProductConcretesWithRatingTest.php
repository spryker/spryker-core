<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductReview\Business\Facade;

use Codeception\Test\Unit;
use Orm\Zed\ProductReview\Persistence\Map\SpyProductReviewTableMap;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductReview
 * @group Business
 * @group Facade
 * @group ExpandProductConcretesWithRatingTest
 * Add your own group annotations below this line
 */
class ExpandProductConcretesWithRatingTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductReview\ProductReviewBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testExpandProductConcretesWithRatingWithoutRatings(): void
    {
        // Arrange
        $productConcreteTransfers = [
            $this->tester->haveFullProduct(),
            $this->tester->haveFullProduct(),
        ];

        // Act
        $expandedProductConcreteTransfers = $this->tester->getFacade()
            ->expandProductConcretesWithRating($productConcreteTransfers);

        // Assert
        foreach ($expandedProductConcreteTransfers as $productConcreteTransfer) {
            $this->assertNull($productConcreteTransfer->getRating(), 'Ratings for products without approved reviews should be empty');
        }
    }

    /**
     * @return void
     */
    public function testExpandProductConcretesWithRatingCountsOnlyApprovedRatings(): void
    {
        // Arrange
        $productConcreteTransfers = [
            $this->tester->haveFullProduct(),
            $this->tester->haveFullProduct(),
        ];

        $pendingProductReviewTransfersForProduct1 = $this->tester->createReviewsForProduct(
            $productConcreteTransfers[0],
            3,
            SpyProductReviewTableMap::COL_STATUS_PENDING,
            [1, 2, 1],
        );

        $approvedProductReviewTransfersForProduct1 = $this->tester->createReviewsForProduct(
            $productConcreteTransfers[0],
            5,
            SpyProductReviewTableMap::COL_STATUS_APPROVED,
            [3, 5, 1, 2, 5],
        );

        $rejectedProductReviewTransfersForProduct1 = $this->tester->createReviewsForProduct(
            $productConcreteTransfers[0],
            1,
            SpyProductReviewTableMap::COL_STATUS_REJECTED,
            [1],
        );

        $approvedProductReviewTransfersForProduct2 = $this->tester->createReviewsForProduct(
            $productConcreteTransfers[1],
            2,
            SpyProductReviewTableMap::COL_STATUS_APPROVED,
            [5, 1],
        );

        $rejectedProductReviewTransfersForProduct2 = $this->tester->createReviewsForProduct(
            $productConcreteTransfers[1],
            4,
            SpyProductReviewTableMap::COL_STATUS_REJECTED,
            [1, 2, 1, 1],
        );

        // Act
        $expandedProductConcreteTransfers = $this->tester->getFacade()
            ->expandProductConcretesWithRating($productConcreteTransfers);

        // Assert
        foreach ($expandedProductConcreteTransfers as $productConcreteTransfer) {
            $this->assertNotNull(
                $productConcreteTransfer->getRating(),
                'Ratings for products with existing approved reviews should not be empty',
            );
        }

        $this->assertSame(3.2, $expandedProductConcreteTransfers[0]->getRating());
        $this->assertSame(3.0, $expandedProductConcreteTransfers[1]->getRating());
    }

    /**
     * @return void
     */
    public function testExpandProductConcretesWithRatingIgnoresNotApprovedRatings(): void
    {
        // Arrange
        $productConcreteTransfers = [
            $this->tester->haveFullProduct(),
            $this->tester->haveFullProduct(),
        ];

        $pendingProductReviewTransfersForProduct1 = $this->tester->createReviewsForProduct(
            $productConcreteTransfers[0],
            3,
            SpyProductReviewTableMap::COL_STATUS_PENDING,
            [4, 5, 5],
        );

        $approvedProductReviewTransfersForProduct1 = $this->tester->createReviewsForProduct(
            $productConcreteTransfers[0],
            5,
            SpyProductReviewTableMap::COL_STATUS_APPROVED,
            [1, 2, 3, 2, 1],
        );

        $rejectedProductReviewTransfersForProduct1 = $this->tester->createReviewsForProduct(
            $productConcreteTransfers[0],
            1,
            SpyProductReviewTableMap::COL_STATUS_REJECTED,
            [4],
        );

        $rejectedProductReviewTransfersForProduct2 = $this->tester->createReviewsForProduct(
            $productConcreteTransfers[1],
            4,
            SpyProductReviewTableMap::COL_STATUS_REJECTED,
            [4, 3, 4, 4],
        );

        // Act
        $expandedProductConcreteTransfers = $this->tester->getFacade()
            ->expandProductConcretesWithRating($productConcreteTransfers);

        // Assert
        $this->assertNotNull(
            $expandedProductConcreteTransfers[0]->getRating(),
            'Ratings for products with existing approved reviews should not be empty',
        );

        $this->assertNull(
            $expandedProductConcreteTransfers[1]->getRating(),
            'Ratings for products without existing approved reviews should be empty',
        );

        $this->assertSame(1.8, $expandedProductConcreteTransfers[0]->getRating());
    }
}
