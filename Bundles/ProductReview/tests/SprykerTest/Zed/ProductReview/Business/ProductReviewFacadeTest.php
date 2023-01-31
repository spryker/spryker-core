<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductReview\Business;

use Codeception\Test\Unit;
use SprykerTest\Zed\ProductReview\ProductReviewBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductReview
 * @group Business
 * @group Facade
 * @group ProductReviewFacadeTest
 * Add your own group annotations below this line
 */
class ProductReviewFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductReview\ProductReviewBusinessTester
     */
    protected ProductReviewBusinessTester $tester;

    /**
     * @return void
     */
    public function testHandleAddReviewsCreatesReview(): void
    {
        // Arrange
        $addReviewsTransfer = $this->tester->haveAddReviewTransferWithValidProductAndLocale();

        // Act
        $this->tester->getFacade()->handleAddReviews($addReviewsTransfer);

        // Assert
        $expectedReview = $addReviewsTransfer->getReviews()[0];
        $this->tester->assertReviewExists($expectedReview);
    }

    /**
     * @return void
     */
    public function testHandleAddReviewsIgnoresReviewWhenProductNotFound(): void
    {
        // Arrange
        $addReviewsTransfer = $this->tester->haveAddReviewTransferWithoutValidProduct();

        // Act
        $this->tester->getFacade()->handleAddReviews($addReviewsTransfer);

        // Assert
        $expectedReview = $addReviewsTransfer->getReviews()[0];
        $this->tester->assertReviewNotExists($expectedReview);
    }

    /**
     * @return void
     */
    public function testHandleAddReviewsIgnoresReviewWhenLocaleNotFound(): void
    {
        // Arrange
        $addReviewsTransfer = $this->tester->haveAddReviewTransferWithoutValidLocale();

        // Act
        $this->tester->getFacade()->handleAddReviews($addReviewsTransfer);

        // Assert
        $expectedReview = $addReviewsTransfer->getReviews()[0];
        $this->tester->assertReviewNotExists($expectedReview);
    }
}
