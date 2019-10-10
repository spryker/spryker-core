<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductReviewsRestApi;

use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;
use Spryker\Glue\ProductReviewsRestApi\Dependency\Client\ProductReviewsRestApiToProductReviewClientBridge;
use Spryker\Glue\ProductReviewsRestApi\Dependency\Client\ProductReviewsRestApiToProductReviewStorageClientBridge;
use Spryker\Glue\ProductReviewsRestApi\Dependency\Client\ProductReviewsRestApiToProductStorageClientBridge;

/**
 * @method \Spryker\Glue\ProductReviewsRestApi\ProductReviewsRestApiConfig getConfig()
 */
class ProductReviewsRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_PRODUCT_REVIEW_STORAGE = 'CLIENT_PRODUCT_REVIEW_STORAGE';
    public const CLIENT_PRODUCT_STORAGE = 'CLIENT_PRODUCT_STORAGE';
    public const CLIENT_PRODUCT_REVIEW = 'CLIENT_PRODUCT_REVIEW';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);

        $container = $this->addProductReviewStorageClient($container);
        $container = $this->addProductStorageClientDependency($container);
        $container = $this->addProductReviewClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function addProductReviewStorageClient(Container $container): Container
    {
        $container->set(static::CLIENT_PRODUCT_REVIEW_STORAGE, function (Container $container) {
            return new ProductReviewsRestApiToProductReviewStorageClientBridge(
                $container->getLocator()->productReviewStorage()->client()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addProductStorageClientDependency(Container $container): Container
    {
        $container->set(static::CLIENT_PRODUCT_STORAGE, function (Container $container) {
            return new ProductReviewsRestApiToProductStorageClientBridge(
                $container->getLocator()->productStorage()->client()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addProductReviewClient(Container $container)
    {
        $container->set(static::CLIENT_PRODUCT_REVIEW, function (Container $container) {
            return new ProductReviewsRestApiToProductReviewClientBridge(
                $container->getLocator()->productReview()->client()
            );
        });

        return $container;
    }
}
