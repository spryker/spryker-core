<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Dependency\Injector;

use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Kernel\Dependency\Injector\AbstractDependencyInjector;
use Spryker\Zed\Oms\Communication\Plugin\Oms\Command\CommandCollectionInterface;
use Spryker\Zed\Oms\Dependency\Plugin\Condition\ConditionCollectionInterface;
use Spryker\Zed\Oms\OmsDependencyProvider;
use Spryker\Zed\Ratepay\Communication\Plugin\Oms\Command\CancelPaymentPlugin;
use Spryker\Zed\Ratepay\Communication\Plugin\Oms\Command\ConfirmDeliveryPlugin;
use Spryker\Zed\Ratepay\Communication\Plugin\Oms\Command\ConfirmPaymentPlugin;
use Spryker\Zed\Ratepay\Communication\Plugin\Oms\Command\PaymentRequestPlugin;
use Spryker\Zed\Ratepay\Communication\Plugin\Oms\Command\RefundPaymentPlugin;
use Spryker\Zed\Ratepay\Communication\Plugin\Oms\Condition\IsCancellationConfirmedPlugin;
use Spryker\Zed\Ratepay\Communication\Plugin\Oms\Condition\IsDeliveryConfirmedPlugin;
use Spryker\Zed\Ratepay\Communication\Plugin\Oms\Condition\IsPaymentConfirmedPlugin;
use Spryker\Zed\Ratepay\Communication\Plugin\Oms\Condition\IsPaymentRequestPlugin;
use Spryker\Zed\Ratepay\Communication\Plugin\Oms\Condition\IsRefundedPlugin;

class OmsDependencyInjector extends AbstractDependencyInjector
{
    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function injectBusinessLayerDependencies(Container $container)
    {
        $container = $this->injectCommands($container);
        $container = $this->injectConditions($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function injectCommands(Container $container)
    {
        $container->extend(OmsDependencyProvider::COMMAND_PLUGINS, function (CommandCollectionInterface $commandCollection) {
            $commandCollection
                ->add(new PaymentRequestPlugin(), 'Ratepay/PaymentRequest')
                ->add(new CancelPaymentPlugin(), 'Ratepay/CancelOrder')
                ->add(new ConfirmPaymentPlugin(), 'Ratepay/ConfirmPayment')
                ->add(new ConfirmDeliveryPlugin(), 'Ratepay/ConfirmDelivery')
                ->add(new RefundPaymentPlugin(), 'Ratepay/Refund');

            return $commandCollection;
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function injectConditions(Container $container)
    {
        $container->extend(OmsDependencyProvider::CONDITION_PLUGINS, function (ConditionCollectionInterface $conditionCollection) {
            $conditionCollection
                ->add(new IsPaymentRequestPlugin(), 'Ratepay/IsPaymentRequestSuccess')
                ->add(new IsCancellationConfirmedPlugin(), 'Ratepay/IsCancellationConfirmed')
                ->add(new IsPaymentConfirmedPlugin(), 'Ratepay/IsPaymentConfirmed')
                ->add(new IsDeliveryConfirmedPlugin(), 'Ratepay/IsDeliveryConfirmed')
                ->add(new IsRefundedPlugin(), 'Ratepay/IsRefunded');

            return $conditionCollection;
        });

        return $container;
    }
}
