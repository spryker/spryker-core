<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\LocaleGui\Communication;

use Orm\Zed\Locale\Persistence\SpyLocaleQuery;
use Orm\Zed\Locale\Persistence\SpyLocaleStoreQuery;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\LocaleGui\Communication\Expander\LocaleStoreTableExpanderInterface;
use Spryker\Zed\LocaleGui\Communication\Expander\SelectableLocaleStoreTableExpander;
use Spryker\Zed\LocaleGui\Communication\Expander\StoreTableExpander;
use Spryker\Zed\LocaleGui\Communication\Expander\StoreTableExpanderInterface;
use Spryker\Zed\LocaleGui\Communication\Form\DataProvider\StoreLocaleFormDataProvider;
use Spryker\Zed\LocaleGui\Communication\Form\StoreLocaleForm;
use Spryker\Zed\LocaleGui\Communication\Table\AssignedLocaleStoreTable;
use Spryker\Zed\LocaleGui\Communication\Table\AvailableLocaleStoreTable;
use Spryker\Zed\LocaleGui\Communication\Table\LocaleStoreTable;
use Spryker\Zed\LocaleGui\Communication\Tabs\AssignedLocalesStoreRelationTabs;
use Spryker\Zed\LocaleGui\Communication\Tabs\AvailableLocalesStoreRelationTabs;
use Spryker\Zed\LocaleGui\Dependency\Facade\LocaleGuiToLocaleFacadeInterface;
use Spryker\Zed\LocaleGui\Dependency\Facade\LocaleGuiToStoreFacadeInterface;
use Spryker\Zed\LocaleGui\LocaleGuiDependencyProvider;
use Twig\Environment;

/**
 * @method \Spryker\Zed\LocaleGui\LocaleGuiConfig getConfig()
 */
class LocaleGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\LocaleGui\Communication\Form\DataProvider\StoreLocaleFormDataProvider
     */
    public function createStoreLocaleFormDataProvider(): StoreLocaleFormDataProvider
    {
        return new StoreLocaleFormDataProvider(
            $this->getLocaleFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\LocaleGui\Communication\Form\StoreLocaleForm
     */
    public function createStoreLocaleForm(): StoreLocaleForm
    {
        return new StoreLocaleForm();
    }

    /**
     * @param int|null $idStore
     * @param array<\Spryker\Zed\LocaleGui\Communication\Expander\LocaleStoreTableExpanderInterface> $expanders
     *
     * @return \Spryker\Zed\LocaleGui\Communication\Table\LocaleStoreTable
     */
    public function createAssignedLocaleStoreTable(?int $idStore, array $expanders = []): LocaleStoreTable
    {
        return new AssignedLocaleStoreTable(
            $idStore,
            $expanders,
            $this->getLocaleStorePropelQuery(),
        );
    }

    /**
     * @param int|null $idStore
     *
     * @return \Spryker\Zed\LocaleGui\Communication\Table\LocaleStoreTable
     */
    public function createSelectableAssignedLocaleStoreTable(?int $idStore): LocaleStoreTable
    {
        return new AssignedLocaleStoreTable(
            $idStore,
            [$this->createLocaleStoreTableSelectableExpander()],
            $this->getLocaleStorePropelQuery(),
        );
    }

    /**
     * @param int|null $idStore
     *
     * @return \Spryker\Zed\LocaleGui\Communication\Table\LocaleStoreTable
     */
    public function createSelectableAvailableLocaleStoreTable(?int $idStore): LocaleStoreTable
    {
        return new AvailableLocaleStoreTable(
            $idStore,
            [$this->createLocaleStoreTableSelectableExpander()],
            $this->getLocalePropelQuery(),
        );
    }

    /**
     * @return \Spryker\Zed\LocaleGui\Communication\Expander\LocaleStoreTableExpanderInterface
     */
    public function createLocaleStoreTableSelectableExpander(): LocaleStoreTableExpanderInterface
    {
        return new SelectableLocaleStoreTableExpander();
    }

    /**
     * @return \Spryker\Zed\LocaleGui\Dependency\Facade\LocaleGuiToLocaleFacadeInterface
     */
    public function getLocaleFacade(): LocaleGuiToLocaleFacadeInterface
    {
        return $this->getProvidedDependency(LocaleGuiDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Spryker\Zed\LocaleGui\Dependency\Facade\LocaleGuiToStoreFacadeInterface
     */
    public function getStoreFacade(): LocaleGuiToStoreFacadeInterface
    {
        return $this->getProvidedDependency(LocaleGuiDependencyProvider::FACADE_STORE);
    }

    /**
     * @return \Spryker\Zed\LocaleGui\Communication\Expander\StoreTableExpanderInterface
     */
    public function createStoreTableExpander(): StoreTableExpanderInterface
    {
        return new StoreTableExpander($this->getStoreFacade());
    }

    /**
     * @return \Orm\Zed\Locale\Persistence\SpyLocaleStoreQuery<mixed>
     */
    public function getLocaleStorePropelQuery(): SpyLocaleStoreQuery
    {
        return $this->getProvidedDependency(LocaleGuiDependencyProvider::PROPEL_QUERY_LOCALE_STORE);
    }

    /**
     * @return \Orm\Zed\Locale\Persistence\SpyLocaleQuery<mixed>
     */
    public function getLocalePropelQuery(): SpyLocaleQuery
    {
        return $this->getProvidedDependency(LocaleGuiDependencyProvider::PROPEL_QUERY_LOCALE);
    }

    /**
     * @return \Twig\Environment
     */
    public function getTwigEnvironment(): Environment
    {
        return $this->getProvidedDependency(LocaleGuiDependencyProvider::RENDERER);
    }

    /**
     * @return \Spryker\Zed\LocaleGui\Communication\Tabs\AvailableLocalesStoreRelationTabs
     */
    public function createAvailableLocaleRelationTabs(): AvailableLocalesStoreRelationTabs
    {
        return new AvailableLocalesStoreRelationTabs();
    }

    /**
     * @return \Spryker\Zed\LocaleGui\Communication\Tabs\AssignedLocalesStoreRelationTabs
     */
    public function createAssignedLocaleRelationTabs(): AssignedLocalesStoreRelationTabs
    {
        return new AssignedLocalesStoreRelationTabs();
    }
}
