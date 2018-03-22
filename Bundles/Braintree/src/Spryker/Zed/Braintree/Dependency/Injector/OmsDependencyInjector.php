<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Braintree\Dependency\Injector;

use Spryker\Zed\Braintree\Communication\Plugin\Oms\Command\AuthorizePlugin;
use Spryker\Zed\Braintree\Communication\Plugin\Oms\Command\CapturePlugin;
use Spryker\Zed\Braintree\Communication\Plugin\Oms\Command\RefundPlugin;
use Spryker\Zed\Braintree\Communication\Plugin\Oms\Command\RevertPlugin;
use Spryker\Zed\Braintree\Communication\Plugin\Oms\Condition\IsAuthorizationApprovedPlugin;
use Spryker\Zed\Braintree\Communication\Plugin\Oms\Condition\IsCaptureApprovedPlugin;
use Spryker\Zed\Braintree\Communication\Plugin\Oms\Condition\IsRefundApprovedPlugin;
use Spryker\Zed\Braintree\Communication\Plugin\Oms\Condition\IsReversalApprovedPlugin;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Kernel\Dependency\Injector\AbstractDependencyInjector;
use Spryker\Zed\Oms\Communication\Plugin\Oms\Command\CommandCollectionInterface;
use Spryker\Zed\Oms\Dependency\Plugin\Condition\ConditionCollectionInterface;
use Spryker\Zed\Oms\OmsDependencyProvider;

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
                ->add(new AuthorizePlugin(), 'Braintree/Authorize')
                ->add(new RevertPlugin(), 'Braintree/Revert')
                ->add(new CapturePlugin(), 'Braintree/Capture')
                ->add(new RefundPlugin(), 'Braintree/Refund');

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
                ->add(new IsAuthorizationApprovedPlugin(), 'Braintree/IsAuthorizationApproved')
                ->add(new IsReversalApprovedPlugin(), 'Braintree/IsReversalApproved')
                ->add(new IsCaptureApprovedPlugin(), 'Braintree/IsCaptureApproved')
                ->add(new IsRefundApprovedPlugin(), 'Braintree/IsRefundApproved');

            return $conditionCollection;
        });

        return $container;
    }
}
