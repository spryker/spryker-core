<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\NavigationCategoryNodesResourceRelationship;

use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;
use Spryker\Glue\NavigationCategoryNodesResourceRelationship\Dependency\RestResource\NavigationCategoryNodesResourceRelationshipToCategoriesRestApiResourceBridge;

/**
 * @method \Spryker\Glue\NavigationCategoryNodesResourceRelationship\NavigationCategoryNodesResourceRelationshipConfig getConfig()
 */
class NavigationCategoryNodesResourceRelationshipDependencyProvider extends AbstractBundleDependencyProvider
{
    public const RESOURCE_CATEGORIES = 'RESOURCE_CATEGORIES';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);
        $container = $this->addCategoriesResource($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addCategoriesResource(Container $container): Container
    {
        $container[static::RESOURCE_CATEGORIES] = function (Container $container) {
            return new NavigationCategoryNodesResourceRelationshipToCategoriesRestApiResourceBridge(
                $container->getLocator()->categoriesRestApi()->resource()
            );
        };

        return $container;
    }
}
