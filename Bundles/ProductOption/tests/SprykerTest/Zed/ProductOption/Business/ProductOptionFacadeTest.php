<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOption\Business;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductOptionGroupTransfer;
use Generated\Shared\Transfer\ProductOptionTransfer;
use Generated\Shared\Transfer\ProductOptionTranslationTransfer;
use Generated\Shared\Transfer\ProductOptionValueTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Orm\Zed\Country\Persistence\SpyCountryQuery;
use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Orm\Zed\ProductOption\Persistence\SpyProductOptionGroupQuery;
use Orm\Zed\ProductOption\Persistence\SpyProductOptionValueQuery;
use Orm\Zed\Tax\Persistence\SpyTaxRate;
use Orm\Zed\Tax\Persistence\SpyTaxSet;
use Orm\Zed\Tax\Persistence\SpyTaxSetTax;
use Spryker\Zed\ProductOption\Business\ProductOptionFacade;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group ProductOption
 * @group Business
 * @group Facade
 * @group ProductOptionFacadeTest
 * Add your own group annotations below this line
 */
class ProductOptionFacadeTest extends Unit
{
    const DEFAULT_LOCALE_ISO_CODE = 'en_US';

    const DEFAULT_ID_CURRENCY = 5;
    const DEFAULT_ID_STORE = null;
    const DEFAULT_NET_PRICE = 100;
    const DEFAULT_GROSS_PRICE = 200;

    /**
     * @var \SprykerTest\Zed\ProductOption\ProductOptionBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testSaveProductOptionGroupShouldPersistProvidedOption()
    {
        $productOptionFacade = $this->createProductOptionFacade();

        $productOptionValueTransfer = $this->createProductOptionValueTransfer();
        $productOptionGroupTransfer = $this->createProductOptionGroupTransfer($productOptionValueTransfer);
        $productOptionGroupTransfer->addProductOptionValue($productOptionValueTransfer);

        $idOfProductOptionGroup = $productOptionFacade->saveProductOptionGroup($productOptionGroupTransfer);

        $productOptionGroupEntity = SpyProductOptionGroupQuery::create()
            ->findOneByIdProductOptionGroup($idOfProductOptionGroup);

        $this->assertNotEmpty($idOfProductOptionGroup);
        $this->assertEquals($productOptionGroupTransfer->getName(), $productOptionGroupEntity->getName());
        $this->assertSame($productOptionGroupTransfer->getActive(), $productOptionGroupEntity->getActive());

        $productOptionValues = $productOptionGroupEntity->getSpyProductOptionValues();
        $productOptionValueEntity = $productOptionValues[0];

        $this->assertEquals($productOptionValueTransfer->getValue(), $productOptionValueEntity->getValue());
        $this->assertEquals($productOptionValueTransfer->getSku(), $productOptionValueEntity->getSku());
    }

    /**
     * @return void
     */
    public function testSaveProductOptionGroupUpdatesCurrencyPrices()
    {
        // Assign
        $expectedNetResult = 5;
        $expectedGrossResult = 6;
        $productOptionFacade = $this->createProductOptionFacade();

        $productOptionValueTransfer = $this->createProductOptionValueTransfer();
        $productOptionGroupTransfer = $this->createProductOptionGroupTransfer($productOptionValueTransfer);
        $productOptionGroupTransfer->addProductOptionValue($productOptionValueTransfer);
        $productOptionFacade->saveProductOptionGroup($productOptionGroupTransfer);

        $productOptionValueTransfer->setPrices(new ArrayObject());
        $this->tester->addPrice(
            $productOptionValueTransfer,
            static::DEFAULT_ID_STORE,
            static::DEFAULT_ID_CURRENCY,
            $expectedNetResult,
            $expectedGrossResult
        );

        // Act
        $idProductOptionGroup = $productOptionFacade->saveProductOptionGroup($productOptionGroupTransfer);

        // Assert
        $productOptionPriceEntity = $this->tester->getFirstProductOptionValueByIdProductOptionGroup($idProductOptionGroup);
        $actualNetPrice = $productOptionPriceEntity->getNetPrice();
        $actualGrossPrice = $productOptionPriceEntity->getGrossPrice();

        $this->assertSame($expectedNetResult, $actualNetPrice);
        $this->assertSame($expectedGrossResult, $actualGrossPrice);
    }

