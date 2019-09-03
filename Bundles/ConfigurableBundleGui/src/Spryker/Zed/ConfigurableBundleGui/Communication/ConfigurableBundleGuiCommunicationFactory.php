<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundleGui\Communication;

use Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer;
use Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplateSlotQuery;
use Spryker\Zed\ConfigurableBundleGui\Communication\Form\ConfigurableBundleTemplateForm;
use Spryker\Zed\ConfigurableBundleGui\Communication\Form\DataProvider\ConfigurableBundleTemplateFormDataProvider;
use Spryker\Zed\ConfigurableBundleGui\Communication\Table\ConfigurableBundleTemplateSlotTable;
use Spryker\Zed\ConfigurableBundleGui\Communication\Tabs\ConfigurableBundleTemplateCreateTabs;
use Spryker\Zed\ConfigurableBundleGui\Communication\Tabs\ConfigurableBundleTemplateEditTabs;
use Spryker\Zed\ConfigurableBundleGui\ConfigurableBundleGuiDependencyProvider;
use Spryker\Zed\ConfigurableBundleGui\Dependency\Facade\ConfigurableBundleGuiToConfigurableBundleFacadeInterface;
use Spryker\Zed\ConfigurableBundleGui\Dependency\Facade\ConfigurableBundleGuiToGlossaryFacadeInterface;
use Spryker\Zed\ConfigurableBundleGui\Dependency\Facade\ConfigurableBundleGuiToLocaleFacadeInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Symfony\Component\Form\FormInterface;

/**
 * @method \Spryker\Zed\ConfigurableBundleGui\Persistence\ConfigurableBundleGuiQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ConfigurableBundleGui\ConfigurableBundleGuiConfig getConfig()
 * @method \Spryker\Zed\ConfigurableBundleGui\Business\ConfigurableBundleGuiFacadeInterface getFacade()
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
     * @return \Spryker\Zed\ConfigurableBundleGui\Communication\Table\ConfigurableBundleTemplateSlotTable
     */
    public function createConfigurableBundleTemplateSlotTable(): ConfigurableBundleTemplateSlotTable
    {
        return new ConfigurableBundleTemplateSlotTable($this->getConfigurableBundleTemplatePropelQuery());
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
     * @return \Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplateSlotQuery
     */
    public function getConfigurableBundleTemplatePropelQuery(): SpyConfigurableBundleTemplateSlotQuery
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
