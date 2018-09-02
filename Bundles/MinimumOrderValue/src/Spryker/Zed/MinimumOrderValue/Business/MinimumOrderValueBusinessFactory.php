<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValue\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\MinimumOrderValue\Business\DataSource\MinimumOrderValueDataSourceStrategyResolver;
use Spryker\Zed\MinimumOrderValue\Business\DataSource\MinimumOrderValueDataSourceStrategyResolverInterface;
use Spryker\Zed\MinimumOrderValue\Business\ExpenseCalculator\ExpenseCalculator;
use Spryker\Zed\MinimumOrderValue\Business\ExpenseCalculator\ExpenseCalculatorInterface;
use Spryker\Zed\MinimumOrderValue\Business\ExpenseRemover\ExpenseRemover;
use Spryker\Zed\MinimumOrderValue\Business\ExpenseRemover\ExpenseRemoverInterface;
use Spryker\Zed\MinimumOrderValue\Business\ExpenseSaver\ExpenseSaver;
use Spryker\Zed\MinimumOrderValue\Business\ExpenseSaver\ExpenseSaverInterface;
use Spryker\Zed\MinimumOrderValue\Business\HardThresholdCheck\HardThresholdChecker;
use Spryker\Zed\MinimumOrderValue\Business\HardThresholdCheck\HardThresholdCheckerInterface;
use Spryker\Zed\MinimumOrderValue\Business\Installer\MinimumOrderValueTypeInstaller;
use Spryker\Zed\MinimumOrderValue\Business\Installer\MinimumOrderValueTypeInstallerInterface;
use Spryker\Zed\MinimumOrderValue\Business\MinimumOrderValue\MinimumOrderValueReader;
use Spryker\Zed\MinimumOrderValue\Business\MinimumOrderValue\MinimumOrderValueReaderInterface;
use Spryker\Zed\MinimumOrderValue\Business\MinimumOrderValue\MinimumOrderValueWriter;
use Spryker\Zed\MinimumOrderValue\Business\MinimumOrderValue\MinimumOrderValueWriterInterface;
use Spryker\Zed\MinimumOrderValue\Business\MinimumOrderValueType\MinimumOrderValueTypeReader;
use Spryker\Zed\MinimumOrderValue\Business\MinimumOrderValueType\MinimumOrderValueTypeReaderInterface;
use Spryker\Zed\MinimumOrderValue\Business\Strategy\Resolver\MinimumOrderValueStrategyResolver;
use Spryker\Zed\MinimumOrderValue\Business\Strategy\Resolver\MinimumOrderValueStrategyResolverInterface;
use Spryker\Zed\MinimumOrderValue\Business\TaxRateReader\TaxRateReader;
use Spryker\Zed\MinimumOrderValue\Business\TaxRateReader\TaxRateReaderInterface;
use Spryker\Zed\MinimumOrderValue\Business\ThresholdMessenger\ThresholdMessenger;
use Spryker\Zed\MinimumOrderValue\Business\ThresholdMessenger\ThresholdMessengerInterface;
use Spryker\Zed\MinimumOrderValue\Business\Translation\MinimumOrderValueGlossaryKeyGenerator;
use Spryker\Zed\MinimumOrderValue\Business\Translation\MinimumOrderValueGlossaryKeyGeneratorInterface;
use Spryker\Zed\MinimumOrderValue\Business\Translation\MinimumOrderValueTranslationReader;
use Spryker\Zed\MinimumOrderValue\Business\Translation\MinimumOrderValueTranslationReaderInterface;
use Spryker\Zed\MinimumOrderValue\Business\Translation\MinimumOrderValueTranslationWriter;
use Spryker\Zed\MinimumOrderValue\Business\Translation\MinimumOrderValueTranslationWriterInterface;
use Spryker\Zed\MinimumOrderValue\Dependency\Facade\MinimumOrderValueToGlossaryFacadeInterface;
use Spryker\Zed\MinimumOrderValue\Dependency\Facade\MinimumOrderValueToMessengerFacadeInterface;
use Spryker\Zed\MinimumOrderValue\Dependency\Facade\MinimumOrderValueToMoneyFacadeInterface;
use Spryker\Zed\MinimumOrderValue\Dependency\Facade\MinimumOrderValueToSalesFacadeInterface;
use Spryker\Zed\MinimumOrderValue\Dependency\Facade\MinimumOrderValueToStoreFacadeInterface;
use Spryker\Zed\MinimumOrderValue\Dependency\Facade\MinimumOrderValueToTaxFacadeInterface;
use Spryker\Zed\MinimumOrderValue\MinimumOrderValueDependencyProvider;

