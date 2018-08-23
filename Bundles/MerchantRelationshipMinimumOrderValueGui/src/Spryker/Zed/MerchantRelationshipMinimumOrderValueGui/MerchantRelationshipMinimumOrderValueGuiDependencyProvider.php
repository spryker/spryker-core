<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipMinimumOrderValueGui;

use Orm\Zed\MerchantRelationship\Persistence\SpyMerchantRelationshipQuery;
use Orm\Zed\MerchantRelationshipMinimumOrderValue\Persistence\SpyMerchantRelationshipMinimumOrderValueQuery;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Dependency\Facade\MerchantRelationshipMinimumOrderValueGuiToCurrencyFacadeBridge;
use Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Dependency\Facade\MerchantRelationshipMinimumOrderValueGuiToLocaleFacadeBridge;
use Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Dependency\Facade\MerchantRelationshipMinimumOrderValueGuiToMinimumOrderValueFacadeBridge;
use Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Dependency\Facade\MerchantRelationshipMinimumOrderValueGuiToMoneyFacadeBridge;
use Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Dependency\Facade\MerchantRelationshipMinimumOrderValueGuiToStoreFacadeBridge;

class MerchantRelationshipMinimumOrderValueGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_CURRENCY = 'FACADE_CURRENCY';
    public const FACADE_STORE = 'FACADE_STORE';
    public const FACADE_MINIMUM_ORDER_VALUE = 'FACADE_MINIMUM_ORDER_VALUE';
    public const FACADE_MONEY = 'FACADE_MONEY';
    public const FACADE_LOCALE = 'FACADE_LOCALE';
    public const PROPEL_QUERY_MERCHANT_RELATIONSHIP = 'PROPEL_QUERY_MERCHANT_RELATIONSHIP';
    public const PROPEL_QUERY_MERCHANT_RELATIONSHIP_MINIMUM_ORDER_VALUE = 'PROPEL_QUERY_MERCHANT_RELATIONSHIP_MINIMUM_ORDER_VALUE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = parent::provideCommunicationLayerDependencies($container);

        $container = $this->addCurrencyFacade($container);
        $container = $this->addStoreFacade($container);
        $container = $this->addMinimumOrderValueFacade($container);
        $container = $this->addMoneyFacade($container);
        $container = $this->addLocaleFacade($container);
        $container = $this->addMerchantRelationshipPropelQuery($container);
        $container = $this->addMerchantRelationshipMinimumOrderValuePropelQuery($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCurrencyFacade(Container $container): Container
    {
        $container[static::FACADE_CURRENCY] = function (Container $container) {
            return new MerchantRelationshipMinimumOrderValueGuiToCurrencyFacadeBridge($container->getLocator()->currency()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addStoreFacade(Container $container): Container
    {
        $container[static::FACADE_STORE] = function (Container $container) {
            return new MerchantRelationshipMinimumOrderValueGuiToStoreFacadeBridge($container->getLocator()->store()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMinimumOrderValueFacade(Container $container): Container
    {
        $container[static::FACADE_MINIMUM_ORDER_VALUE] = function (Container $container) {
            return new MerchantRelationshipMinimumOrderValueGuiToMinimumOrderValueFacadeBridge($container->getLocator()->minimumOrderValue()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMoneyFacade(Container $container): Container
    {
        $container[static::FACADE_MONEY] = function (Container $container) {
            return new MerchantRelationshipMinimumOrderValueGuiToMoneyFacadeBridge($container->getLocator()->money()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addLocaleFacade(Container $container): Container
    {
        $container[static::FACADE_LOCALE] = function (Container $container) {
            return new MerchantRelationshipMinimumOrderValueGuiToLocaleFacadeBridge($container->getLocator()->locale()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMerchantRelationshipPropelQuery(Container $container): Container
    {
        $container[static::PROPEL_QUERY_MERCHANT_RELATIONSHIP] = function () {
            return SpyMerchantRelationshipQuery::create();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMerchantRelationshipMinimumOrderValuePropelQuery(Container $container): Container
    {
        $container[static::PROPEL_QUERY_MERCHANT_RELATIONSHIP_MINIMUM_ORDER_VALUE] = function () {
            return SpyMerchantRelationshipMinimumOrderValueQuery::create();
        };

        return $container;
    }
}
