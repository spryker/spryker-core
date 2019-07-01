<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\EntityTagsRestApi;

use Spryker\Glue\EntityTagsRestApi\Dependency\Client\EntityTagsRestApiToEntityTagClientBridge;
use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;

/**
 * @method \Spryker\Glue\EntityTagsRestApi\EntityTagsRestApiConfig getConfig()
 */
class EntityTagsRestApiDependencyProvider extends AbstractBundleDependencyProvider
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
            return new EntityTagsRestApiToEntityTagClientBridge(
                $container->getLocator()->entityTag()->client()
            );
        };

        return $container;
    }
}
