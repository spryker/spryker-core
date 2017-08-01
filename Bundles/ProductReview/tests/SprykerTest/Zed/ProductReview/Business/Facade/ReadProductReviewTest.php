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
 * @group ReadProductReviewTest
 * Add your own group annotations below this line
 */
class ReadProductReviewTest extends Test
{

    /**
     * @var \SprykerTest\Zed\ProductReview\ProductReviewBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testFindProductReviewDoesntFindNonExistingEntities()
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
    public function testFindProductReviewFindsExistingEntities()
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
        $actualProductReviewTransfer = $this->tester->getFacade()->findProductReview($productReviewTransfer);

        // Assert
        $this->assertNotNull($actualProductReviewTransfer, 'Existing product review should have been found in database.');
        $this->assertArraySubset($productReviewTransfer->modifiedToArray(), $actualProductReviewTransfer->toArray(), '');
    }

}
