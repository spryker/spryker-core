<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Cms;

use SprykerEngine\Zed\Kernel\AbstractBundleDependencyProvider;
use SprykerEngine\Zed\Kernel\Container;

class CmsDependencyProvider extends AbstractBundleDependencyProvider
{
    const URL_BUNDLE = 'url_bundle';
    const LOCALE_BUNDLE = 'locale_bundle';

    /**
     * @param Container $container
     *
     * @return Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container[self::URL_BUNDLE] = function (Container $container) {
            return $container->getLocator()->url()->facade();
        };

        $container[self::LOCALE_BUNDLE] = function (Container $container) {
            return $container->getLocator()->locale()->facade();
        };

        return $container;
    }
}
