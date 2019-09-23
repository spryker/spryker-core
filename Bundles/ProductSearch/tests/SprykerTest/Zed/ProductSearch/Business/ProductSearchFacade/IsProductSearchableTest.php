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
 * @group IsProductSearchableTest
 * Add your own group annotations below this line
 */
class IsProductSearchableTest extends Unit
{
    /**
     * @var \Spryker\Zed\ProductSearch\Business\ProductSearchFacade
     */
    protected $productSearchFacade;

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->productSearchFacade = new ProductSearchFacade();
    }

    /**
     * @return void
     */
    public function testProductAbstractIsSearchableShouldReturnTrueIfAnyVariantIsSearchable()
    {
        $productAbstractEntity = $this->createProductAbstract();
        $localeTransfer = $this->createLocale('aa_AA');

        // make first variant searchable
        $this->createProductSearchEntity(
            $productAbstractEntity->getSpyProducts()[0]->getIdProduct(),
            $localeTransfer->getIdLocale(),
            true
        );

        // make second variant not searchable
        $this->createProductSearchEntity(
            $productAbstractEntity->getSpyProducts()[1]->getIdProduct(),
            $localeTransfer->getIdLocale(),
            false
        );

        $isSearchable = $this->productSearchFacade->isProductAbstractSearchable($productAbstractEntity->getIdProductAbstract(), $localeTransfer);

        $this->assertTrue($isSearchable);
    }

    /**
     * @return void
     */
    public function testProductAbstractIsSearchableShouldReturnFalseIfNoVariantIsSearchable()
    {
        $productAbstractEntity = $this->createProductAbstract();
        $localeTransfer = $this->createLocale('aa_AA');

        // make first variant not searchable
        $this->createProductSearchEntity(
            $productAbstractEntity->getSpyProducts()[0]->getIdProduct(),
            $localeTransfer->getIdLocale(),
            false
        );

        $isSearchable = $this->productSearchFacade->isProductAbstractSearchable($productAbstractEntity->getIdProductAbstract(), $localeTransfer);

        $this->assertFalse($isSearchable);
    }

    /**
     * @return void
     */
    public function testProductConcreteIsSearchableShouldReturnTrueIfSearchable()
    {
        $productAbstractEntity = $this->createProductAbstract();
        $localeTransfer = $this->createLocale('aa_AA');
        $productConcreteEntity = $productAbstractEntity->getSpyProducts()[0];

        $this->createProductSearchEntity($productConcreteEntity->getIdProduct(), $localeTransfer->getIdLocale(), true);

        $isSearchable = $this->productSearchFacade->isProductConcreteSearchable($productConcreteEntity->getIdProduct(), $localeTransfer);

        $this->assertTrue($isSearchable);
    }

    /**
     * @return void
     */
    public function testProductConcreteIsSearchableShouldReturnFalseIfNotSearchable()
    {
        $productAbstractEntity = $this->createProductAbstract();
        $localeTransfer = $this->createLocale('aa_AA');
        $productConcreteEntity = $productAbstractEntity->getSpyProducts()[0];

        $this->createProductSearchEntity($productConcreteEntity->getIdProduct(), $localeTransfer->getIdLocale(), false);

        $isSearchable = $this->productSearchFacade->isProductConcreteSearchable($productConcreteEntity->getIdProduct(), $localeTransfer);

        $this->assertFalse($isSearchable);
    }

    /**
     * @return void
     */
    public function testPersistProductSearchShouldSaveCorrectDataToDatabase()
    {
        $productAbstractEntity = $this->createProductAbstract();
        $productConcreteEntity = $productAbstractEntity->getSpyProducts()[0];

        $localeTransfer1 = $this->createLocale('aa_AA');
        $localeTransfer2 = $this->createLocale('bb_BB');

        $productConcreteTransfer = new ProductConcreteTransfer();
        $productConcreteTransfer->setIdProductConcrete($productConcreteEntity->getIdProduct());
        $productConcreteTransfer->addLocalizedAttributes(
            (new LocalizedAttributesTransfer())
                ->setIsSearchable(true)
                ->setLocale($localeTransfer1)
        );
        $productConcreteTransfer->addLocalizedAttributes(
            (new LocalizedAttributesTransfer())
                ->setIsSearchable(false)
                ->setLocale($localeTransfer2)
        );

        $this->productSearchFacade->persistProductSearch($productConcreteTransfer);

        $isSearchable1 = $this->productSearchFacade->isProductConcreteSearchable($productConcreteEntity->getIdProduct(), $localeTransfer1);
        $isSearchable2 = $this->productSearchFacade->isProductConcreteSearchable($productConcreteEntity->getIdProduct(), $localeTransfer2);

        $this->assertTrue($isSearchable1);
        $this->assertFalse($isSearchable2);
    }

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstract
     */
    protected function createProductAbstract()
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
    protected function createProductConcrete($sku)
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
    protected function createLocale($localeName)
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
    protected function createProductSearchEntity($idProduct, $idLocale, $isSearchable)
    {
        $productSearchEntity = new SpyProductSearch();

        $productSearchEntity
            ->setFkProduct($idProduct)
            ->setFkLocale($idLocale)
            ->setIsSearchable($isSearchable)
            ->save();
    }
}
