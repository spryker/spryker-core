<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderThresholdGui\Communication;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\SalesOrderThresholdGui\Communication\Form\DataProvider\GlobalThresholdDataProvider;
use Spryker\Zed\SalesOrderThresholdGui\Communication\Form\DataProvider\SettingsFormDataProvider;
use Spryker\Zed\SalesOrderThresholdGui\Communication\Form\DataProvider\ThresholdStrategy\GlobalThresholdDataProviderResolver;
use Spryker\Zed\SalesOrderThresholdGui\Communication\Form\DataProvider\ThresholdStrategy\GlobalThresholdDataProviderResolverInterface;
use Spryker\Zed\SalesOrderThresholdGui\Communication\Form\GlobalThresholdType;
use Spryker\Zed\SalesOrderThresholdGui\Communication\Form\Mapper\GlobalHardThresholdFormMapper;
use Spryker\Zed\SalesOrderThresholdGui\Communication\Form\Mapper\GlobalThresholdFormMapperInterface;
use Spryker\Zed\SalesOrderThresholdGui\Communication\Form\Mapper\GlobalThresholdMapperResolver;
use Spryker\Zed\SalesOrderThresholdGui\Communication\Form\Mapper\GlobalThresholdMapperResolverInterface;
use Spryker\Zed\SalesOrderThresholdGui\Communication\Form\SettingsForm;
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
            $formDataProvider->getOptions()
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
            SettingsForm::class,
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
            $this->createGlobalSoftThresholdDataProviderResolver()
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
     * @return \Spryker\Zed\SalesOrderThresholdGui\Communication\Form\Mapper\GlobalThresholdFormMapperInterface
     */
    public function createGlobalHardThresholdFormMapper(): GlobalThresholdFormMapperInterface
    {
        return new GlobalHardThresholdFormMapper(
            $this->getLocaleFacade(),
            $this->createStoreCurrencyFinder()
        );
    }

    /**
     * @return \Spryker\Zed\SalesOrderThresholdGui\Communication\Form\Mapper\GlobalThresholdMapperResolverInterface
     */
    public function createGlobalSoftThresholdFormMapperResolver(): GlobalThresholdMapperResolverInterface
    {
        return new GlobalThresholdMapperResolver(
            $this->getLocaleFacade(),
            $this->createStoreCurrencyFinder(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\SalesOrderThresholdGui\Communication\Form\DataProvider\ThresholdStrategy\GlobalThresholdDataProviderResolverInterface
     */
    public function createGlobalSoftThresholdDataProviderResolver(): GlobalThresholdDataProviderResolverInterface
    {
        return new GlobalThresholdDataProviderResolver(
            $this->getConfig()
        );
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
}
