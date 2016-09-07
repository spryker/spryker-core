<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\ProductOption\Business;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductOptionGroupTransfer;
use Generated\Shared\Transfer\ProductOptionTransfer;
use Generated\Shared\Transfer\ProductOptionTranslationTransfer;
use Generated\Shared\Transfer\ProductOptionValueTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Orm\Zed\Country\Persistence\SpyCountryQuery;
use Orm\Zed\ProductOption\Persistence\Base\SpyProductOptionValueQuery;
use Orm\Zed\ProductOption\Persistence\SpyProductOptionGroupQuery;
use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Orm\Zed\Tax\Persistence\SpyTaxRate;
use Orm\Zed\Tax\Persistence\SpyTaxSet;
use Orm\Zed\Tax\Persistence\SpyTaxSetTax;
use Spryker\Zed\ProductOption\Business\ProductOptionFacade;

/**
 * @group Functional
 * @group Spryker
 * @group Zed
 * @group ProductOption
 * @group Business
 * @group ProductOptionFacadeTest
 */
class ProductOptionFacadeTest extends Test
{

    const DEFAULT_LOCALE_ISO_CODE = 'en_US';

    /**
     * @return void
     */
    public function testSaveProductOptionGroupShouldPersistProvidedOption()
    {
        $productOptionFacade = $this->createProductOptionFacade();

        $producOptionValueTransfer = $this->createProductOptionValueTransfer();
        $productOptionGroupTransfer = $this->createProductOptionGroupTransfer($producOptionValueTransfer);
        $productOptionGroupTransfer->addProductOptionValue($producOptionValueTransfer);

        $idOfProductOptionGroup = $productOptionFacade->saveProductOptionGroup($productOptionGroupTransfer);

        $productOptionGroupEntity = SpyProductOptionGroupQuery::create()
            ->findOneByIdProductOptionGroup($idOfProductOptionGroup);

        $this->assertNotEmpty($idOfProductOptionGroup);
        $this->assertEquals($productOptionGroupTransfer->getName(), $productOptionGroupEntity->getName());
        $this->assertSame($productOptionGroupTransfer->getActive(), $productOptionGroupEntity->getActive());

        $productOptionValues = $productOptionGroupEntity->getSpyProductOptionValues();
        $productOptionValueEntity = $productOptionValues[0];

        $this->assertSame($productOptionValueEntity->getPrice(), $producOptionValueTransfer->getPrice());
        $this->assertEquals($producOptionValueTransfer->getValue(), $productOptionValueEntity->getValue());
        $this->assertEquals($producOptionValueTransfer->getSku(), $productOptionValueEntity->getSku());

    }

    /**
     * @return void
     */
    public function testSaveProductGroupOptionAndAssignProductAbstract()
    {
        $productOptionFacade = $this->createProductOptionFacade();

        $producOptionValueTransfer = $this->createProductOptionValueTransfer();
        $productOptionGroupTransfer = $this->createProductOptionGroupTransfer($producOptionValueTransfer);
        $productOptionGroupTransfer->addProductOptionValue($producOptionValueTransfer);

        $productAbstractEntity = $this->createProductAbstract('testingSku');

        $productOptionGroupTransfer->setProductsToBeAssigned([$productAbstractEntity->getIdProductAbstract()]);

        $idOfProductOptionGroup = $productOptionFacade->saveProductOptionGroup($productOptionGroupTransfer);

        $productOptionGroupEntity = SpyProductOptionGroupQuery::create()
            ->findOneByIdProductOptionGroup($idOfProductOptionGroup);

        $assignedProductAbstractEntity = $productOptionGroupEntity->getSpyProductAbstracts()[0];

        $this->assertEquals($assignedProductAbstractEntity->getSku(), $productAbstractEntity->getSku());
    }

