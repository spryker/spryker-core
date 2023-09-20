<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductImage\Business\Facade;

use Generated\Shared\DataBuilder\ProductImageBuilder;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\ProductImageSetConditionsTransfer;
use Generated\Shared\Transfer\ProductImageSetCriteriaTransfer;
use Generated\Shared\Transfer\ProductImageSetTransfer;
use Generated\Shared\Transfer\ProductImageTransfer;
use Generated\Shared\Transfer\SortTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductImage
 * @group Business
 * @group Facade
 * @group GetConcreteProductImageSetCollectionTest
 * Add your own group annotations below this line
 */
class GetConcreteProductImageSetCollectionTest extends AbstractProductImageFacadeTest
{
    /**
     * @return void
     */
    public function testGetConcreteProductImageSetCollectionRetrievesProductImageSets(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveProduct();
        $this->tester->haveProductImageSet([
            ProductImageSetTransfer::ID_PRODUCT => $productConcreteTransfer->getIdProductConcrete(),
        ]);

        // Act
        $productImageSetCollectionTransfer = $this->productImageFacade
            ->getConcreteProductImageSetCollection(new ProductImageSetCriteriaTransfer());

        // Assert
        $this->assertNotEmpty($productImageSetCollectionTransfer->getProductImageSets());
    }

    /**
     * @return void
     */
    public function testGetConcreteProductImageSetCollectionShouldNotReturnAbstractProductImageSets(): void
    {
        // Arrange
        $productAbstractTransfer = $this->tester->haveProductAbstract();
        $this->tester->haveProductImageSet([
            ProductImageSetTransfer::ID_PRODUCT_ABSTRACT => $productAbstractTransfer->getIdProductAbstract(),
        ]);

        $productImageSetCriteriaTransfer = (new ProductImageSetCriteriaTransfer())->setProductImageSetConditions(
            (new ProductImageSetConditionsTransfer())->addSku($productAbstractTransfer->getSku()),
        );

        // Act
        $productImageSetCollectionTransfer = $this->productImageFacade
            ->getConcreteProductImageSetCollection($productImageSetCriteriaTransfer);

        // Assert
        $this->assertEmpty($productImageSetCollectionTransfer->getProductImageSets());
    }

    /**
     * @return void
     */
    public function testGetConcreteProductImageSetCollectionRetrievesProductImageSetsFilteredBySku(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveProduct();
        $this->tester->haveProductImageSet([
            ProductImageSetTransfer::ID_PRODUCT => $productConcreteTransfer->getIdProductConcrete(),
        ]);

        $productImageSetCriteriaTransfer = (new ProductImageSetCriteriaTransfer())->setProductImageSetConditions(
            (new ProductImageSetConditionsTransfer())->addSku($productConcreteTransfer->getSku()),
        );

        // Act
        $productImageSetCollectionTransfer = $this->productImageFacade
            ->getConcreteProductImageSetCollection($productImageSetCriteriaTransfer);

        // Assert
        $this->assertCount(1, $productImageSetCollectionTransfer->getProductImageSets());
    }

