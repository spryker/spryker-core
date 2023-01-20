<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Category\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\CategoryLocalizedAttributesBuilder;
use Generated\Shared\Transfer\CategoryConditionsTransfer;
use Generated\Shared\Transfer\CategoryCriteriaTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\LocalizedAttributesTransfer;
use Generated\Shared\Transfer\PaginationTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Category
 * @group Business
 * @group Facade
 * @group GetCategoryCollectionTest
 * Add your own group annotations below this line
 */
class GetCategoryCollectionTest extends Unit
{
    /**
     * @var string
     */
    protected const FAKE_LOCALE_NAME = 'f_l';

    /**
     * @var \SprykerTest\Zed\Category\CategoryBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGetCategoryCollectionChecksCategoryProperties(): void
    {
        // Arrange
        $categoryTransfer = $this->tester->haveLocalizedCategory();

        $categoryCriteriaTransfer = (new CategoryCriteriaTransfer())
            ->setIdCategory($categoryTransfer->getIdCategory());

        // Act
        /** @var \Generated\Shared\Transfer\CategoryTransfer $persistedCategory */
        $persistedCategory = $this->tester->getFacade()
            ->getCategoryCollection($categoryCriteriaTransfer)
            ->getCategories()
            ->getIterator()
            ->current();

        // Assert
        $this->assertNotNull($persistedCategory);
        $this->assertSame($categoryTransfer->getIdCategory(), $persistedCategory->getIdCategory());
        $this->assertSame($categoryTransfer->getCategoryKey(), $persistedCategory->getCategoryKey());

