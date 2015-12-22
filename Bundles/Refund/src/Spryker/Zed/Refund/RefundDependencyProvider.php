<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Refund;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Refund\Dependency\Facade\RefundToOmsBridge;
use Spryker\Zed\Refund\Dependency\Facade\RefundToSalesBridge;
use Spryker\Zed\Refund\Dependency\Plugin\PaymentDataPluginInterface;
use Symfony\Component\Intl\Exception\NotImplementedException;

class RefundDependencyProvider extends AbstractBundleDependencyProvider
{

    const QUERY_CONTAINER_REFUND = 'QUERY_CONTAINER_REFUND';
    const QUERY_CONTAINER_SALES = 'QUERY_CONTAINER_SALES';

    const FACADE_SALES = 'FACADE_SALES';
    const FACADE_OMS = 'FACADE_OMS';
    const FACADE_REFUND = 'FACADE_REFUND';

    /**
     * @param Container $container
     *
     * @return Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[self::FACADE_SALES] = function (Container $container) {
            return new RefundToSalesBridge($container->getLocator()->sales()->facade());
        };

        $container[static::FACADE_OMS] = function (Container $container) {
            return new RefundToOmsBridge($container->getLocator()->oms()->facade());
        };

        $container[static::QUERY_CONTAINER_REFUND] = function (Container $container) {
            return $container->getLocator()->refund()->queryContainer();
        };

        $container[static::QUERY_CONTAINER_SALES] = function (Container $container) {
            return $container->getLocator()->sales()->queryContainer();
        };

        return $container;
    }

    /**
     * @param Container $container
     *
     * @return Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container[static::QUERY_CONTAINER_REFUND] = function (Container $container) {
            return $container->getLocator()->refund()->queryContainer();
        };

        $container[static::QUERY_CONTAINER_SALES] = function (Container $container) {
            return $container->getLocator()->sales()->queryContainer();
        };

        return $container;
    }


    /**
     * @throws NotImplementedException
     *
     * @return PaymentDataPluginInterface
     */
    public function getPaymentDataPlugin()
    {
        throw new NotImplementedException('No Payment Data Plugin Provided. Please implement on project level.');
    }

}
