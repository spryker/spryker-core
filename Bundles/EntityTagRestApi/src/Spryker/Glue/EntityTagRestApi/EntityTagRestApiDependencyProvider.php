<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\EntityTagRestApi;

use Spryker\Glue\EntityTagRestApi\Dependency\Client\EntityTagRestApiToEntityTagClientBridge;
use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;

/**
 * @method \Spryker\Glue\EntityTagRestApi\EntityTagRestApiConfig getConfig()
 */
class EntityTagRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_ENTITY_TAG = 'CLIENT_ENTITY_TAG';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);
        $container = $this->addEntityTagClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addEntityTagClient(Container $container): Container
    {
        $container[static::CLIENT_ENTITY_TAG] = function (Container $container) {
            return new EntityTagRestApiToEntityTagClientBridge(
                $container->getLocator()->entityTag()->client()
            );
        };

        return $container;
    }
}