    /**
     * @return void
     */
    public function testSaveProductGroupOptionAndDeAssignProductAbstract()
    {
        $productOptionFacade = $this->createProductOptionFacade();

        $producOptionValueTransfer = $this->createProductOptionValueTransfer();
        $productOptionGroupTransfer = $this->createProductOptionGroupTransfer($producOptionValueTransfer);
        $productOptionGroupTransfer->addProductOptionValue($producOptionValueTransfer);

        $productAbstractEntity = $this->createProductAbstract('testingSku');

        $productOptionGroupTransfer->setProductsToBeAssigned([$productAbstractEntity->getIdProductAbstract()]);

        $idOfProductOptionGroup = $productOptionFacade->saveProductOptionGroup($productOptionGroupTransfer);

        $productOptionGroupTransfer->setProductsToBeAssigned([]);
        $productOptionGroupTransfer->setProductsToBeDeAssigned([$productAbstractEntity->getIdProductAbstract()]);

        $productOptionFacade->saveProductOptionGroup($productOptionGroupTransfer);

        $productOptionGroupEntity = SpyProductOptionGroupQuery::create()
            ->findOneByIdProductOptionGroup($idOfProductOptionGroup);

        $this->assertEmpty($productOptionGroupEntity->getSpyProductAbstracts());
    }

    /**
     * @return void
     */
    public function testSaveProductGroupOptionAndRemoveProductOptionValues()
    {
        $productOptionFacade = $this->createProductOptionFacade();

        $producOptionValueTransfer = $this->createProductOptionValueTransfer();
        $productOptionGroupTransfer = $this->createProductOptionGroupTransfer($producOptionValueTransfer);
        $productOptionGroupTransfer->addProductOptionValue($producOptionValueTransfer);

        $productAbstractEntity = $this->createProductAbstract('testingSku');

        $productOptionGroupTransfer->setProductsToBeAssigned([$productAbstractEntity->getIdProductAbstract()]);

        $idOfProductOptionGroup = $productOptionFacade->saveProductOptionGroup($productOptionGroupTransfer);

        $productOptionGroupTransfer->setProductsToBeAssigned([]);
        $productOptionGroupTransfer->setProductOptionValuesToBeRemoved([$producOptionValueTransfer->getIdProductOptionValue()]);

        $productOptionFacade->saveProductOptionGroup($productOptionGroupTransfer);

        $productOptionGroupEntity = SpyProductOptionGroupQuery::create()
            ->findOneByIdProductOptionGroup($idOfProductOptionGroup);

        $this->assertEmpty($productOptionGroupEntity->getSpyProductOptionValues());
    }

    /**
     * @return void
     */
    public function testSaveOptionValueShouldPersistOption()
    {
        $productOptionFacade = $this->createProductOptionFacade();

        $productOptionGroupTransfer = $this->createProductOptionGroupTransfer();

        $idProductOptionGroup = $productOptionFacade->saveProductOptionGroup($productOptionGroupTransfer);

        $productOptionValueTransfer = $this->createProductOptionValueTransfer();
        $productOptionValueTransfer->setFkProductOptionGroup($idProductOptionGroup);

        $idProductOptionValue = $productOptionFacade->saveProductOptionValue($productOptionValueTransfer);

        $this->assertNotEmpty($idProductOptionGroup);

        $productOptionValueEntity = SpyProductOptionValueQuery::create()
            ->findOneByIdProductOptionValue($idProductOptionValue);

        $this->assertEquals($productOptionValueTransfer->getSku(), $productOptionValueEntity->getSku());
        $this->assertEquals($productOptionValueTransfer->getValue(), $productOptionValueEntity->getValue());
        $this->assertSame($productOptionValueTransfer->getPrice(), $productOptionValueEntity->getPrice());
    }

    /**
     * @return void
     */
    public function testGetProductOptionValueShouldReturnPersistedOptionValue()
    {
        $productOptionFacade = $this->createProductOptionFacade();

        $producOptionValueTransfer = $this->createProductOptionValueTransfer();
        $productOptionGroupTransfer = $this->createProductOptionGroupTransfer($producOptionValueTransfer);
        $productOptionGroupTransfer->addProductOptionValue($producOptionValueTransfer);

        $productOptionFacade->saveProductOptionGroup($productOptionGroupTransfer);

        $idOfPersistedOptionValue = $producOptionValueTransfer->getIdProductOptionValue();

        $productOptionTransfer = $productOptionFacade->getProductOptionValueById($idOfPersistedOptionValue);

        $this->assertEquals($idOfPersistedOptionValue, $productOptionTransfer->getIdProductOptionValue());
        $this->assertEquals($producOptionValueTransfer->getValue(), $productOptionTransfer->getValue());
        $this->assertSame($producOptionValueTransfer->getPrice(), $productOptionTransfer->getUnitGrossPrice());
        $this->assertEquals($producOptionValueTransfer->getSku(), $productOptionTransfer->getSku());
    }


