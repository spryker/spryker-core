<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductReview\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ProductReviewBuilder;
use SprykerTest\Shared\Testify\Helper\AssertArraySubsetTrait;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductReview
 * @group Business
 * @group Facade
 * @group ReadProductReviewTest
 * Add your own group annotations below this line
 */
class ReadProductReviewTest extends Unit
{
    use AssertArraySubsetTrait;

    /**
     * @var \SprykerTest\Zed\ProductReview\ProductReviewBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testFindProductReviewDoesntFindNonExistingEntities(): void
    {
        // Arrange
        $productReviewTransfer = (new ProductReviewBuilder())->makeEmpty()->build();
        $productReviewTransfer->setIdProductReview(98765);

        // Act
        $actualProductReviewTransfer = $this->tester->getFacade()->findProductReview($productReviewTransfer);

        // Assert
        $this->assertNull($actualProductReviewTransfer, 'Non existing product review should not have been found in database.');
    }

    /**
     * @return void
     */
    public function testFindProductReviewFindsExistingEntities(): void
    {
        // Arrange
        $productReviewTransfer = $this->tester->haveProductReview();
        $productReviewTransfer = $this->tester->getFacade()->createProductReview($productReviewTransfer);

        // Act
        $actualProductReviewTransfer = $this->tester->getFacade()->findProductReview($productReviewTransfer);

        // Assert
        $this->assertNotNull($actualProductReviewTransfer, 'Existing product review should have been found in database.');
        $this->assertArraySubset(
            $this->tester->removeProductReviewDateFields($productReviewTransfer->modifiedToArray()),
            $this->tester->removeProductReviewDateFields($actualProductReviewTransfer->toArray()),
            'Create should return the updated entity',
        );
    }
}
