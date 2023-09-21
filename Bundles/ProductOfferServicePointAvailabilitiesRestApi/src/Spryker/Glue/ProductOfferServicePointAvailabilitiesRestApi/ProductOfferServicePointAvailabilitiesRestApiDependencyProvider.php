<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductOfferServicePointAvailabilitiesRestApi;

use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;
use Spryker\Glue\ProductOfferServicePointAvailabilitiesRestApi\Dependency\Client\ProductOfferServicePointAvailabilitiesRestApiToProductOfferServicePointAvailabilityCalculatorClientBridge;

/**
 * @method \Spryker\Glue\ProductOfferServicePointAvailabilitiesRestApi\ProductOfferServicePointAvailabilitiesRestApiConfig getConfig()
 */
class ProductOfferServicePointAvailabilitiesRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const CLIENT_PRODUCT_OFFER_SERVICE_POINT_AVAILABILITY_CALCULATOR = 'CLIENT_PRODUCT_OFFER_SERVICE_POINT_AVAILABILITY_CALCULATOR';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);

        $container = $this->addProductOfferServicePointAvailabilityCalculatorClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addProductOfferServicePointAvailabilityCalculatorClient(Container $container): Container
    {
        $container->set(static::CLIENT_PRODUCT_OFFER_SERVICE_POINT_AVAILABILITY_CALCULATOR, function (Container $container) {
            return new ProductOfferServicePointAvailabilitiesRestApiToProductOfferServicePointAvailabilityCalculatorClientBridge(
                $container->getLocator()->productOfferServicePointAvailabilityCalculator()->client(),
            );
        });

        return $container;
    }
}
