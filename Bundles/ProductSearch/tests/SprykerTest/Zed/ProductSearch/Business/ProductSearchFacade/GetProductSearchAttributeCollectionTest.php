<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductSearch\Business\ProductSearchFacade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductAttributeKeyTransfer;
use Generated\Shared\Transfer\ProductSearchAttributeConditionsTransfer;
use Generated\Shared\Transfer\ProductSearchAttributeCriteriaTransfer;
use Generated\Shared\Transfer\ProductSearchAttributeTransfer;
use Generated\Shared\Transfer\SortTransfer;
use SprykerTest\Zed\ProductSearch\ProductSearchBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductSearch
 * @group Business
 * @group ProductSearchFacade
 * @group GetProductSearchAttributeCollectionTest
 *
 * Add your own group annotations below this line
 */
class GetProductSearchAttributeCollectionTest extends Unit
{
    /**
     * @var int
     */
    protected const UNKNOWN_ID = 0;

    /**
     * @var \SprykerTest\Zed\ProductSearch\ProductSearchBusinessTester
     */
    protected ProductSearchBusinessTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->ensureProductAttributeKeyTableIsEmpty();
    }

    /**
     * @return void
     */
    public function testShouldReturnEmptyCollection(): void
    {
        // Arrange
        $this->tester->haveProductSearchAttribute();
        $productSearchAttributeConditionsTransfer = (new ProductSearchAttributeConditionsTransfer())
            ->addIdProductSearchAttribute(static::UNKNOWN_ID);
        $productSearchAttributeCriteriaTransfer = (new ProductSearchAttributeCriteriaTransfer())
            ->setProductSearchAttributeConditions($productSearchAttributeConditionsTransfer);

        // Act
        $productSearchAttributeCollectionTransfer = $this->tester->getFacade()->getProductSearchAttributeCollection($productSearchAttributeCriteriaTransfer);

        // Assert
        $this->assertCount(0, $productSearchAttributeCollectionTransfer->getProductSearchAttributes());
    }

    /**
     * @return void
     */
    public function testShouldReturnCollectionByIds(): void
    {
        // Arrange
        $productSearchAttributeTransfer = $this->tester->haveProductSearchAttribute();
        $this->tester->haveProductSearchAttribute();

        $productSearchAttributeConditionsTransfer = (new ProductSearchAttributeConditionsTransfer())
            ->addIdProductSearchAttribute($productSearchAttributeTransfer->getIdProductSearchAttributeOrFail());
        $productSearchAttributeCriteriaTransfer = (new ProductSearchAttributeCriteriaTransfer())
            ->setProductSearchAttributeConditions($productSearchAttributeConditionsTransfer);

        // Act
        $productSearchAttributeCollectionTransfer = $this->tester->getFacade()->getProductSearchAttributeCollection($productSearchAttributeCriteriaTransfer);

        // Assert
        $this->assertCount(1, $productSearchAttributeCollectionTransfer->getProductSearchAttributes());
        $this->assertSame(
            $productSearchAttributeTransfer->getIdProductSearchAttributeOrFail(),
            $productSearchAttributeCollectionTransfer->getProductSearchAttributes()->getIterator()->current()->getIdProductSearchAttributeOrFail(),
        );
        $this->assertCount(
            0,
            $productSearchAttributeCollectionTransfer->getProductSearchAttributes()->getIterator()->current()->getLocalizedKeys(),
        );
    }

    /**
     * @return void
     */
    public function testShouldReturnCollectionSortedByFieldAsc(): void
    {
        // Arrange
        $this->tester->haveProductSearchAttribute(
            [],
            [
                ProductSearchAttributeTransfer::POSITION => 2,
            ],
        );
        $this->tester->haveProductSearchAttribute(
            [],
            [
                ProductSearchAttributeTransfer::POSITION => 1,
            ],
        );
        $this->tester->haveProductSearchAttribute(
            [],
            [
                ProductSearchAttributeTransfer::POSITION => 3,
            ],
        );

        $sortTransfer = (new SortTransfer())
            ->setIsAscending(true)
            ->setField(ProductSearchAttributeTransfer::POSITION);
        $productSearchAttributeConditionsTransfer = new ProductSearchAttributeConditionsTransfer();
        $productSearchAttributeCriteriaTransfer = (new ProductSearchAttributeCriteriaTransfer())
            ->addSort($sortTransfer)
            ->setProductSearchAttributeConditions($productSearchAttributeConditionsTransfer);

        // Act
        $productSearchAttributeCollectionTransfer = $this->tester->getFacade()->getProductSearchAttributeCollection($productSearchAttributeCriteriaTransfer);

        // Assert
        $this->assertCount(3, $productSearchAttributeCollectionTransfer->getProductSearchAttributes());

        foreach ($productSearchAttributeCollectionTransfer->getProductSearchAttributes() as $key => $productSearchAttributeTransfer) {
            $this->assertSame($key + 1, $productSearchAttributeTransfer->getPositionOrFail());
        }
    }

    /**
     * @return void
     */
    public function testShouldReturnCollectionSortedByFieldDesc(): void
    {
        // Arrange
        $this->tester->haveProductSearchAttribute(
            [],
            [
                ProductSearchAttributeTransfer::POSITION => 2,
            ],
        );
        $this->tester->haveProductSearchAttribute(
            [],
            [
                ProductSearchAttributeTransfer::POSITION => 1,
            ],
        );
        $this->tester->haveProductSearchAttribute(
            [],
            [
                ProductSearchAttributeTransfer::POSITION => 3,
            ],
        );

        $sortTransfer = (new SortTransfer())
            ->setIsAscending(false)
            ->setField(ProductSearchAttributeTransfer::POSITION);
        $productSearchAttributeConditionsTransfer = new ProductSearchAttributeConditionsTransfer();
        $productSearchAttributeCriteriaTransfer = (new ProductSearchAttributeCriteriaTransfer())
            ->addSort($sortTransfer)
            ->setProductSearchAttributeConditions($productSearchAttributeConditionsTransfer);

        // Act
        $productSearchAttributeCollectionTransfer = $this->tester->getFacade()->getProductSearchAttributeCollection($productSearchAttributeCriteriaTransfer);

        // Assert
        $this->assertCount(3, $productSearchAttributeCollectionTransfer->getProductSearchAttributes());

        foreach ($productSearchAttributeCollectionTransfer->getProductSearchAttributes() as $key => $productSearchAttributeTransfer) {
            $this->assertSame(
                count($productSearchAttributeCollectionTransfer->getProductSearchAttributes()) - $key,
                $productSearchAttributeTransfer->getPositionOrFail(),
            );
        }
    }

    /**
     * @return void
     */
    public function testShouldReturnCollectionWithLocalizedAttributes(): void
    {
        // Arrange
        $glossaryKey = uniqid();
        $this->tester->haveProductSearchAttribute([
            ProductAttributeKeyTransfer::KEY => $glossaryKey,
        ]);
        $localeTransfers = $this->tester->getLocator()->locale()->facade()->getLocaleCollection();

        foreach ($localeTransfers as $localeTransfer) {
            $this->tester->addProductSearchKeyTranslation($glossaryKey, $localeTransfer, $glossaryKey);
        }

        $productSearchAttributeConditionsTransfer = (new ProductSearchAttributeConditionsTransfer())
            ->setWithLocalizedAttributes(true);
        $productSearchAttributeCriteriaTransfer = (new ProductSearchAttributeCriteriaTransfer())
            ->setProductSearchAttributeConditions($productSearchAttributeConditionsTransfer);

        // Act
        $productSearchAttributeCollectionTransfer = $this->tester->getFacade()->getProductSearchAttributeCollection($productSearchAttributeCriteriaTransfer);

        // Assert
        $this->assertCount(1, $productSearchAttributeCollectionTransfer->getProductSearchAttributes());
        /** @var \Generated\Shared\Transfer\ProductSearchAttributeTransfer $productSearchAttributeTransfer */
        $productSearchAttributeTransfer = $productSearchAttributeCollectionTransfer->getProductSearchAttributes()->getIterator()->current();
        $this->assertCount(count($localeTransfers), $productSearchAttributeTransfer->getLocalizedKeys());

        foreach ($productSearchAttributeTransfer->getLocalizedKeys() as $localizedProductSearchAttributeKeyTransfer) {
            $this->assertSame($glossaryKey, $localizedProductSearchAttributeKeyTransfer->getKeyTranslationOrFail());
        }
    }
}
