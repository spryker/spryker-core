<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOption\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductOption\Business\Calculator\ProductOptionTaxRateCalculator;
use Spryker\Zed\ProductOption\Business\OptionGroup\AbstractProductOptionSaver;
use Spryker\Zed\ProductOption\Business\OptionGroup\ProductOptionGroupReader;
use Spryker\Zed\ProductOption\Business\OptionGroup\ProductOptionGroupSaver;
use Spryker\Zed\ProductOption\Business\OptionGroup\ProductOptionOrderHydrate;
use Spryker\Zed\ProductOption\Business\OptionGroup\ProductOptionOrderSaver;
use Spryker\Zed\ProductOption\Business\OptionGroup\ProductOptionValueReader;
use Spryker\Zed\ProductOption\Business\OptionGroup\ProductOptionValueSaver;
use Spryker\Zed\ProductOption\Business\OptionGroup\TranslationSaver;
use Spryker\Zed\ProductOption\ProductOptionDependencyProvider;

/**
 * @method \Spryker\Zed\ProductOption\ProductOptionConfig getConfig()
 * @method \Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainer getQueryContainer()
 */
class ProductOptionBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\ProductOption\Business\OptionGroup\ProductOptionGroupReaderInterface
     */
    public function createProductOptionGroupReader()
    {
        return new ProductOptionGroupReader(
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
            $this->createProductOptionValueSaver()
        );
    }

    /**
     * @return \Spryker\Zed\ProductOption\Business\OptionGroup\ProductOptionValueSaverInterface
     */
    public function createProductOptionValueSaver()
    {
        return new ProductOptionValueSaver(
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
            $this->getTouchFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ProductOption\Business\OptionGroup\ProductOptionOrderSaverInterface
     */
    public function createProductOptionOrderSaver()
    {
        return new ProductOptionOrderSaver($this->getGlossaryFacade());
    }

    /**
     * @return \Spryker\Zed\ProductOption\Business\OptionGroup\ProductOptionValueReaderInterface
     */
    public function createProductOptionValueReader()
    {
        return new ProductOptionValueReader($this->getQueryContainer());
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
     * @return \Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToLocaleInterface
     */
    protected function getLocaleFacade()
    {
        return $this->getProvidedDependency(ProductOptionDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToTouchInterface
     */
    protected function getTouchFacade()
    {
        return $this->getProvidedDependency(ProductOptionDependencyProvider::FACADE_TOUCH);
    }

    /**
     * @return \Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToTaxInterface
     */
    protected function getTaxFacade()
    {
        return $this->getProvidedDependency(ProductOptionDependencyProvider::FACADE_TAX);
    }

    /**
     * @return \Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToGlossaryInterface
     */
    protected function getGlossaryFacade()
    {
        return $this->getProvidedDependency(ProductOptionDependencyProvider::FACADE_GLOSSARY);
    }

}
