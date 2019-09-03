<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundleGui;

use Orm\Zed\ConfigurableBundle\Persistence\Base\SpyConfigurableBundleTemplateQuery;
use Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplateSlotQuery;
use Spryker\Zed\ConfigurableBundleGui\Dependency\Facade\ConfigurableBundleGuiToConfigurableBundleFacadeBridge;
use Spryker\Zed\ConfigurableBundleGui\Dependency\Facade\ConfigurableBundleGuiToConfigurableBundleFacadeInterface;
use Spryker\Zed\ConfigurableBundleGui\Dependency\Facade\ConfigurableBundleGuiToGlossaryFacadeBridge;
use Spryker\Zed\ConfigurableBundleGui\Dependency\Facade\ConfigurableBundleGuiToGlossaryFacadeInterface;
use Spryker\Zed\ConfigurableBundleGui\Dependency\Facade\ConfigurableBundleGuiToLocaleFacadeBridge;
use Spryker\Zed\ConfigurableBundleGui\Dependency\Facade\ConfigurableBundleGuiToLocaleFacadeInterface;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\ConfigurableBundleGui\ConfigurableBundleGuiConfig getConfig()
 */
class ConfigurableBundleGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_CONFIGURABLE_BUNDLE = 'FACADE_CONFIGURABLE_BUNDLE';
    public const FACADE_LOCALE = 'FACADE_LOCALE';
    public const FACADE_GLOSSARY = 'FACADE_GLOSSARY';
    public const PROPEL_QUERY_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT = 'PROPEL_QUERY_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT';
    public const PROPEL_QUERY_CONFIGURABLE_BUNDLE_TEMPLATE = 'PROPEL_QUERY_CONFIGURABLE_BUNDLE_TEMPLATE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = parent::provideCommunicationLayerDependencies($container);
        $container = $this->addConfigurableBundleFacade($container);
        $container = $this->addLocaleFacade($container);
        $container = $this->addGlossaryFacade($container);
        $container = $this->addConfigurableBundleTemplatePropelQuery($container);
        $container = $this->addConfigurableBundleTemplateSlotPropelQuery($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function addConfigurableBundleFacade(Container $container): Container
    {
        $container->set(static::FACADE_CONFIGURABLE_BUNDLE, function (Container $container): ConfigurableBundleGuiToConfigurableBundleFacadeInterface {
            return new ConfigurableBundleGuiToConfigurableBundleFacadeBridge(
                $container->getLocator()->configurableBundle()->facade()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function addLocaleFacade(Container $container): Container
    {
        $container->set(static::FACADE_LOCALE, function (Container $container): ConfigurableBundleGuiToLocaleFacadeInterface {
            return new ConfigurableBundleGuiToLocaleFacadeBridge(
                $container->getLocator()->locale()->facade()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function addGlossaryFacade(Container $container): Container
    {
        $container->set(static::FACADE_GLOSSARY, function (Container $container): ConfigurableBundleGuiToGlossaryFacadeInterface {
            return new ConfigurableBundleGuiToGlossaryFacadeBridge(
                $container->getLocator()->glossary()->facade()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addConfigurableBundleTemplatePropelQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_CONFIGURABLE_BUNDLE_TEMPLATE, function () {
            return SpyConfigurableBundleTemplateQuery::create();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function addConfigurableBundleTemplateSlotPropelQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT, function (Container $container): SpyConfigurableBundleTemplateSlotQuery {
            return SpyConfigurableBundleTemplateSlotQuery::create();
        });

        return $container;
    }
}
