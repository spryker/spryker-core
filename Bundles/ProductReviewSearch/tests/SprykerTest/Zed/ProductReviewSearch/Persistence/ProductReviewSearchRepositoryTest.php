<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductReviewSearch\Persistence;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductPayloadTransfer;
use Orm\Zed\ProductReview\Persistence\Map\SpyProductReviewTableMap;
use Spryker\DecimalObject\Decimal;
use Spryker\Zed\ProductReviewSearch\Persistence\ProductReviewSearchRepository;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductReviewSearch
 * @group Persistence
 * @group ProductReviewSearchRepositoryTest
 * Add your own group annotations below this line
 */
class ProductReviewSearchRepositoryTest extends Unit
{
    protected const TEST_LOCALE_NAME = 'xxx';

    /**
     * @var \SprykerTest\Zed\ProductReviewSearch\ProductReviewSearchPersistenceTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGetProductReviewRatingByIdAbstractProductInReturnsCorrectData(): void
    {
        // Arrange
        $localeTransfer = $this->tester->haveLocale([
            LocaleTransfer::LOCALE_NAME => static::TEST_LOCALE_NAME,
        ]);

        $customerTransfer = $this->tester->haveCustomer();

        $productAbstractTransfer1 = $this->tester->haveProductAbstract();
        $productAbstractTransfer2 = $this->tester->haveProductAbstract();

        $productReviewTransfer11 = $this->tester->haveApprovedCustomerReviewForAbstractProduct(
            $localeTransfer->getIdLocale(),
            $customerTransfer->getCustomerReference(),
            $productAbstractTransfer1->getIdProductAbstract(),
            SpyProductReviewTableMap::COL_STATUS_APPROVED
        );
        $productReviewTransfer12 = $this->tester->haveApprovedCustomerReviewForAbstractProduct(
            $localeTransfer->getIdLocale(),
            $customerTransfer->getCustomerReference(),
            $productAbstractTransfer1->getIdProductAbstract(),
            SpyProductReviewTableMap::COL_STATUS_APPROVED
        );
        $productReviewTransfer21 = $this->tester->haveApprovedCustomerReviewForAbstractProduct(
            $localeTransfer->getIdLocale(),
            $customerTransfer->getCustomerReference(),
            $productAbstractTransfer2->getIdProductAbstract(),
            SpyProductReviewTableMap::COL_STATUS_APPROVED
        );
        $productReviewTransfer22 = $this->tester->haveApprovedCustomerReviewForAbstractProduct(
            $localeTransfer->getIdLocale(),
            $customerTransfer->getCustomerReference(),
            $productAbstractTransfer2->getIdProductAbstract(),
            SpyProductReviewTableMap::COL_STATUS_APPROVED
        );
        $productAbstract1RatingAverage = new Decimal(($productReviewTransfer11->getRating() + $productReviewTransfer12->getRating()) / 2);
        $productAbstract2RatingAverage = new Decimal(($productReviewTransfer21->getRating() + $productReviewTransfer22->getRating()) / 2);

        // Act
        $result = (new ProductReviewSearchRepository())->getProductReviewRatingByIdAbstractProductIn([
            $productAbstractTransfer1->getIdProductAbstract(),
            $productAbstractTransfer2->getIdProductAbstract(),
        ]);

        // Assert
        $this->assertCount(2, $result);
        $this->assertArrayHasKey($productAbstractTransfer1->getIdProductAbstract(), $result);
        $this->assertArrayHasKey($productAbstractTransfer2->getIdProductAbstract(), $result);
        $this->assertTrue($productAbstract1RatingAverage->equals($result[$productAbstractTransfer1->getIdProductAbstract()][ProductPayloadTransfer::AVERAGE_RATING]));
        $this->assertTrue($productAbstract2RatingAverage->equals($result[$productAbstractTransfer2->getIdProductAbstract()][ProductPayloadTransfer::AVERAGE_RATING]));
    }
}
