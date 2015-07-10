<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Catalog;

use SprykerEngine\Client\Kernel\AbstractDependencyProvider;
use SprykerEngine\Client\Kernel\Container;

class CatalogDependencyProvider extends AbstractDependencyProvider
{

    const INDEX = 'index';

    const  KVSTORAGE = 'kvstorage';

    /**
     * @param Container $container
     *
     * @return Container
     */
    public function provideServiceLayerDependencies(Container $container)
    {
        $container[self::INDEX] = function (Container $container) {
            return $container->getLocator()->search()->client()->getIndexClient();
        };

        $container[self::KVSTORAGE] = function (Container $container) {
            return $container->getLocator()->storage()->client();
        };

        return $container;
    }

}
