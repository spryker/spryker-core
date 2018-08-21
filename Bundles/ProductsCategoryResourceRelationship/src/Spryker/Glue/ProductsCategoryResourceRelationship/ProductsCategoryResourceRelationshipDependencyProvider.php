<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductsCategoryResourceRelationship;

use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;
use Spryker\Glue\ProductsCategoryResourceRelationship\Dependency\RestResource\ProductsCategoryResourceRelationToCategoriesRestApiBridge;

class ProductsCategoryResourceRelationshipDependencyProvider extends AbstractBundleDependencyProvider
{
    public const RESOURCE_CATEGORY = 'RESOURCE_CATEGORY';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
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
            return new ProductsCategoryResourceRelationToCategoriesRestApiBridge(
                $container->getLocator()->categoriesRestApi()->resource()
            );
        };

        return $container;
    }
}
