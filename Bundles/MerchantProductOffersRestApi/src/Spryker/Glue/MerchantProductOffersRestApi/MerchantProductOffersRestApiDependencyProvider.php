<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MerchantProductOffersRestApi;

use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;
use Spryker\Glue\MerchantProductOffersRestApi\Dependency\Client\MerchantProductOffersRestApiToMerchantProductOfferStorageClientBridge;

/**
 * @method \Spryker\Glue\MerchantProductOffersRestApi\MerchantProductOffersRestApiConfig getConfig()
 */
class MerchantProductOffersRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_MERCHANT_PRODUCT_OFFER_STORAGE = 'CLIENT_MERCHANT_PRODUCT_OFFER_STORAGE';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);
        $container = $this->addMerchantProductOfferStorageClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addMerchantProductOfferStorageClient(Container $container): Container
    {
        $container->set(static::CLIENT_MERCHANT_PRODUCT_OFFER_STORAGE, function (Container $container) {
            return new MerchantProductOffersRestApiToMerchantProductOfferStorageClientBridge(
                $container->getLocator()->merchantProductOfferStorage()->client()
            );
        });

        return $container;
    }
}
