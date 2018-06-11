<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOption;

use Codeception\Actor;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\ProductOptionValueTransfer;
use Orm\Zed\Country\Persistence\SpyCountryQuery;
use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Orm\Zed\ProductOption\Persistence\SpyProductOptionValuePriceQuery;
use Orm\Zed\ProductOption\Persistence\SpyProductOptionValueQuery;
use Orm\Zed\Tax\Persistence\SpyTaxRate;
use Orm\Zed\Tax\Persistence\SpyTaxSet;
use Orm\Zed\Tax\Persistence\SpyTaxSetTax;
use Propel\Runtime\Propel;
use Pyz\Zed\ProductOption\ProductOptionDependencyProvider;
use Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToCurrencyFacadeBridge;
use Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToStoreFacadeBridge;

/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
 */
class ProductOptionBusinessTester extends Actor
{
    use _generated\ProductOptionBusinessTesterActions;

   /**
    * Define custom actions here
    */

    /**
     * @param \Generated\Shared\Transfer\ProductOptionValueTransfer $productOptionValueTransfer
     * @param int $idStore
     * @param int $idCurrency
     * @param int $netPrice
     * @param int $grossPrice
     *
     * @return void
     */
    public function addPrice(ProductOptionValueTransfer $productOptionValueTransfer, $idStore, $idCurrency, $netPrice, $grossPrice)
    {
        $productOptionValueTransfer->addPrice(
            (new MoneyValueTransfer())
                ->setFkStore($idStore)
                ->setFkCurrency($idCurrency)
                ->setNetAmount($netPrice)
                ->setGrossAmount($grossPrice)
        );
    }

    /**
     * @param int $idProductOptionGroup
     *
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionValuePrice
     */
    public function getFirstProductOptionValuePriceByIdProductOptionGroup($idProductOptionGroup)
    {
        return SpyProductOptionValuePriceQuery::create()
            ->joinProductOptionValue()
            ->useProductOptionValueQuery()
            ->filterByFkProductOptionGroup($idProductOptionGroup)
            ->endUse()
            ->findOne();
    }

    /**
     * @param int $idProductOptionValue
     *
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionValuePrice
     */
    public function getFirstProductOptionValuePriceByIdProductOptionValue($idProductOptionValue)
    {
        return SpyProductOptionValuePriceQuery::create()
            ->filterByFkProductOptionValue($idProductOptionValue)
            ->findOne();
    }

    /**
     * @param string $iso2Code
     * @param int $taxRate
     *
     * @return \Orm\Zed\Tax\Persistence\SpyTaxSet
     */
    public function createTaxSet($iso2Code, $taxRate)
    {
        $countryEntity = SpyCountryQuery::create()->findOneByIso2Code($iso2Code);

        $taxRateEntity = (new SpyTaxRate())
            ->setName('test rate')
            ->setCountry($countryEntity)
            ->setRate($taxRate);
        $taxRateEntity->save();

        $taxSetEntity = (new SpyTaxSet())
            ->setName('test tax set');
        $taxSetEntity->save();

        (new SpyTaxSetTax())
            ->setFkTaxSet($taxSetEntity->getIdTaxSet())
            ->setFkTaxRate($taxRateEntity->getIdTaxRate())
            ->save();

        return $taxSetEntity;
    }

    /**
     * @return void
     */
    public function enablePropelInstancePooling()
    {
        Propel::enableInstancePooling();
    }

    /**
     * @param int $idProductOptionValue
     *
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionValue
     */
    public function findOneProductOptionValueById($idProductOptionValue)
    {
        return SpyProductOptionValueQuery::create()
            ->findOneByIdProductOptionValue($idProductOptionValue);
    }

    /**
     * @param string $sku
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstract
     */
    public function createProductAbstract($sku)
    {
        $productAbstractEntity = new SpyProductAbstract();
        $productAbstractEntity->setSku($sku);
        $productAbstractEntity->setAttributes('');
        $productAbstractEntity->save();

        return $productAbstractEntity;
    }

    /**
     * @param string $iso2Code
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    public function createAddressTransfer($iso2Code)
    {
        $addressTransfer = new AddressTransfer();
        $addressTransfer->setIso2Code($iso2Code);

        return $addressTransfer;
    }

    /**
     * @param \Spryker\Zed\Store\Business\StoreFacade|\PHPUnit_Framework_MockObject_MockObject $storeFacade
     *
     * @return void
     */
    public function setDependencyStoreFacade($storeFacade)
    {
        $this->setDependency(
            ProductOptionDependencyProvider::FACADE_STORE,
            new ProductOptionToStoreFacadeBridge($storeFacade)
        );
    }

    /**
     * @param \Spryker\Zed\Currency\Business\CurrencyFacade|\PHPUnit_Framework_MockObject_MockObject $currencyFacade
     *
     * @return void
     */
    public function setDependencyCurrencyFacade($currencyFacade)
    {
        $this->setDependency(
            ProductOptionDependencyProvider::FACADE_CURRENCY,
            new ProductOptionToCurrencyFacadeBridge($currencyFacade)
        );
    }
}
