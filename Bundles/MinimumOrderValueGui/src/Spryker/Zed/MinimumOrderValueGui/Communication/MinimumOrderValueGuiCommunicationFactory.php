<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValueGui\Communication;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\MinimumOrderValueGui\Communication\Form\DataProvider\GlobalThresholdDataProvider;
use Spryker\Zed\MinimumOrderValueGui\Communication\Form\DataProvider\LocaleProvider;
use Spryker\Zed\MinimumOrderValueGui\Communication\Form\DataProvider\SettingsFormDataProvider;
use Spryker\Zed\MinimumOrderValueGui\Communication\Form\DataProvider\ThresholdStrategy\GlobalThresholdDataProviderResolver;
use Spryker\Zed\MinimumOrderValueGui\Communication\Form\DataProvider\ThresholdStrategy\GlobalThresholdDataProviderResolverInterface;
use Spryker\Zed\MinimumOrderValueGui\Communication\Form\GlobalThresholdType;
use Spryker\Zed\MinimumOrderValueGui\Communication\Form\Mapper\GlobalHardThresholdFormMapper;
use Spryker\Zed\MinimumOrderValueGui\Communication\Form\Mapper\GlobalThresholdFormMapperInterface;
use Spryker\Zed\MinimumOrderValueGui\Communication\Form\Mapper\GlobalThresholdMapperResolver;
use Spryker\Zed\MinimumOrderValueGui\Communication\Form\Mapper\GlobalThresholdMapperResolverInterface;
use Spryker\Zed\MinimumOrderValueGui\Communication\Form\SettingsForm;
use Spryker\Zed\MinimumOrderValueGui\Communication\StoreCurrency\StoreCurrencyFinder;
use Spryker\Zed\MinimumOrderValueGui\Communication\StoreCurrency\StoreCurrencyFinderInterface;
use Spryker\Zed\MinimumOrderValueGui\Dependency\Facade\MinimumOrderValueGuiToCurrencyFacadeInterface;
use Spryker\Zed\MinimumOrderValueGui\Dependency\Facade\MinimumOrderValueGuiToLocaleFacadeInterface;
use Spryker\Zed\MinimumOrderValueGui\Dependency\Facade\MinimumOrderValueGuiToMinimumOrderValueFacadeInterface;
use Spryker\Zed\MinimumOrderValueGui\Dependency\Facade\MinimumOrderValueGuiToMoneyFacadeInterface;
use Spryker\Zed\MinimumOrderValueGui\Dependency\Facade\MinimumOrderValueGuiToStoreFacadeInterface;
use Spryker\Zed\MinimumOrderValueGui\Dependency\Facade\MinimumOrderValueGuiToTaxFacadeInterface;
use Spryker\Zed\MinimumOrderValueGui\MinimumOrderValueGuiDependencyProvider;
use Symfony\Component\Form\FormInterface;

/**
 * @method \Spryker\Zed\MinimumOrderValueGui\MinimumOrderValueGuiConfig getConfig()
 */