    /**
     * @return void
     */
    public function testGetConcreteProductImageSetCollectionRetrievesProductImageSetsFilteredByLocaleName(): void
    {
        // Arrange
        $localeTransfer = $this->tester->haveLocale(['localeName' => 'de_DE']);
        $localeTransfer2 = $this->tester->haveLocale(['localeName' => 'en_US']);

        $firstProductConcreteTransfer = $this->tester->haveProduct();
        $this->tester->haveProductImageSet([
            ProductImageSetTransfer::ID_PRODUCT => $firstProductConcreteTransfer->getIdProductConcrete(),
            ProductImageSetTransfer::LOCALE => $localeTransfer,
        ]);
        $secondProductConcreteTransfer = $this->tester->haveProduct();
        $this->tester->haveProductImageSet([
            ProductImageSetTransfer::ID_PRODUCT => $secondProductConcreteTransfer->getIdProductConcrete(),
            ProductImageSetTransfer::LOCALE => $localeTransfer2,
        ]);

        $productImageSetConditionsTransfer = (new ProductImageSetConditionsTransfer())
            ->addSku($firstProductConcreteTransfer->getSku())
            ->addSku($secondProductConcreteTransfer->getSku())
            ->addLocaleName($localeTransfer->getLocaleName());

        $productImageSetCriteriaTransfer = (new ProductImageSetCriteriaTransfer())
            ->setProductImageSetConditions($productImageSetConditionsTransfer);

        // Act
        $productImageSetCollectionTransfer = $this->productImageFacade
            ->getConcreteProductImageSetCollection($productImageSetCriteriaTransfer);

        // Assert
        $this->assertCount(1, $productImageSetCollectionTransfer->getProductImageSets());
    }

    /**
     * @return void
     */
    public function testGetConcreteProductImageSetCollectionRetrievesProductImageSetsFilteredByLocaleId(): void
    {
        // Arrange
        $this->tester->ensureProductImageSetDatabaseTablesAreEmpty();

        $localeTransfer = $this->tester->haveLocale([LocaleTransfer::LOCALE_NAME => static::LOCALE_DE_DE]);
        $localeTransfer2 = $this->tester->haveLocale([LocaleTransfer::LOCALE_NAME => static::LOCALE_EN_EN]);

        $firstProductConcreteTransfer = $this->tester->haveProduct();
        $this->tester->haveProductImageSet([
            ProductImageSetTransfer::ID_PRODUCT => $firstProductConcreteTransfer->getIdProductConcrete(),
            ProductImageSetTransfer::LOCALE => $localeTransfer,
        ]);
        $secondProductConcreteTransfer = $this->tester->haveProduct();
        $this->tester->haveProductImageSet([
            ProductImageSetTransfer::ID_PRODUCT => $secondProductConcreteTransfer->getIdProductConcrete(),
            ProductImageSetTransfer::LOCALE => $localeTransfer2,
        ]);

        $productImageSetConditionsTransfer = (new ProductImageSetConditionsTransfer())
            ->addIdLocale($localeTransfer->getIdLocale());

        $productImageSetCriteriaTransfer = (new ProductImageSetCriteriaTransfer())
            ->setProductImageSetConditions($productImageSetConditionsTransfer);

        // Act
        $productImageSetCollectionTransfer = $this->productImageFacade
            ->getConcreteProductImageSetCollection($productImageSetCriteriaTransfer);

        // Assert
        $this->assertCount(1, $productImageSetCollectionTransfer->getProductImageSets());
        $this->assertSame(
            $localeTransfer->getIdLocale(),
            $productImageSetCollectionTransfer->getProductImageSets()->offsetGet(0)->getLocaleOrFail()->getIdLocale(),
        );
    }

    /**
     * @return void
     */
    public function testGetConcreteProductImageSetCollectionRetrievesProductImageSetsFilteredBySkuWithTwoProductImageSets(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveProduct();
        $this->tester->haveProductImageSet([
            ProductImageSetTransfer::ID_PRODUCT => $productConcreteTransfer->getIdProductConcrete(),
            ProductImageSetTransfer::NAME => 'default',
        ]);
        $this->tester->haveProductImageSet([
            ProductImageSetTransfer::ID_PRODUCT => $productConcreteTransfer->getIdProductConcrete(),
            ProductImageSetTransfer::NAME => 'default',
        ]);

        $productImageSetCriteriaTransfer = (new ProductImageSetCriteriaTransfer())->setProductImageSetConditions(
            (new ProductImageSetConditionsTransfer())->addSku($productConcreteTransfer->getSku()),
        );

        // Act
        $productImageSetCollectionTransfer = $this->productImageFacade
            ->getConcreteProductImageSetCollection($productImageSetCriteriaTransfer);

        // Assert
        $this->assertCount(2, $productImageSetCollectionTransfer->getProductImageSets());
        $this->assertSame('default', $productImageSetCollectionTransfer->getProductImageSets()->offsetGet(0)->getName());
        $this->assertSame('default', $productImageSetCollectionTransfer->getProductImageSets()->offsetGet(1)->getName());
    }

