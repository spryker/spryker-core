<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CountryGui\Communication;

use Orm\Zed\Country\Persistence\Base\SpyCountryStoreQuery;
use Orm\Zed\Country\Persistence\SpyCountryQuery;
use Spryker\Zed\CountryGui\Communication\Expander\CountryStoreTableExpanderInterface;
use Spryker\Zed\CountryGui\Communication\Expander\SelectableCountryStoreTableExpander;
use Spryker\Zed\CountryGui\Communication\Expander\StoreTableExpander;
use Spryker\Zed\CountryGui\Communication\Expander\StoreTableExpanderInterface;
use Spryker\Zed\CountryGui\Communication\Form\StoreCountryForm;
use Spryker\Zed\CountryGui\Communication\Table\AssignedCountryStoreTable;
use Spryker\Zed\CountryGui\Communication\Table\AvailableCountryStoreTable;
use Spryker\Zed\CountryGui\Communication\Table\CountryStoreTable;
use Spryker\Zed\CountryGui\Communication\Tabs\AssignedCountriesStoreRelationTabs;
use Spryker\Zed\CountryGui\Communication\Tabs\AvailableCountriesStoreRelationTabs;
use Spryker\Zed\CountryGui\CountryGuiDependencyProvider;
use Spryker\Zed\CountryGui\Dependency\Facade\CountryGuiToCountryFacadeInterface;
use Spryker\Zed\CountryGui\Dependency\Facade\CountryGuiToStoreFacadeInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Symfony\Component\Form\FormTypeInterface;
use Twig\Environment;

/**
 * @method \Spryker\Zed\CountryGui\CountryGuiConfig getConfig()
 */
class CountryGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Symfony\Component\Form\FormTypeInterface
     */
    public function createStoreCountryForm(): FormTypeInterface
    {
        return new StoreCountryForm();
    }

    /**
     * @param int|null $idStore
     * @param array<\Spryker\Zed\CountryGui\Communication\Expander\CountryStoreTableExpanderInterface> $expanders
     *
     * @return \Spryker\Zed\CountryGui\Communication\Table\CountryStoreTable
     */
    public function createAssignedCountryStoreTable(?int $idStore, array $expanders = []): CountryStoreTable
    {
        return new AssignedCountryStoreTable(
            $idStore,
            $expanders,
            $this->getCountryStorePropelQuery(),
        );
    }

    /**
     * @param int|null $idStore
     *
     * @return \Spryker\Zed\CountryGui\Communication\Table\CountryStoreTable
     */
    public function createSelectableAssignedCountryStoreTable(?int $idStore): CountryStoreTable
    {
        return new AssignedCountryStoreTable(
            $idStore,
            [$this->createCountryStoreTableSelectableExpander()],
            $this->getCountryStorePropelQuery(),
        );
    }

    /**
     * @param int|null $idStore
     *
     * @return \Spryker\Zed\CountryGui\Communication\Table\CountryStoreTable
     */
    public function createSelectableAvailableCountryStoreTable(?int $idStore): CountryStoreTable
    {
        return new AvailableCountryStoreTable(
            $idStore,
            [$this->createCountryStoreTableSelectableExpander()],
            $this->getCountryPropelQuery(),
        );
    }

    /**
     * @return \Spryker\Zed\CountryGui\Communication\Expander\CountryStoreTableExpanderInterface
     */
    public function createCountryStoreTableSelectableExpander(): CountryStoreTableExpanderInterface
    {
        return new SelectableCountryStoreTableExpander();
    }

    /**
     * @return \Spryker\Zed\CountryGui\Dependency\Facade\CountryGuiToCountryFacadeInterface
     */
    public function getCountryFacade(): CountryGuiToCountryFacadeInterface
    {
        return $this->getProvidedDependency(CountryGuiDependencyProvider::FACADE_COUNTRY);
    }

    /**
     * @return \Spryker\Zed\CountryGui\Dependency\Facade\CountryGuiToStoreFacadeInterface
     */
    public function getStoreFacade(): CountryGuiToStoreFacadeInterface
    {
        return $this->getProvidedDependency(CountryGuiDependencyProvider::FACADE_STORE);
    }

    /**
     * @return \Spryker\Zed\CountryGui\Communication\Expander\StoreTableExpanderInterface
     */
    public function createStoreTableExpander(): StoreTableExpanderInterface
    {
        return new StoreTableExpander($this->getStoreFacade());
    }

    /**
     * @return \Orm\Zed\Country\Persistence\SpyCountryStoreQuery<mixed>
     */
    public function getCountryStorePropelQuery(): SpyCountryStoreQuery
    {
        return $this->getProvidedDependency(CountryGuiDependencyProvider::PROPEL_QUERY_COUNTRY_STORE);
    }

    /**
     * @return \Orm\Zed\Country\Persistence\SpyCountryQuery<mixed>
     */
    public function getCountryPropelQuery(): SpyCountryQuery
    {
        return $this->getProvidedDependency(CountryGuiDependencyProvider::PROPEL_QUERY_COUNTRY);
    }

    /**
     * @return \Twig\Environment
     */
    public function getTwigEnvironment(): Environment
    {
        return $this->getProvidedDependency(CountryGuiDependencyProvider::RENDERER);
    }

    /**
     * @return \Spryker\Zed\CountryGui\Communication\Tabs\AvailableCountriesStoreRelationTabs
     */
    public function createAvailableCountryRelationTabs(): AvailableCountriesStoreRelationTabs
    {
        return new AvailableCountriesStoreRelationTabs();
    }

    /**
     * @return \Spryker\Zed\CountryGui\Communication\Tabs\AssignedCountriesStoreRelationTabs
     */
    public function createAssignedCountryRelationTabs(): AssignedCountriesStoreRelationTabs
    {
        return new AssignedCountriesStoreRelationTabs();
    }
}
