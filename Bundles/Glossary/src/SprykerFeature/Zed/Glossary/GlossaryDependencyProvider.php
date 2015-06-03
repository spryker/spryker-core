<?php

/*
 * (c) Copyright Spryker Systems GmbH 2015
 */

namespace SprykerFeature\Zed\Glossary;

use SprykerEngine\Zed\Kernel\AbstractBundleDependencyProvider;
use SprykerEngine\Zed\Kernel\Container;

class GlossaryDependencyProvider extends AbstractBundleDependencyProvider
{

    const TOUCH_FACADE = 'TOUCH_FACADE';

    const LOCALE_FACADE = 'LOCALE_FACADE';

    const PLUGIN_VALIDATOR = 'PLUGIN_VALIDATOR';


    /**
     * @param Container $container
     *
     * @return Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container[GlossaryDependencyProvider::LOCALE_FACADE] = function (Container $container) {
            return $container->getLocator()->locale()->facade();
        };

        $container[GlossaryDependencyProvider::PLUGIN_VALIDATOR] = function (Container $container) {
            return $container->getLocator()->application()->pluginPimple()->getApplication()['validator'];
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
        $container[GlossaryDependencyProvider::TOUCH_FACADE] = function (Container $container) {
            return $container->getLocator()->touch()->facade();
        };

        $container[GlossaryDependencyProvider::LOCALE_FACADE] = function (Container $container) {
            return $container->getLocator()->locale()->facade();
        };

        return $container;
    }

}
