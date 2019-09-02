<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundleGui\Communication;

use Spryker\Zed\ConfigurableBundleGui\Communication\Form\ConfigurableBundleTemplateForm;
use Spryker\Zed\ConfigurableBundleGui\Communication\Form\DataProvider\ConfigurableBundleTemplateFormDataProvider;
use Spryker\Zed\ConfigurableBundleGui\ConfigurableBundleGuiDependencyProvider;
use Spryker\Zed\ConfigurableBundleGui\Dependency\Facade\ConfigurableBundleGuiToConfigurableBundleFacadeInterface;
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
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getConfigurableBundleTemplateForm(): FormInterface
    {
        $dataProvider = $this->createConfigurableBundleTemplateFormDataProvider();

        return $this->getFormFactory()->create(
            ConfigurableBundleTemplateForm::class,
            $dataProvider->getData(),
            $dataProvider->getOptions()
        );
    }

    /**
     * @return \Spryker\Zed\ConfigurableBundleGui\Communication\Form\DataProvider\ConfigurableBundleTemplateFormDataProvider
     */
    public function createConfigurableBundleTemplateFormDataProvider(): ConfigurableBundleTemplateFormDataProvider
    {
        return new ConfigurableBundleTemplateFormDataProvider($this->getLocaleFacade());
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
}