    /**
     * @return void
     */
    public function testGetConcreteProductImageSetCollectionRetrievesProductImageSetsFilteredBySkuWithTwoImagesInside(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveProduct();
        $this->tester->haveProductImageSet([
            ProductImageSetTransfer::ID_PRODUCT => $productConcreteTransfer->getIdProductConcrete(),
            ProductImageSetTransfer::PRODUCT_IMAGES => [
                (new ProductImageBuilder())->build(),
                (new ProductImageBuilder())->build(),
            ],
        ]);

        $productImageSetCriteriaTransfer = (new ProductImageSetCriteriaTransfer())->setProductImageSetConditions(
            (new ProductImageSetConditionsTransfer())->addSku($productConcreteTransfer->getSku()),
        );

        // Act
        $productImageSetCollectionTransfer = $this->productImageFacade
            ->getConcreteProductImageSetCollection($productImageSetCriteriaTransfer);

        // Assert
        $this->assertCount(1, $productImageSetCollectionTransfer->getProductImageSets());
        $this->assertCount(2, $productImageSetCollectionTransfer->getProductImageSets()->getIterator()->current()->getProductImages());
    }

    /**
     * @return void
     */
    public function testGetConcreteProductImageSetCollectionShouldReturnProductImageSetsWithFallbackLocale(): void
    {
        // Arrange
        $this->tester->ensureProductImageSetDatabaseTablesAreEmpty();

        $localeTransfer = $this->tester->haveLocale([LocaleTransfer::LOCALE_NAME => static::LOCALE_DE_DE]);
        $productConcreteTransfer = $this->tester->haveProduct();
        $this->tester->haveProductImageSet([
            ProductImageSetTransfer::ID_PRODUCT => $productConcreteTransfer->getIdProductConcrete(),
            ProductImageSetTransfer::LOCALE => null,
        ]);
        $this->tester->haveProductImageSet([
            ProductImageSetTransfer::ID_PRODUCT => $productConcreteTransfer->getIdProductConcrete(),
            ProductImageSetTransfer::LOCALE => $localeTransfer,
        ]);

        $productImageSetCriteriaTransfer = (new ProductImageSetCriteriaTransfer())
            ->setProductImageSetConditions(
                (new ProductImageSetConditionsTransfer())
                    ->addIdLocale($localeTransfer->getIdLocaleOrFail())
                    ->setAddFallbackLocale(true),
            );

        // Act
        $productImageSetCollectionTransfer = $this->tester->getFacade()
            ->getConcreteProductImageSetCollection($productImageSetCriteriaTransfer);

        // Assert
        $this->assertCount(2, $productImageSetCollectionTransfer->getProductImageSets());
    }

    /**
     * @return void
     */
    public function testGetConcreteProductImageSetCollectionShouldReturnProductImageSetWithIdProductAndSku(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveProduct();
        $this->tester->haveProductImageSet([
            ProductImageSetTransfer::ID_PRODUCT => $productConcreteTransfer->getIdProductConcrete(),
            ProductImageSetTransfer::PRODUCT_IMAGES => [
                (new ProductImageBuilder())->build(),
                (new ProductImageBuilder())->build(),
            ],
        ]);

        $productImageSetCriteriaTransfer = (new ProductImageSetCriteriaTransfer())->setProductImageSetConditions(
            (new ProductImageSetConditionsTransfer())->addSku($productConcreteTransfer->getSku()),
        );

        // Act
        $productImageSetCollectionTransfer = $this->productImageFacade
            ->getConcreteProductImageSetCollection($productImageSetCriteriaTransfer);

        // Assert
        /** @var \Generated\Shared\Transfer\ProductImageSetTransfer $productImageSetTransfer */
        $productImageSetTransfer = $productImageSetCollectionTransfer->getProductImageSets()->getIterator()->current();

        $this->assertSame($productConcreteTransfer->getIdProductConcrete(), $productImageSetTransfer->getIdProduct());
        $this->assertSame($productConcreteTransfer->getSku(), $productImageSetTransfer->getSku());
    }

