<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductImage\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ProductImageBuilder;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\ProductImageSetConditionsTransfer;
use Generated\Shared\Transfer\ProductImageSetCriteriaTransfer;
use Generated\Shared\Transfer\ProductImageSetTransfer;
use Generated\Shared\Transfer\ProductImageTransfer;
use Generated\Shared\Transfer\SortTransfer;
use SprykerTest\Zed\ProductImage\ProductImageBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductImage
 * @group Business
 * @group Facade
 * @group GetAbstractProductImageSetCollectionTest
 * Add your own group annotations below this line
 */
class GetAbstractProductImageSetCollectionTest extends Unit
{
    /**
     * @var string
     */
    protected const FAKE_LARGE_URL = 'fake-large-url';

    /**
     * @var string
     */
    protected const FAKE_SMALL_URL = 'fake-small-url';

    /**
     * @var string
     */
    protected const FAKE_NAME_FOO = 'foo';

    /**
     * @var string
     */
    protected const FAKE_NAME_BAR = 'bar';

    /**
     * @var string
     */
    protected const LOCALE_DE_DE = 'de_DE';

    /**
     * @var string
     */
    protected const LOCALE_EN_EN = 'en_EN';

    /**
     * @var \SprykerTest\Zed\ProductImage\ProductImageBusinessTester
     */
    protected ProductImageBusinessTester $tester;

    /**
     * @return void
     */
    public function testGetAbstractProductImageSetCollectionRetrievesProductImageSets(): void
    {
        // Arrange
        $this->tester->ensureProductImageSetDatabaseTablesAreEmpty();

        $productAbstractTransfer = $this->tester->haveProductAbstract();
        $this->tester->haveProductImageSet([
            ProductImageSetTransfer::ID_PRODUCT_ABSTRACT => $productAbstractTransfer->getIdProductAbstract(),
        ]);

        // Act
        $productImageSetCollectionTransfer = $this->tester->getFacade()
            ->getAbstractProductImageSetCollection(new ProductImageSetCriteriaTransfer());

        // Assert
        $this->assertCount(1, $productImageSetCollectionTransfer->getProductImageSets());
    }

    /**
     * @return void
     */
    public function testGetAbstractProductImageSetCollectionRetrievesProductImageSetsFilteredByProductAbstractId(): void
    {
        // Arrange
        $this->tester->ensureProductImageSetDatabaseTablesAreEmpty();

        $productAbstractTransfer1 = $this->tester->haveProductAbstract();
        $productAbstractTransfer2 = $this->tester->haveProductAbstract();
        $this->tester->haveProductImageSet([
            ProductImageSetTransfer::ID_PRODUCT_ABSTRACT => $productAbstractTransfer1->getIdProductAbstract(),
        ]);
        $this->tester->haveProductImageSet([
            ProductImageSetTransfer::ID_PRODUCT_ABSTRACT => $productAbstractTransfer2->getIdProductAbstract(),
        ]);

        $productImageSetCriteriaTransfer = (new ProductImageSetCriteriaTransfer())->setProductImageSetConditions(
            (new ProductImageSetConditionsTransfer())->addIdProductAbstract($productAbstractTransfer1->getIdProductAbstract()),
        );

        // Act
        $productImageSetCollectionTransfer = $this->tester->getFacade()
            ->getAbstractProductImageSetCollection($productImageSetCriteriaTransfer);

        // Assert
        $this->assertCount(1, $productImageSetCollectionTransfer->getProductImageSets());
        $this->assertSame(
            $productAbstractTransfer1->getIdProductAbstract(),
            $productImageSetCollectionTransfer->getProductImageSets()->getIterator()->current()->getIdProductAbstract(),
        );
    }

    /**
     * @return void
     */
    public function testGetAbstractProductImageSetCollectionRetrievesProductImageSetsFilteredByName(): void
    {
        // Arrange
        $this->tester->ensureProductImageSetDatabaseTablesAreEmpty();

        $productAbstractTransfer = $this->tester->haveProductAbstract();
        $this->tester->haveProductImageSet([
            ProductImageSetTransfer::ID_PRODUCT_ABSTRACT => $productAbstractTransfer->getIdProductAbstract(),
            ProductImageSetTransfer::NAME => static::FAKE_NAME_FOO,
        ]);
        $this->tester->haveProductImageSet([
            ProductImageSetTransfer::ID_PRODUCT_ABSTRACT => $productAbstractTransfer->getIdProductAbstract(),
            ProductImageSetTransfer::NAME => static::FAKE_NAME_BAR,
        ]);

        $productImageSetCriteriaTransfer = (new ProductImageSetCriteriaTransfer())->setProductImageSetConditions(
            (new ProductImageSetConditionsTransfer())
                ->addName(static::FAKE_NAME_FOO),
        );

        // Act
        $productImageSetCollectionTransfer = $this->tester->getFacade()
            ->getAbstractProductImageSetCollection($productImageSetCriteriaTransfer);

        // Assert
        $this->assertCount(1, $productImageSetCollectionTransfer->getProductImageSets());
        $this->assertSame(
            static::FAKE_NAME_FOO,
            $productImageSetCollectionTransfer->getProductImageSets()->offsetGet(0)->getName(),
        );
    }

