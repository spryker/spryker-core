<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundleGui;

use Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplateQuery;
use Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplateSlotQuery;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Spryker\Zed\ConfigurableBundleGui\Dependency\Facade\ConfigurableBundleGuiToConfigurableBundleFacadeBridge;
use Spryker\Zed\ConfigurableBundleGui\Dependency\Facade\ConfigurableBundleGuiToConfigurableBundleFacadeInterface;
use Spryker\Zed\ConfigurableBundleGui\Dependency\Facade\ConfigurableBundleGuiToGlossaryFacadeBridge;
use Spryker\Zed\ConfigurableBundleGui\Dependency\Facade\ConfigurableBundleGuiToGlossaryFacadeInterface;
use Spryker\Zed\ConfigurableBundleGui\Dependency\Facade\ConfigurableBundleGuiToLocaleFacadeBridge;
use Spryker\Zed\ConfigurableBundleGui\Dependency\Facade\ConfigurableBundleGuiToLocaleFacadeInterface;
use Spryker\Zed\ConfigurableBundleGui\Dependency\Facade\ConfigurableBundleGuiToProductListFacadeBridge;
use Spryker\Zed\ConfigurableBundleGui\Dependency\Facade\ConfigurableBundleGuiToProductListFacadeInterface;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductListGui\Communication\Plugin\ConfigurableBundleGui\ProductConcreteRelationConfigurableBundleTemplateSlotEditSubTabsProviderPlugin;
use Spryker\Zed\ProductListGui\Communication\Plugin\ConfigurableBundleGui\ProductConcreteRelationConfigurableBundleTemplateSlotEditTablesProviderPlugin;
use Spryker\Zed\ProductListGui\Communication\Plugin\ConfigurableBundleGui\ProductListManagementConfigurableBundleTemplateSlotEditFormDataProviderExpanderPlugin;
use Spryker\Zed\ProductListGui\Communication\Plugin\ConfigurableBundleGui\ProductListManagementConfigurableBundleTemplateSlotEditFormExpanderPlugin;
use Spryker\Zed\ProductListGui\Communication\Plugin\ConfigurableBundleGui\ProductListManagementConfigurableBundleTemplateSlotEditTabsExpanderPlugin;

/**
 * @method \Spryker\Zed\ConfigurableBundleGui\ConfigurableBundleGuiConfig getConfig()
 */
class ConfigurableBundleGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_CONFIGURABLE_BUNDLE = 'FACADE_CONFIGURABLE_BUNDLE';
    public const FACADE_LOCALE = 'FACADE_LOCALE';
    public const FACADE_GLOSSARY = 'FACADE_GLOSSARY';
    public const FACADE_PRODUCT_LIST = 'FACADE_PRODUCT_LIST';
    public const PROPEL_QUERY_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT = 'PROPEL_QUERY_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT';
    public const PROPEL_QUERY_CONFIGURABLE_BUNDLE_TEMPLATE = 'PROPEL_QUERY_CONFIGURABLE_BUNDLE_TEMPLATE';
    public const PROPEL_QUERY_PRODUCT = 'PROPEL_QUERY_PRODUCT';

    public const PLUGINS_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT_EDIT_TABS_EXPANDER = 'PLUGINS_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT_EDIT_TABS_EXPANDER';
    public const PLUGINS_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT_EDIT_FORM_EXPANDER = 'PLUGINS_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT_EDIT_FORM_EXPANDER';
    public const PLUGINS_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT_EDIT_FORM_DATA_PROVIDER_EXPANDER = 'PLUGINS_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT_EDIT_FORM_DATA_PROVIDER_EXPANDER';
    public const PLUGINS_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT_EDIT_SUB_TABS_PROVIDER = 'PLUGINS_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT_EDIT_SUB_TABS_PROVIDER';
    public const PLUGINS_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT_EDIT_TABLES_PROVIDER = 'PLUGINS_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT_EDIT_TABLES_PROVIDER';

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
        $container = $this->addProductListFacade($container);
        $container = $this->addConfigurableBundleTemplatePropelQuery($container);
        $container = $this->addConfigurableBundleTemplateSlotPropelQuery($container);
        $container = $this->addProductPropelQuery($container);
        $container = $this->addConfigurableBundleTemplateSlotEditTabsExpanderPlugins($container);
        $container = $this->addConfigurableBundleTemplateSlotEditFormExpanderPlugins($container);
        $container = $this->addConfigurableBundleTemplateSlotEditFormDataProviderExpanderPlugins($container);
        $container = $this->addConfigurableBundleTemplateSlotEditSubTabsProviderPlugins($container);
        $container = $this->addConfigurableBundleTemplateSlotEditTablesProviderPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addConfigurableBundleFacade(Container $container): Container
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
    protected function addLocaleFacade(Container $container): Container
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
    protected function addGlossaryFacade(Container $container): Container
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
    protected function addProductListFacade(Container $container): Container
    {
        $container->set(static::FACADE_PRODUCT_LIST, function (Container $container): ConfigurableBundleGuiToProductListFacadeInterface {
            return new ConfigurableBundleGuiToProductListFacadeBridge(
                $container->getLocator()->productList()->facade()
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
        $container->set(static::PROPEL_QUERY_CONFIGURABLE_BUNDLE_TEMPLATE, $container->factory(function (): SpyConfigurableBundleTemplateQuery {
            return SpyConfigurableBundleTemplateQuery::create();
        }));

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addConfigurableBundleTemplateSlotPropelQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT, $container->factory(function (): SpyConfigurableBundleTemplateSlotQuery {
            return SpyConfigurableBundleTemplateSlotQuery::create();
        }));

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductPropelQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_PRODUCT, $container->factory(function (): SpyProductQuery {
            return SpyProductQuery::create();
        }));

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addConfigurableBundleTemplateSlotEditTabsExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT_EDIT_TABS_EXPANDER, function () {
            return $this->getConfigurableBundleTemplateSlotEditTabsExpanderPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addConfigurableBundleTemplateSlotEditFormExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT_EDIT_FORM_EXPANDER, function () {
            return $this->getConfigurableBundleTemplateSlotEditFormExpanderPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addConfigurableBundleTemplateSlotEditFormDataProviderExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT_EDIT_FORM_DATA_PROVIDER_EXPANDER, function () {
            return $this->getConfigurableBundleTemplateSlotEditFormDataProviderExpanderPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addConfigurableBundleTemplateSlotEditSubTabsProviderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT_EDIT_SUB_TABS_PROVIDER, function () {
            return $this->getConfigurableBundleTemplateSlotEditSubTabsProviderPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addConfigurableBundleTemplateSlotEditTablesProviderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT_EDIT_TABLES_PROVIDER, function () {
            return $this->getConfigurableBundleTemplateSlotEditTablesProviderPlugins();
        });

        return $container;
    }

    /**
     * @return \Spryker\Zed\ConfigurableBundleGuiExtension\Dependency\Plugin\ConfigurableBundleTemplateSlotEditTabsExpanderPluginInterface[]
     */
    protected function getConfigurableBundleTemplateSlotEditTabsExpanderPlugins(): array
    {
        return [
            new ProductListManagementConfigurableBundleTemplateSlotEditTabsExpanderPlugin(),
        ];
    }

    /**
     * @return \Spryker\Zed\ConfigurableBundleGuiExtension\Dependency\Plugin\ConfigurableBundleTemplateSlotEditFormExpanderPluginInterface[]
     */
    protected function getConfigurableBundleTemplateSlotEditFormExpanderPlugins(): array
    {
        return [
            new ProductListManagementConfigurableBundleTemplateSlotEditFormExpanderPlugin(),
        ];
    }

    /**
     * @return \Spryker\Zed\ConfigurableBundleGuiExtension\Dependency\Plugin\ConfigurableBundleTemplateSlotEditFormDataProviderExpanderPluginInterface[]
     */
    protected function getConfigurableBundleTemplateSlotEditFormDataProviderExpanderPlugins(): array
    {
        return [
            new ProductListManagementConfigurableBundleTemplateSlotEditFormDataProviderExpanderPlugin(),
        ];
    }

    /**
     * @return \Spryker\Zed\ConfigurableBundleGuiExtension\Dependency\Plugin\ConfigurableBundleTemplateSlotEditSubTabsProviderPluginInterface[]
     */
    protected function getConfigurableBundleTemplateSlotEditSubTabsProviderPlugins(): array
    {
        return [
            new ProductConcreteRelationConfigurableBundleTemplateSlotEditSubTabsProviderPlugin(),
        ];
    }

    /**
     * @return \Spryker\Zed\ConfigurableBundleGuiExtension\Dependency\Plugin\ConfigurableBundleTemplateSlotEditTablesProviderPluginInterface[]
     */
    protected function getConfigurableBundleTemplateSlotEditTablesProviderPlugins(): array
    {
        return [
            new ProductConcreteRelationConfigurableBundleTemplateSlotEditTablesProviderPlugin(),
        ];
    }
}
