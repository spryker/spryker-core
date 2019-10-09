<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductReviewsRestApi;

use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;
use Spryker\Glue\ProductReviewsRestApi\Dependency\Client\ProductReviewsRestApiToProductReviewStorageClientBridge;

/**
 * @method \Spryker\Glue\ProductReviewsRestApi\ProductReviewsRestApiConfig getConfig()
 */
class ProductReviewsRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_PRODUCT_REVIEW_STORAGE = 'CLIENT_PRODUCT_REVIEW_STORAGE';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);

        $container = $this->addProductReviewStorageClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function addProductReviewStorageClient(Container $container): Container
    {
        $container[static::CLIENT_PRODUCT_REVIEW_STORAGE] = function (Container $container) {
            return new ProductReviewsRestApiToProductReviewStorageClientBridge(
                $container->getLocator()->productReviewStorage()->client()
            );
        };

        return $container;
    }
}