    /**
     * @return void
     */
    public function testGetProductOptionByIdShouldReturnPersistedOptionGroup()
    {
        $productOptionFacade = $this->createProductOptionFacade();

        $producOptionValueTransfer = $this->createProductOptionValueTransfer();
        $productOptionGroupTransfer = $this->createProductOptionGroupTransfer($producOptionValueTransfer);
        $productOptionGroupTransfer->addProductOptionValue($producOptionValueTransfer);

        $idOfPersistendOptionGroup = $productOptionFacade->saveProductOptionGroup($productOptionGroupTransfer);

        $persistedProductOptionGroupTransfer = $productOptionFacade->getProductOptionGroupById($idOfPersistendOptionGroup);

        $this->assertNotEmpty($persistedProductOptionGroupTransfer);
        $this->assertEquals($productOptionGroupTransfer->getName(), $persistedProductOptionGroupTransfer->getName());
    }

    /**
     * @return void
     */
    public function testCalculateTaxRateForProductOptionShouldSetRateToProvidedOptions()
    {
        $iso2Code = 'DE';
        $taxRate = 19;

        $productOptionFacade = $this->createProductOptionFacade();

        $producOptionValueTransfer = $this->createProductOptionValueTransfer();
        $productOptionGroupTransfer = $this->createProductOptionGroupTransfer($producOptionValueTransfer);
        $productOptionGroupTransfer->addProductOptionValue($producOptionValueTransfer);

        $taxSetEntity = $this->createTaxSet($iso2Code, $taxRate);

        $productOptionGroupTransfer->setFkTaxSet($taxSetEntity->getIdTaxSet());

        $productOptionFacade->saveProductOptionGroup($productOptionGroupTransfer);

        $quoteTransfer = new QuoteTransfer();

        $addressTransfer = new AddressTransfer();
        $addressTransfer->setIso2Code($iso2Code);
        $quoteTransfer->setShippingAddress($addressTransfer);

        $productOptionTransfer = new ProductOptionTransfer();
        $productOptionTransfer->setIdProductOptionValue($producOptionValueTransfer->getIdProductOptionValue());

        $itemTransfer = new ItemTransfer();
        $itemTransfer->addProductOption($productOptionTransfer);

        $quoteTransfer->addItem($itemTransfer);

        $productOptionFacade->calculateProductOptionTaxRate($quoteTransfer);

        $itemTransfer = $quoteTransfer->getItems()[0];
        $productOptionTransfer = $itemTransfer->getProductOptions()[0];

        $this->assertEquals($taxRate, $productOptionTransfer->getTaxRate());
    }

    /**
     * @return void
     */
    public function testToggleOptionActiveShouldActivateDeactiveOptionAcordingly()
    {
        $productOptionFacade = $this->createProductOptionFacade();

        $producOptionValueTransfer = $this->createProductOptionValueTransfer();
        $productOptionGroupTransfer = $this->createProductOptionGroupTransfer($producOptionValueTransfer);
        $productOptionGroupTransfer->addProductOptionValue($producOptionValueTransfer);

        $idOfPersistendOptionGroup = $productOptionFacade->saveProductOptionGroup($productOptionGroupTransfer);

        $productOptionFacade->toggleOptionActive($idOfPersistendOptionGroup, 1);

        $productOptionGroupEntity = SpyProductOptionGroupQuery::create()
            ->findOneByIdProductOptionGroup($idOfPersistendOptionGroup);

        $this->assertTrue($productOptionGroupEntity->getActive());

        $productOptionFacade->toggleOptionActive($idOfPersistendOptionGroup, 0);

        $productOptionGroupEntity = SpyProductOptionGroupQuery::create()
            ->findOneByIdProductOptionGroup($idOfPersistendOptionGroup);

        $this->assertFalse($productOptionGroupEntity->getActive());
    }

