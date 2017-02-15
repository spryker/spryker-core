<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\NavigationGui\Communication;

use Generated\Shared\Transfer\NavigationTransfer;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\NavigationGui\Communication\Form\DataProvider\NavigationFormDataProvider;
use Spryker\Zed\NavigationGui\Communication\Form\NavigationFormType;
use Spryker\Zed\NavigationGui\Communication\Table\NavigationTable;
use Spryker\Zed\NavigationGui\NavigationGuiDependencyProvider;

/**
 * @method \Spryker\Zed\NavigationGui\NavigationGuiConfig getConfig()
 * @method \Spryker\Zed\NavigationGui\Persistence\NavigationGuiQueryContainer getQueryContainer()
 */
class NavigationGuiCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @return \Spryker\Zed\NavigationGui\Communication\Table\NavigationTable
     */
    public function createNavigationTable()
    {
        return new NavigationTable($this->getQueryContainer());
    }

    /**
     * @param \Generated\Shared\Transfer\NavigationTransfer|null $data
     * @param array|null $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createNavigationForm(NavigationTransfer $data = null, array $options = [])
    {
        return $this->getFormFactory()->create($this->createNavigationFormType(), $data, $options);
    }

    /**
     * @return \Spryker\Zed\NavigationGui\Communication\Form\NavigationFormType
     */
    protected function createNavigationFormType()
    {
        return new NavigationFormType($this->getQueryContainer());
    }

    /**
     * @return \Spryker\Zed\NavigationGui\Communication\Form\DataProvider\NavigationFormDataProvider
     */
    public function createNavigationFormDataProvider()
    {
        return new NavigationFormDataProvider($this->getNavigationFacade());
    }

    /**
     * @return \Spryker\Zed\NavigationGui\Dependency\Facade\NavigationGuiToNavigationInterface
     */
    public function getNavigationFacade()
    {
        return $this->getProvidedDependency(NavigationGuiDependencyProvider::FACADE_NAVIGATION);
    }

}
