<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CategoriesRestApi;

use Spryker\Glue\CategoriesRestApi\Dependency\Client\CategoriesRestApiToCategoryStorageClientBridge;
use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;

class CategoriesRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CATEGORY_STORAGE_CLIENT = 'CATEGORY_STORAGE_CLIENT';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container[static::CATEGORY_STORAGE_CLIENT] = function () use ($container) {
            return new CategoriesRestApiToCategoryStorageClientBridge(
                $container->getLocator()->categoryStorage()->client()
            );
        };
        return $container;
    }
}