    /**
     * @return void
     */
    public function testGetConcreteProductImageSetCollectionShouldReturnProductImageSetWithProductImages(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveProduct();
        $this->tester->haveProductImageSet([
            ProductImageSetTransfer::ID_PRODUCT => $productConcreteTransfer->getIdProductConcrete(),
            ProductImageSetTransfer::PRODUCT_IMAGES => [
                (new ProductImageBuilder())->seed([
                    ProductImageTransfer::EXTERNAL_URL_LARGE => 'fake-large-url',
                    ProductImageTransfer::EXTERNAL_URL_SMALL => 'fake-small-url',
                ])->build(),
            ],
        ]);

        $productImageSetCriteriaTransfer = (new ProductImageSetCriteriaTransfer())->setProductImageSetConditions(
            (new ProductImageSetConditionsTransfer())->addSku($productConcreteTransfer->getSku()),
        );

        // Act
        $productImageSetCollectionTransfer = $this->productImageFacade
            ->getConcreteProductImageSetCollection($productImageSetCriteriaTransfer);

        // Assert
        /** @var \Generated\Shared\Transfer\ProductImageSetTransfer $productImageSetTransfer */
        $productImageSetTransfer = $productImageSetCollectionTransfer->getProductImageSets()->getIterator()->current();
        /** @var \Generated\Shared\Transfer\ProductImageTransfer $productImageTransfer */
        $productImageTransfer = $productImageSetTransfer->getProductImages()->getIterator()->current();

        $this->assertSame('fake-large-url', $productImageTransfer->getExternalUrlLarge());
        $this->assertSame('fake-small-url', $productImageTransfer->getExternalUrlSmall());
    }

    /**
     * @return void
     */
    public function testGetConcreteProductImageSetCollectionShouldReturnProductImageSetWithLocaleName(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveProduct();
        $this->tester->haveProductImageSet([
            ProductImageSetTransfer::ID_PRODUCT => $productConcreteTransfer->getIdProductConcrete(),
            ProductImageSetTransfer::PRODUCT_IMAGES => [
                (new ProductImageBuilder())->seed([
                    ProductImageTransfer::EXTERNAL_URL_LARGE => 'fake-large-url',
                    ProductImageTransfer::EXTERNAL_URL_SMALL => 'fake-small-url',
                ])->build(),
            ],
        ]);

        $productImageSetCriteriaTransfer = (new ProductImageSetCriteriaTransfer())->setProductImageSetConditions(
            (new ProductImageSetConditionsTransfer())->addSku($productConcreteTransfer->getSku()),
        );

        // Act
        $productImageSetCollectionTransfer = $this->productImageFacade
            ->getConcreteProductImageSetCollection($productImageSetCriteriaTransfer);

        // Assert
        /** @var \Generated\Shared\Transfer\ProductImageSetTransfer $productImageSetTransfer */
        $productImageSetTransfer = $productImageSetCollectionTransfer->getProductImageSets()->getIterator()->current();
        $this->assertNotEmpty($productImageSetTransfer->getLocaleOrFail()->getLocaleName());
    }