    /**
     * @return void
     */
    public function testGetAbstractProductImageSetCollectionRetrievesProductImageSetsFilteredByIdLocale(): void
    {
        // Arrange
        $this->tester->ensureProductImageSetDatabaseTablesAreEmpty();

        $localeTransfer1 = $this->tester->haveLocale([LocaleTransfer::LOCALE_NAME => static::LOCALE_DE_DE]);
        $localeTransfer2 = $this->tester->haveLocale([LocaleTransfer::LOCALE_NAME => static::LOCALE_EN_EN]);
        $productImageSetTransfer = $this->tester->haveProductImageSet([
            ProductImageSetTransfer::LOCALE => $localeTransfer1,
        ]);
        $this->tester->haveProductImageSet([
            ProductImageSetTransfer::LOCALE => $localeTransfer2,
        ]);

        $productImageSetCriteriaTransfer = (new ProductImageSetCriteriaTransfer())->setProductImageSetConditions(
            (new ProductImageSetConditionsTransfer())
                ->addIdLocale($localeTransfer1->getIdLocale()),
        );

        // Act
        $productImageSetCollectionTransfer = $this->tester->getFacade()
            ->getAbstractProductImageSetCollection($productImageSetCriteriaTransfer);

        // Assert
        $this->assertCount(1, $productImageSetCollectionTransfer->getProductImageSets());
        $this->assertSame(
            $productImageSetTransfer->getIdProductImageSet(),
            $productImageSetCollectionTransfer->getProductImageSets()->offsetGet(0)->getIdProductImageSet(),
        );
    }

    /**
     * @return void
     */
    public function testGetAbstractProductImageSetCollectionShouldReturnProductImageSetsWithFallbackLocale(): void
    {
        // Arrange
        $this->tester->ensureProductImageSetDatabaseTablesAreEmpty();
        $localeTransfer = $this->tester->haveLocale([LocaleTransfer::LOCALE_NAME => static::LOCALE_DE_DE]);
        $this->tester->haveProductImageSet([
            ProductImageSetTransfer::LOCALE => null,
        ]);

        $productImageSetCriteriaTransfer = (new ProductImageSetCriteriaTransfer())
            ->setProductImageSetConditions(
                (new ProductImageSetConditionsTransfer())
                    ->addIdLocale($localeTransfer->getIdLocale())
                    ->setAddFallbackLocale(true),
            );

        // Act
        $productImageSetCollectionTransfer = $this->tester->getFacade()
            ->getAbstractProductImageSetCollection($productImageSetCriteriaTransfer);

        // Assert
        $this->assertCount(1, $productImageSetCollectionTransfer->getProductImageSets());
    }

    /**
     * @return void
     */
    public function testGetAbstractProductImageSetCollectionShouldReturnProductImageSetWithProductImages(): void
    {
        // Arrange
        $productAbstractTransfer = $this->tester->haveProductAbstract();
        $this->tester->haveProductImageSet([
            ProductImageSetTransfer::ID_PRODUCT_ABSTRACT => $productAbstractTransfer->getIdProductAbstract(),
            ProductImageSetTransfer::PRODUCT_IMAGES => [
                (new ProductImageBuilder())->seed([
                    ProductImageTransfer::EXTERNAL_URL_LARGE => static::FAKE_LARGE_URL,
                    ProductImageTransfer::EXTERNAL_URL_SMALL => static::FAKE_SMALL_URL,
                ])->build(),
            ],
        ]);

        $productImageSetCriteriaTransfer = (new ProductImageSetCriteriaTransfer())->setProductImageSetConditions(
            (new ProductImageSetConditionsTransfer())->addIdProductAbstract($productAbstractTransfer->getIdProductAbstract()),
        );

        // Act
        $productImageSetCollectionTransfer = $this->tester->getFacade()
            ->getAbstractProductImageSetCollection($productImageSetCriteriaTransfer);

        // Assert
        /** @var \Generated\Shared\Transfer\ProductImageSetTransfer $productImageSetTransfer */
        $productImageSetTransfer = $productImageSetCollectionTransfer->getProductImageSets()->getIterator()->current();
        /** @var \Generated\Shared\Transfer\ProductImageTransfer $productImageTransfer */
        $productImageTransfer = $productImageSetTransfer->getProductImages()->getIterator()->current();

        $this->assertSame(static::FAKE_LARGE_URL, $productImageTransfer->getExternalUrlLarge());
        $this->assertSame(static::FAKE_SMALL_URL, $productImageTransfer->getExternalUrlSmall());
    }

