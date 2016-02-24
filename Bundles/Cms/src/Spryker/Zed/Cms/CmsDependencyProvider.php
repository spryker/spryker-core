<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms;

use Spryker\Zed\Cms\Dependency\Facade\CmsToGlossaryBridge;
use Spryker\Zed\Cms\Dependency\Facade\CmsToLocaleBridge;
use Spryker\Zed\Cms\Dependency\Facade\CmsToTouchBridge;
use Spryker\Zed\Cms\Dependency\Facade\CmsToUrlBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Propel\Communication\Plugin\Connection;

class CmsDependencyProvider extends AbstractBundleDependencyProvider
{

    const FACADE_URL = 'facade_url';
    const FACADE_LOCALE = 'facade_locale';
    const FACADE_GLOSSARY = 'facade glossary';
    const FACADE_TOUCH = 'facade_touch';
    const QUERY_CONTAINER_URL = 'url_query_container';
    const QUERY_CONTAINER_GLOSSARY = 'glossary_query_container';
    const QUERY_CONTAINER_CATEGORY = 'category query container';

    const PLUGIN_PROPEL_CONNECTION = 'propel connection plugin';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container[self::FACADE_URL] = function (Container $container) {
            return new CmsToUrlBridge($container->getLocator()->url()->facade());
        };

        $container[self::FACADE_LOCALE] = function (Container $container) {
            return new CmsToLocaleBridge($container->getLocator()->locale()->facade());
        };

        $container[self::FACADE_GLOSSARY] = function (Container $container) {
            return new CmsToGlossaryBridge($container->getLocator()->glossary()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[self::PLUGIN_PROPEL_CONNECTION] = function (Container $container) {
            return (new Connection())->get();
        };

        $container[self::FACADE_TOUCH] = function (Container $container) {
            return new CmsToTouchBridge($container->getLocator()->touch()->facade());
        };

        $container[self::FACADE_GLOSSARY] = function (Container $container) {
            return new CmsToGlossaryBridge($container->getLocator()->glossary()->facade());
        };

        $container[self::FACADE_URL] = function (Container $container) {
            return new CmsToUrlBridge($container->getLocator()->url()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container)
    {
        $container[self::QUERY_CONTAINER_URL] = function (Container $container) {
            return $container->getLocator()->url()->queryContainer();
        };

        $container[self::QUERY_CONTAINER_GLOSSARY] = function (Container $container) {
            return $container->getLocator()->glossary()->queryContainer();
        };

        $container[self::QUERY_CONTAINER_CATEGORY] = function (Container $container) {
            return $container->getLocator()->category()->queryContainer();
        };
    }

}
