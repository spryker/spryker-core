<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Product\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\LocalizedAttributesTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteCollectionTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductCriteriaTransfer;
use Generated\Shared\Transfer\ProductUrlCriteriaFilterTransfer;
use Spryker\Zed\Product\Business\Exception\ProductConcreteExistsException;
use Spryker\Zed\Product\Business\Product\Sku\SkuGenerator;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Product
 * @group Business
 * @group Facade
 * @group ProductFacadeTest
 * Add your own group annotations below this line
 */
class ProductFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Product\ProductBusinessTester
     */
    protected $tester;

    /**
     * @var array<string, string>
     */
    protected const PRODUCT_NAME = [
        self::LOCALE_NAME_EN => 'Product name en_US',
        self::LOCALE_NAME_DE => 'Product name de_DE',
    ];

    /**
     * @var string
     */
    protected const LOCALE_NAME_DE = 'de_DE';

    /**
     * @var string
     */
    protected const LOCALE_NAME_EN = 'en_US';

    /**
     * @var string
     */
    protected const SKU_1 = 'test-sku1';

    /**
     * @var string
     */
    protected const SKU_2 = 'test-sku2';

    /**
     * @var string
     */
    protected const LOCALIZED_ATTRIBUTE_NAME = 'name';

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->setUpDatabase();
    }

    /**
     * @return void
     */
    public function testGenerateProductConcreteSku(): void
    {
        $sku = $this->tester->getFacade()->generateProductConcreteSku(
            $this->createProductAbstractTransfer(),
            $this->createProductConcreteTransfer(),
        );

        $this->assertSame($this->getExpectedProductConcreteSku(), $sku);
    }

    /**
     * @return void
     */
    public function testGetProductConcreteTransfersByProductIdsRetrievesAllSpecifiedProductconcreteAsTransferWithId(): void
    {
        $productConcreteIds = $this->tester->getProductConcreteIds();

        $this->assertTrue(count($productConcreteIds) > 0);
        $productConcreteTransfers = $this->tester->getProductFacade()->getProductConcreteTransfersByProductIds($productConcreteIds);
        $this->assertSame(count($productConcreteIds), count($productConcreteTransfers));

        foreach ($productConcreteTransfers as $productConcreteTransfer) {
            $this->assertInstanceOf(ProductConcreteTransfer::class, $productConcreteTransfer);
            $this->assertContains($productConcreteTransfer->getIdProductConcrete(), $productConcreteIds);
        }
    }

    /**
     * @return void
     */
    public function testGetProductConcreteTransfersByProductAbstractIds(): void
    {
        $productAbstractIds = $this->tester->getProductAbstractIds();

        $this->assertTrue(count($productAbstractIds) > 0);
        $productConcreteTransfers = $this->tester->getProductFacade()->getProductConcreteTransfersByProductAbstractIds($productAbstractIds);

        foreach ($productConcreteTransfers as $productConcreteTransfer) {
            $this->assertInstanceOf(ProductConcreteTransfer::class, $productConcreteTransfer);
            $this->assertContains($productConcreteTransfer->getFkProductAbstract(), $productAbstractIds);
        }
    }

    /**
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    protected function createProductAbstractTransfer(): ProductAbstractTransfer
    {
        $productAbstractTransfer = new ProductAbstractTransfer();
        $productAbstractTransfer->setSku('abstract_sku');

        return $productAbstractTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected function createProductConcreteTransfer(): ProductConcreteTransfer
    {
        $productConcreteTransfer = new ProductConcreteTransfer();
        $productConcreteTransfer->setAttributes([
            'processor_frequency' => '4 GHz',
            'processor_cache' => '12 MB',
        ]);

        return $productConcreteTransfer;
    }

    /**
     * @return string
     */
    protected function getExpectedProductConcreteSku(): string
    {
        return 'abstract_sku' .
            SkuGenerator::SKU_ABSTRACT_SEPARATOR .
            'processor_frequency' .
            SkuGenerator::SKU_TYPE_SEPARATOR .
            '4GHz' .
            SkuGenerator::SKU_VALUE_SEPARATOR .
            'processor_cache' .
            SkuGenerator::SKU_TYPE_SEPARATOR .
            '12MB';
    }

    /**
     * @return void
     */
    public function testGetProductUrlsByOneProductAbstractIdAndLocale(): void
    {
        // Arrange
        $this->tester->createProductUrls();

        $idProductAbstract = $this->tester->getProductAbstractIds()[0];

        $productUrlCriteriaFilterTransfer = (new ProductUrlCriteriaFilterTransfer())
            ->setProductAbstractIds([$idProductAbstract])
            ->setIdLocale($this->tester->getLocaleFacade()->getCurrentLocale()->getIdLocale());

        $correctUrl = $this->tester->getProductUrl($idProductAbstract, $this->tester->getLocaleFacade()->getCurrentLocale()->getLocaleName());

        // Act
        $productUrls = $this->tester->getProductFacade()->getProductUrls($productUrlCriteriaFilterTransfer);

        // Assert
        $this->assertCount(1, $productUrls);
        $this->assertSame($correctUrl, $productUrls[0]->getUrl());
    }

    /**
     * @return void
     */
    public function testGetProductUrlsByLocaleAndWithoutProductAbstractIds(): void
    {
        // Arrange
        $idLocale = $this->tester->getLocaleFacade()->getCurrentLocale()->getIdLocale();
        $this->tester->createProductUrls();

        $expectedProductUrlsCount = $this->tester->getUrlsCount($idLocale);
        $productUrlCriteriaFilterTransfer = (new ProductUrlCriteriaFilterTransfer())->setIdLocale($idLocale);

        // Act
        $productUrls = $this->tester->getProductFacade()->getProductUrls($productUrlCriteriaFilterTransfer);

        // Assert
        $this->assertCount($expectedProductUrlsCount, $productUrls);
    }

    /**
     * @return void
     */
    public function testGetProductUrlsByProductAbstractIdAndWithoutLocale(): void
    {
        // Arrange
        $this->tester->createProductUrls();

        $productUrlCriteriaFilterTransfer = (new ProductUrlCriteriaFilterTransfer())
            ->setProductAbstractIds([$this->tester->getProductAbstractIds()[0]]);

        // Act
        $productUrls = $this->tester->getProductFacade()->getProductUrls($productUrlCriteriaFilterTransfer);

        // Assert
        $this->assertCount(2, $productUrls);
    }

    /**
     * @return void
     */
    public function testGetProductConcretesByCriteria(): void
    {
        // Arrange
        $productConcreteIds = $this->tester->getProductConcreteIds();
        $productConcreteTransfer = $this->tester->getFacade()->findProductConcreteById($productConcreteIds[0]);
        $productCriteriaTransferWithExistingStore = new ProductCriteriaTransfer();
        $productCriteriaTransferWithExistingStore->setIdStore(
            $this->tester->getStoreFacade()->getCurrentStore()->getIdStore(),
        );
        $productCriteriaTransferWithExistingStore->setIsActive(true);
        $productCriteriaTransferWithExistingStore->setSkus([$productConcreteTransfer->getSku()]);

        $productCriteriaTransferWithNotExistingStore = clone $productCriteriaTransferWithExistingStore;
        $productCriteriaTransferWithNotExistingStore->setIdStore(9999);

        // Act
        $productConcreteTransfersWithStore = $this->tester->getFacade()->getProductConcretesByCriteria($productCriteriaTransferWithExistingStore);
        $productConcreteTransfersWithoutStore = $this->tester->getFacade()->getProductConcretesByCriteria($productCriteriaTransferWithNotExistingStore);

        // Assert
        $this->assertCount(1, $productConcreteTransfersWithStore);
        $this->assertCount(0, $productConcreteTransfersWithoutStore);
    }

    /**
     * @return void
     */
    public function testCreateProductConcreteCollectionCreatesConcreteProducts(): void
    {
        // Arrange
        $this->tester->deleteConcreteProductBySkus([static::SKU_1, static::SKU_2]);
        $productAbstractTransfer = $this->tester->haveProductAbstract();
        $expectedProductsNumber = $this->tester->getProductConcreteDatabaseEntriesCount() + 2;
        $productConcreteCollectionTransfer = (new ProductConcreteCollectionTransfer())
            ->addProduct(
                (new ProductConcreteTransfer())
                    ->setSku(static::SKU_1)
                    ->setFkProductAbstract($productAbstractTransfer->getIdProductAbstractOrFail()),
            )->addProduct(
                (new ProductConcreteTransfer())
                    ->setSku(static::SKU_2)
                    ->setFkProductAbstract($productAbstractTransfer->getIdProductAbstractOrFail()),
            );

        // Act
        $this->tester->getFacade()->createProductConcreteCollection($productConcreteCollectionTransfer);

        // Assert
        $this->assertEquals($expectedProductsNumber, $this->tester->getProductConcreteDatabaseEntriesCount());
    }

    /**
     * @return void
     */
    public function testCreateProductConcreteCollectionCreatesLocalizedAttributes(): void
    {
        // Arrange
        $localeTransfer1 = $this->tester->haveLocale();
        $localeTransfer2 = $this->tester->haveLocale();
        $productAbstractTransfer = $this->tester->haveProductAbstract();
        $expectedLocalizedAttributesNumber = $this->tester->countProductLocalizedAttributesByProductBySkus([static::SKU_1]) + 2;
        $productConcreteCollectionTransfer = (new ProductConcreteCollectionTransfer())
            ->addProduct(
                (new ProductConcreteTransfer())
                    ->setSku(static::SKU_1)
                    ->setFkProductAbstract($productAbstractTransfer->getIdProductAbstractOrFail())
                    ->addLocalizedAttributes(
                        (new LocalizedAttributesTransfer())->setLocale($localeTransfer1)->setName(static::LOCALIZED_ATTRIBUTE_NAME),
                    )->addLocalizedAttributes(
                        (new LocalizedAttributesTransfer())->setLocale($localeTransfer2)->setName(static::LOCALIZED_ATTRIBUTE_NAME),
                    ),
            );

        // Act
        $this->tester->getFacade()->createProductConcreteCollection($productConcreteCollectionTransfer);

        // Assert
        $this->assertEquals(
            $expectedLocalizedAttributesNumber,
            $this->tester->countProductLocalizedAttributesByProductBySkus([static::SKU_1]),
        );
    }

    /**
     * @return void
     */
    public function testCreateProductConcreteCollectionThrowsExceptionIfConcreteProductWithTheSameSkuExists(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveProduct();
        $productConcreteCollectionTransfer = (new ProductConcreteCollectionTransfer())
            ->addProduct(
                (new ProductConcreteTransfer())
                    ->setSku($productConcreteTransfer->getSku())
                    ->setFkProductAbstract($productConcreteTransfer->getFkProductAbstractOrFail()),
            );

        // Assert
        $this->expectException(ProductConcreteExistsException::class);

        // Act
        $this->tester->getFacade()->createProductConcreteCollection($productConcreteCollectionTransfer);
    }

    /**
     * @return void
     */
    public function testGetProductAbstractLocalizedAttributeNamesIndexedByIdProductAbstractShouldReturnProductAbstractNamesByGivenIds(): void
    {
        // Arrange
        $productAbstractTransfer1 = $this->tester->haveProductAbstract();
        $productAbstractTransfer2 = $this->tester->haveProductAbstract();

        $localeTransfers = $this->getLocales();

        $localizedAttributeEN = $this->createLocalizedAttributesTransfer(static::PRODUCT_NAME[static::LOCALE_NAME_EN], $localeTransfers[static::LOCALE_NAME_EN]);
        $localizedAttributeDE = $this->createLocalizedAttributesTransfer(static::PRODUCT_NAME[static::LOCALE_NAME_DE], $localeTransfers[static::LOCALE_NAME_DE]);

        $this->tester->addLocalizedAttributesToProductAbstract($productAbstractTransfer1, [$localizedAttributeEN]);
        $this->tester->addLocalizedAttributesToProductAbstract($productAbstractTransfer2, [$localizedAttributeDE]);

        $expectedProductAbstractLocalizedAttributeNames = [];
        $expectedProductAbstractLocalizedAttributeNames[$productAbstractTransfer1->getIdProductAbstract()] = static::PRODUCT_NAME[static::LOCALE_NAME_EN];
        $expectedProductAbstractLocalizedAttributeNames[$productAbstractTransfer2->getIdProductAbstract()] = static::PRODUCT_NAME[static::LOCALE_NAME_DE];

        // Act
        $actualProductAbstractLocalizedAttributeNames = $this->tester->getFacade()->getProductAbstractLocalizedAttributeNamesIndexedByIdProductAbstract([
            $productAbstractTransfer1->getIdProductAbstract(), $productAbstractTransfer2->getIdProductAbstract(),
        ]);

        // Assert
        $this->assertEquals($expectedProductAbstractLocalizedAttributeNames, $actualProductAbstractLocalizedAttributeNames);
    }

    /**
     * @return void
     */
    public function testGetProductAbstractLocalizedAttributeNamesIndexedByIdProductAbstractShouldReturnEmptyArrayWhenProductAbstractDoesNotHaveLocalizedAttributes(): void
    {
        // Arrange
        $this->tester->ensureProductAbstractTableIsEmpty();

        $productAbstractTransfer1 = $this->tester->haveProductAbstract();
        $productAbstractTransfer2 = $this->tester->haveProductAbstract();

        // Act
        $productAbstractLocalizedAttributeNames = $this->tester->getFacade()->getProductAbstractLocalizedAttributeNamesIndexedByIdProductAbstract([
            $productAbstractTransfer1->getIdProductAbstract(), $productAbstractTransfer2->getIdProductAbstract(),
        ]);

        // Assert
        $this->assertEmpty($productAbstractLocalizedAttributeNames);
    }

    /**
     * @param string $localizedAttributeName
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\LocalizedAttributesTransfer
     */
    protected function createLocalizedAttributesTransfer(string $localizedAttributeName, LocaleTransfer $localeTransfer): LocalizedAttributesTransfer
    {
        return (new LocalizedAttributesTransfer())
            ->setName($localizedAttributeName)
            ->setLocale($localeTransfer);
    }

    /**
     * @return array<string, \Generated\Shared\Transfer\LocaleTransfer>
     */
    protected function getLocales(): array
    {
        return [
            static::LOCALE_NAME_DE => $this->tester->haveLocale([LocaleTransfer::LOCALE_NAME => static::LOCALE_NAME_DE]),
            static::LOCALE_NAME_EN => $this->tester->haveLocale([LocaleTransfer::LOCALE_NAME => static::LOCALE_NAME_EN]),
        ];
    }

    /**
     * @return void
     */
    public function testGetProductConcreteTransfersByProductIdsReturnsAttributesArray(): void
    {
        // Arrange
        $productConcreteIds = $this->tester->getProductConcreteIds();
        $expectedIdCount = count($productConcreteIds);

        // Act
        $productConcreteTransfers = $this->tester->getProductFacade()->getProductConcreteTransfersByProductIds($productConcreteIds);

        // Assert
        $this->assertTrue($expectedIdCount > 0);
        $this->assertSame($expectedIdCount, count($productConcreteTransfers));
        foreach ($productConcreteTransfers as $productConcreteTransfer) {
            $this->assertInstanceOf(ProductConcreteTransfer::class, $productConcreteTransfer);
            $this->assertIsArray($productConcreteTransfer->getAttributes());
        }
    }
}
