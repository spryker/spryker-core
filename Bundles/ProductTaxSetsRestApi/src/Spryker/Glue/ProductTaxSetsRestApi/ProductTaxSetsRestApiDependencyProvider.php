<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductTaxSetsRestApi;

use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;
use Spryker\Glue\ProductTaxSetsRestApi\Dependency\Client\ProductTaxSetsRestApiApiToTaxProductConnectorClientBridge;

class ProductTaxSetsRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_TAX_PRODUCT_CONNECTOR = 'CLIENT_TAX_PRODUCT_CONNECTOR';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);
        $container = $this->addTaxProductConnectorClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addTaxProductConnectorClient(Container $container): Container
    {
        $container[static::CLIENT_TAX_PRODUCT_CONNECTOR] = function (Container $container) {
            return new ProductTaxSetsRestApiApiToTaxProductConnectorClientBridge($container->getLocator()->taxProductConnector()->client());
        };

        return $container;
    }
}
