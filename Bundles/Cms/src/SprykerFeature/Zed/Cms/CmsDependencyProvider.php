<?php

/**
 * (c) Spryker Systems GmbH copyright protected.
 */

namespace SprykerFeature\Zed\Cms;

use SprykerEngine\Zed\Kernel\AbstractBundleDependencyProvider;
use SprykerEngine\Zed\Kernel\Container;

class CmsDependencyProvider extends AbstractBundleDependencyProvider
{
    const FACADE_URL = 'facade_url';
    const FACADE_LOCALE = 'facade_locale';

    const URL_QUERY_CONTAINER = 'url_query_container';
    const GLOSSARY_QUERY_CONTAINER = 'glossary_query_container';

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

        return $container;
    }

    /**
     * @var Container $container
     *
     * @return Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[self::PLUGIN_PROPEL_CONNECTION] = function (Container $container) {
            return $container->getLocator()->propel()->pluginConnection();
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
        $container[self::URL_QUERY_CONTAINER] = function (Container $container) {
            return $container->getLocator()->url()->queryContainer();
        };

        $container[self::GLOSSARY_QUERY_CONTAINER] = function (Container $container) {
            return $container->getLocator()->glossary()->queryContainer();
        };
    }
}
