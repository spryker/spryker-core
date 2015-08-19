<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Cms;

use SprykerEngine\Client\Kernel\AbstractDependencyProvider;
use SprykerEngine\Client\Kernel\Container;

class CmsDependencyProvider extends AbstractDependencyProvider
{
    const KV_STORAGE = 'kv storage';

    /**
     * @param Container $container
     *
     * @return Container
     */
    public function provideServiceLayerDependencies(Container $container)
    {
        $container[self::KV_STORAGE] = function (Container $container) {
            return $container->getLocator()->storage()->client();
        };

        return $container;
    }
}
