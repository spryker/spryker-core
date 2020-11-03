<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductAttributesRestApi;

use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;
use Spryker\Glue\ProductAttributesRestApi\Dependency\Client\ProductAttributesRestApiToProductAttributeClientBridge;

/**
 * @method \Spryker\Glue\ProductAttributesRestApi\ProductAttributesRestApiConfig getConfig()
 */
class ProductAttributesRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_PRODUCT_ATTRIBUTE = 'CLIENT_PRODUCT_ATTRIBUTE';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);

        $container = $this->addProductAttributeClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addProductAttributeClient(Container $container): Container
    {
        $container->set(static::CLIENT_PRODUCT_ATTRIBUTE, function (Container $container) {
            return new ProductAttributesRestApiToProductAttributeClientBridge(
                $container->getLocator()->productAttribute()->client()
            );
        });

        return $container;
    }
}
