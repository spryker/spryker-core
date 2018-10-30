<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductLabelsRestApi;

use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;
use Spryker\Glue\ProductLabelsRestApi\Dependency\Client\ProductLabelsRestApiToProductLabelStorageClientBridge;
use Spryker\Glue\ProductLabelsRestApi\Dependency\Client\ProductLabelsRestApiToProductStorageClientBridge;

class ProductLabelsRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_PRODUCT_STORAGE = 'CLIENT_PRODUCT_STORAGE';
    public const CLIENT_PRODUCT_LABEL_STORAGE = 'CLIENT_PRODUCT_LABEL_STORAGE';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);

        $container = $this->addProductStorageClientDependency($container);
        $container = $this->addProductLabelStorageClientDependency($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addProductStorageClientDependency(Container $container): Container
    {
        $container[static::CLIENT_PRODUCT_STORAGE] = function (Container $container) {
            return new ProductLabelsRestApiToProductStorageClientBridge(
                $container->getLocator()->productStorage()->client()
            );
        };

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addProductLabelStorageClientDependency(Container $container): Container
    {
        $container[static::CLIENT_PRODUCT_LABEL_STORAGE] = function (Container $container) {
            return new ProductLabelsRestApiToProductLabelStorageClientBridge(
                $container->getLocator()->productLabelStorage()->client()
            );
        };

        return $container;
    }
}
