<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductCategory\Business\Facade;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\CategoryLocalizedAttributesBuilder;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductCategoryConditionsTransfer;
use Generated\Shared\Transfer\ProductCategoryCriteriaTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductCategory
 * @group Business
 * @group Facade
 * @group GetProductCategoryCollectionTest
 * Add your own group annotations below this line
 */
class GetProductCategoryCollectionTest extends Unit
{
    /**
     * @var string
     */
    protected const FAKE_LOCALE_NAME = 'f_l';

    /**
     * @var int
     */
    protected const FAKE_ID_PRODUCT_ABSTRACT = 88888;

    /**
     * @var \SprykerTest\Zed\ProductCategory\ProductCategoryBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGetProductCategoryCollectionByProductAbstractId(): void
    {
        // Arrange
        $categoryTransfer = $this->tester->haveLocalizedCategory();

        $productTransfer = $this->tester->haveProduct();
        $this->tester->assignProductToCategory($categoryTransfer->getIdCategory(), $productTransfer->getFkProductAbstract());

        $productCategoryConditionsTransfer = (new ProductCategoryConditionsTransfer())
            ->addIdProductAbstract($productTransfer->getFkProductAbstract());

        $productCategoryCriteriaTransfer = (new ProductCategoryCriteriaTransfer())
            ->setProductCategoryConditions($productCategoryConditionsTransfer);

        // Act
        $productCategoryTransfers = $this->tester->getFacade()
            ->getProductCategoryCollection($productCategoryCriteriaTransfer)
            ->getProductCategories();

        // Assert
        $this->assertCount(1, $productCategoryTransfers);

        $this->assertSame($productTransfer->getFkProductAbstract(), $productCategoryTransfers->offsetGet(0)->getFkProductAbstract());
        $this->assertSame($categoryTransfer->getIdCategory(), $productCategoryTransfers->offsetGet(0)->getFkCategory());
    }

    /**
     * @return void
     */
    public function testGetProductCategoryCollectionByProductAbstractIds(): void
    {
        // Arrange
        $categoryTransfer = $this->tester->haveLocalizedCategory();

        $firstProductTransfer = $this->tester->haveProduct();
        $secondProductTransfer = $this->tester->haveProduct();

        $this->tester->assignProductToCategory($categoryTransfer->getIdCategory(), $firstProductTransfer->getFkProductAbstract());
        $this->tester->assignProductToCategory($categoryTransfer->getIdCategory(), $secondProductTransfer->getFkProductAbstract());

        $productCategoryConditionsTransfer = (new ProductCategoryConditionsTransfer())
            ->addIdProductAbstract($firstProductTransfer->getFkProductAbstract())
            ->addIdProductAbstract($secondProductTransfer->getFkProductAbstract());

        $productCategoryCriteriaTransfer = (new ProductCategoryCriteriaTransfer())
            ->setProductCategoryConditions($productCategoryConditionsTransfer);

        // Act
        $productCategoryTransfers = $this->tester->getFacade()
            ->getProductCategoryCollection($productCategoryCriteriaTransfer)
            ->getProductCategories();

        // Assert
        $this->assertCount(2, $productCategoryTransfers);

        $this->assertProductAssignedToCategoryInProductCategoryList($firstProductTransfer, $categoryTransfer, $productCategoryTransfers);
        $this->assertProductAssignedToCategoryInProductCategoryList($secondProductTransfer, $categoryTransfer, $productCategoryTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     * @param \ArrayObject<\Generated\Shared\Transfer\ProductCategoryTransfer> $productCategoryTransfers
     *
     * @return void
     */
    protected function assertProductAssignedToCategoryInProductCategoryList(
        ProductConcreteTransfer $productConcreteTransfer,
        CategoryTransfer $categoryTransfer,
        ArrayObject $productCategoryTransfers
    ): void {
        foreach ($productCategoryTransfers as $productCategoryTransfer) {
            if ($productCategoryTransfer->getFkProductAbstract() === $productConcreteTransfer->getFkProductAbstract()) {
                $this->assertSame(
                    $categoryTransfer->getIdCategory(),
                    $productCategoryTransfer->getFkCategory(),
                );

                return;
            }
        }

        $this->fail('Unable to find linked product to product category');
    }

    /**
     * @return void
     */
    public function testGetProductCategoryCollectionByLocale(): void
    {
        // Arrange
        $localeTransfer = $this->tester->haveLocale([LocaleTransfer::LOCALE_NAME => static::FAKE_LOCALE_NAME]);
        $categoryLocalizedAttributesData = (new CategoryLocalizedAttributesBuilder())->build()->toArray();

        $categoryTransfer = $this->tester->haveCategory();
        $this->tester->haveCategoryLocalizedAttributeForCategory(
            $categoryTransfer->getIdCategory(),
            ['locale' => $localeTransfer] + $categoryLocalizedAttributesData,
        );

        $firstProductTransfer = $this->tester->haveProduct();
        $secondProductTransfer = $this->tester->haveProduct();

        $this->tester->assignProductToCategory($categoryTransfer->getIdCategory(), $firstProductTransfer->getFkProductAbstract());
        $this->tester->assignProductToCategory($categoryTransfer->getIdCategory(), $secondProductTransfer->getFkProductAbstract());

        $productCategoryConditionsTransfer = (new ProductCategoryConditionsTransfer())
            ->addIdLocale($localeTransfer->getIdLocale());

        $productCategoryCriteriaTransfer = (new ProductCategoryCriteriaTransfer())
            ->setProductCategoryConditions($productCategoryConditionsTransfer);

        // Act
        $productCategoryTransfers = $this->tester->getFacade()
            ->getProductCategoryCollection($productCategoryCriteriaTransfer)
            ->getProductCategories();

        // Assert
        $this->assertCount(2, $productCategoryTransfers);
    }

    /**
     * @return void
     */
    public function testGetProductCategoryCollectionByFakeLocale(): void
    {
        // Arrange
        $localeTransfer = $this->tester->haveLocale([LocaleTransfer::LOCALE_NAME => static::FAKE_LOCALE_NAME]);
        $categoryTransfer = $this->tester->haveLocalizedCategory();

        $productTransfer = $this->tester->haveProduct();
        $this->tester->assignProductToCategory($categoryTransfer->getIdCategory(), $productTransfer->getFkProductAbstract());

        $productCategoryConditionsTransfer = (new ProductCategoryConditionsTransfer())
            ->addIdLocale($localeTransfer->getIdLocale())
            ->addIdProductAbstract($productTransfer->getFkProductAbstract());

        $productCategoryCriteriaTransfer = (new ProductCategoryCriteriaTransfer())
            ->setProductCategoryConditions($productCategoryConditionsTransfer);

        // Act
        $productCategoryTransfers = $this->tester->getFacade()
            ->getProductCategoryCollection($productCategoryCriteriaTransfer)
            ->getProductCategories();

        // Assert
        $this->assertEmpty($productCategoryTransfers);
    }

    /**
     * @return void
     */
    public function testGetProductCategoryCollectionByFakeProductAbstract(): void
    {
        // Arrange
        $categoryTransfer = $this->tester->haveLocalizedCategory();

        $productTransfer = $this->tester->haveProduct();
        $this->tester->assignProductToCategory($categoryTransfer->getIdCategory(), $productTransfer->getFkProductAbstract());

        $productCategoryConditionsTransfer = (new ProductCategoryConditionsTransfer())
            ->addIdProductAbstract(static::FAKE_ID_PRODUCT_ABSTRACT);

        $productCategoryCriteriaTransfer = (new ProductCategoryCriteriaTransfer())
            ->setProductCategoryConditions($productCategoryConditionsTransfer);

        // Act
        $productCategoryTransfers = $this->tester->getFacade()
            ->getProductCategoryCollection($productCategoryCriteriaTransfer)
            ->getProductCategories();

        // Assert
        $this->assertEmpty($productCategoryTransfers);
    }

    /**
     * @return void
     */
    public function testGetProductCategoryCollectionEnsureCategoryExpansion(): void
    {
        // Arrange
        $categoryTransfer = $this->tester->haveLocalizedCategory();

        $productTransfer = $this->tester->haveProduct();
        $this->tester->assignProductToCategory($categoryTransfer->getIdCategory(), $productTransfer->getFkProductAbstract());

        $productCategoryConditionsTransfer = (new ProductCategoryConditionsTransfer())
            ->addIdProductAbstract($productTransfer->getFkProductAbstract());

        $productCategoryCriteriaTransfer = (new ProductCategoryCriteriaTransfer())
            ->setProductCategoryConditions($productCategoryConditionsTransfer);

        // Act
        $productCategoryTransfers = $this->tester->getFacade()
            ->getProductCategoryCollection($productCategoryCriteriaTransfer)
            ->getProductCategories();

        // Assert
        /** @var \Generated\Shared\Transfer\CategoryTransfer $expandedCategory */
        $expandedCategory = $productCategoryTransfers->offsetGet(0)->getCategory();

        $this->assertSame($categoryTransfer->getIdCategory(), $expandedCategory->getIdCategory());
        $this->assertSame($categoryTransfer->getCategoryKey(), $expandedCategory->getCategoryKey());
    }

    /**
     * @return void
     */
    public function testGetProductCategoryCollectionEnsureLocalizedAttributeExpansion(): void
    {
        // Arrange
        $categoryTransfer = $this->tester->haveLocalizedCategory();

        $productTransfer = $this->tester->haveProduct();
        $this->tester->assignProductToCategory($categoryTransfer->getIdCategory(), $productTransfer->getFkProductAbstract());

        $productCategoryConditionsTransfer = (new ProductCategoryConditionsTransfer())
            ->addIdProductAbstract($productTransfer->getFkProductAbstract());

        $productCategoryCriteriaTransfer = (new ProductCategoryCriteriaTransfer())
            ->setProductCategoryConditions($productCategoryConditionsTransfer);

        // Act
        $productCategoryTransfers = $this->tester->getFacade()
            ->getProductCategoryCollection($productCategoryCriteriaTransfer)
            ->getProductCategories();

        // Assert
        /** @var \Generated\Shared\Transfer\CategoryTransfer $expandedCategory */
        $expandedCategory = $productCategoryTransfers->offsetGet(0)->getCategory();

        $this->assertCount($categoryTransfer->getLocalizedAttributes()->count(), $expandedCategory->getLocalizedAttributes());
        $this->assertSame(
            $categoryTransfer->getLocalizedAttributes()->offsetGet(0)->getName(),
            $expandedCategory->getLocalizedAttributes()->offsetGet(0)->getName(),
        );
    }

    /**
     * @return void
     */
    public function testGetProductCategoryCollectionEnsureLocaleExpansion(): void
    {
        // Arrange
        $categoryTransfer = $this->tester->haveLocalizedCategory();

        $productTransfer = $this->tester->haveProduct();
        $this->tester->assignProductToCategory($categoryTransfer->getIdCategory(), $productTransfer->getFkProductAbstract());

        $productCategoryConditionsTransfer = (new ProductCategoryConditionsTransfer())
            ->addIdProductAbstract($productTransfer->getFkProductAbstract());

        $productCategoryCriteriaTransfer = (new ProductCategoryCriteriaTransfer())
            ->setProductCategoryConditions($productCategoryConditionsTransfer);

        // Act
        $productCategoryTransfers = $this->tester->getFacade()
            ->getProductCategoryCollection($productCategoryCriteriaTransfer)
            ->getProductCategories();

        // Assert
        $this->assertEquals(
            $categoryTransfer->getLocalizedAttributes()->offsetGet(0)->getLocale(),
            $productCategoryTransfers->offsetGet(0)->getCategory()->getLocalizedAttributes()->offsetGet(0)->getLocale(),
        );
    }
}
