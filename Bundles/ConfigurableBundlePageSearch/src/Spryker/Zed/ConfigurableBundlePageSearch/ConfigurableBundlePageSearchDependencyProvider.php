<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundlePageSearch;

use Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplateQuery;
use Spryker\Zed\ConfigurableBundlePageSearch\Dependency\Facade\ConfigurableBundlePageSearchToConfigurableBundleFacadeBridge;
use Spryker\Zed\ConfigurableBundlePageSearch\Dependency\Facade\ConfigurableBundlePageSearchToEvenBehaviorFacadeBridge;
use Spryker\Zed\ConfigurableBundlePageSearch\Dependency\Facade\ConfigurableBundlePageSearchToSearchFacadeBridge;
use Spryker\Zed\ConfigurableBundlePageSearch\Dependency\Service\ConfigurableBundlePageSearchToUtilEncodingServiceBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\ConfigurableBundlePageSearch\ConfigurableBundlePageSearchConfig getConfig()
 */
class ConfigurableBundlePageSearchDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_CONFIGURABLE_BUNDLE = 'FACADE_CONFIGURABLE_BUNDLE';
    public const FACADE_EVENT_BEHAVIOR = 'FACADE_EVENT_BEHAVIOR';
    public const FACADE_SEARCH = 'FACADE_SEARCH';

    public const SERVICE_UTIL_ENCODING = 'SERVICE_UTIL_ENCODING';

    public const PROPEL_QUERY_CONFIGURABLE_BUNDLE_TEMPLATE = 'PROPEL_QUERY_CONFIGURABLE_BUNDLE_TEMPLATE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addConfigurableBundleFacade($container);
        $container = $this->addConfigurableBundleFacade($container);
        $container = $this->addSearchFacade($container);
        $container = $this->addUtilEncodingService($container);

        return $container;
    }

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
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addConfigurableBundleFacade(Container $container): Container
    {
        $container->set(static::FACADE_CONFIGURABLE_BUNDLE, function (Container $container) {
            return new ConfigurableBundlePageSearchToConfigurableBundleFacadeBridge(
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
    protected function addEventBehaviorFacade(Container $container): Container
    {
        $container->set(static::FACADE_EVENT_BEHAVIOR, function (Container $container) {
            return new ConfigurableBundlePageSearchToEvenBehaviorFacadeBridge(
                $container->getLocator()->eventBehavior()->facade()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addSearchFacade(Container $container): Container
    {
        $container->set(static::FACADE_SEARCH, function (Container $container) {
            return new ConfigurableBundlePageSearchToSearchFacadeBridge(
                $container->getLocator()->search()->facade()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUtilEncodingService(Container $container): Container
    {
        $container->set(static::SERVICE_UTIL_ENCODING, function (Container $container) {
            return new ConfigurableBundlePageSearchToUtilEncodingServiceBridge(
                $container->getLocator()->utilEncoding()->service()
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
        $container->set(static::PROPEL_QUERY_CONFIGURABLE_BUNDLE_TEMPLATE, $container->factory(function () {
            return SpyConfigurableBundleTemplateQuery::create();
        }));

        return $container;
    }
}
