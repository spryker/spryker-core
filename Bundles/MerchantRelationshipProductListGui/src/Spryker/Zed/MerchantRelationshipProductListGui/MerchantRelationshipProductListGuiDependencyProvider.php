<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipProductListGui;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\MerchantRelationshipProductListGui\Dependency\Facade\MerchantRelationshipProductListGuiToMerchantRelationshipFacadeBridge;
use Spryker\Zed\MerchantRelationshipProductListGui\Dependency\Facade\MerchantRelationshipProductListGuiToMerchantRelationshipProductListFacadeBridge;

/**
 * @method \Spryker\Zed\MerchantRelationshipProductListGui\MerchantRelationshipProductListGuiConfig getConfig()
 */
class MerchantRelationshipProductListGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_MERCHANT_RELATIONSHIP = 'FACADE_MERCHANT_RELATIONSHIP';
    public const FACADE_MERCHANT_RELATIONSHIP_PRODUCT_LIST = 'FACADE_MERCHANT_RELATIONSHIP_PRODUCT_LIST';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = parent::provideCommunicationLayerDependencies($container);
        $container = $this->addMerchantRelationshipFacade($container);
        $container = $this->addMerchantRelationshipProductListFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMerchantRelationshipFacade(Container $container): Container
    {
        $container[static::FACADE_MERCHANT_RELATIONSHIP] = function ($container) {
            return new MerchantRelationshipProductListGuiToMerchantRelationshipFacadeBridge($container->getLocator()->merchantRelationship()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMerchantRelationshipProductListFacade(Container $container): Container
    {
        $container->set(static::FACADE_MERCHANT_RELATIONSHIP_PRODUCT_LIST, function ($container) {
            return new MerchantRelationshipProductListGuiToMerchantRelationshipProductListFacadeBridge($container->getLocator()->merchantRelationshipProductList()->facade());
        });

        return $container;
    }
}
