<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOption;

use Codeception\Actor;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CountryTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ProductOptionTransfer;
use Generated\Shared\Transfer\ProductOptionValueTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Country\Persistence\SpyCountry;
use Orm\Zed\Country\Persistence\SpyCountryQuery;
use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Orm\Zed\ProductOption\Persistence\SpyProductOptionValue;
use Orm\Zed\ProductOption\Persistence\SpyProductOptionValuePrice;
use Orm\Zed\ProductOption\Persistence\SpyProductOptionValuePriceQuery;
use Orm\Zed\ProductOption\Persistence\SpyProductOptionValueQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemOption;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemOptionQuery;
use Orm\Zed\Tax\Persistence\SpyTaxRate;
use Orm\Zed\Tax\Persistence\SpyTaxSet;
use Orm\Zed\Tax\Persistence\SpyTaxSetTax;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\ProductOption\Communication\Plugin\Checkout\ProductOptionOrderSaverPlugin;
use Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToCurrencyFacadeBridge;
use Spryker\Zed\ProductOption\ProductOptionDependencyProvider;
use SprykerTest\Shared\ProductOption\Helper\ProductOptionGroupDataHelper;

/**
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 * @method \Spryker\Zed\ProductOption\Business\ProductOptionFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class ProductOptionBusinessTester extends Actor
{
    use _generated\ProductOptionBusinessTesterActions;

    /**
     * @var string
     */
    protected const SERVICE_CURRENCY = 'currency';

    /**
     * @var string
     */
    protected const DEFAULT_CURRENCY = 'EUR';

    /**
     * @return void
     */
    public function addDependencies(): void
    {
        $this->getContainer()->set(static::SERVICE_CURRENCY, static::DEFAULT_CURRENCY);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOptionValueTransfer $productOptionValueTransfer
     * @param int|null $idStore
     * @param int $idCurrency
     * @param int $netPrice
     * @param int $grossPrice
     *
     * @return void
     */
    public function addPrice(ProductOptionValueTransfer $productOptionValueTransfer, ?int $idStore, int $idCurrency, int $netPrice, int $grossPrice): void
    {
        $productOptionValueTransfer->addPrice(
            (new MoneyValueTransfer())
                ->setFkStore($idStore)
                ->setFkCurrency($idCurrency)
                ->setNetAmount($netPrice)
                ->setGrossAmount($grossPrice),
        );
    }

    /**
     * @param int $idProductOptionGroup
     *
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionValuePrice
     */
    public function getFirstProductOptionValuePriceByIdProductOptionGroup(int $idProductOptionGroup): SpyProductOptionValuePrice
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
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionValuePrice|null
     */
    public function getFirstProductOptionValuePriceByIdProductOptionValue(int $idProductOptionValue): ?SpyProductOptionValuePrice
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
    public function createTaxSet(string $iso2Code, int $taxRate): SpyTaxSet
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
     * @param int $idProductOptionValue
     *
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionValue
     */
    public function findOneProductOptionValueById(int $idProductOptionValue): SpyProductOptionValue
    {
        return SpyProductOptionValueQuery::create()
            ->findOneByIdProductOptionValue($idProductOptionValue);
    }

    /**
     * @param string $sku
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstract
     */
    public function createProductAbstract(string $sku): SpyProductAbstract
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
    public function createAddressTransfer(string $iso2Code): AddressTransfer
    {
        $addressTransfer = new AddressTransfer();
        $addressTransfer->setIso2Code($iso2Code);

        return $addressTransfer;
    }

    /**
     * @param \Spryker\Zed\Currency\Business\CurrencyFacade|\PHPUnit\Framework\MockObject\MockObject $currencyFacade
     *
     * @return void
     */
    public function setDependencyCurrencyFacade($currencyFacade): void
    {
        $this->setDependency(
            ProductOptionDependencyProvider::FACADE_CURRENCY,
            new ProductOptionToCurrencyFacadeBridge($currencyFacade),
        );
    }

    /**
     * @param string $iso2Code
     *
     * @return \Generated\Shared\Transfer\CountryTransfer
     */
    protected function haveCountryWithIso2Code(string $iso2Code): CountryTransfer
    {
        $countryEntity = SpyCountryQuery::create()->filterByIso2Code($iso2Code)->findOne();

        if ($countryEntity === null) {
            $countryEntity = new SpyCountry();
            $countryEntity->setIso2Code($iso2Code);
            $countryEntity->save();
        }

        $countryTransfer = (new CountryTransfer())->fromArray($countryEntity->toArray(), true);

        return $countryTransfer;
    }

    /**
     * @param string $iso2Code
     *
     * @return int
     */
    public function getCountryIdByIso2Code(string $iso2Code): int
    {
        return $this->haveCountryWithIso2Code($iso2Code)->getIdCountry();
    }

    /**
     * @param int $idSalesOrderItem
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemOption
     */
    public function createSalesOrderItemOption(int $idSalesOrderItem): SpySalesOrderItemOption
    {
        $salesOrderItemOptionEntity = (new SpySalesOrderItemOption())
            ->setFkSalesOrderItem($idSalesOrderItem)
            ->setGroupName('test group name')
            ->setSku('test sku')
            ->setTaxRate(0)
            ->setValue('test value');
        $salesOrderItemOptionEntity->save();

        return $salesOrderItemOptionEntity;
    }

    /**
     * @return void
     */
    public function ensureSalesOrderItemOptionTableIsEmpty(): void
    {
        $this->ensureDatabaseTableIsEmpty($this->getSalesOrderItemOptionQuery());
    }

    /**
     * @return \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\Sales\Persistence\SpySalesOrderItemOption>
     */
    public function getSalesOrderItemOptionEntities(): ObjectCollection
    {
        return $this->getSalesOrderItemOptionQuery()->find();
    }

    /**
     * @param string $stateMachineProcessName
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function createOrderWithProductOptions(string $stateMachineProcessName): OrderTransfer
    {
        $storeTransfer = $this->haveStore([StoreTransfer::NAME => 'DE', StoreTransfer::DEFAULT_CURRENCY_ISO_CODE => 'EUR']);

        $quoteTransfer = (new QuoteBuilder())
            ->withItem()
            ->withTotals()
            ->withShippingAddress()
            ->withBillingAddress()
            ->withCurrency()
            ->build();

        $quoteTransfer->getItems()
            ->getIterator()
            ->current()
            ->addProductOption($this->createProductOption($storeTransfer));

        $quoteTransfer
            ->setCustomer($this->haveCustomer())
            ->setStore($storeTransfer);

        $saveOrderTransfer = $this->haveOrderFromQuote($quoteTransfer, $stateMachineProcessName, [new ProductOptionOrderSaverPlugin()]);

        return (new OrderTransfer())
            ->setIdSalesOrder($saveOrderTransfer->getIdSalesOrder())
            ->setOrderReference($saveOrderTransfer->getOrderReference())
            ->setStore($quoteTransfer->getStore()->getName())
            ->setCustomer($quoteTransfer->getCustomer())
            ->setItems($saveOrderTransfer->getOrderItems());
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOptionTransfer
     */
    protected function createProductOption(StoreTransfer $storeTransfer): ProductOptionTransfer
    {
        $productOptionGroupTransfer = $this->haveProductOptionGroupWithValues(
            [],
            [
                [
                    [],
                    [
                        [
                            ProductOptionGroupDataHelper::STORE_NAME => $storeTransfer->getName(),
                            MoneyValueTransfer::GROSS_AMOUNT => 123,
                            MoneyValueTransfer::NET_AMOUNT => 123,
                        ],
                    ],
                ],
            ],
        );

        $productOptionTransfer = (new ProductOptionTransfer())
            ->fromArray($productOptionGroupTransfer->getProductOptionValues()[0]->toArray(), true)
            ->setGroupName($productOptionGroupTransfer->getName())
            ->setQuantity(1)
            ->setUnitGrossPrice(123)
            ->setTaxRate(19.0);

        return $productOptionTransfer;
    }

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemOptionQuery
     */
    protected function getSalesOrderItemOptionQuery(): SpySalesOrderItemOptionQuery
    {
        return SpySalesOrderItemOptionQuery::create();
    }
}
