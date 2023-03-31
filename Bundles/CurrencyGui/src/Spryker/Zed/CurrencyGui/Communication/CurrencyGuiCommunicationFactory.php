<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CurrencyGui\Communication;

use Orm\Zed\Currency\Persistence\Base\SpyCurrencyStoreQuery;
use Orm\Zed\Currency\Persistence\SpyCurrencyQuery;
use Spryker\Zed\CurrencyGui\Communication\Expander\CurrencyStoreTableExpanderInterface;
use Spryker\Zed\CurrencyGui\Communication\Expander\SelectableCurrencyStoreTableExpander;
use Spryker\Zed\CurrencyGui\Communication\Expander\StoreTableExpander;
use Spryker\Zed\CurrencyGui\Communication\Expander\StoreTableExpanderInterface;
use Spryker\Zed\CurrencyGui\Communication\Form\DataProvider\StoreCurrencyFormDataProvider;
use Spryker\Zed\CurrencyGui\Communication\Form\StoreCurrencyForm;
use Spryker\Zed\CurrencyGui\Communication\Table\AssignedCurrencyStoreTable;
use Spryker\Zed\CurrencyGui\Communication\Table\AvailableCurrencyStoreTable;
use Spryker\Zed\CurrencyGui\Communication\Table\CurrencyStoreTable;
use Spryker\Zed\CurrencyGui\Communication\Tabs\AssignedCurrenciesStoreRelationTabs;
use Spryker\Zed\CurrencyGui\Communication\Tabs\AvailableCurrenciesStoreRelationTabs;
use Spryker\Zed\CurrencyGui\CurrencyGuiDependencyProvider;
use Spryker\Zed\CurrencyGui\Dependency\Facade\CurrencyGuiToCurrencyFacadeInterface;
use Spryker\Zed\CurrencyGui\Dependency\Facade\CurrencyGuiToStoreFacadeInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Symfony\Component\Form\FormTypeInterface;
use Twig\Environment;

/**
 * @method \Spryker\Zed\CurrencyGui\CurrencyGuiConfig getConfig()
 */
class CurrencyGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Symfony\Component\Form\FormTypeInterface
     */
    public function createStoreCurrencyForm(): FormTypeInterface
    {
        return new StoreCurrencyForm();
    }

    /**
     * @return \Spryker\Zed\CurrencyGui\Communication\Form\DataProvider\StoreCurrencyFormDataProvider
     */
    public function createStoreCurrencyFormDataProvider(): StoreCurrencyFormDataProvider
    {
        return new StoreCurrencyFormDataProvider(
            $this->getCurrencyFacade(),
        );
    }

    /**
     * @param int|null $idStore
     * @param array<\Spryker\Zed\CurrencyGui\Communication\Expander\CurrencyStoreTableExpanderInterface> $expanders
     *
     * @return \Spryker\Zed\CurrencyGui\Communication\Table\CurrencyStoreTable
     */
    public function createAssignedCurrencyStoreTable(?int $idStore, array $expanders = []): CurrencyStoreTable
    {
        return new AssignedCurrencyStoreTable(
            $idStore,
            $expanders,
            $this->getCurrencyStorePropelQuery(),
        );
    }

    /**
     * @param int|null $idStore
     *
     * @return \Spryker\Zed\CurrencyGui\Communication\Table\CurrencyStoreTable
     */
    public function createSelectableAssignedCurrencyStoreTable(?int $idStore): CurrencyStoreTable
    {
        return new AssignedCurrencyStoreTable(
            $idStore,
            [$this->createCurrencyStoreTableSelectableExpander()],
            $this->getCurrencyStorePropelQuery(),
        );
    }

    /**
     * @param int|null $idStore
     *
     * @return \Spryker\Zed\CurrencyGui\Communication\Table\CurrencyStoreTable
     */
    public function createSelectableAvailableCurrencyStoreTable(?int $idStore): CurrencyStoreTable
    {
        return new AvailableCurrencyStoreTable(
            $idStore,
            [$this->createCurrencyStoreTableSelectableExpander()],
            $this->getCurrencyPropelQuery(),
        );
    }

    /**
     * @return \Spryker\Zed\CurrencyGui\Communication\Expander\CurrencyStoreTableExpanderInterface
     */
    public function createCurrencyStoreTableSelectableExpander(): CurrencyStoreTableExpanderInterface
    {
        return new SelectableCurrencyStoreTableExpander();
    }

    /**
     * @return \Spryker\Zed\CurrencyGui\Dependency\Facade\CurrencyGuiToCurrencyFacadeInterface
     */
    public function getCurrencyFacade(): CurrencyGuiToCurrencyFacadeInterface
    {
        return $this->getProvidedDependency(CurrencyGuiDependencyProvider::FACADE_CURRENCY);
    }

    /**
     * @return \Spryker\Zed\CurrencyGui\Dependency\Facade\CurrencyGuiToStoreFacadeInterface
     */
    public function getStoreFacade(): CurrencyGuiToStoreFacadeInterface
    {
        return $this->getProvidedDependency(CurrencyGuiDependencyProvider::FACADE_STORE);
    }

    /**
     * @return \Orm\Zed\Currency\Persistence\SpyCurrencyStoreQuery<mixed>
     */
    public function getCurrencyStorePropelQuery(): SpyCurrencyStoreQuery
    {
        return $this->getProvidedDependency(CurrencyGuiDependencyProvider::PROPEL_QUERY_CURRENCY_STORE);
    }

    /**
     * @return \Orm\Zed\Currency\Persistence\SpyCurrencyQuery<mixed>
     */
    public function getCurrencyPropelQuery(): SpyCurrencyQuery
    {
        return $this->getProvidedDependency(CurrencyGuiDependencyProvider::PROPEL_QUERY_CURRENCY);
    }

    /**
     * @return \Spryker\Zed\CurrencyGui\Communication\Expander\StoreTableExpanderInterface
     */
    public function createStoreTableExpander(): StoreTableExpanderInterface
    {
        return new StoreTableExpander($this->getStoreFacade());
    }

    /**
     * @return \Twig\Environment
     */
    public function getTwigEnvironment(): Environment
    {
        return $this->getProvidedDependency(CurrencyGuiDependencyProvider::RENDERER);
    }

    /**
     * @return \Spryker\Zed\CurrencyGui\Communication\Tabs\AvailableCurrenciesStoreRelationTabs
     */
    public function createAvailableCurrencyRelationTabs(): AvailableCurrenciesStoreRelationTabs
    {
        return new AvailableCurrenciesStoreRelationTabs();
    }

    /**
     * @return \Spryker\Zed\CurrencyGui\Communication\Tabs\AssignedCurrenciesStoreRelationTabs
     */
    public function createAssignedCurrencyRelationTabs(): AssignedCurrenciesStoreRelationTabs
    {
        return new AssignedCurrenciesStoreRelationTabs();
    }
}