    /**
     * @return void
     */
    public function testGetConcreteProductImageSetCollectionShouldReturnSortedCollection(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveProduct();
        $this->tester->haveProductImageSet([
            ProductImageSetTransfer::ID_PRODUCT => $productConcreteTransfer->getIdProductConcrete(),
            ProductImageSetTransfer::NAME => 'bar',
        ]);
        $this->tester->haveProductImageSet([
            ProductImageSetTransfer::ID_PRODUCT => $productConcreteTransfer->getIdProductConcrete(),
            ProductImageSetTransfer::NAME => 'foo',
        ]);

        $sortTransfer = (new SortTransfer())
            ->setField(ProductImageSetTransfer::NAME)
            ->setIsAscending(false);

        $productImageSetConditionsTransfer = (new ProductImageSetConditionsTransfer())
            ->addSku($productConcreteTransfer->getSku());

        $productImageSetCriteriaTransfer = (new ProductImageSetCriteriaTransfer())
            ->addSort($sortTransfer)
            ->setProductImageSetConditions($productImageSetConditionsTransfer);

        // Act
        $productImageSetCollectionTransfer = $this->productImageFacade
            ->getConcreteProductImageSetCollection($productImageSetCriteriaTransfer);

        // Assert
        $this->assertCount(2, $productImageSetCollectionTransfer->getProductImageSets());
        $this->assertSame('foo', $productImageSetCollectionTransfer->getProductImageSets()->offsetGet(0)->getName());
        $this->assertSame('bar', $productImageSetCollectionTransfer->getProductImageSets()->offsetGet(1)->getName());
    }

    /**
     * @return void
     */
    public function testGetConcreteProductImageSetCollectionShouldReturnPaginatedCollectionByLimitAndOffset(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveProduct();
        $this->tester->haveProductImageSet([
            ProductImageSetTransfer::ID_PRODUCT => $productConcreteTransfer->getIdProductConcrete(),
            ProductImageSetTransfer::NAME => 'bar',
        ]);
        $this->tester->haveProductImageSet([
            ProductImageSetTransfer::ID_PRODUCT => $productConcreteTransfer->getIdProductConcrete(),
            ProductImageSetTransfer::NAME => 'foo',
        ]);

        $productImageSetConditionsTransfer = (new ProductImageSetConditionsTransfer())
            ->addSku($productConcreteTransfer->getSku());

        $productImageSetCriteriaTransfer = (new ProductImageSetCriteriaTransfer())
            ->setPagination((new PaginationTransfer())->setLimit(1)->setOffset(1))
            ->setProductImageSetConditions($productImageSetConditionsTransfer);

        // Act
        $productImageSetCollectionTransfer = $this->productImageFacade
            ->getConcreteProductImageSetCollection($productImageSetCriteriaTransfer);

        // Assert
        $this->assertCount(1, $productImageSetCollectionTransfer->getProductImageSets());
    }

    /**
     * @return void
     */
    public function testGetConcreteProductImageSetCollectionShouldReturnPaginatedCollectionByPageAndMaxPerPage(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveProduct();
        $this->tester->haveProductImageSet([
            ProductImageSetTransfer::ID_PRODUCT => $productConcreteTransfer->getIdProductConcrete(),
            ProductImageSetTransfer::NAME => 'bar',
        ]);
        $this->tester->haveProductImageSet([
            ProductImageSetTransfer::ID_PRODUCT => $productConcreteTransfer->getIdProductConcrete(),
            ProductImageSetTransfer::NAME => 'foo',
        ]);

        $productImageSetConditionsTransfer = (new ProductImageSetConditionsTransfer())
            ->addSku($productConcreteTransfer->getSku());

        $productImageSetCriteriaTransfer = (new ProductImageSetCriteriaTransfer())
            ->setPagination((new PaginationTransfer())->setPage(2)->setMaxPerPage(1))
            ->setProductImageSetConditions($productImageSetConditionsTransfer);

        // Act
        $productImageSetCollectionTransfer = $this->productImageFacade
            ->getConcreteProductImageSetCollection($productImageSetCriteriaTransfer);

        // Assert
        $this->assertCount(1, $productImageSetCollectionTransfer->getProductImageSets());
    }
}
