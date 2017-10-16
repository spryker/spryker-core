<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Money\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Money\Communication\Form\DataProvider\MoneyCollectionMultiStoreCollectionDataProvider;
use Spryker\Zed\Money\Communication\Form\DataProvider\MoneyCollectionSingleStoreDataProvider;
use Spryker\Zed\Money\Communication\Form\DataProvider\MoneyDataProvider;
use Spryker\Zed\Money\Communication\Form\Type\MoneyCollectionType;
use Spryker\Zed\Money\Communication\Form\Type\MoneyType;
use Spryker\Zed\Money\MoneyDependencyProvider;

/**
 * @method \Spryker\Zed\Money\MoneyConfig getConfig()
 * @method \Spryker\Zed\Money\Business\MoneyFacadeInterface getFacade()
 */
class MoneyCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @return \Spryker\Zed\Money\Communication\Form\Type\MoneyType|\Symfony\Component\Form\FormTypeInterface
     */
    public function createMoneyFormType()
    {
        return new MoneyType();
    }

    /**
     * @return \Spryker\Zed\Money\Communication\Form\DataProvider\MoneyCollectionSingleStoreDataProvider|\Spryker\Zed\Money\Communication\Form\DataProvider\MoneyCollectionDataProviderInterface
     */
    public function createMoneyCollectionSingleStoreDataProvider()
    {
        return new MoneyCollectionSingleStoreDataProvider($this->getCurrencyFacade());
    }

    /**
     * @return \Spryker\Zed\Money\Communication\Form\DataProvider\MoneyCollectionMultiStoreCollectionDataProvider|\Spryker\Zed\Money\Communication\Form\DataProvider\MoneyCollectionDataProviderInterface
     */
    public function createMoneyCollectionMultiStoreDataProvider()
    {
        return new MoneyCollectionMultiStoreCollectionDataProvider($this->getCurrencyFacade());
    }

    /**
     * @return \Spryker\Zed\Money\Communication\Form\DataProvider\MoneyDataProvider
     */
    public function createMoneyDataProvider()
    {
        return new MoneyDataProvider($this->getStoreFacade());
    }

    /**
     * @return \Spryker\Zed\Money\Dependency\Facade\MoneyToCurrencyInterface
     */
    protected function getCurrencyFacade()
    {
        return $this->getProvidedDependency(MoneyDependencyProvider::FACADE_CURRENCY);
    }

    /**
     * @return \Spryker\Zed\Money\Dependency\Facade\MoneyToStoreInterface
     */
    public function getStoreFacade()
    {
        return $this->getProvidedDependency(MoneyDependencyProvider::FACADE_STORE);
    }

    /**
     * @return \Spryker\Zed\Money\Communication\Form\Type\MoneyCollectionType
     */
    public function createMoneyCollectionType()
    {
        return new MoneyCollectionType();
    }

}
