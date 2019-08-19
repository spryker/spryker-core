<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundleStorage;

use Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplateQuery;
use Spryker\Zed\ConfigurableBundleStorage\Dependency\Facade\ConfigurableBundleStorageToEventBehaviorFacadeBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\ConfigurableBundleStorage\ConfigurableBundleStorageConfig getConfig()
 */
class ConfigurableBundleStorageDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_EVENT_BEHAVIOR = 'FACADE_EVENT_BEHAVIOR';
    public const FACADE_CONFIGURABLE_BUNDLE = 'FACADE_CONFIGURABLE_BUNDLE';
    public const PROPEL_QUERY_CONFIGURABLE_BUNDLE_TEMPLATE = 'PROPEL_QUERY_CONFIGURABLE_BUNDLE_TEMPLATE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container): Container
    {
        $container = parent::providePersistenceLayerDependencies($container);
        $container = $this->addConfigurableBundleTemplatePropelQuery($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = parent::provideCommunicationLayerDependencies($container);
        $container = $this->addEventBehaviorFacade($container);

        return $container;
    }

    /**
     * @module ConfigurableBundle
     *
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addConfigurableBundleTemplatePropelQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_CONFIGURABLE_BUNDLE_TEMPLATE, $container->factory(function () {
            return SpyConfigurableBundleTemplateQuery::create();
        }));

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addEventBehaviorFacade(Container $container): Container
    {
        $container->set(static::FACADE_EVENT_BEHAVIOR, function (Container $container) {
            return new ConfigurableBundleStorageToEventBehaviorFacadeBridge(
                $container->getLocator()->eventBehavior()->facade()
            );
        });

        return $container;
    }
}
