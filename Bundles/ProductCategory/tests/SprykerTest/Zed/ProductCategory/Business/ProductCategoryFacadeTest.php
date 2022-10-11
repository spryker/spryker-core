<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductCategory\Business;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\CategoryLocalizedAttributesBuilder;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\LocalizedAttributesTransfer;
use Spryker\Zed\ProductCategory\Business\ProductCategoryFacadeInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductCategory
 * @group Business
 * @group Facade
 * @group ProductCategoryFacadeTest
 * Add your own group annotations below this line
 */
class ProductCategoryFacadeTest extends Unit
{
    /**
     * @var string
     */
    protected const LOCALE_EN = 'en_US';

    /**
     * @var string
     */
    protected const LOCALE_DE = 'de_DE';

    /**
     * @var \SprykerTest\Zed\ProductCategory\ProductCategoryBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGetProductConcreteIdsByCategoryIdsReturnArrayOfIdsOfAssignedConcretes(): void
    {
        // Arrange
        $categoryTransfer = $this->tester->haveCategory();
        $productTransfer = $this->tester->haveProduct();

        $this->tester->assignProductToCategory($categoryTransfer->getIdCategory(), $productTransfer->getFkProductAbstract());

        // Act
        $productConcreteIds = $this->getProductCategoryFacade()
            ->getProductConcreteIdsByCategoryIds([$categoryTransfer->getIdCategory()]);

        // Assert
        $this->assertIsArray($productConcreteIds);
        $this->assertEquals([$productTransfer->getIdProductConcrete()], $productConcreteIds);
    }

    /**
     * @return void
     */
    public function testGetProductConcreteIdsByCategoryIdsReturnsEmptyArrayWhenNoProductsAssignedToCategory(): void
    {
        // Arrange
        $categoryTransfer = $this->tester->haveCategory();

        // Act
        $productConcreteIds = $this->getProductCategoryFacade()
            ->getProductConcreteIdsByCategoryIds([$categoryTransfer->getIdCategory()]);

        // Assert
        $this->assertIsArray($productConcreteIds);
        $this->assertEmpty($productConcreteIds);
    }

    /**
     * @return void
     */
    public function testGetLocalizedProductAbstractNamesByCategoryWillReturnLocalizedProductsNamesByCategory(): void
    {
        // Arrange
        $categoryTransfer = $this->tester->haveCategory();
        $productTransfer = $this->tester->haveFullProduct();
        $localeTransfer = $this->tester->getCurrentLocale();

        $expectedProductName = sprintf(
            '%s (%s)',
            $productTransfer->getLocalizedAttributes()[0]->getName(),
            $productTransfer->getAbstractSku(),
        );

        $this->tester->assignProductToCategory($categoryTransfer->getIdCategory(), $productTransfer->getFkProductAbstract());

        // Act
        $productNames = $this->getProductCategoryFacade()
            ->getLocalizedProductAbstractNamesByCategory($categoryTransfer, $localeTransfer);

        // Arrange
        $this->assertTrue(in_array($expectedProductName, $productNames), 'Localized product name should be found.');
    }

