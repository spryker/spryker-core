<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Currency\Business;

use Spryker\Shared\Currency\Builder\CurrencyBuilder;
use Spryker\Zed\Currency\Business\Model\CurrencyMapper;
use Spryker\Zed\Currency\Business\Model\CurrencyReader;
use Spryker\Zed\Currency\Business\Model\CurrencyWriter;
use Spryker\Zed\Currency\Business\Reader\CurrencyBulkReader;
use Spryker\Zed\Currency\Business\Reader\CurrencyBulkReaderInterface;
use Spryker\Zed\Currency\Business\Validator\QuoteValidator;
use Spryker\Zed\Currency\Business\Validator\QuoteValidatorInterface;
use Spryker\Zed\Currency\CurrencyDependencyProvider;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\Currency\Persistence\CurrencyQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Currency\CurrencyConfig getConfig()
 * @method \Spryker\Zed\Currency\Persistence\CurrencyRepositoryInterface getRepository()
 */
class CurrencyBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Shared\Currency\Builder\CurrencyBuilderInterface
     */
    public function createCurrencyBuilder()
    {
        return new CurrencyBuilder(
            $this->getInternationalization(),
            $this->getStoreFacade()->getCurrentStore()->getDefaultCurrencyIsoCode(),
            $this->getStoreFacade()->getCurrentStore()->getSelectedCurrencyIsoCode()
        );
    }

    /**
     * @return \Spryker\Zed\Currency\Business\Model\CurrencyReaderInterface
     */
    public function createCurrencyReader()
    {
        return new CurrencyReader(
            $this->getQueryContainer(),
            $this->createCurrencyMapper(),
            $this->getStoreFacade()
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
     * @return \Spryker\Zed\Currency\Business\Model\CurrencyWriterInterface
     */
    public function createCurrencyWriter()
    {
        return new CurrencyWriter($this->createCurrencyMapper());
    }

    /**
     * @return \Spryker\Zed\Currency\Business\Model\CurrencyMapperInterface
     */
    protected function createCurrencyMapper()
    {
        return new CurrencyMapper($this->getInternationalization());
    }

    /**
     * @return \Spryker\Zed\Currency\Dependency\Facade\CurrencyToStoreFacadeInterface
     */
    protected function getStoreFacade()
    {
        return $this->getProvidedDependency(CurrencyDependencyProvider::FACADE_STORE);
    }

    /**
     * @return \Spryker\Shared\Currency\Dependency\Internationalization\CurrencyToInternationalizationInterface
     */
    protected function getInternationalization()
    {
        return $this->getProvidedDependency(CurrencyDependencyProvider::INTERNATIONALIZATION);
    }
}
