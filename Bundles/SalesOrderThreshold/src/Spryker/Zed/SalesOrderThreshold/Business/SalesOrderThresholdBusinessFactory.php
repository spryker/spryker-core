<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderThreshold\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\SalesOrderThreshold\Business\DataSource\SalesOrderThresholdDataSourceStrategyResolver;
use Spryker\Zed\SalesOrderThreshold\Business\DataSource\SalesOrderThresholdDataSourceStrategyResolverInterface;
use Spryker\Zed\SalesOrderThreshold\Business\Expander\QuoteExpander;
use Spryker\Zed\SalesOrderThreshold\Business\Expander\QuoteExpanderInterface;
use Spryker\Zed\SalesOrderThreshold\Business\Expander\SalesOrderThresholdValueExpander;
use Spryker\Zed\SalesOrderThreshold\Business\Expander\SalesOrderThresholdValueExpanderInterface;
use Spryker\Zed\SalesOrderThreshold\Business\ExpenseCalculator\ExpenseCalculator;
use Spryker\Zed\SalesOrderThreshold\Business\ExpenseCalculator\ExpenseCalculatorInterface;
use Spryker\Zed\SalesOrderThreshold\Business\ExpenseRemover\ExpenseRemover;
use Spryker\Zed\SalesOrderThreshold\Business\ExpenseRemover\ExpenseRemoverInterface;
use Spryker\Zed\SalesOrderThreshold\Business\ExpenseSaver\ExpenseSaver;
use Spryker\Zed\SalesOrderThreshold\Business\ExpenseSaver\ExpenseSaverInterface;
use Spryker\Zed\SalesOrderThreshold\Business\HardThresholdCheck\HardThresholdChecker;
use Spryker\Zed\SalesOrderThreshold\Business\HardThresholdCheck\HardThresholdCheckerInterface;
use Spryker\Zed\SalesOrderThreshold\Business\Installer\SalesOrderThresholdTypeInstaller;
use Spryker\Zed\SalesOrderThreshold\Business\Installer\SalesOrderThresholdTypeInstallerInterface;
use Spryker\Zed\SalesOrderThreshold\Business\Replacer\SalesOrderThresholdExpensesReplacer;
use Spryker\Zed\SalesOrderThreshold\Business\Replacer\SalesOrderThresholdExpensesReplacerInterface;
use Spryker\Zed\SalesOrderThreshold\Business\SalesOrderThreshold\Reader\SalesOrderThresholdReader;
use Spryker\Zed\SalesOrderThreshold\Business\SalesOrderThreshold\SalesOrderThresholdReaderInterface;
use Spryker\Zed\SalesOrderThreshold\Business\SalesOrderThreshold\SalesOrderThresholdWriter;
use Spryker\Zed\SalesOrderThreshold\Business\SalesOrderThreshold\SalesOrderThresholdWriterInterface;
use Spryker\Zed\SalesOrderThreshold\Business\SalesOrderThresholdType\SalesOrderThresholdTypeReader;
use Spryker\Zed\SalesOrderThreshold\Business\SalesOrderThresholdType\SalesOrderThresholdTypeReaderInterface;
use Spryker\Zed\SalesOrderThreshold\Business\Strategy\Resolver\SalesOrderThresholdStrategyResolver;
use Spryker\Zed\SalesOrderThreshold\Business\Strategy\Resolver\SalesOrderThresholdStrategyResolverInterface;
use Spryker\Zed\SalesOrderThreshold\Business\TaxRateReader\TaxRateReader;
use Spryker\Zed\SalesOrderThreshold\Business\TaxRateReader\TaxRateReaderInterface;
use Spryker\Zed\SalesOrderThreshold\Business\ThresholdMessenger\ThresholdMessenger;
use Spryker\Zed\SalesOrderThreshold\Business\ThresholdMessenger\ThresholdMessengerInterface;
use Spryker\Zed\SalesOrderThreshold\Business\Translation\Hydrator\SalesOrderThresholdTranslationHydrator;
use Spryker\Zed\SalesOrderThreshold\Business\Translation\Hydrator\SalesOrderThresholdTranslationHydratorInterface;
use Spryker\Zed\SalesOrderThreshold\Business\Translation\SalesOrderThresholdGlossaryKeyGenerator;
use Spryker\Zed\SalesOrderThreshold\Business\Translation\SalesOrderThresholdGlossaryKeyGeneratorInterface;
use Spryker\Zed\SalesOrderThreshold\Business\Translation\SalesOrderThresholdTranslationReader;
use Spryker\Zed\SalesOrderThreshold\Business\Translation\SalesOrderThresholdTranslationReaderInterface;
use Spryker\Zed\SalesOrderThreshold\Business\Translation\SalesOrderThresholdTranslationWriter;
use Spryker\Zed\SalesOrderThreshold\Business\Translation\SalesOrderThresholdTranslationWriterInterface;
use Spryker\Zed\SalesOrderThreshold\Business\Validator\SalesOrderThresholdValidator;
use Spryker\Zed\SalesOrderThreshold\Business\Validator\SalesOrderThresholdValidatorInterface;
use Spryker\Zed\SalesOrderThreshold\Dependency\Facade\SalesOrderThresholdToGlossaryFacadeInterface;
use Spryker\Zed\SalesOrderThreshold\Dependency\Facade\SalesOrderThresholdToLocaleFacadeInterface;
use Spryker\Zed\SalesOrderThreshold\Dependency\Facade\SalesOrderThresholdToMessengerFacadeInterface;
use Spryker\Zed\SalesOrderThreshold\Dependency\Facade\SalesOrderThresholdToMoneyFacadeInterface;
use Spryker\Zed\SalesOrderThreshold\Dependency\Facade\SalesOrderThresholdToSalesFacadeInterface;
use Spryker\Zed\SalesOrderThreshold\Dependency\Facade\SalesOrderThresholdToStoreFacadeInterface;
use Spryker\Zed\SalesOrderThreshold\Dependency\Facade\SalesOrderThresholdToTaxFacadeInterface;
use Spryker\Zed\SalesOrderThreshold\SalesOrderThresholdDependencyProvider;

