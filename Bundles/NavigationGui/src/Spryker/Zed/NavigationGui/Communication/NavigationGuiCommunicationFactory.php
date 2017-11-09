<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\NavigationGui\Communication;

use Generated\Shared\Transfer\NavigationNodeTransfer;
use Generated\Shared\Transfer\NavigationTransfer;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\NavigationGui\Communication\Form\DataProvider\NavigationFormDataProvider;
use Spryker\Zed\NavigationGui\Communication\Form\DataProvider\NavigationNodeFormDataProvider;
use Spryker\Zed\NavigationGui\Communication\Form\NavigationFormType;
use Spryker\Zed\NavigationGui\Communication\Form\NavigationNodeFormType;
use Spryker\Zed\NavigationGui\Communication\Form\NavigationNodeLocalizedAttributesFormType;
use Spryker\Zed\NavigationGui\Communication\Form\UpdateNavigationFormType;
use Spryker\Zed\NavigationGui\Communication\Table\NavigationTable;
use Spryker\Zed\NavigationGui\NavigationGuiDependencyProvider;

/**
 * @method \Spryker\Zed\NavigationGui\NavigationGuiConfig getConfig()
 * @method \Spryker\Zed\NavigationGui\Persistence\NavigationGuiQueryContainerInterface getQueryContainer()
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
     * @param \Generated\Shared\Transfer\NavigationTransfer|null $data
     * @param array|null $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createUpdateNavigationForm(NavigationTransfer $data = null, array $options = [])
    {
        return $this->getFormFactory()->create($this->createUpdateNavigationFormType(), $data, $options);
    }

    /**
     * @return \Spryker\Zed\NavigationGui\Communication\Form\NavigationFormType
     */
    protected function createUpdateNavigationFormType()
    {
        return new UpdateNavigationFormType($this->getQueryContainer());
    }

    /**
     * @return \Spryker\Zed\NavigationGui\Communication\Form\DataProvider\NavigationFormDataProvider
     */
    public function createNavigationFormDataProvider()
    {
        return new NavigationFormDataProvider($this->getNavigationFacade());
    }

    /**
     * @param \Generated\Shared\Transfer\NavigationNodeTransfer|null $data
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createNavigationNodeForm(NavigationNodeTransfer $data = null, array $options = [])
    {
        return $this->getFormFactory()->create($this->createNavigationNodeFormType(), $data, $options);
    }

    /**
     * @return \Spryker\Zed\NavigationGui\Communication\Form\NavigationNodeFormType
     */
    protected function createNavigationNodeFormType()
    {
        return new NavigationNodeFormType($this->createNavigationNodeLocalizedAttributesFormType());
    }

    /**
     * @return \Spryker\Zed\NavigationGui\Communication\Form\DataProvider\NavigationNodeFormDataProvider
     */
    public function createNavigationNodeFormDataProvider()
    {
        return new NavigationNodeFormDataProvider($this->getNavigationFacade(), $this->getLocaleFacade());
    }

    /**
     * @return \Spryker\Zed\NavigationGui\Communication\Form\NavigationNodeLocalizedAttributesFormType
     */
    protected function createNavigationNodeLocalizedAttributesFormType()
    {
        return new NavigationNodeLocalizedAttributesFormType($this->getUrlFacade());
    }

    /**
     * @return \Spryker\Zed\NavigationGui\Dependency\Facade\NavigationGuiToNavigationInterface
     */
    public function getNavigationFacade()
    {
        return $this->getProvidedDependency(NavigationGuiDependencyProvider::FACADE_NAVIGATION);
    }

    /**
     * @return \Spryker\Zed\NavigationGui\Dependency\Facade\NavigationGuiToLocaleInterface
     */
    public function getLocaleFacade()
    {
        return $this->getProvidedDependency(NavigationGuiDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Spryker\Zed\NavigationGui\Dependency\Facade\NavigationGuiToUrlInterface
     */
    public function getUrlFacade()
    {
        return $this->getProvidedDependency(NavigationGuiDependencyProvider::FACADE_URL);
    }
}