    /**
     * @return void
     */
    public function testSaveProductOptionGroupInsertsNewCurrencyPrices()
    {
        // Assign
        $expectedNetResult = static::DEFAULT_NET_PRICE;
        $expectedGrossResult = static::DEFAULT_GROSS_PRICE;
        $productOptionFacade = $this->createProductOptionFacade();

        $productOptionValueTransfer = $this->createProductOptionValueTransfer();
        $productOptionGroupTransfer = $this->createProductOptionGroupTransfer($productOptionValueTransfer);
        $productOptionGroupTransfer->addProductOptionValue($productOptionValueTransfer);

        // Act
        $idProductOptionGroup = $productOptionFacade->saveProductOptionGroup($productOptionGroupTransfer);

        // Assert
        $productOptionPriceEntity = $this->tester->getFirstProductOptionValueByIdProductOptionGroup($idProductOptionGroup);
        $actualNetPrice = $productOptionPriceEntity->getNetPrice();
        $actualGrossPrice = $productOptionPriceEntity->getGrossPrice();

        $this->assertSame($expectedNetResult, $actualNetPrice);
        $this->assertSame($expectedGrossResult, $actualGrossPrice);
    }

    /**
     * @return void
     */
    public function testSaveProductGroupOptionAndAssignProductAbstract()
    {
        $this->markTestSkipped('ProductAbstract not assigned');

        $productOptionFacade = $this->createProductOptionFacade();

        $productOptionValueTransfer = $this->createProductOptionValueTransfer();
        $productOptionGroupTransfer = $this->createProductOptionGroupTransfer($productOptionValueTransfer);
        $productOptionGroupTransfer->addProductOptionValue($productOptionValueTransfer);

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

        $productOptionValueTransfer = $this->createProductOptionValueTransfer();
        $productOptionGroupTransfer = $this->createProductOptionGroupTransfer($productOptionValueTransfer);
        $productOptionGroupTransfer->addProductOptionValue($productOptionValueTransfer);

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

        $productOptionValueTransfer = $this->createProductOptionValueTransfer();
        $productOptionGroupTransfer = $this->createProductOptionGroupTransfer($productOptionValueTransfer);
        $productOptionGroupTransfer->addProductOptionValue($productOptionValueTransfer);

        $productAbstractEntity = $this->createProductAbstract('testingSku');

        $productOptionGroupTransfer->setProductsToBeAssigned([$productAbstractEntity->getIdProductAbstract()]);

        $idOfProductOptionGroup = $productOptionFacade->saveProductOptionGroup($productOptionGroupTransfer);

        $productOptionGroupTransfer->setProductsToBeAssigned([]);
        $productOptionGroupTransfer->setProductOptionValuesToBeRemoved([$productOptionValueTransfer->getIdProductOptionValue()]);

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
    }

    /**
     * @return void
     */
    public function testGetProductOptionValueShouldReturnPersistedOptionValue()
    {
        $productOptionFacade = $this->createProductOptionFacade();

        $productOptionValueTransfer = $this->createProductOptionValueTransfer();
        $productOptionGroupTransfer = $this->createProductOptionGroupTransfer($productOptionValueTransfer);
        $productOptionGroupTransfer->addProductOptionValue($productOptionValueTransfer);

        $productOptionFacade->saveProductOptionGroup($productOptionGroupTransfer);

        $idOfPersistedOptionValue = $productOptionValueTransfer->getIdProductOptionValue();

        $productOptionTransfer = $productOptionFacade->getProductOptionValueById($idOfPersistedOptionValue);

        $this->assertEquals($idOfPersistedOptionValue, $productOptionTransfer->getIdProductOptionValue());
        $this->assertEquals($productOptionValueTransfer->getValue(), $productOptionTransfer->getValue());
        $this->assertEquals($productOptionValueTransfer->getSku(), $productOptionTransfer->getSku());
    }

