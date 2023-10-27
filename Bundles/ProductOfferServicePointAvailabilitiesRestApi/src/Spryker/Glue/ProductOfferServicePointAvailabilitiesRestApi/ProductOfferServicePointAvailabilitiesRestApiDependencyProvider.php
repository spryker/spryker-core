<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductOfferServicePointAvailabilitiesRestApi;

use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;
use Spryker\Glue\ProductOfferServicePointAvailabilitiesRestApi\Dependency\Client\ProductOfferServicePointAvailabilitiesRestApiToProductOfferServicePointAvailabilityCalculatorStorageClientBridge;

/**
 * @method \Spryker\Glue\ProductOfferServicePointAvailabilitiesRestApi\ProductOfferServicePointAvailabilitiesRestApiConfig getConfig()
 */
class ProductOfferServicePointAvailabilitiesRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const CLIENT_PRODUCT_OFFER_SERVICE_POINT_AVAILABILITY_CALCULATOR_STORAGE = 'CLIENT_PRODUCT_OFFER_SERVICE_POINT_AVAILABILITY_CALCULATOR_STORAGE';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);

        $container = $this->addProductOfferServicePointAvailabilityCalculatorStorageClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addProductOfferServicePointAvailabilityCalculatorStorageClient(Container $container): Container
    {
        $container->set(static::CLIENT_PRODUCT_OFFER_SERVICE_POINT_AVAILABILITY_CALCULATOR_STORAGE, function (Container $container) {
            return new ProductOfferServicePointAvailabilitiesRestApiToProductOfferServicePointAvailabilityCalculatorStorageClientBridge(
                $container->getLocator()->productOfferServicePointAvailabilityCalculatorStorage()->client(),
            );
        });

        return $container;
    }
}
