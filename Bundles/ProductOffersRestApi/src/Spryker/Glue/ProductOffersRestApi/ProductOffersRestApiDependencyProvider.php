<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductOffersRestApi;

use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;
use Spryker\Glue\ProductOffersRestApi\Dependency\Client\ProductOffersRestApiToProductOfferStorageClientBridge;

/**
 * @method \Spryker\Glue\ProductOffersRestApi\ProductOffersRestApiConfig getConfig()
 */
class ProductOffersRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const CLIENT_PRODUCT_OFFER_STORAGE = 'CLIENT_PRODUCT_OFFER_STORAGE';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);
        $container = $this->addProductOfferStorageClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addProductOfferStorageClient(Container $container): Container
    {
        $container->set(static::CLIENT_PRODUCT_OFFER_STORAGE, function (Container $container) {
            return new ProductOffersRestApiToProductOfferStorageClientBridge(
                $container->getLocator()->productOfferStorage()->client(),
            );
        });

        return $container;
    }
}
