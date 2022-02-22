<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductSearch\Business\ProductSearchFacade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\LocalizedAttributesTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Orm\Zed\Locale\Persistence\SpyLocaleQuery;
use Orm\Zed\Product\Persistence\SpyProduct;
use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Orm\Zed\ProductSearch\Persistence\SpyProductSearch;
use Spryker\Zed\ProductSearch\Business\ProductSearchFacade;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductSearch
 * @group Business
 * @group ProductSearchFacade
 * @group ExpandProductConcreteTransfersWithIsSearchableTest
 * Add your own group annotations below this line
 */
class ExpandProductConcreteTransfersWithIsSearchableTest extends Unit
{
    /**
     * @var \Spryker\Zed\ProductSearch\Business\ProductSearchFacade
     */
    protected $productSearchFacade;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->productSearchFacade = new ProductSearchFacade();
    }

    /**
     * @return void
     */
    public function testExpandProductConcreteTransfersWithIsSearchableSuccessful(): void
    {
        // Arrange
        $productAbstractEntitySearchable = $this->createProductAbstract();
        $localeTransfer = $this->createLocale('aa_AA');
        $productConcreteEntity = $productAbstractEntitySearchable->getSpyProducts()[0];
        $this->createProductSearchEntity($productConcreteEntity->getIdProduct(), $localeTransfer->getIdLocale(), true);
        $productConcreteTransferSearchable = $this->mapProductConcreteEntityToProductConcreteTransfers(
            $productConcreteEntity,
            $localeTransfer,
        );

        $productAbstractEntityNotSearchable = $this->createProductAbstract();
        $productConcreteEntity = $productAbstractEntityNotSearchable->getSpyProducts()[1];
        $this->createProductSearchEntity($productConcreteEntity->getIdProduct(), $localeTransfer->getIdLocale(), false);
        $productConcreteTransferNotSearchable = $this->mapProductConcreteEntityToProductConcreteTransfers(
            $productConcreteEntity,
            $localeTransfer,
        );

        // Act
        $productConcreteTransfers = $this->productSearchFacade->expandProductConcreteTransfersWithIsSearchable(
            [$productConcreteTransferSearchable, $productConcreteTransferNotSearchable],
        );

        // Assert
        $this->assertTrue($productConcreteTransfers[0]->getLocalizedAttributes()[0]->getIsSearchableOrFail());
        $this->assertFalse($productConcreteTransfers[1]->getLocalizedAttributes()[0]->getIsSearchableOrFail());
    }

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstract
     */
    protected function createProductAbstract(): SpyProductAbstract
    {
        $sku = uniqid('sku_');

        $productAbstractEntity = new SpyProductAbstract();
        $productAbstractEntity
            ->setSku($sku)
            ->setAttributes('[]')
            ->addSpyProduct($this->createProductConcrete($sku . '-1'))
            ->addSpyProduct($this->createProductConcrete($sku . '-2'));

        $productAbstractEntity->save();

        return $productAbstractEntity;
    }

    /**
     * @param string $sku
     *
     * @return \Orm\Zed\Product\Persistence\SpyProduct
     */
    protected function createProductConcrete(string $sku): SpyProduct
    {
        $productConcreteEntity = new SpyProduct();
        $productConcreteEntity
            ->setSku($sku)
            ->setAttributes('[]');

        return $productConcreteEntity;
    }

    /**
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    protected function createLocale(string $localeName): LocaleTransfer
    {
        $localeEntity = SpyLocaleQuery::create()
            ->filterByLocaleName($localeName)
            ->findOneOrCreate();

        $localeEntity->save();

        $localeTransfer = (new LocaleTransfer())->fromArray($localeEntity->toArray(), true);

        return $localeTransfer;
    }

    /**
     * @param int $idProduct
     * @param int $idLocale
     * @param bool $isSearchable
     *
     * @return void
     */
    protected function createProductSearchEntity(int $idProduct, int $idLocale, bool $isSearchable): void
    {
        $productSearchEntity = new SpyProductSearch();

        $productSearchEntity
            ->setFkProduct($idProduct)
            ->setFkLocale($idLocale)
            ->setIsSearchable($isSearchable)
            ->save();
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProduct $productEntity
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected function mapProductConcreteEntityToProductConcreteTransfers(SpyProduct $productEntity, LocaleTransfer $localeTransfer): ProductConcreteTransfer
    {
        return (new ProductConcreteTransfer())->fromArray($productEntity->toArray(), true)
            ->setIdProductConcrete($productEntity->getIdProduct())
            ->addLocalizedAttributes((new LocalizedAttributesTransfer())->setLocale($localeTransfer));
    }
}
