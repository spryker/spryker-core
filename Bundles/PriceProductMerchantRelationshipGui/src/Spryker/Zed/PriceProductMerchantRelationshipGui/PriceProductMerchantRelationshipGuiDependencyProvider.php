<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationshipGui;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\PriceProductMerchantRelationshipGui\Dependency\Facade\PriceProductMerchantRelationshipGuiToMerchantRelationshipFacadeBridge;

/**
 * @method \Spryker\Zed\PriceProductMerchantRelationshipGui\PriceProductMerchantRelationshipGuiConfig getConfig()
 */
class PriceProductMerchantRelationshipGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_MERCHANT_RELATIONSHIP = 'FACADE_MERCHANT_RELATIONSHIP';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container = parent::provideCommunicationLayerDependencies($container);

        $container = $this->addMerchantRelationshipFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMerchantRelationshipFacade(Container $container)
    {
        $container[static::FACADE_MERCHANT_RELATIONSHIP] = function (Container $container) {
            return new PriceProductMerchantRelationshipGuiToMerchantRelationshipFacadeBridge(
                $container->getLocator()->merchantRelationship()->facade()
            );
        };

        return $container;
    }
}
