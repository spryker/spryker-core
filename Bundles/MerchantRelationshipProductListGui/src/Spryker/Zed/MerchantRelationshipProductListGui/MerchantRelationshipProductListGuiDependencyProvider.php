<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipProductListGui;

use Orm\Zed\MerchantRelationship\Persistence\SpyMerchantRelationshipQuery;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\MerchantRelationshipProductListGui\Dependency\Facade\MerchantRelationshipProductListGuiToMerchantRelationshipFacadeBridge;

class MerchantRelationshipProductListGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_MERCHANT_RELATION = 'FACADE_MERCHANT_RELATION';
    public const PROPEL_QUERY_MERCHANT_RELATION = 'PROPEL_QUERY_MERCHANT_RELATION';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = $this->addMerchantRelationFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container): Container
    {
        $container = $this->addMerchantRelationPropelQuery($container);
        $container = $this->addMerchantRelationFacade($container);

        return $container;
    }

    /**
     * @module MerchantRelation
     *
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMerchantRelationPropelQuery(Container $container): Container
    {
        $container[static::PROPEL_QUERY_MERCHANT_RELATION] = function (Container $container) {
            return new SpyMerchantRelationshipQuery();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMerchantRelationFacade(Container $container): Container
    {
        $container[static::FACADE_MERCHANT_RELATION] = function (Container $container) {
            return new MerchantRelationshipProductListGuiToMerchantRelationshipFacadeBridge(
                $container->getLocator()->merchantRelationship()->facade()
            );
        };

        return $container;
    }
}