    /**
     * @return void
     */
    public function testProductAbstractToProductOptionGroupShouldAddNewProductToGroup()
    {
        $productOptionFacade = $this->createProductOptionFacade();

        $producOptionValueTransfer = $this->createProductOptionValueTransfer();
        $productOptionGroupTransfer = $this->createProductOptionGroupTransfer($producOptionValueTransfer);
        $productOptionGroupTransfer->addProductOptionValue($producOptionValueTransfer);

        $idOfPersistendOptionGroup = $productOptionFacade->saveProductOptionGroup($productOptionGroupTransfer);

        $testSku = 'testing-sku';
        $productAbstractEntity = $this->createProductAbstract($testSku);

        $productOptionFacade->addProductAbstractToProductOptionGroup(
            $productAbstractEntity->getSku(),
            $idOfPersistendOptionGroup
        );

        $groupProducts = SpyProductOptionGroupQuery::create()
            ->findOneByIdProductOptionGroup($idOfPersistendOptionGroup);

        $assignedAbstractProducts = $groupProducts->getSpyProductAbstracts();

        $this->assertEquals($assignedAbstractProducts[0]->getSku(), $productAbstractEntity->getSku());
    }

    /**
     * @param string $iso2Code
     * @param int $taxRate
     *
     * @return \Orm\Zed\Tax\Persistence\SpyTaxSet
     */
    protected function createTaxSet($iso2Code, $taxRate)
    {
        $countryEntity = SpyCountryQuery::create()->findOneByIso2Code($iso2Code);

        $taxRateEntity = new SpyTaxRate();
        $taxRateEntity->setName('test rate');
        $taxRateEntity->setCountry($countryEntity);
        $taxRateEntity->setRate($taxRate);
        $taxRateEntity->save();

        $taxSetEntity = new SpyTaxSet();
        $taxSetEntity->setName('test tax set');
        $taxSetEntity->save();

        $taxSetTaxRateEntity = new SpyTaxSetTax();
        $taxSetTaxRateEntity->setFkTaxSet($taxSetEntity->getIdTaxSet());
        $taxSetTaxRateEntity->setFkTaxRate($taxRateEntity->getIdTaxRate());
        $taxSetTaxRateEntity->save();

        return $taxSetEntity;
    }

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstract
     */
    protected function createProductAbstract($sku)
    {
        $productAbstractEntity = new SpyProductAbstract();
        $productAbstractEntity->setSku($sku);
        $productAbstractEntity->setAttributes('');
        $productAbstractEntity->save();

        return $productAbstractEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOptionValueTransfer|null $productOptionValueTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOptionGroupTransfer
     */
    protected function createProductOptionGroupTransfer(ProductOptionValueTransfer $productOptionValueTransfer = null)
    {
        $productOptionGroupTransfer = new ProductOptionGroupTransfer();
        $productOptionGroupTransfer->setName('translation.key');
        $productOptionGroupTransfer->setActive(true);

        $groupNameTranslationTransfer = new ProductOptionTranslationTransfer();
        $groupNameTranslationTransfer->setKey($productOptionGroupTransfer->getName());
        $groupNameTranslationTransfer->setLocaleCode(self::DEFAULT_LOCALE_ISO_CODE);
        $groupNameTranslationTransfer->setName('Translation1');
        $productOptionGroupTransfer->addGroupNameTranslation($groupNameTranslationTransfer);

        if ($productOptionValueTransfer) {
            $productOptionTranslationTransfer = clone $groupNameTranslationTransfer;
            $productOptionTranslationTransfer->setKey($productOptionValueTransfer->getValue());
            $productOptionTranslationTransfer->setName('value translation');
            $productOptionTranslationTransfer->setLocaleCode(self::DEFAULT_LOCALE_ISO_CODE);
            $productOptionGroupTransfer->addProductOptionValueTranslation($productOptionTranslationTransfer);
        }

        return $productOptionGroupTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\ProductOptionValueTransfer
     */
    protected function createProductOptionValueTransfer()
    {
        $producOptionValueTransfer = new ProductOptionValueTransfer();
        $producOptionValueTransfer->setValue('value.translation.key');
        $producOptionValueTransfer->setPrice(1000);
        $producOptionValueTransfer->setSku('sku_for_testing');

        return $producOptionValueTransfer;
    }

    /**
     * @return \Spryker\Zed\ProductOption\Business\ProductOptionFacade
     */
    protected function createProductOptionFacade()
    {
        $productOptionFacade = new ProductOptionFacade();

        return $productOptionFacade;
    }

    /**
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    protected function createLocaleTransfer()
    {
        $localeTransfer = new LocaleTransfer();
        $localeTransfer->setLocaleName(self::DEFAULT_LOCALE_ISO_CODE);

        return $localeTransfer;
    }

}
