<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Currency\Business;

use Spryker\Shared\Currency\Builder\CurrencyBuilder;
use Spryker\Shared\Currency\Builder\CurrencyBuilderInterface;
use Spryker\Shared\Currency\Dependency\Internationalization\CurrencyToInternationalizationInterface;
use Spryker\Zed\Currency\Business\Expander\StoreExpander;
use Spryker\Zed\Currency\Business\Expander\StoreExpanderInterface;
use Spryker\Zed\Currency\Business\Reader\CurrencyBulkReader;
use Spryker\Zed\Currency\Business\Reader\CurrencyBulkReaderInterface;
use Spryker\Zed\Currency\Business\Reader\CurrencyReader;
use Spryker\Zed\Currency\Business\Reader\CurrencyReaderInterface;
use Spryker\Zed\Currency\Business\Validator\QuoteValidator;
use Spryker\Zed\Currency\Business\Validator\QuoteValidatorInterface;
use Spryker\Zed\Currency\Business\Validator\StoreCurrencyValidator;
use Spryker\Zed\Currency\Business\Validator\StoreCurrencyValidatorInterface;
use Spryker\Zed\Currency\Business\Writer\CurrencyStoreWriter;
use Spryker\Zed\Currency\Business\Writer\CurrencyStoreWriterInterface;
use Spryker\Zed\Currency\CurrencyDependencyProvider;
use Spryker\Zed\Currency\Dependency\Facade\CurrencyToStoreFacadeInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\Currency\CurrencyConfig getConfig()
 * @method \Spryker\Zed\Currency\Persistence\CurrencyRepositoryInterface getRepository()
 * @method \Spryker\Zed\Currency\Persistence\CurrencyEntityManagerInterface getEntityManager()
 */
class CurrencyBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Shared\Currency\Builder\CurrencyBuilderInterface
     */
    public function createCurrencyBuilder(): CurrencyBuilderInterface
    {
        return new CurrencyBuilder(
            $this->getInternationalization(),
            $this->getStoreFacade()->getCurrentStore()->getDefaultCurrencyIsoCodeOrFail(),
            $this->getCurrentCurrencyCode(),
        );
    }

    /**
     * @return \Spryker\Zed\Currency\Business\Reader\CurrencyReaderInterface
     */
    public function createCurrencyReader(): CurrencyReaderInterface
    {
        return new CurrencyReader(
            $this->getStoreFacade(),
            $this->getRepository(),
        );
    }

    /**
     * @return \Spryker\Zed\Currency\Business\Reader\CurrencyBulkReaderInterface
     */
    public function createCurrencyBulkReader(): CurrencyBulkReaderInterface
    {
        return new CurrencyBulkReader($this->getRepository());
    }

    /**
     * @return \Spryker\Zed\Currency\Business\Validator\QuoteValidatorInterface
     */
    public function createQuoteValidator(): QuoteValidatorInterface
    {
        return new QuoteValidator($this->getStoreFacade());
    }

    /**
     * @return \Spryker\Zed\Currency\Business\Writer\CurrencyStoreWriterInterface
     */
    public function createCurrencyStoreWriter(): CurrencyStoreWriterInterface
    {
        return new CurrencyStoreWriter(
            $this->getRepository(),
            $this->getEntityManager(),
            $this->getStoreFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\Currency\Business\Expander\StoreExpanderInterface
     */
    public function createStoreExpander(): StoreExpanderInterface
    {
        return new StoreExpander($this->getRepository(), $this->getStoreFacade());
    }

    /**
     * @return \Spryker\Zed\Currency\Dependency\Facade\CurrencyToStoreFacadeInterface
     */
    public function getStoreFacade(): CurrencyToStoreFacadeInterface
    {
        return $this->getProvidedDependency(CurrencyDependencyProvider::FACADE_STORE);
    }

    /**
     * @return \Spryker\Shared\Currency\Dependency\Internationalization\CurrencyToInternationalizationInterface
     */
    public function getInternationalization(): CurrencyToInternationalizationInterface
    {
        return $this->getProvidedDependency(CurrencyDependencyProvider::INTERNATIONALIZATION);
    }

    /**
     * @return string
     */
    public function getCurrentCurrencyCode(): string
    {
        return $this->getProvidedDependency(CurrencyDependencyProvider::CURRENCY_CURRENT);
    }

    /**
     * @return \Spryker\Zed\Currency\Business\Validator\StoreCurrencyValidatorInterface
     */
    public function createStoreCurrencyValidator(): StoreCurrencyValidatorInterface
    {
        return new StoreCurrencyValidator();
    }
}
