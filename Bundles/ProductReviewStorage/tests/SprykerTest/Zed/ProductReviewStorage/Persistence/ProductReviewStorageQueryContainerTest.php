<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductReviewStorage\Persistence;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductPageSearchTransfer;
use Generated\Shared\Transfer\ProductPayloadTransfer;
use Orm\Zed\ProductReview\Persistence\Map\SpyProductReviewTableMap;
use Spryker\DecimalObject\Decimal;
use Spryker\Zed\ProductReviewStorage\Persistence\ProductReviewStorageQueryContainer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductReviewStorage
 * @group Persistence
 * @group ProductReviewStorageQueryContainerTest
 * Add your own group annotations below this line
 */
class ProductReviewStorageQueryContainerTest extends Unit
{
    protected const TEST_LOCALE_NAME = 'xxx';

    /**
     * @var \SprykerTest\Zed\ProductReviewStorage\ProductReviewStoragePersistenceTester
     */
    protected $tester;

    /**
     * @var \Spryker\Zed\ProductReviewStorage\Persistence\ProductReviewStorageQueryContainerInterface
     */
    protected $productReviewStorageQueryContainer;

    /**
     * @var \Generated\Shared\Transfer\LocaleTransfer
     */
    protected $localeTransfer;

    /**
     * @var \Generated\Shared\Transfer\CustomerTransfer
     */
    protected $customerTransfer;

    /**
     * @var \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    protected $productAbstractTransfer1;

    /**
     * @var \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    protected $productAbstractTransfer2;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->productReviewStorageQueryContainer = new ProductReviewStorageQueryContainer();

        $this->localeTransfer = $this->tester->haveLocale([
            LocaleTransfer::LOCALE_NAME => static::TEST_LOCALE_NAME,
        ]);
        $this->customerTransfer = $this->tester->haveCustomer();

        $this->productAbstractTransfer1 = $this->tester->haveProductAbstract();
        $this->productAbstractTransfer2 = $this->tester->haveProductAbstract();
    }

    /**
     * @return void
     */
    public function testQueryProductReviewsByIdProductAbstractsReturnsCorrectData(): void
    {
        // Arrange
        $productReviewTransfer11 = $this->tester->haveApprovedCustomerReviewForAbstractProduct(
            $this->localeTransfer->getIdLocale(),
            $this->customerTransfer->getCustomerReference(),
            $this->productAbstractTransfer1->getIdProductAbstract(),
            SpyProductReviewTableMap::COL_STATUS_APPROVED
        );
        $productReviewTransfer12 = $this->tester->haveApprovedCustomerReviewForAbstractProduct(
            $this->localeTransfer->getIdLocale(),
            $this->customerTransfer->getCustomerReference(),
            $this->productAbstractTransfer1->getIdProductAbstract(),
            SpyProductReviewTableMap::COL_STATUS_APPROVED
        );
        $productReviewTransfer21 = $this->tester->haveApprovedCustomerReviewForAbstractProduct(
            $this->localeTransfer->getIdLocale(),
            $this->customerTransfer->getCustomerReference(),
            $this->productAbstractTransfer2->getIdProductAbstract(),
            SpyProductReviewTableMap::COL_STATUS_APPROVED
        );
        $productReviewTransfer22 = $this->tester->haveApprovedCustomerReviewForAbstractProduct(
            $this->localeTransfer->getIdLocale(),
            $this->customerTransfer->getCustomerReference(),
            $this->productAbstractTransfer2->getIdProductAbstract(),
            SpyProductReviewTableMap::COL_STATUS_APPROVED
        );
        $productAbstract1RatingAverage = new Decimal(($productReviewTransfer11->getRating() + $productReviewTransfer12->getRating()) / 2);
        $productAbstract2RatingAverage = new Decimal(($productReviewTransfer21->getRating() + $productReviewTransfer22->getRating()) / 2);

        // Act
        $result = $this->productReviewStorageQueryContainer->queryProductReviewsByIdProductAbstracts([
            $this->productAbstractTransfer1->getIdProductAbstract(),
            $this->productAbstractTransfer2->getIdProductAbstract(),
        ])->find()->toArray(ProductPageSearchTransfer::ID_PRODUCT_ABSTRACT);

        // Assert
        $this->assertCount(2, $result);
        $this->assertArrayHasKey($this->productAbstractTransfer1->getIdProductAbstract(), $result);
        $this->assertArrayHasKey($this->productAbstractTransfer2->getIdProductAbstract(), $result);
        $this->assertTrue($productAbstract1RatingAverage->equals($result[$this->productAbstractTransfer1->getIdProductAbstract()][ProductPayloadTransfer::AVERAGE_RATING]));
        $this->assertTrue($productAbstract2RatingAverage->equals($result[$this->productAbstractTransfer2->getIdProductAbstract()][ProductPayloadTransfer::AVERAGE_RATING]));
    }

    /**
     * @return void
     */
    public function testQueryProductReviewsByIdsReturnsCorrectData(): void
    {
        // Arrange
        $productReviewTransfer1 = $this->tester->haveApprovedCustomerReviewForAbstractProduct(
            $this->localeTransfer->getIdLocale(),
            $this->customerTransfer->getCustomerReference(),
            $this->productAbstractTransfer1->getIdProductAbstract(),
            SpyProductReviewTableMap::COL_STATUS_APPROVED
        );
        $productReviewTransfer2 = $this->tester->haveApprovedCustomerReviewForAbstractProduct(
            $this->localeTransfer->getIdLocale(),
            $this->customerTransfer->getCustomerReference(),
            $this->productAbstractTransfer2->getIdProductAbstract(),
            SpyProductReviewTableMap::COL_STATUS_APPROVED
        );

        // Act
        $result = $this->productReviewStorageQueryContainer->queryProductReviewsByIds([
            $productReviewTransfer1->getIdProductReview(),
            $productReviewTransfer2->getIdProductReview(),
        ])->find()->toArray();

        // Assert
        $this->assertCount(2, $result);
        $resultProductAbstractIds = array_column($result, ProductPageSearchTransfer::ID_PRODUCT_ABSTRACT);
        $this->assertContains($this->productAbstractTransfer1->getIdProductAbstract(), $resultProductAbstractIds);
        $this->assertContains($this->productAbstractTransfer2->getIdProductAbstract(), $resultProductAbstractIds);
    }
}
