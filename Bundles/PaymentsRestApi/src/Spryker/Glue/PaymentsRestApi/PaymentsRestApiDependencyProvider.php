<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PaymentsRestApi;

use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;
use Spryker\Glue\PaymentsRestApi\Dependency\Client\PaymentsRestApiToPaymentAppClientBridge;
use Spryker\Glue\PaymentsRestApi\Dependency\Client\PaymentsRestApiToPaymentClientBridge;

/**
 * @method \Spryker\Glue\PaymentsRestApi\PaymentsRestApiConfig getConfig()
 */
class PaymentsRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const CLIENT_PAYMENT = 'PAYMENTS_REST_API:CLIENT_PAYMENT';

    /**
     * @var string
     */
    public const CLIENT_PAYMENT_APP = 'PAYMENTS_REST_API:CLIENT_PAYMENT_APP';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = $this->addPaymentClient($container);

        return $this->addPaymentAppClient($container);
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addPaymentClient(Container $container): Container
    {
        $container->set(static::CLIENT_PAYMENT, function (Container $container) {
            return new PaymentsRestApiToPaymentClientBridge($container->getLocator()->payment()->client());
        });

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addPaymentAppClient(Container $container): Container
    {
        $container->set(static::CLIENT_PAYMENT_APP, function (Container $container) {
            return new PaymentsRestApiToPaymentAppClientBridge($container->getLocator()->paymentApp()->client());
        });

        return $container;
    }
}
