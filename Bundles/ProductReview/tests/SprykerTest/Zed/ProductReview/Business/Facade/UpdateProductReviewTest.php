<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductReview\Business\Facade;

use Codeception\TestCase\Test;
use Generated\Shared\DataBuilder\ProductReviewBuilder;
use Generated\Shared\Transfer\ProductReviewTransfer;
use Orm\Zed\ProductReview\Persistence\Map\SpyProductReviewTableMap;
use Spryker\Shared\ProductReview\ProductReviewConfig;

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
class UpdateProductReviewTest extends Test
{

    /**
     * @var \SprykerTest\Zed\ProductReview\ProductReviewBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testUpdateProductReviewPersistsChangesToDatabase()
    {
        // Arrange
        $productAbstractTransfer = $this->tester->haveProductAbstract();
        $customerTransfer = $this->tester->haveCustomer();
        $localeTransfer = $this->tester->haveLocale();
        $productReviewTransfer = (new ProductReviewBuilder([
            ProductReviewTransfer::FK_PRODUCT_ABSTRACT => $productAbstractTransfer->getIdProductAbstract(),
            ProductReviewTransfer::CUSTOMER_REFERENCE => $customerTransfer->getCustomerReference(),
            ProductReviewTransfer::FK_LOCALE => $localeTransfer->getIdLocale(),
        ]))->build();
        $productReviewTransfer = $this->tester->getFacade()->createProductReview($productReviewTransfer);

        $productReviewTransferToUpdate = (new ProductReviewBuilder([
            ProductReviewTransfer::ID_PRODUCT_REVIEW => $productReviewTransfer->getIdProductReview(),
        ]))->build();

        // Act
        $updatedProductReviewTransfer = $this->tester->getFacade()->updateProductReview($productReviewTransferToUpdate);

        // Assert
        $actualProductReviewTransfer = $this->tester->getFacade()->findProductReview($updatedProductReviewTransfer);
        $this->assertArraySubset($productReviewTransferToUpdate->modifiedToArray(), $actualProductReviewTransfer->toArray(), 'Updated product review should have expected data.');
    }

    /**
     * @dataProvider statusDataProvider
     *
     * @param string $inputStatus
     * @param bool $isTouchActive
     *
     * @return void
     */
    public function testUpdateProductReviewTouchesProductReviewSearchResource($inputStatus, $isTouchActive)
    {
        // Arrange
        $productAbstractTransfer = $this->tester->haveProductAbstract();
        $customerTransfer = $this->tester->haveCustomer();
        $localeTransfer = $this->tester->haveLocale();
        $productReviewTransfer = (new ProductReviewBuilder([
            ProductReviewTransfer::FK_PRODUCT_ABSTRACT => $productAbstractTransfer->getIdProductAbstract(),
            ProductReviewTransfer::CUSTOMER_REFERENCE => $customerTransfer->getCustomerReference(),
            ProductReviewTransfer::FK_LOCALE => $localeTransfer->getIdLocale(),
        ]))->build();
        $productReviewTransfer = $this->tester->getFacade()->createProductReview($productReviewTransfer);

        $productReviewTransferToUpdate = (new ProductReviewBuilder([
            ProductReviewTransfer::ID_PRODUCT_REVIEW => $productReviewTransfer->getIdProductReview(),
            ProductReviewTransfer::STATUS => $inputStatus,
        ]))->build();

        // Act
        $this->tester->getFacade()->updateProductReview($productReviewTransferToUpdate);

        // Assert
        if ($isTouchActive) {
            $this->tester->assertTouchActive(ProductReviewConfig::RESOURCE_TYPE_PRODUCT_REVIEW, $productReviewTransferToUpdate->getIdProductReview(), 'Product review should have been touched as active.');
            return;
        }

        $this->tester->assertTouchDeleted(ProductReviewConfig::RESOURCE_TYPE_PRODUCT_REVIEW, $productReviewTransferToUpdate->getIdProductReview(), 'Product review should have been touched as deleted.');
    }

    /**
     * @return array
     */
    public function statusDataProvider()
    {
        return [
            'pending status' => [SpyProductReviewTableMap::COL_STATUS_PENDING, false],
            'approved status' => [SpyProductReviewTableMap::COL_STATUS_APPROVED, true],
            'rejected status' => [SpyProductReviewTableMap::COL_STATUS_REJECTED, false],
        ];
    }

}