    /**
     * @return void
     */
    public function testGetCategoryTransferCollectionByIdProductAbstractWillReturnCategoriesWithLocalizedAttributesForProvidedLocaleOnly(): void
    {
        // Arrange
        $localeTransferEn = $this->tester->haveLocale([LocaleTransfer::LOCALE_NAME => static::LOCALE_EN]);
        $localeTransferDe = $this->tester->haveLocale([LocaleTransfer::LOCALE_NAME => static::LOCALE_DE]);

        $categoryTransfer = $this->tester->haveCategory();

        $categoryLocalizedAttributesDataEn = (new CategoryLocalizedAttributesBuilder())->build()->toArray();
        $categoryLocalizedAttributesDataEn[LocalizedAttributesTransfer::LOCALE] = $localeTransferEn;
        $categoryLocalizedAttributesTransferEn = $this->tester->haveCategoryLocalizedAttributeForCategory(
            $categoryTransfer->getIdCategory(),
            $categoryLocalizedAttributesDataEn,
        );

        $categoryLocalizedAttributesDataDe = (new CategoryLocalizedAttributesBuilder())->build()->toArray();
        $categoryLocalizedAttributesDataDe[LocalizedAttributesTransfer::LOCALE] = $localeTransferDe;
        $this->tester->haveCategoryLocalizedAttributeForCategory(
            $categoryTransfer->getIdCategory(),
            $categoryLocalizedAttributesDataDe,
        );

        $productAbstractTransfer = $this->tester->haveProductAbstract();
        $this->tester->assignProductToCategory($categoryTransfer->getIdCategory(), $productAbstractTransfer->getIdProductAbstract());

        // Act
        $categoryCollectionTransfer = $this->getProductCategoryFacade()->getCategoryTransferCollectionByIdProductAbstract(
            $productAbstractTransfer->getIdProductAbstract(),
            $localeTransferEn,
        );

        // Assert
        $this->assertCount(1, $categoryCollectionTransfer->getCategories());

        /** @var \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer */
        $categoryTransfer = $categoryCollectionTransfer->getCategories()->offsetGet(0);
        $this->assertCount(1, $categoryTransfer->getLocalizedAttributes());

        /** @var \Generated\Shared\Transfer\CategoryLocalizedAttributesTransfer $categoryLocalizedAttributesTransfer */
        $categoryLocalizedAttributesTransfer = $categoryTransfer->getLocalizedAttributes()->offsetGet(0);
        $this->assertSame($localeTransferEn->getIdLocale(), $categoryLocalizedAttributesTransfer->getLocale()->getIdLocale());
        $this->assertSame($categoryLocalizedAttributesTransferEn->getName(), $categoryLocalizedAttributesTransfer->getName());
    }

    /**
     * @return \Spryker\Zed\ProductCategory\Business\ProductCategoryFacadeInterface
     */
    public function getProductCategoryFacade(): ProductCategoryFacadeInterface
    {
        return $this->tester->getLocator()->productCategory()->facade();
    }

    /**
     * @return void
     */
    public function testExpandProductConcreteTransfersWithProductCategories()
    {
        // Arrange
        $localeTransferEn = $this->tester->haveLocale([LocaleTransfer::LOCALE_NAME => static::LOCALE_EN]);
        $localeTransferDe = $this->tester->haveLocale([LocaleTransfer::LOCALE_NAME => static::LOCALE_DE]);

        $categoryTransfer = $this->tester->haveCategory();

        $categoryLocalizedAttributesDataEn = (new CategoryLocalizedAttributesBuilder())->build()->toArray();
        $categoryLocalizedAttributesDataEn[LocalizedAttributesTransfer::LOCALE] = $localeTransferEn;
        $this->tester->haveCategoryLocalizedAttributeForCategory(
            $categoryTransfer->getIdCategory(),
            $categoryLocalizedAttributesDataEn,
        );

        $categoryLocalizedAttributesDataDe = (new CategoryLocalizedAttributesBuilder())->build()->toArray();
        $categoryLocalizedAttributesDataDe[LocalizedAttributesTransfer::LOCALE] = $localeTransferDe;
        $this->tester->haveCategoryLocalizedAttributeForCategory(
            $categoryTransfer->getIdCategory(),
            $categoryLocalizedAttributesDataDe,
        );

        $productTransfer = $this->tester->haveProduct();
        $this->tester->assignProductToCategory($categoryTransfer->getIdCategory(), $productTransfer->getFkProductAbstract());

        // Act
        $productTransfers = $this->getProductCategoryFacade()->expandProductConcreteTransfersWithProductCategories([$productTransfer]);

        // Assert
        $this->assertNotEmpty($productTransfers);
        $this->assertSame(
            $productTransfers[0]->getProductCategories()[0]->getCategory()->getIdCategory(),
            $categoryTransfer->getIdCategory(),
        );
    }
}