        $this->assertNotEmpty($persistedCategory->getNodeCollection());
        $this->assertNotEmpty($persistedCategory->getStoreRelation());
        $this->assertNotEmpty($persistedCategory->getLocalizedAttributes());
    }

    /**
     * @return void
     */
    public function testGetCategoryCollectionFiltersMainCategory(): void
    {
        // Arrange
        $categoryTransfer = $this->tester->haveCategory();

        $categoryCriteriaTransfer = (new CategoryCriteriaTransfer())
            ->setIdCategory($categoryTransfer->getIdCategory())
            ->setIsMain(true);

        // Act
        $categoryTransfers = $this->tester->getFacade()
            ->getCategoryCollection($categoryCriteriaTransfer)
            ->getCategories();

        // Assert
        $this->assertCount(1, $categoryTransfers);
    }

    /**
     * @return void
     */
    public function testGetCategoryCollectionFiltersCategoriesByLocaleName(): void
    {
        // Arrange
        $localeTransfer = $this->tester->haveLocale([LocaleTransfer::LOCALE_NAME => static::FAKE_LOCALE_NAME]);
        $categoryLocalizedAttributesData = (new CategoryLocalizedAttributesBuilder())->build()->toArray();

        $categoryTransfer = $this->tester->haveCategory();
        $this->tester->haveCategoryLocalizedAttributeForCategory(
            $categoryTransfer->getIdCategory(),
            [LocalizedAttributesTransfer::LOCALE => $localeTransfer] + $categoryLocalizedAttributesData,
        );

        $categoryCriteriaTransfer = (new CategoryCriteriaTransfer())
            ->setIdCategory($categoryTransfer->getIdCategory())
            ->setLocaleName($localeTransfer->getLocaleName());

        // Act
        $categoryTransfers = $this->tester->getFacade()
            ->getCategoryCollection($categoryCriteriaTransfer)
            ->getCategories();

        // Assert
        $this->assertCount(1, $categoryTransfers);
    }

    /**
     * @return void
     */
    public function testGetCategoryCollectionFiltersCategoriesByLocaleId(): void
    {
        // Arrange
        $localeTransfer = $this->tester->haveLocale([LocaleTransfer::LOCALE_NAME => static::FAKE_LOCALE_NAME]);
        $categoryLocalizedAttributesData = (new CategoryLocalizedAttributesBuilder())->build()->toArray();

        $categoryTransfer = $this->tester->haveCategory();
        $this->tester->haveCategoryLocalizedAttributeForCategory(
            $categoryTransfer->getIdCategory(),
            [LocalizedAttributesTransfer::LOCALE => $localeTransfer] + $categoryLocalizedAttributesData,
        );

        $categoryCriteriaTransfer = (new CategoryCriteriaTransfer())
            ->setIdCategory($categoryTransfer->getIdCategory())
            ->setIdLocale($localeTransfer->getIdLocale());

        // Act
        $categoryTransfers = $this->tester->getFacade()
            ->getCategoryCollection($categoryCriteriaTransfer)
            ->getCategories();

        // Assert
        $this->assertCount(1, $categoryTransfers);
    }

    /**
     * @return void
     */
    public function testGetCategoryCollectionFiltersCategoriesByCategoryNodeId(): void
    {
        // Arrange
        $categoryTransfer = $this->tester->haveCategory();

        $categoryCriteriaTransfer = (new CategoryCriteriaTransfer())
            ->setIdCategoryNode($categoryTransfer->getCategoryNode()->getIdCategoryNode());

        // Act
        $categoryTransfers = $this->tester->getFacade()
            ->getCategoryCollection($categoryCriteriaTransfer)
            ->getCategories();

        // Assert
        $this->assertCount(1, $categoryTransfers);
    }

    /**
     * @return void
     */
    public function testGetCategoryCollectionReturnsEmptyCollectionWhileNoCriteriaMatched(): void
    {
        // Arrange
        $this->tester->haveLocalizedCategory();
        $categoryConditionsTransfer = (new CategoryConditionsTransfer())->addIdCategory(0);
        $categoryCriteriaTransfer = (new CategoryCriteriaTransfer())
            ->setCategoryConditions($categoryConditionsTransfer);

        // Act
        $categoryCollectionTransfer = $this->tester->getFacade()
            ->getCategoryCollection($categoryCriteriaTransfer);

        // Assert
        $this->assertCount(0, $categoryCollectionTransfer->getCategories());
    }

    /**
     * @return void
     */
    public function testGetCategoryCollectionReturnsCollectionWithOneCategoryWhileAllCriteriasMatched(): void
    {
        // Arrange
        $localeTransfer = $this->tester->haveLocale([LocaleTransfer::LOCALE_NAME => static::FAKE_LOCALE_NAME]);
        $categoryLocalizedAttributesData = (new CategoryLocalizedAttributesBuilder())->build()->toArray();
        $categoryTransfer1 = $this->tester->haveCategory();
        $this->tester->haveCategoryLocalizedAttributeForCategory(
            $categoryTransfer1->getIdCategory(),
            [LocalizedAttributesTransfer::LOCALE => $localeTransfer] + $categoryLocalizedAttributesData,
        );

        $categoryTransfer2 = $this->tester->haveCategory();
        $this->tester->haveCategoryLocalizedAttributeForCategory(
            $categoryTransfer2->getIdCategory(),
            [LocalizedAttributesTransfer::LOCALE => $localeTransfer] + $categoryLocalizedAttributesData,
        );

        $categoryConditionsTransfer = (new CategoryConditionsTransfer())
            ->addIdCategory($categoryTransfer1->getIdCategory())
            ->setIsMain(true)
            ->addIdLocale($localeTransfer->getIdLocale())
            ->addIdCategoryNode($categoryTransfer1->getCategoryNode()->getIdCategoryNode())
            ->addLocaleName($localeTransfer->getLocaleName());
        $categoryCriteriaTransfer = (new CategoryCriteriaTransfer())
            ->setCategoryConditions($categoryConditionsTransfer);

        // Act
        $categoryCollectionTransfer = $this->tester->getFacade()
            ->getCategoryCollection($categoryCriteriaTransfer);

        // Assert
        $this->assertCount(1, $categoryCollectionTransfer->getCategories());
        $this->assertSame(
            $categoryTransfer1->getIdCategory(),
            $categoryCollectionTransfer->getCategories()->getIterator()->current()->getIdCategory(),
        );
    }

    /**
     * @return void
     */
    public function testGetCategoryCollectionReturnsCollectionWithFiveGetCategoriesWhileHavingLimitOffsetPaginationApplied(): void
    {
        // Arrange
        for ($i = 0; $i < 15; $i++) {
            $this->tester->haveCategory();
        }
        $categoryCriteriaTransfer = (new CategoryCriteriaTransfer())
            ->setPagination(
                (new PaginationTransfer())->setLimit(5)->setOffset(10),
            );

        // Act
        $categoryCollectionTransfer = $this->tester->getFacade()
            ->getCategoryCollection($categoryCriteriaTransfer);

        // Assert
        $this->assertCount(5, $categoryCollectionTransfer->getCategories());
    }
}