    /**
     * @return void
     */
    public function testGetAbstractProductImageSetCollectionShouldReturnSortedCollection(): void
    {
        // Arrange
        $this->tester->ensureProductImageSetDatabaseTablesAreEmpty();

        $productAbstractTransfer = $this->tester->haveProductAbstract();
        $this->tester->haveProductImageSet([
            ProductImageSetTransfer::ID_PRODUCT_ABSTRACT => $productAbstractTransfer->getIdProductAbstract(),
            ProductImageSetTransfer::NAME => static::FAKE_NAME_BAR,
        ]);
        $this->tester->haveProductImageSet([
            ProductImageSetTransfer::ID_PRODUCT_ABSTRACT => $productAbstractTransfer->getIdProductAbstract(),
            ProductImageSetTransfer::NAME => static::FAKE_NAME_FOO,
        ]);

        $sortTransfer = (new SortTransfer())
            ->setField(ProductImageSetTransfer::NAME)
            ->setIsAscending(false);

        $productImageSetConditionsTransfer = (new ProductImageSetConditionsTransfer());
        $productImageSetCriteriaTransfer = (new ProductImageSetCriteriaTransfer())
            ->addSort($sortTransfer)
            ->setProductImageSetConditions($productImageSetConditionsTransfer);

        // Act
        $productImageSetCollectionTransfer = $this->tester->getFacade()
            ->getAbstractProductImageSetCollection($productImageSetCriteriaTransfer);

        // Assert
        $this->assertCount(2, $productImageSetCollectionTransfer->getProductImageSets());
        $this->assertSame(static::FAKE_NAME_FOO, $productImageSetCollectionTransfer->getProductImageSets()->offsetGet(0)->getName());
        $this->assertSame(static::FAKE_NAME_BAR, $productImageSetCollectionTransfer->getProductImageSets()->offsetGet(1)->getName());
    }

    /**
     * @return void
     */
    public function testGetAbstractProductImageSetCollectionShouldReturnPaginatedCollectionByLimitAndOffset(): void
    {
        // Arrange
        $this->tester->ensureProductImageSetDatabaseTablesAreEmpty();

        $productAbstractTransfer = $this->tester->haveProductAbstract();
        $this->tester->haveProductImageSet([
            ProductImageSetTransfer::ID_PRODUCT_ABSTRACT => $productAbstractTransfer->getIdProductAbstract(),
            ProductImageSetTransfer::NAME => static::FAKE_NAME_BAR,
        ]);
        $this->tester->haveProductImageSet([
            ProductImageSetTransfer::ID_PRODUCT_ABSTRACT => $productAbstractTransfer->getIdProductAbstract(),
            ProductImageSetTransfer::NAME => static::FAKE_NAME_FOO,
        ]);

        $productImageSetConditionsTransfer = (new ProductImageSetConditionsTransfer())
            ->addIdProductAbstract($productAbstractTransfer->getIdProductAbstract());

        $productImageSetCriteriaTransfer = (new ProductImageSetCriteriaTransfer())
            ->setPagination((new PaginationTransfer())->setLimit(1)->setOffset(1))
            ->setProductImageSetConditions($productImageSetConditionsTransfer);

        // Act
        $productImageSetCollectionTransfer = $this->tester->getFacade()
            ->getAbstractProductImageSetCollection($productImageSetCriteriaTransfer);

        // Assert
        $this->assertCount(1, $productImageSetCollectionTransfer->getProductImageSets());
    }

    /**
     * @return void
     */
    public function testGetAbstractProductImageSetCollectionShouldReturnPaginatedCollectionByPageAndMaxPerPage(): void
    {
        // Arrange
        $this->tester->ensureProductImageSetDatabaseTablesAreEmpty();

        $productAbstractTransfer = $this->tester->haveProductAbstract();
        $this->tester->haveProductImageSet([
            ProductImageSetTransfer::ID_PRODUCT_ABSTRACT => $productAbstractTransfer->getIdProductAbstract(),
            ProductImageSetTransfer::NAME => static::FAKE_NAME_BAR,
        ]);
        $this->tester->haveProductImageSet([
            ProductImageSetTransfer::ID_PRODUCT_ABSTRACT => $productAbstractTransfer->getIdProductAbstract(),
            ProductImageSetTransfer::NAME => static::FAKE_NAME_FOO,
        ]);

        $productImageSetConditionsTransfer = (new ProductImageSetConditionsTransfer())
            ->addIdProductAbstract($productAbstractTransfer->getIdProductAbstract());

        $productImageSetCriteriaTransfer = (new ProductImageSetCriteriaTransfer())
            ->setPagination((new PaginationTransfer())->setPage(2)->setMaxPerPage(1))
            ->setProductImageSetConditions($productImageSetConditionsTransfer);

        // Act
        $productImageSetCollectionTransfer = $this->tester->getFacade()
            ->getAbstractProductImageSetCollection($productImageSetCriteriaTransfer);

        // Assert
        $this->assertCount(1, $productImageSetCollectionTransfer->getProductImageSets());
    }
}
