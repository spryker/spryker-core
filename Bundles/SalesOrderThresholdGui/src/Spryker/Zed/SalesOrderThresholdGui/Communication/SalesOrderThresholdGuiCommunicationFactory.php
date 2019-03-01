<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderThresholdGui\Communication;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\SalesOrderThresholdGui\Communication\Form\Constraint\ThresholdStrategyConstraint;
use Spryker\Zed\SalesOrderThresholdGui\Communication\Form\DataProvider\GlobalThresholdDataProvider;
use Spryker\Zed\SalesOrderThresholdGui\Communication\Form\DataProvider\SettingsFormDataProvider;
use Spryker\Zed\SalesOrderThresholdGui\Communication\Form\DataProvider\ThresholdGroup\Resolver\GlobalThresholdDataProviderResolver;
use Spryker\Zed\SalesOrderThresholdGui\Communication\Form\DataProvider\ThresholdGroup\Resolver\GlobalThresholdDataProviderResolverInterface;
use Spryker\Zed\SalesOrderThresholdGui\Communication\Form\GlobalThresholdType;
use Spryker\Zed\SalesOrderThresholdGui\Communication\Form\Mapper\ThresholdGroup\Resolver\GlobalThresholdFormMapperResolver;
use Spryker\Zed\SalesOrderThresholdGui\Communication\Form\Mapper\ThresholdGroup\Resolver\GlobalThresholdFormMapperResolverInterface;
use Spryker\Zed\SalesOrderThresholdGui\Communication\Form\SettingsType;
use Spryker\Zed\SalesOrderThresholdGui\Communication\StoreCurrency\StoreCurrencyFinder;
use Spryker\Zed\SalesOrderThresholdGui\Communication\StoreCurrency\StoreCurrencyFinderInterface;
use Spryker\Zed\SalesOrderThresholdGui\Dependency\Facade\SalesOrderThresholdGuiToCurrencyFacadeInterface;
use Spryker\Zed\SalesOrderThresholdGui\Dependency\Facade\SalesOrderThresholdGuiToLocaleFacadeInterface;
use Spryker\Zed\SalesOrderThresholdGui\Dependency\Facade\SalesOrderThresholdGuiToMoneyFacadeInterface;
use Spryker\Zed\SalesOrderThresholdGui\Dependency\Facade\SalesOrderThresholdGuiToSalesOrderThresholdFacadeInterface;
use Spryker\Zed\SalesOrderThresholdGui\Dependency\Facade\SalesOrderThresholdGuiToStoreFacadeInterface;
use Spryker\Zed\SalesOrderThresholdGui\Dependency\Facade\SalesOrderThresholdGuiToTaxFacadeInterface;
use Spryker\Zed\SalesOrderThresholdGui\SalesOrderThresholdGuiDependencyProvider;
use Symfony\Component\Form\FormInterface;

/**
 * @method \Spryker\Zed\SalesOrderThresholdGui\SalesOrderThresholdGuiConfig getConfig()
 */
class SalesOrderThresholdGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createGlobalThresholdForm(
        StoreTransfer $storeTransfer,
        CurrencyTransfer $currencyTransfer
    ): FormInterface {
        $formDataProvider = $this->createGlobalThresholdFormDataProvider();

        return $this->getFormFactory()->create(
            GlobalThresholdType::class,
            $formDataProvider->getData($storeTransfer, $currencyTransfer),
            $formDataProvider->getOptions($currencyTransfer)
        );
    }

    /**
     * @param \Spryker\Zed\SalesOrderThresholdGui\Communication\Form\DataProvider\SettingsFormDataProvider $settingsFormDataProvider
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getSettingsForm(SettingsFormDataProvider $settingsFormDataProvider): FormInterface
    {
        return $this->getFormFactory()->create(
            SettingsType::class,
            $settingsFormDataProvider->getData(),
            $settingsFormDataProvider->getOptions()
        );
    }

    /**
     * @return \Spryker\Zed\SalesOrderThresholdGui\Communication\Form\DataProvider\SettingsFormDataProvider
     */
    public function createSettingsFromDataProvider(): SettingsFormDataProvider
    {
        return new SettingsFormDataProvider(
            $this->getTaxFacade(),
            $this->getSalesOrderThresholdFacade()
        );
    }

    /**
     * @return \Spryker\Zed\SalesOrderThresholdGui\Communication\Form\DataProvider\GlobalThresholdDataProvider
     */
    public function createGlobalThresholdFormDataProvider(): GlobalThresholdDataProvider
    {
        return new GlobalThresholdDataProvider(
            $this->getSalesOrderThresholdFacade(),
            $this->getCurrencyFacade(),
            $this->createGlobalSoftThresholdDataProviderResolver(),
            $this->getSalesOrderThresholdFormExpanderPlugins()
        );
    }

    /**
     * @return \Spryker\Zed\SalesOrderThresholdGui\Communication\StoreCurrency\StoreCurrencyFinderInterface
     */
    public function createStoreCurrencyFinder(): StoreCurrencyFinderInterface
    {
        return new StoreCurrencyFinder(
            $this->getCurrencyFacade(),
            $this->getStoreFacade()
        );
    }

    /**
     * @return \Spryker\Zed\SalesOrderThresholdGui\Communication\Form\Mapper\ThresholdGroup\Resolver\GlobalThresholdFormMapperResolverInterface
     */
    public function createGlobalThresholdFormMapperResolver(): GlobalThresholdFormMapperResolverInterface
    {
        return new GlobalThresholdFormMapperResolver(
            $this->getLocaleFacade(),
            $this->getConfig(),
            $this->getSalesOrderThresholdFormExpanderPlugins()
        );
    }

    /**
     * @return \Spryker\Zed\SalesOrderThresholdGui\Communication\Form\DataProvider\ThresholdGroup\Resolver\GlobalThresholdDataProviderResolverInterface
     */
    public function createGlobalSoftThresholdDataProviderResolver(): GlobalThresholdDataProviderResolverInterface
    {
        return new GlobalThresholdDataProviderResolver(
            $this->getConfig(),
            $this->getSalesOrderThresholdFormExpanderPlugins()
        );
    }

    /**
     * @return \Spryker\Zed\SalesOrderThresholdGui\Communication\Form\Constraint\ThresholdStrategyConstraint
     */
    public function createThresholdStrategyConstraint(): ThresholdStrategyConstraint
    {
        return new ThresholdStrategyConstraint([
            ThresholdStrategyConstraint::OPTION_SALES_ORDER_THRESHOLD_FORM_EXPANDER_PLUGINS => $this->getSalesOrderThresholdFormExpanderPlugins(),
        ]);
    }

    /**
     * @return \Spryker\Zed\SalesOrderThresholdGui\Dependency\Facade\SalesOrderThresholdGuiToCurrencyFacadeInterface
     */
    public function getCurrencyFacade(): SalesOrderThresholdGuiToCurrencyFacadeInterface
    {
        return $this->getProvidedDependency(SalesOrderThresholdGuiDependencyProvider::FACADE_CURRENCY);
    }

    /**
     * @return \Spryker\Zed\SalesOrderThresholdGui\Dependency\Facade\SalesOrderThresholdGuiToStoreFacadeInterface
     */
    public function getStoreFacade(): SalesOrderThresholdGuiToStoreFacadeInterface
    {
        return $this->getProvidedDependency(SalesOrderThresholdGuiDependencyProvider::FACADE_STORE);
    }

    /**
     * @return \Spryker\Zed\SalesOrderThresholdGui\Dependency\Facade\SalesOrderThresholdGuiToSalesOrderThresholdFacadeInterface
     */
    public function getSalesOrderThresholdFacade(): SalesOrderThresholdGuiToSalesOrderThresholdFacadeInterface
    {
        return $this->getProvidedDependency(SalesOrderThresholdGuiDependencyProvider::FACADE_SALES_ORDER_THRESHOLD);
    }

    /**
     * @return \Spryker\Zed\SalesOrderThresholdGui\Dependency\Facade\SalesOrderThresholdGuiToMoneyFacadeInterface
     */
    public function getMoneyFacade(): SalesOrderThresholdGuiToMoneyFacadeInterface
    {
        return $this->getProvidedDependency(SalesOrderThresholdGuiDependencyProvider::FACADE_MONEY);
    }

    /**
     * @return \Spryker\Zed\SalesOrderThresholdGui\Dependency\Facade\SalesOrderThresholdGuiToLocaleFacadeInterface
     */
    public function getLocaleFacade(): SalesOrderThresholdGuiToLocaleFacadeInterface
    {
        return $this->getProvidedDependency(SalesOrderThresholdGuiDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Spryker\Zed\SalesOrderThresholdGui\Dependency\Facade\SalesOrderThresholdGuiToTaxFacadeInterface
     */
    public function getTaxFacade(): SalesOrderThresholdGuiToTaxFacadeInterface
    {
        return $this->getProvidedDependency(SalesOrderThresholdGuiDependencyProvider::FACADE_TAX);
    }

    /**
     * @return \Spryker\Zed\SalesOrderThresholdGuiExtension\Dependency\Plugin\SalesOrderThresholdFormExpanderPluginInterface[]
     */
    public function getSalesOrderThresholdFormExpanderPlugins(): array
    {
        return $this->getProvidedDependency(SalesOrderThresholdGuiDependencyProvider::SALES_ORDER_THRESHOLD_FORM_EXPANDER_PLUGINS);
    }
}
