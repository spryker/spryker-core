<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOption\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductOption\Business\Calculator\ProductOptionTaxRateCalculator;
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
use Spryker\Zed\ProductOption\ProductOptionDependencyProvider;

/**
 * @method \Spryker\Zed\ProductOption\ProductOptionConfig getConfig()
 * @method \Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainerInterface getQueryContainer()
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
            $this->getLocaleFacade()
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
            $this->getEventFacade()
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
            $this->createTranslationSaver()
        );
    }

    /**
     * @return \Spryker\Zed\ProductOption\Business\OptionGroup\TranslationSaverInterface
     */
    protected function createTranslationSaver()
    {
        return new TranslationSaver(
            $this->getGlossaryFacade(),
            $this->getLocaleFacade()
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
            $this->getEventFacade()
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
    public function createPlaceOrderProductOptionOrderSaver()
    {
        return new ProductOptionOrderSaver(
            $this->getGlossaryFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ProductOption\Business\OptionGroup\ProductOptionValueReaderInterface
     */
    public function createProductOptionValueReader()
    {
        return new ProductOptionValueReader(
            $this->createProductOptionValuePriceReader(),
            $this->getQueryContainer()
        );
    }

    /**
     * @return \Spryker\Zed\ProductOption\Business\Calculator\CalculatorInterface
     */
    public function createProductOptionTaxRateCalculator()
    {
        return new ProductOptionTaxRateCalculator($this->getQueryContainer(), $this->getTaxFacade());
    }

    /**
     * @return \Spryker\Zed\ProductOption\Business\OptionGroup\ProductOptionOrderHydrateInterface
     */
    public function createProductOptionOrderHydrate()
    {
        return new ProductOptionOrderHydrate($this->getQueryContainer());
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
            $this->getPriceFacade()
        );
    }
}
