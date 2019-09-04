<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundleGui\Communication;

use Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer;
use Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplateQuery;
use Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplateSlotQuery;
use Spryker\Zed\ConfigurableBundleGui\Communication\Form\ConfigurableBundleTemplateForm;
use Spryker\Zed\ConfigurableBundleGui\Communication\Form\DataProvider\ConfigurableBundleTemplateFormDataProvider;
use Spryker\Zed\ConfigurableBundleGui\Communication\Table\ConfigurableBundleTemplateSlotTable;
use Spryker\Zed\ConfigurableBundleGui\Communication\Table\ConfigurableBundleTemplateTable;
use Spryker\Zed\ConfigurableBundleGui\Communication\Tabs\ConfigurableBundleTemplateCreateTabs;
use Spryker\Zed\ConfigurableBundleGui\Communication\Tabs\ConfigurableBundleTemplateEditTabs;
use Spryker\Zed\ConfigurableBundleGui\ConfigurableBundleGuiDependencyProvider;
use Spryker\Zed\ConfigurableBundleGui\Dependency\Facade\ConfigurableBundleGuiToConfigurableBundleFacadeInterface;
use Spryker\Zed\ConfigurableBundleGui\Dependency\Facade\ConfigurableBundleGuiToGlossaryFacadeInterface;
use Spryker\Zed\ConfigurableBundleGui\Dependency\Facade\ConfigurableBundleGuiToLocaleFacadeInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Symfony\Component\Form\FormInterface;

/**
 * @method \SprykerEco\Zed\ConfigurableBundleGui\Persistence\ConfigurableBundleGuiRepositoryInterface getRepository()
 * @method \SprykerEco\Zed\ConfigurableBundleGui\Persistence\ConfigurableBundleGuiEntityManagerInterface getEntityManager()
 * @method \SprykerEco\Zed\ConfigurableBundleGui\Business\ConfigurableBundleGuiFacadeInterface getFacade()
 * @method \SprykerEco\Zed\ConfigurableBundleGui\ConfigurableBundleGuiConfig getConfig()
 */
class ConfigurableBundleGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getConfigurableBundleTemplateForm(
        ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer,
        array $options = []
    ): FormInterface {
        return $this->getFormFactory()->create(
            ConfigurableBundleTemplateForm::class,
            $configurableBundleTemplateTransfer,
            $options
        );
    }

    /**
     * @return \Spryker\Zed\ConfigurableBundleGui\Communication\Form\DataProvider\ConfigurableBundleTemplateFormDataProvider
     */
    public function createConfigurableBundleTemplateFormDataProvider(): ConfigurableBundleTemplateFormDataProvider
    {
        return new ConfigurableBundleTemplateFormDataProvider(
            $this->getConfigurableBundleFacade(),
            $this->getLocaleFacade(),
            $this->getGlossaryFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ConfigurableBundleGui\Communication\Table\ConfigurableBundleTemplateTable
     */
    public function createConfigurableBundleTemplateTable(): ConfigurableBundleTemplateTable
    {
        return new ConfigurableBundleTemplateTable(
            $this->getConfigurableBundleTemplatePropelQuery(),
            $this->getLocaleFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ConfigurableBundleGui\Communication\Table\ConfigurableBundleTemplateSlotTable
     */
    public function createConfigurableBundleTemplateSlotTable(): ConfigurableBundleTemplateSlotTable
    {
        return new ConfigurableBundleTemplateSlotTable(
            $this->getConfigurableBundleTemplateSlotPropelQuery(),
            $this->getLocaleFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ConfigurableBundleGui\Communication\Tabs\ConfigurableBundleTemplateCreateTabs
     */
    public function createConfigurableBundleTemplateCreateTabs(): ConfigurableBundleTemplateCreateTabs
    {
        return new ConfigurableBundleTemplateCreateTabs();
    }

    /**
     * @return \Spryker\Zed\ConfigurableBundleGui\Communication\Tabs\ConfigurableBundleTemplateEditTabs
     */
    public function createConfigurableBundleTemplateEditTabs(): ConfigurableBundleTemplateEditTabs
    {
        return new ConfigurableBundleTemplateEditTabs();
    }

    /**
     * @return \Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplateQuery
     */
    public function getConfigurableBundleTemplatePropelQuery(): SpyConfigurableBundleTemplateQuery
    {
        return $this->getProvidedDependency(ConfigurableBundleGuiDependencyProvider::PROPEL_QUERY_CONFIGURABLE_BUNDLE_TEMPLATE);
    }

    /**
     * @return \Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplateSlotQuery
     */
    public function getConfigurableBundleTemplateSlotPropelQuery(): SpyConfigurableBundleTemplateSlotQuery
    {
        return $this->getProvidedDependency(ConfigurableBundleGuiDependencyProvider::PROPEL_QUERY_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT);
    }

    /**
     * @return \Spryker\Zed\ConfigurableBundleGui\Dependency\Facade\ConfigurableBundleGuiToConfigurableBundleFacadeInterface
     */
    public function getConfigurableBundleFacade(): ConfigurableBundleGuiToConfigurableBundleFacadeInterface
    {
        return $this->getProvidedDependency(ConfigurableBundleGuiDependencyProvider::FACADE_CONFIGURABLE_BUNDLE);
    }

    /**
     * @return \Spryker\Zed\ConfigurableBundleGui\Dependency\Facade\ConfigurableBundleGuiToLocaleFacadeInterface
     */
    public function getLocaleFacade(): ConfigurableBundleGuiToLocaleFacadeInterface
    {
        return $this->getProvidedDependency(ConfigurableBundleGuiDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Spryker\Zed\ConfigurableBundleGui\Dependency\Facade\ConfigurableBundleGuiToGlossaryFacadeInterface
     */
    public function getGlossaryFacade(): ConfigurableBundleGuiToGlossaryFacadeInterface
    {
        return $this->getProvidedDependency(ConfigurableBundleGuiDependencyProvider::FACADE_GLOSSARY);
    }
}