/**
 * @method \Spryker\Zed\MinimumOrderValue\Persistence\MinimumOrderValueEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\MinimumOrderValue\Persistence\MinimumOrderValueRepositoryInterface getRepository()
 * @method \Spryker\Zed\MinimumOrderValue\MinimumOrderValueConfig getConfig()
 */
class MinimumOrderValueBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\MinimumOrderValue\Business\Installer\MinimumOrderValueTypeInstallerInterface
     */
    public function createMinimumOrderValueTypeInstaller(): MinimumOrderValueTypeInstallerInterface
    {
        return new MinimumOrderValueTypeInstaller(
            $this->getMinimumOrderValueStrategyPlugins(),
            $this->getEntityManager()
        );
    }

    /**
     * @return \Spryker\Zed\MinimumOrderValue\Business\MinimumOrderValueType\MinimumOrderValueTypeReaderInterface
     */
    public function createMinimumOrderValueTypeReader(): MinimumOrderValueTypeReaderInterface
    {
        return new MinimumOrderValueTypeReader(
            $this->createMinimumOrderValueStrategyResolver(),
            $this->getRepository()
        );
    }

    /**
     * @return \Spryker\Zed\MinimumOrderValue\Business\MinimumOrderValue\MinimumOrderValueReaderInterface
     */
    public function createMinimumOrderValueReader(): MinimumOrderValueReaderInterface
    {
        return new MinimumOrderValueReader(
            $this->getRepository(),
            $this->createMinimumOrderValueTranslationReader()
        );
    }

    /**
     * @return \Spryker\Zed\MinimumOrderValue\Business\MinimumOrderValue\MinimumOrderValueWriterInterface
     */
    public function createMinimumOrderValueWriter(): MinimumOrderValueWriterInterface
    {
        return new MinimumOrderValueWriter(
            $this->createMinimumOrderValueStrategyResolver(),
            $this->getEntityManager(),
            $this->createMinimumOrderValueGlossaryKeyGenerator(),
            $this->createMinimumOrderValueTranslationWriter()
        );
    }

    /**
     * @return \Spryker\Zed\MinimumOrderValue\Business\Translation\MinimumOrderValueGlossaryKeyGeneratorInterface
     */
    public function createMinimumOrderValueGlossaryKeyGenerator(): MinimumOrderValueGlossaryKeyGeneratorInterface
    {
        return new MinimumOrderValueGlossaryKeyGenerator();
    }

    /**
     * @return \Spryker\Zed\MinimumOrderValue\Business\Translation\MinimumOrderValueTranslationReaderInterface
     */
    public function createMinimumOrderValueTranslationReader(): MinimumOrderValueTranslationReaderInterface
    {
        return new MinimumOrderValueTranslationReader(
            $this->getGlossaryFacade(),
            $this->getStoreFacade()
        );
    }

    /**
     * @return \Spryker\Zed\MinimumOrderValue\Business\Translation\MinimumOrderValueTranslationWriterInterface
     */
    public function createMinimumOrderValueTranslationWriter(): MinimumOrderValueTranslationWriterInterface
    {
        return new MinimumOrderValueTranslationWriter(
            $this->getGlossaryFacade()
        );
    }

    /**
     * @return \Spryker\Zed\MinimumOrderValue\Business\Strategy\Resolver\MinimumOrderValueStrategyResolverInterface
     */
    public function createMinimumOrderValueStrategyResolver(): MinimumOrderValueStrategyResolverInterface
    {
        return new MinimumOrderValueStrategyResolver(
            $this->getMinimumOrderValueStrategyPlugins()
        );
    }

    /**
     * @return \Spryker\Zed\MinimumOrderValue\Business\HardThresholdCheck\HardThresholdCheckerInterface
     */
    public function createHardThresholdChecker(): HardThresholdCheckerInterface
    {
        return new HardThresholdChecker(
            $this->createMinimumOrderValueDataSourceStrategyResolver(),
            $this->createMinimumOrderValueStrategyResolver(),
            $this->getMessengerFacade(),
            $this->getMoneyFacade()
        );
    }

    /**
     * @return \Spryker\Zed\MinimumOrderValue\Business\ExpenseSaver\ExpenseSaverInterface
     */
    public function createExpenseSaver(): ExpenseSaverInterface
    {
        return new ExpenseSaver(
            $this->getSalesFacade()
        );
    }

    /**
     * @return \Spryker\Zed\MinimumOrderValue\Business\ThresholdMessenger\ThresholdMessengerInterface
     */
    public function createThresholdMessenger(): ThresholdMessengerInterface
    {
        return new ThresholdMessenger(
            $this->getMessengerFacade(),
            $this->getMoneyFacade(),
            $this->createMinimumOrderValueDataSourceStrategyResolver()
        );
    }

    /**
     * @return \Spryker\Zed\MinimumOrderValue\Business\ExpenseCalculator\ExpenseCalculatorInterface
     */
    public function createExpenseCalculator(): ExpenseCalculatorInterface
    {
        return new ExpenseCalculator(
            $this->createMinimumOrderValueStrategyResolver(),
            $this->createMinimumOrderValueDataSourceStrategyResolver(),
            $this->createTaxRateReader()
        );
    }

    /**
     * @return \Spryker\Zed\MinimumOrderValue\Business\ExpenseRemover\ExpenseRemoverInterface
     */
    public function createExpenseRemover(): ExpenseRemoverInterface
    {
        return new ExpenseRemover();
    }

    /**
     * @return \Spryker\Zed\MinimumOrderValue\Business\DataSource\MinimumOrderValueDataSourceStrategyResolverInterface
     */
    public function createMinimumOrderValueDataSourceStrategyResolver(): MinimumOrderValueDataSourceStrategyResolverInterface
    {
        return new MinimumOrderValueDataSourceStrategyResolver(
            $this->getMinimumOrderValueDataSourceStrategies(),
            $this->createMinimumOrderValueReader()
        );
    }

    /**
     * @return \Spryker\Zed\MinimumOrderValue\Business\TaxRateReader\TaxRateReaderInterface
     */
    public function createTaxRateReader(): TaxRateReaderInterface
    {
        return new TaxRateReader(
            $this->getTaxFacade(),
            $this->getRepository()
        );
    }

    /**
     * @return \Spryker\Zed\MinimumOrderValueExtension\Dependency\Plugin\MinimumOrderValueDataSourceStrategyPluginInterface[]
     */
    public function getMinimumOrderValueDataSourceStrategies(): array
    {
        return $this->getProvidedDependency(MinimumOrderValueDependencyProvider::MINIMUM_ORDER_VALUE_DATA_SOURCE_STRATEGIES);
    }

    /**
     * @return \Spryker\Zed\MinimumOrderValue\Dependency\Facade\MinimumOrderValueToGlossaryFacadeInterface
     */
    public function getGlossaryFacade(): MinimumOrderValueToGlossaryFacadeInterface
    {
        return $this->getProvidedDependency(MinimumOrderValueDependencyProvider::FACADE_GLOSSARY);
    }

    /**
     * @return \Spryker\Zed\MinimumOrderValue\Dependency\Facade\MinimumOrderValueToMoneyFacadeInterface
     */
    public function getMoneyFacade(): MinimumOrderValueToMoneyFacadeInterface
    {
        return $this->getProvidedDependency(MinimumOrderValueDependencyProvider::FACADE_MONEY);
    }

    /**
     * @return \Spryker\Zed\MinimumOrderValue\Dependency\Facade\MinimumOrderValueToStoreFacadeInterface
     */
    public function getStoreFacade(): MinimumOrderValueToStoreFacadeInterface
    {
        return $this->getProvidedDependency(MinimumOrderValueDependencyProvider::FACADE_STORE);
    }

    /**
     * @return \Spryker\Zed\MinimumOrderValue\Dependency\Facade\MinimumOrderValueToMessengerFacadeInterface
     */
    public function getMessengerFacade(): MinimumOrderValueToMessengerFacadeInterface
    {
        return $this->getProvidedDependency(MinimumOrderValueDependencyProvider::FACADE_MESSENGER);
    }

    /**
     * @return \Spryker\Zed\MinimumOrderValue\Dependency\Facade\MinimumOrderValueToTaxFacadeInterface
     */
    public function getTaxFacade(): MinimumOrderValueToTaxFacadeInterface
    {
        return $this->getProvidedDependency(MinimumOrderValueDependencyProvider::FACADE_TAX);
    }

    /**
     * @return \Spryker\Zed\MinimumOrderValueExtension\Dependency\Plugin\MinimumOrderValueStrategyPluginInterface[]
     */
    public function getMinimumOrderValueStrategyPlugins(): array
    {
        return $this->getProvidedDependency(MinimumOrderValueDependencyProvider::PLUGINS_MINIMUM_ORDER_VALUE_STRATEGY);
    }

    /**
     * @return \Spryker\Zed\MinimumOrderValue\Dependency\Facade\MinimumOrderValueToSalesFacadeInterface
     */
    public function getSalesFacade(): MinimumOrderValueToSalesFacadeInterface
    {
        return $this->getProvidedDependency(MinimumOrderValueDependencyProvider::FACADE_SALES);
    }
}
