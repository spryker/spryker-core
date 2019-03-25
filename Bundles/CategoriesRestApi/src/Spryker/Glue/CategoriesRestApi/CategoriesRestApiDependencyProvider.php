<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CategoriesRestApi;

use Spryker\Glue\CategoriesRestApi\Dependency\Client\CategoriesRestApiToCategoryStorageClientBridge;
use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;

/**
 * @method \Spryker\Glue\CategoriesRestApi\CategoriesRestApiConfig getConfig()
 */
class CategoriesRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_CATEGORY_STORAGE = 'CLIENT_CATEGORY_STORAGE';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = $this->addCategoryStorageClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addCategoryStorageClient(Container $container): Container
    {
        $container[static::CLIENT_CATEGORY_STORAGE] = function (Container $container) {
            return new CategoriesRestApiToCategoryStorageClientBridge(
                $container->getLocator()->categoryStorage()->client()
            );
        };

        return $container;
    }
}