    /**
     * @return void
     */
    public function testGetProductOptionByIdShouldReturnPersistedOptionGroup()
    {
        $productOptionFacade = $this->createProductOptionFacade();

        $productOptionValueTransfer = $this->createProductOptionValueTransfer();
        $productOptionGroupTransfer = $this->createProductOptionGroupTransfer($productOptionValueTransfer);
        $productOptionGroupTransfer->addProductOptionValue($productOptionValueTransfer);

        $idOfPersistedOptionGroup = $productOptionFacade->saveProductOptionGroup($productOptionGroupTransfer);

        $persistedProductOptionGroupTransfer = $productOptionFacade->getProductOptionGroupById($idOfPersistedOptionGroup);

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

        $productOptionValueTransfer = $this->createProductOptionValueTransfer();
        $productOptionGroupTransfer = $this->createProductOptionGroupTransfer($productOptionValueTransfer);
        $productOptionGroupTransfer->addProductOptionValue($productOptionValueTransfer);

        $taxSetEntity = $this->createTaxSet($iso2Code, $taxRate);

        $productOptionGroupTransfer->setFkTaxSet($taxSetEntity->getIdTaxSet());

        $productOptionFacade->saveProductOptionGroup($productOptionGroupTransfer);

        $quoteTransfer = new QuoteTransfer();

        $addressTransfer = new AddressTransfer();
        $addressTransfer->setIso2Code($iso2Code);
        $quoteTransfer->setShippingAddress($addressTransfer);

        $productOptionTransfer = new ProductOptionTransfer();
        $productOptionTransfer->setIdProductOptionValue($productOptionValueTransfer->getIdProductOptionValue());

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

        $productOptionValueTransfer = $this->createProductOptionValueTransfer();
        $productOptionGroupTransfer = $this->createProductOptionGroupTransfer($productOptionValueTransfer);
        $productOptionGroupTransfer->addProductOptionValue($productOptionValueTransfer);

        $idOfPersistedOptionGroup = $productOptionFacade->saveProductOptionGroup($productOptionGroupTransfer);

        $productOptionFacade->toggleOptionActive($idOfPersistedOptionGroup, 1);

        $productOptionGroupEntity = SpyProductOptionGroupQuery::create()
            ->findOneByIdProductOptionGroup($idOfPersistedOptionGroup);

        $this->assertTrue($productOptionGroupEntity->getActive());

        $productOptionFacade->toggleOptionActive($idOfPersistedOptionGroup, 0);

        $productOptionGroupEntity = SpyProductOptionGroupQuery::create()
            ->findOneByIdProductOptionGroup($idOfPersistedOptionGroup);

        $this->assertFalse($productOptionGroupEntity->getActive());
    }

    /**
     * @return void
     */
    public function testProductAbstractToProductOptionGroupShouldAddNewProductToGroup()
    {
        $productOptionFacade = $this->createProductOptionFacade();

        $productOptionValueTransfer = $this->createProductOptionValueTransfer();
        $productOptionGroupTransfer = $this->createProductOptionGroupTransfer($productOptionValueTransfer);
        $productOptionGroupTransfer->addProductOptionValue($productOptionValueTransfer);

        $idOfPersistedOptionGroup = $productOptionFacade->saveProductOptionGroup($productOptionGroupTransfer);

        $testSku = 'testing-sku';
        $productAbstractEntity = $this->createProductAbstract($testSku);

        $productOptionFacade->addProductAbstractToProductOptionGroup(
            $productAbstractEntity->getSku(),
            $idOfPersistedOptionGroup
        );

        $groupProducts = SpyProductOptionGroupQuery::create()
            ->findOneByIdProductOptionGroup($idOfPersistedOptionGroup);

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
     * @param string $sku
     *
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
        $productOptionValueTransfer = new ProductOptionValueTransfer();
        $productOptionValueTransfer->setValue('value.translation.key');
        $productOptionValueTransfer->setPrices(new ArrayObject());
        $productOptionValueTransfer->setSku('sku_for_testing');

        $this->tester->addPrice(
            $productOptionValueTransfer,
            static::DEFAULT_ID_STORE,
            static::DEFAULT_ID_CURRENCY,
            static::DEFAULT_NET_PRICE,
            static::DEFAULT_GROSS_PRICE
        );

        return $productOptionValueTransfer;
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