class MinimumOrderValueGuiCommunicationFactory extends AbstractCommunicationFactory
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
     * @param \Spryker\Zed\MinimumOrderValueGui\Communication\Form\DataProvider\SettingsFormDataProvider $settingsFormDataProvider
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
     * @return \Spryker\Zed\MinimumOrderValueGui\Communication\Form\DataProvider\SettingsFormDataProvider
     */
    public function createSettingsFromDataProvider(): SettingsFormDataProvider
    {
        return new SettingsFormDataProvider(
            $this->getTaxFacade(),
            $this->getMinimumOrderValueFacade()
        );
    }

    /**
     * @return \Spryker\Zed\MinimumOrderValueGui\Communication\Form\DataProvider\GlobalThresholdDataProvider
     */
    public function createGlobalThresholdFormDataProvider(): GlobalThresholdDataProvider
    {
        return new GlobalThresholdDataProvider(
            $this->getMinimumOrderValueFacade(),
            $this->getCurrencyFacade(),
            $this->createGlobalSoftThresholdDataProviderResolver()
        );
    }

    /**
     * @return \Spryker\Zed\MinimumOrderValueGui\Communication\Form\DataProvider\LocaleProvider
     */
    public function createLocaleProvider(): LocaleProvider
    {
        return new LocaleProvider(
            $this->getLocaleFacade()
        );
    }

    /**
     * @return \Spryker\Zed\MinimumOrderValueGui\Communication\StoreCurrency\StoreCurrencyFinderInterface
     */
    public function createStoreCurrencyFinder(): StoreCurrencyFinderInterface
    {
        return new StoreCurrencyFinder(
            $this->getCurrencyFacade(),
            $this->getStoreFacade()
        );
    }

    /**
     * @return \Spryker\Zed\MinimumOrderValueGui\Communication\Form\Mapper\GlobalThresholdFormMapperInterface
     */
    public function createGlobalHardThresholdFormMapper(): GlobalThresholdFormMapperInterface
    {
        return new GlobalHardThresholdFormMapper(
            $this->createLocaleProvider(),
            $this->createStoreCurrencyFinder()
        );
    }

    /**
     * @return \Spryker\Zed\MinimumOrderValueGui\Communication\Form\Mapper\GlobalThresholdMapperResolverInterface
     */
    public function createGlobalSoftThresholdFormMapperResolver(): GlobalThresholdMapperResolverInterface
    {
        return new GlobalThresholdMapperResolver(
            $this->createLocaleProvider(),
            $this->createStoreCurrencyFinder(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\MinimumOrderValueGui\Communication\Form\DataProvider\ThresholdStrategy\GlobalThresholdDataProviderResolverInterface
     */
    public function createGlobalSoftThresholdDataProviderResolver(): GlobalThresholdDataProviderResolverInterface
    {
        return new GlobalThresholdDataProviderResolver(
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\MinimumOrderValueGui\Dependency\Facade\MinimumOrderValueGuiToCurrencyFacadeInterface
     */
    public function getCurrencyFacade(): MinimumOrderValueGuiToCurrencyFacadeInterface
    {
        return $this->getProvidedDependency(MinimumOrderValueGuiDependencyProvider::FACADE_CURRENCY);
    }

    /**
     * @return \Spryker\Zed\MinimumOrderValueGui\Dependency\Facade\MinimumOrderValueGuiToStoreFacadeInterface
     */
    public function getStoreFacade(): MinimumOrderValueGuiToStoreFacadeInterface
    {
        return $this->getProvidedDependency(MinimumOrderValueGuiDependencyProvider::FACADE_STORE);
    }

    /**
     * @return \Spryker\Zed\MinimumOrderValueGui\Dependency\Facade\MinimumOrderValueGuiToMinimumOrderValueFacadeInterface
     */
    public function getMinimumOrderValueFacade(): MinimumOrderValueGuiToMinimumOrderValueFacadeInterface
    {
        return $this->getProvidedDependency(MinimumOrderValueGuiDependencyProvider::FACADE_MINIMUM_ORDER_VALUE);
    }

    /**
     * @return \Spryker\Zed\MinimumOrderValueGui\Dependency\Facade\MinimumOrderValueGuiToMoneyFacadeInterface
     */
    public function getMoneyFacade(): MinimumOrderValueGuiToMoneyFacadeInterface
    {
        return $this->getProvidedDependency(MinimumOrderValueGuiDependencyProvider::FACADE_MONEY);
    }

    /**
     * @return \Spryker\Zed\MinimumOrderValueGui\Dependency\Facade\MinimumOrderValueGuiToLocaleFacadeInterface
     */
    public function getLocaleFacade(): MinimumOrderValueGuiToLocaleFacadeInterface
    {
        return $this->getProvidedDependency(MinimumOrderValueGuiDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Spryker\Zed\MinimumOrderValueGui\Dependency\Facade\MinimumOrderValueGuiToTaxFacadeInterface
     */
    public function getTaxFacade(): MinimumOrderValueGuiToTaxFacadeInterface
    {
        return $this->getProvidedDependency(MinimumOrderValueGuiDependencyProvider::FACADE_TAX);
    }
}