/**
 * @method \Spryker\Zed\SalesOrderThreshold\Persistence\SalesOrderThresholdEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\SalesOrderThreshold\Persistence\SalesOrderThresholdRepositoryInterface getRepository()
 * @method \Spryker\Zed\SalesOrderThreshold\SalesOrderThresholdConfig getConfig()
 */
class SalesOrderThresholdBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\SalesOrderThreshold\Business\Installer\SalesOrderThresholdTypeInstallerInterface
     */
    public function createSalesOrderThresholdTypeInstaller(): SalesOrderThresholdTypeInstallerInterface
    {
        return new SalesOrderThresholdTypeInstaller(
            $this->getSalesOrderThresholdStrategyPlugins(),
            $this->getEntityManager(),
        );
    }

    /**
     * @return \Spryker\Zed\SalesOrderThreshold\Business\SalesOrderThresholdType\SalesOrderThresholdTypeReaderInterface
     */
    public function createSalesOrderThresholdTypeReader(): SalesOrderThresholdTypeReaderInterface
    {
        return new SalesOrderThresholdTypeReader(
            $this->createSalesOrderThresholdStrategyResolver(),
            $this->getRepository(),
        );
    }

    /**
     * @return \Spryker\Zed\SalesOrderThreshold\Business\SalesOrderThreshold\SalesOrderThresholdReaderInterface
     */
    public function createSalesOrderThresholdReader(): SalesOrderThresholdReaderInterface
    {
        return new SalesOrderThresholdReader(
            $this->getRepository(),
            $this->createSalesOrderThresholdTranslationHydrator(),
        );
    }

    /**
     * @return \Spryker\Zed\SalesOrderThreshold\Business\SalesOrderThreshold\SalesOrderThresholdWriterInterface
     */
    public function createSalesOrderThresholdWriter(): SalesOrderThresholdWriterInterface
    {
        return new SalesOrderThresholdWriter(
            $this->createSalesOrderThresholdStrategyResolver(),
            $this->getEntityManager(),
            $this->getRepository(),
            $this->createSalesOrderThresholdGlossaryKeyGenerator(),
            $this->createSalesOrderThresholdTranslationWriter(),
        );
    }

    /**
     * @return \Spryker\Zed\SalesOrderThreshold\Business\Translation\SalesOrderThresholdGlossaryKeyGeneratorInterface
     */
    public function createSalesOrderThresholdGlossaryKeyGenerator(): SalesOrderThresholdGlossaryKeyGeneratorInterface
    {
        return new SalesOrderThresholdGlossaryKeyGenerator();
    }

    /**
     * @return \Spryker\Zed\SalesOrderThreshold\Business\Translation\SalesOrderThresholdTranslationReaderInterface
     */
    public function createSalesOrderThresholdTranslationReader(): SalesOrderThresholdTranslationReaderInterface
    {
        return new SalesOrderThresholdTranslationReader(
            $this->getGlossaryFacade(),
            $this->getStoreFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\SalesOrderThreshold\Business\Translation\SalesOrderThresholdTranslationWriterInterface
     */
    public function createSalesOrderThresholdTranslationWriter(): SalesOrderThresholdTranslationWriterInterface
    {
        return new SalesOrderThresholdTranslationWriter(
            $this->getGlossaryFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\SalesOrderThreshold\Business\Strategy\Resolver\SalesOrderThresholdStrategyResolverInterface
     */
    public function createSalesOrderThresholdStrategyResolver(): SalesOrderThresholdStrategyResolverInterface
    {
        return new SalesOrderThresholdStrategyResolver(
            $this->getSalesOrderThresholdStrategyPlugins(),
        );
    }

    /**
     * @return \Spryker\Zed\SalesOrderThreshold\Business\HardThresholdCheck\HardThresholdCheckerInterface
     */
    public function createHardThresholdChecker(): HardThresholdCheckerInterface
    {
        return new HardThresholdChecker(
            $this->createSalesOrderThresholdDataSourceStrategyResolver(),
            $this->createSalesOrderThresholdStrategyResolver(),
            $this->getMessengerFacade(),
            $this->getMoneyFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\SalesOrderThreshold\Business\ExpenseSaver\ExpenseSaverInterface
     */
    public function createExpenseSaver(): ExpenseSaverInterface
    {
        return new ExpenseSaver(
            $this->getSalesFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\SalesOrderThreshold\Business\ThresholdMessenger\ThresholdMessengerInterface
     */
    public function createThresholdMessenger(): ThresholdMessengerInterface
    {
        return new ThresholdMessenger(
            $this->getMessengerFacade(),
            $this->getMoneyFacade(),
            $this->createSalesOrderThresholdDataSourceStrategyResolver(),
            $this->createSalesOrderThresholdStrategyResolver(),
        );
    }

    /**
     * @return \Spryker\Zed\SalesOrderThreshold\Business\ExpenseCalculator\ExpenseCalculatorInterface
     */
    public function createExpenseCalculator(): ExpenseCalculatorInterface
    {
        return new ExpenseCalculator(
            $this->createSalesOrderThresholdStrategyResolver(),
            $this->createSalesOrderThresholdDataSourceStrategyResolver(),
            $this->createTaxRateReader(),
        );
    }

    /**
     * @return \Spryker\Zed\SalesOrderThreshold\Business\ExpenseRemover\ExpenseRemoverInterface
     */
    public function createExpenseRemover(): ExpenseRemoverInterface
    {
        return new ExpenseRemover();
    }

    /**
     * @return \Spryker\Zed\SalesOrderThreshold\Business\DataSource\SalesOrderThresholdDataSourceStrategyResolverInterface
     */
    public function createSalesOrderThresholdDataSourceStrategyResolver(): SalesOrderThresholdDataSourceStrategyResolverInterface
    {
        return new SalesOrderThresholdDataSourceStrategyResolver(
            $this->getSalesOrderThresholdDataSourceStrategies(),
            $this->createSalesOrderThresholdReader(),
        );
    }

    /**
     * @return \Spryker\Zed\SalesOrderThreshold\Business\TaxRateReader\TaxRateReaderInterface
     */
    public function createTaxRateReader(): TaxRateReaderInterface
    {
        return new TaxRateReader(
            $this->getTaxFacade(),
            $this->getRepository(),
            $this->getStoreFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\SalesOrderThreshold\Business\Translation\Hydrator\SalesOrderThresholdTranslationHydratorInterface
     */
    public function createSalesOrderThresholdTranslationHydrator(): SalesOrderThresholdTranslationHydratorInterface
    {
        return new SalesOrderThresholdTranslationHydrator($this->getGlossaryFacade(), $this->getLocaleFacade());
    }

    /**
     * @return \Spryker\Zed\SalesOrderThreshold\Business\Validator\SalesOrderThresholdValidatorInterface
     */
    public function createSalesOrderThresholdsValidator(): SalesOrderThresholdValidatorInterface
    {
        return new SalesOrderThresholdValidator(
            $this->createSalesOrderThresholdDataSourceStrategyResolver(),
            $this->createSalesOrderThresholdStrategyResolver(),
            $this->getMoneyFacade(),
            $this->getConfig(),
        );
    }

    /**
     * @return \Spryker\Zed\SalesOrderThreshold\Business\Expander\QuoteExpanderInterface
     */
    public function createQuoteExpander(): QuoteExpanderInterface
    {
        return new QuoteExpander(
            $this->createSalesOrderThresholdDataSourceStrategyResolver(),
            $this->createSalesOrderThresholdValueExpander(),
        );
    }

    /**
     * @return \Spryker\Zed\SalesOrderThreshold\Business\Expander\SalesOrderThresholdValueExpanderInterface
     */
    public function createSalesOrderThresholdValueExpander(): SalesOrderThresholdValueExpanderInterface
    {
        return new SalesOrderThresholdValueExpander(
            $this->createSalesOrderThresholdStrategyResolver(),
            $this->getGlossaryFacade(),
            $this->getLocaleFacade(),
            $this->getMoneyFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\SalesOrderThreshold\Business\Replacer\SalesOrderThresholdExpensesReplacerInterface
     */
    public function createSalesOrderThresholdExpensesReplacer(): SalesOrderThresholdExpensesReplacerInterface
    {
        return new SalesOrderThresholdExpensesReplacer($this->createExpenseSaver(), $this->getSalesFacade());
    }

    /**
     * @return array<\Spryker\Zed\SalesOrderThresholdExtension\Dependency\Plugin\SalesOrderThresholdDataSourceStrategyPluginInterface>
     */
    public function getSalesOrderThresholdDataSourceStrategies(): array
    {
        return $this->getProvidedDependency(SalesOrderThresholdDependencyProvider::SALES_ORDER_THRESHOLD_DATA_SOURCE_STRATEGIES);
    }

    /**
     * @return \Spryker\Zed\SalesOrderThreshold\Dependency\Facade\SalesOrderThresholdToGlossaryFacadeInterface
     */
    public function getGlossaryFacade(): SalesOrderThresholdToGlossaryFacadeInterface
    {
        return $this->getProvidedDependency(SalesOrderThresholdDependencyProvider::FACADE_GLOSSARY);
    }

    /**
     * @return \Spryker\Zed\SalesOrderThreshold\Dependency\Facade\SalesOrderThresholdToMoneyFacadeInterface
     */
    public function getMoneyFacade(): SalesOrderThresholdToMoneyFacadeInterface
    {
        return $this->getProvidedDependency(SalesOrderThresholdDependencyProvider::FACADE_MONEY);
    }

    /**
     * @return \Spryker\Zed\SalesOrderThreshold\Dependency\Facade\SalesOrderThresholdToStoreFacadeInterface
     */
    public function getStoreFacade(): SalesOrderThresholdToStoreFacadeInterface
    {
        return $this->getProvidedDependency(SalesOrderThresholdDependencyProvider::FACADE_STORE);
    }

    /**
     * @return \Spryker\Zed\SalesOrderThreshold\Dependency\Facade\SalesOrderThresholdToMessengerFacadeInterface
     */
    public function getMessengerFacade(): SalesOrderThresholdToMessengerFacadeInterface
    {
        return $this->getProvidedDependency(SalesOrderThresholdDependencyProvider::FACADE_MESSENGER);
    }

    /**
     * @return \Spryker\Zed\SalesOrderThreshold\Dependency\Facade\SalesOrderThresholdToTaxFacadeInterface
     */
    public function getTaxFacade(): SalesOrderThresholdToTaxFacadeInterface
    {
        return $this->getProvidedDependency(SalesOrderThresholdDependencyProvider::FACADE_TAX);
    }

    /**
     * @return array<\Spryker\Zed\SalesOrderThresholdExtension\Dependency\Plugin\SalesOrderThresholdStrategyPluginInterface>
     */
    public function getSalesOrderThresholdStrategyPlugins(): array
    {
        return $this->getProvidedDependency(SalesOrderThresholdDependencyProvider::PLUGINS_SALES_ORDER_THRESHOLD_STRATEGY);
    }

    /**
     * @return \Spryker\Zed\SalesOrderThreshold\Dependency\Facade\SalesOrderThresholdToSalesFacadeInterface
     */
    public function getSalesFacade(): SalesOrderThresholdToSalesFacadeInterface
    {
        return $this->getProvidedDependency(SalesOrderThresholdDependencyProvider::FACADE_SALES);
    }

    /**
     * @return \Spryker\Zed\SalesOrderThreshold\Dependency\Facade\SalesOrderThresholdToLocaleFacadeInterface
     */
    public function getLocaleFacade(): SalesOrderThresholdToLocaleFacadeInterface
    {
        return $this->getProvidedDependency(SalesOrderThresholdDependencyProvider::FACADE_LOCALE);
    }
}
