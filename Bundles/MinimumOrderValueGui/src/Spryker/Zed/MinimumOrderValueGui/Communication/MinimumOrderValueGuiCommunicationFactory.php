<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValueGui\Communication;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\GlobalMinimumOrderValueTransfer;
use Generated\Shared\Transfer\MinimumOrderValueTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\MinimumOrderValueGui\Communication\Form\DataProvider\GlobalThresholdDataProvider;
use Spryker\Zed\MinimumOrderValueGui\Communication\Form\DataProvider\LocaleProvider;
use Spryker\Zed\MinimumOrderValueGui\Communication\Form\DataProvider\ThresholdStrategy\GlobalThresholdDataProviderResolver;
use Spryker\Zed\MinimumOrderValueGui\Communication\Form\DataProvider\ThresholdStrategy\GlobalThresholdDataProviderResolverInterface;
use Spryker\Zed\MinimumOrderValueGui\Communication\Form\GlobalThresholdType;
use Spryker\Zed\MinimumOrderValueGui\Communication\Form\Mapper\GlobalHardThresholdFormMapper;
use Spryker\Zed\MinimumOrderValueGui\Communication\Form\Mapper\GlobalThresholdFormMapperInterface;
use Spryker\Zed\MinimumOrderValueGui\Communication\Form\Mapper\GlobalThresholdMapperResolver;
use Spryker\Zed\MinimumOrderValueGui\Communication\Form\Mapper\GlobalThresholdMapperResolverInterface;
use Spryker\Zed\MinimumOrderValueGui\Communication\StoreCurrency\StoreCurrencyFinder;
use Spryker\Zed\MinimumOrderValueGui\Communication\StoreCurrency\StoreCurrencyFinderInterface;
use Spryker\Zed\MinimumOrderValueGui\Dependency\Facade\MinimumOrderValueGuiToCurrencyFacadeInterface;
use Spryker\Zed\MinimumOrderValueGui\Dependency\Facade\MinimumOrderValueGuiToMinimumOrderValueFacadeInterface;
use Spryker\Zed\MinimumOrderValueGui\Dependency\Facade\MinimumOrderValueGuiToMoneyFacadeInterface;
use Spryker\Zed\MinimumOrderValueGui\Dependency\Facade\MinimumOrderValueGuiToStoreFacadeInterface;
use Spryker\Zed\MinimumOrderValueGui\MinimumOrderValueGuiDependencyProvider;
use Symfony\Component\Form\FormInterface;

/**
 * @method \Spryker\Zed\MinimumOrderValueGui\MinimumOrderValueGuiConfig getConfig()
 */
class MinimumOrderValueGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @param \Generated\Shared\Transfer\GlobalMinimumOrderValueTransfer[] $globalMinimumOrderValueTransfers
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createGlobalThresholdForm(
        array $globalMinimumOrderValueTransfers,
        StoreTransfer $storeTransfer,
        CurrencyTransfer $currencyTransfer
    ): FormInterface {
        $formDataProvider = $this->createGlobalThresholdFormDataProvider();

        return $this->getFormFactory()->create(
            GlobalThresholdType::class,
            $formDataProvider->getData($globalMinimumOrderValueTransfers, $storeTransfer, $currencyTransfer),
            $formDataProvider->getOptions()
        );
    }

    /**
     * @return \Spryker\Zed\MinimumOrderValueGui\Communication\Form\DataProvider\GlobalThresholdDataProvider
     */
    public function createGlobalThresholdFormDataProvider(): GlobalThresholdDataProvider
    {
        return new GlobalThresholdDataProvider(
            $this->getCurrencyFacade(),
            $this
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
     * @return \Generated\Shared\Transfer\GlobalMinimumOrderValueTransfer
     */
    public function createGlobalMinimumOrderValueTransfer(): GlobalMinimumOrderValueTransfer
    {
        return (new GlobalMinimumOrderValueTransfer())
            ->setMinimumOrderValue($this->createMinimumOrderValueTransfer());
    }

    /**
     * @return \Generated\Shared\Transfer\MinimumOrderValueTransfer
     */
    public function createMinimumOrderValueTransfer(): MinimumOrderValueTransfer
    {
        return new MinimumOrderValueTransfer();
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
     * @return \Spryker\Zed\MinimumOrderValueGui\Dependency\Facade\MinimumOrderValueGuiToLocaleInterface
     */
    public function getLocaleFacade()
    {
        return $this->getProvidedDependency(MinimumOrderValueGuiDependencyProvider::FACADE_LOCALE);
    }
}
