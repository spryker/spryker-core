<?php

/**
 * (c) Spryker Systems GmbH copyright protected.
 */

namespace Spryker\Zed\Cms;

use Spryker\Zed\Propel\Communication\Plugin\Connection;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class CmsDependencyProvider extends AbstractBundleDependencyProvider
{

    const FACADE_URL = 'facade_url';
    const FACADE_LOCALE = 'facade_locale';
    const FACADE_GLOSSARY = 'facade glossary';
    const QUERY_CONTAINER_URL = 'url_query_container';
    const QUERY_CONTAINER_GLOSSARY = 'glossary_query_container';
    const QUERY_CONTAINER_CATEGORY = 'category query container';

    const PLUGIN_PROPEL_CONNECTION = 'propel connection plugin';

    /**
     * @param Container $container
     *
     * @return Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container[self::FACADE_URL] = function (Container $container) {
            return $container->getLocator()->url()->facade();
        };

        $container[self::FACADE_LOCALE] = function (Container $container) {
            return $container->getLocator()->locale()->facade();
        };

        $container[self::FACADE_GLOSSARY] = function (Container $container) {
            return $container->getLocator()->glossary()->facade();
        };

        return $container;
    }

    /**
     * @param Container $container
     *
     * @return Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[self::PLUGIN_PROPEL_CONNECTION] = function (Container $container) {
            return (new Connection())->get();
        };

        return $container;
    }

    /**
     * @param Container $container
     *
     * @return Container
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
