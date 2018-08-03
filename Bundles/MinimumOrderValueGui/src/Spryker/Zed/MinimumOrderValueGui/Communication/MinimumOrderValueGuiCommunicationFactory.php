<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValueGui\Communication;

use Generated\Shared\Transfer\StoreCurrencyTransfer;
use Spryker\Shared\MinimumOrderValueGui\MinimumOrderValueGuiConstants;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\MinimumOrderValueGui\Communication\Exception\MissingGlobalThresholdFormMapperException;
use Spryker\Zed\MinimumOrderValueGui\Communication\Form\DataProvider\GlobalThresholdDataProvider;
use Spryker\Zed\MinimumOrderValueGui\Communication\Form\DataProvider\LocaleProvider;
use Spryker\Zed\MinimumOrderValueGui\Communication\Form\GlobalThresholdType;
use Spryker\Zed\MinimumOrderValueGui\Communication\Form\Mapper\GlobalHardThresholdFormMapper;
use Spryker\Zed\MinimumOrderValueGui\Communication\Form\Mapper\GlobalSoftThresholdFixedFeeFormMapper;
use Spryker\Zed\MinimumOrderValueGui\Communication\Form\Mapper\GlobalSoftThresholdFlexibleFeeFormMapper;
use Spryker\Zed\MinimumOrderValueGui\Communication\Form\Mapper\GlobalSoftThresholdFormMapper;
use Spryker\Zed\MinimumOrderValueGui\Communication\Form\Mapper\GlobalThresholdFormMapperInterface;
use Spryker\Zed\MinimumOrderValueGui\Communication\Model\StoreCurrencyFinder;
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
     * @param \Generated\Shared\Transfer\MinimumOrderValueTransfer[] $minimumOrderValueTransfers
     * @param \Generated\Shared\Transfer\StoreCurrencyTransfer $storeCurrencyTransfer
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createGlobalThresholdForm(
        array $minimumOrderValueTransfers,
        StoreCurrencyTransfer $storeCurrencyTransfer
    ): FormInterface {
        $formDataProvider = $this->createGlobalThresholdFormDataProvider();

        return $this->getFormFactory()->create(
            GlobalThresholdType::class,
            $formDataProvider->getData($minimumOrderValueTransfers, $storeCurrencyTransfer),
            $formDataProvider->getOptions()
        );
    }

    /**
     * @return \Spryker\Zed\MinimumOrderValueGui\Communication\Form\DataProvider\GlobalThresholdDataProvider
     */
    public function createGlobalThresholdFormDataProvider(): GlobalThresholdDataProvider
    {
        return new GlobalThresholdDataProvider(
            $this->getCurrencyFacade()
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
     * @return \Spryker\Zed\MinimumOrderValueGui\Communication\Model\StoreCurrencyFinder
     */
    public function createStoreCurrencyFinder(): StoreCurrencyFinder
    {
        return new StoreCurrencyFinder(
            $this->getCurrencyFacade()
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
     * @param string $softStrategy
     *
     * @throws \Spryker\Zed\MinimumOrderValueGui\Communication\Exception\MissingGlobalThresholdFormMapperException
     *
     * @return \Spryker\Zed\MinimumOrderValueGui\Communication\Form\Mapper\GlobalThresholdFormMapperInterface
     */
    public function createGlobalSoftThresholdFormMapperByStrategy(string $softStrategy): GlobalThresholdFormMapperInterface
    {
        switch ($softStrategy) {
            case MinimumOrderValueGuiConstants::SOFT_TYPE_STRATEGY_MESSAGE:
                return $this->createGlobalSoftThresholdFormMapper();

            case MinimumOrderValueGuiConstants::SOFT_TYPE_STRATEGY_FIXED:
                return $this->createGlobalSoftThresholdFixedFeeFormMapper();

            case MinimumOrderValueGuiConstants::SOFT_TYPE_STRATEGY_FLEXIBLE:
                return $this->createGlobalSoftThresholdFlexibleFeeFormMapper();

            default:
                throw new MissingGlobalThresholdFormMapperException();
        }
    }

    /**
     * @return \Spryker\Zed\MinimumOrderValueGui\Communication\Form\Mapper\GlobalThresholdFormMapperInterface
     */
    public function createGlobalSoftThresholdFormMapper(): GlobalThresholdFormMapperInterface
    {
        return new GlobalSoftThresholdFormMapper(
            $this->createLocaleProvider(),
            $this->createStoreCurrencyFinder()
        );
    }

    /**
     * @return \Spryker\Zed\MinimumOrderValueGui\Communication\Form\Mapper\GlobalThresholdFormMapperInterface
     */
    public function createGlobalSoftThresholdFixedFeeFormMapper(): GlobalThresholdFormMapperInterface
    {
        return new GlobalSoftThresholdFixedFeeFormMapper(
            $this->createLocaleProvider(),
            $this->createStoreCurrencyFinder()
        );
    }

    /**
     * @return \Spryker\Zed\MinimumOrderValueGui\Communication\Form\Mapper\GlobalThresholdFormMapperInterface
     */
    public function createGlobalSoftThresholdFlexibleFeeFormMapper(): GlobalThresholdFormMapperInterface
    {
        return new GlobalSoftThresholdFlexibleFeeFormMapper(
            $this->createLocaleProvider(),
            $this->createStoreCurrencyFinder()
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
     * @return \Spryker\Zed\MinimumOrderValueGui\Dependency\Facade\MinimumOrderValueGuiToLocaleInterface
     */
    public function getLocaleFacade()
    {
        return $this->getProvidedDependency(MinimumOrderValueGuiDependencyProvider::FACADE_LOCALE);
    }
}
