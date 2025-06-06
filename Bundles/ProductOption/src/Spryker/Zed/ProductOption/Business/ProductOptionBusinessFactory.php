<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOption\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductOption\Business\Calculator\CalculatorInterface;
use Spryker\Zed\ProductOption\Business\Calculator\ProductOptionTaxRateCalculator;
use Spryker\Zed\ProductOption\Business\Calculator\ProductOptionTaxRateWithItemShipmentTaxRateCalculator;
use Spryker\Zed\ProductOption\Business\Deleter\SalesOrderItemOptionDeleter;
use Spryker\Zed\ProductOption\Business\Deleter\SalesOrderItemOptionDeleterInterface;
use Spryker\Zed\ProductOption\Business\Expander\ProductOptionExpander;
use Spryker\Zed\ProductOption\Business\Expander\ProductOptionExpanderInterface;
use Spryker\Zed\ProductOption\Business\Hydrator\CartReorderItemHydrator;
use Spryker\Zed\ProductOption\Business\Hydrator\CartReorderItemHydratorInterface;
use Spryker\Zed\ProductOption\Business\OptionGroup\AbstractProductOptionSaver;
use Spryker\Zed\ProductOption\Business\OptionGroup\ProductOptionGroupIdHydrator;
use Spryker\Zed\ProductOption\Business\OptionGroup\ProductOptionGroupReader;
use Spryker\Zed\ProductOption\Business\OptionGroup\ProductOptionGroupSaver;
use Spryker\Zed\ProductOption\Business\OptionGroup\ProductOptionItemSorter;
use Spryker\Zed\ProductOption\Business\OptionGroup\ProductOptionOrderHydrate;
use Spryker\Zed\ProductOption\Business\OptionGroup\ProductOptionOrderSaver as ObsoleteProductOptionOrderSaver;
use Spryker\Zed\ProductOption\Business\OptionGroup\ProductOptionValuePriceHydrator;
use Spryker\Zed\ProductOption\Business\OptionGroup\ProductOptionValuePriceReader;
use Spryker\Zed\ProductOption\Business\OptionGroup\ProductOptionValuePriceSaver;
use Spryker\Zed\ProductOption\Business\OptionGroup\ProductOptionValueReader;
use Spryker\Zed\ProductOption\Business\OptionGroup\ProductOptionValueSaver;
use Spryker\Zed\ProductOption\Business\OptionGroup\TranslationSaver;
use Spryker\Zed\ProductOption\Business\PlaceOrder\ProductOptionOrderSaver;
use Spryker\Zed\ProductOption\Business\PlaceOrder\ProductOptionOrderSaverInterface;
use Spryker\Zed\ProductOption\Business\Replacer\SalesOrderItemOptionReplacer;
use Spryker\Zed\ProductOption\Business\Replacer\SalesOrderItemOptionReplacerInterface;
use Spryker\Zed\ProductOption\Business\StrategyResolver\TaxRateCalculatorStrategyResolver;
use Spryker\Zed\ProductOption\Business\StrategyResolver\TaxRateCalculatorStrategyResolverInterface;
use Spryker\Zed\ProductOption\ProductOptionDependencyProvider;

/**
 * @method \Spryker\Zed\ProductOption\ProductOptionConfig getConfig()
 * @method \Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductOption\Persistence\ProductOptionRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductOption\Persistence\ProductOptionEntityManagerInterface getEntityManager()
 */
class ProductOptionBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductOption\Business\OptionGroup\ProductOptionGroupReaderInterface
     */
    public function createProductOptionGroupReader()
    {
        return new ProductOptionGroupReader(
            $this->createProductOptionValuePriceHydrator(),
            $this->getQueryContainer(),
            $this->getGlossaryFacade(),
            $this->getLocaleFacade(),
            $this->getProductOptionGroupExpanderPlugins(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductOption\Business\OptionGroup\ProductOptionGroupSaverInterface
     */
    public function createProductOptionGroupSaver()
    {
        return new ProductOptionGroupSaver(
            $this->getQueryContainer(),
            $this->getTouchFacade(),
            $this->createTranslationSaver(),
            $this->createAbstractProductOptionSaver(),
            $this->createProductOptionValueSaver(),
            $this->getProductOptionValuesPreRemovePlugins(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductOption\Business\OptionGroup\ProductOptionValueSaverInterface
     */
    public function createProductOptionValueSaver()
    {
        return new ProductOptionValueSaver(
            $this->createProductOptionPriceSaver(),
            $this->getQueryContainer(),
            $this->getTouchFacade(),
            $this->createTranslationSaver(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductOption\Business\OptionGroup\TranslationSaverInterface
     */
    protected function createTranslationSaver()
    {
        return new TranslationSaver(
            $this->getGlossaryFacade(),
            $this->getLocaleFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductOption\Business\OptionGroup\AbstractProductOptionSaverInterface
     */
    public function createAbstractProductOptionSaver()
    {
        return new AbstractProductOptionSaver(
            $this->getQueryContainer(),
            $this->getTouchFacade(),
            $this->getEventFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductOption\Business\OptionGroup\ProductOptionOrderSaverInterface
     */
    public function createProductOptionOrderSaver()
    {
        return new ObsoleteProductOptionOrderSaver($this->getGlossaryFacade());
    }

    /**
     * @return \Spryker\Zed\ProductOption\Business\PlaceOrder\ProductOptionOrderSaverInterface
     */
    public function createPlaceOrderProductOptionOrderSaver(): ProductOptionOrderSaverInterface
    {
        return new ProductOptionOrderSaver(
            $this->getGlossaryFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductOption\Business\Replacer\SalesOrderItemOptionReplacerInterface
     */
    public function createSalesOrderItemOptionReplacer(): SalesOrderItemOptionReplacerInterface
    {
        return new SalesOrderItemOptionReplacer(
            $this->getEntityManager(),
            $this->createPlaceOrderProductOptionOrderSaver(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductOption\Business\OptionGroup\ProductOptionValueReaderInterface
     */
    public function createProductOptionValueReader()
    {
        return new ProductOptionValueReader(
            $this->createProductOptionValuePriceReader(),
            $this->getQueryContainer(),
        );
    }

    /**
     * @deprecated Use {@link createProductOptionTaxRateWithItemShipmentTaxRateCalculator()} instead.
     *
     * @return \Spryker\Zed\ProductOption\Business\Calculator\CalculatorInterface
     */
    public function createProductOptionTaxRateCalculator()
    {
        return new ProductOptionTaxRateCalculator($this->getQueryContainer(), $this->getTaxFacade(), $this->getStoreFacade());
    }

    /**
     * @return \Spryker\Zed\ProductOption\Business\Calculator\CalculatorInterface
     */
    public function createProductOptionTaxRateWithItemShipmentTaxRateCalculator(): CalculatorInterface
    {
        return new ProductOptionTaxRateWithItemShipmentTaxRateCalculator(
            $this->getQueryContainer(),
            $this->getTaxFacade(),
            $this->getStoreFacade(),
        );
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return \Spryker\Zed\ProductOption\Business\OptionGroup\ProductOptionOrderHydrateInterface
     */
    public function createProductOptionOrderHydrate()
    {
        return new ProductOptionOrderHydrate($this->getQueryContainer());
    }

    /**
     * @return \Spryker\Zed\ProductOption\Business\Expander\ProductOptionExpanderInterface
     */
    public function createProductOptionExpander(): ProductOptionExpanderInterface
    {
        return new ProductOptionExpander($this->getRepository());
    }

    /**
     * @return \Spryker\Zed\ProductOption\Business\Hydrator\CartReorderItemHydratorInterface
     */
    public function createCartReorderItemHydrator(): CartReorderItemHydratorInterface
    {
        return new CartReorderItemHydrator();
    }

    /**
     * @return \Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToLocaleFacadeInterface
     */
    protected function getLocaleFacade()
    {
        return $this->getProvidedDependency(ProductOptionDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToCurrencyFacadeInterface
     */
    protected function getCurrencyFacade()
    {
        return $this->getProvidedDependency(ProductOptionDependencyProvider::FACADE_CURRENCY);
    }

    /**
     * @return \Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToStoreFacadeInterface
     */
    protected function getStoreFacade()
    {
        return $this->getProvidedDependency(ProductOptionDependencyProvider::FACADE_STORE);
    }

    /**
     * @return \Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToTouchFacadeInterface
     */
    protected function getTouchFacade()
    {
        return $this->getProvidedDependency(ProductOptionDependencyProvider::FACADE_TOUCH);
    }

    /**
     * @return \Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToTaxFacadeInterface
     */
    protected function getTaxFacade()
    {
        return $this->getProvidedDependency(ProductOptionDependencyProvider::FACADE_TAX);
    }

    /**
     * @return \Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToGlossaryFacadeInterface
     */
    protected function getGlossaryFacade()
    {
        return $this->getProvidedDependency(ProductOptionDependencyProvider::FACADE_GLOSSARY);
    }

    /**
     * @return \Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToPriceFacadeInterface
     */
    protected function getPriceFacade()
    {
        return $this->getProvidedDependency(ProductOptionDependencyProvider::FACADE_PRICE);
    }

    /**
     * @return \Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToEventFacadeInterface
     */
    protected function getEventFacade()
    {
        return $this->getProvidedDependency(ProductOptionDependencyProvider::FACADE_EVENT);
    }

    /**
     * @deprecated Not used anymore.
     *
     * @return \Spryker\Zed\ProductOption\Business\OptionGroup\ProductOptionItemSorterInterface
     */
    public function createProductOptionItemSorter()
    {
        return new ProductOptionItemSorter();
    }

    /**
     * @return \Spryker\Zed\ProductOption\Business\OptionGroup\ProductOptionGroupIdHydratorInterface
     */
    public function createProductOptionGroupIdHydrator()
    {
        return new ProductOptionGroupIdHydrator($this->getQueryContainer());
    }

    /**
     * @return \Spryker\Zed\ProductOption\Business\OptionGroup\ProductOptionValuePriceSaverInterface
     */
    protected function createProductOptionPriceSaver()
    {
        return new ProductOptionValuePriceSaver($this->getQueryContainer());
    }

    /**
     * @return \Spryker\Zed\ProductOption\Business\OptionGroup\ProductOptionValuePriceHydratorInterface
     */
    protected function createProductOptionValuePriceHydrator()
    {
        return new ProductOptionValuePriceHydrator($this->getCurrencyFacade());
    }

    /**
     * @return \Spryker\Zed\ProductOption\Business\OptionGroup\ProductOptionValuePriceReaderInterface
     */
    public function createProductOptionValuePriceReader()
    {
        return new ProductOptionValuePriceReader(
            $this->getCurrencyFacade(),
            $this->getStoreFacade(),
            $this->getPriceFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductOption\Business\Deleter\SalesOrderItemOptionDeleterInterface
     */
    public function createSalesOrderItemOptionDeleter(): SalesOrderItemOptionDeleterInterface
    {
        return new SalesOrderItemOptionDeleter($this->getEntityManager());
    }

    /**
     * @return array<\Spryker\Zed\ProductOptionExtension\Dependency\Plugin\ProductOptionValuesPreRemovePluginInterface>
     */
    protected function getProductOptionValuesPreRemovePlugins(): array
    {
        return $this->getProvidedDependency(ProductOptionDependencyProvider::PLUGINS_PRODUCT_OPTION_VALUES_PRE_REMOVE);
    }

    /**
     * @deprecated Exists for Backward Compatibility reasons only. Use $this->createProductOptionTaxRateWithItemShipmentTaxRateCalculator() instead.
     *
     * @return \Spryker\Zed\ProductOption\Business\StrategyResolver\TaxRateCalculatorStrategyResolverInterface
     */
    public function createProductItemTaxRateCalculatorStrategyResolver(): TaxRateCalculatorStrategyResolverInterface
    {
        $strategyContainer = [];

        $strategyContainer[TaxRateCalculatorStrategyResolver::STRATEGY_KEY_WITHOUT_MULTI_SHIPMENT] = function () {
            return $this->createProductOptionTaxRateCalculator();
        };

        $strategyContainer[TaxRateCalculatorStrategyResolver::STRATEGY_KEY_WITH_MULTI_SHIPMENT] = function () {
            return $this->createProductOptionTaxRateWithItemShipmentTaxRateCalculator();
        };

        return new TaxRateCalculatorStrategyResolver($strategyContainer);
    }

    /**
     * @return array<\Spryker\Zed\ProductOptionExtension\Dependency\Plugin\ProductOptionGroupExpanderPluginInterface>
     */
    public function getProductOptionGroupExpanderPlugins(): array
    {
        return $this->getProvidedDependency(ProductOptionDependencyProvider::PLUGINS_PRODUCT_OPTION_GROUP_EXPANDER);
    }
}
