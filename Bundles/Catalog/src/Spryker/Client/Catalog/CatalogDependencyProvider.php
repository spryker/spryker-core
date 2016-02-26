<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Catalog;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;

class CatalogDependencyProvider extends AbstractDependencyProvider
{

    const INDEX = 'index';
    const KVSTORAGE = 'kvstorage';
    const CLIENT_SEARCH = 'search client';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container)
    {
        $container = parent::provideServiceLayerDependencies($container);

        // TODO: remove this dependency
        $container[self::INDEX] = function (Container $container) {
            return $container->getLocator()->search()->client()->getIndexClient();
        };

        $container[self::KVSTORAGE] = function (Container $container) {
            return $container->getLocator()->storage()->client();
        };

        $container[self::CLIENT_SEARCH] = function (Container $container) {
            return $container->getLocator()->search()->client();
        };

        return $container;
    }

}
