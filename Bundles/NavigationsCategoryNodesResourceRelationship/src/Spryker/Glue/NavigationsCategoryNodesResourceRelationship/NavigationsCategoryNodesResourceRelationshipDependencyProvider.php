<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\NavigationsCategoryNodesResourceRelationship;

use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;
use Spryker\Glue\NavigationsCategoryNodesResourceRelationship\Dependency\RestResource\NavigationsCategoryNodesResourceRelationshipToCategoriesRestApiResourceBridge;

/**
 * @method \Spryker\Glue\NavigationsCategoryNodesResourceRelationship\NavigationsCategoryNodesResourceRelationshipConfig getConfig()
 */
class NavigationsCategoryNodesResourceRelationshipDependencyProvider extends AbstractBundleDependencyProvider
{
    public const RESOURCE_CATEGORIES_REST_API = 'RESOURCE_CATEGORIES_REST_API';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);
        $container = $this->addCategoriesRestApiResource($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addCategoriesRestApiResource(Container $container): Container
    {
        $container[static::RESOURCE_CATEGORIES_REST_API] = function (Container $container) {
            return new NavigationsCategoryNodesResourceRelationshipToCategoriesRestApiResourceBridge(
                $container->getLocator()->categoriesRestApi()->resource()
            );
        };

        return $container;
    }
}
