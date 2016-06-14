<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payolution\Dependency\Injector;

use Spryker\Shared\Kernel\ContainerInterface;
use Spryker\Shared\Kernel\Dependency\Injector\DependencyInjectorInterface;
use Spryker\Zed\Oms\Communication\Plugin\Oms\Command\CommandCollectionInterface;
use Spryker\Zed\Oms\Communication\Plugin\Oms\Condition\ConditionCollectionInterface;
use Spryker\Zed\Oms\OmsDependencyProvider;
use Spryker\Zed\Payolution\Communication\Plugin\Oms\Command\CapturePlugin;
use Spryker\Zed\Payolution\Communication\Plugin\Oms\Command\PreAuthorizePlugin;
use Spryker\Zed\Payolution\Communication\Plugin\Oms\Command\ReAuthorizePlugin;
use Spryker\Zed\Payolution\Communication\Plugin\Oms\Command\RefundPlugin;
use Spryker\Zed\Payolution\Communication\Plugin\Oms\Command\RevertPlugin;
use Spryker\Zed\Payolution\Communication\Plugin\Oms\Condition\IsCaptureApprovedPlugin;
use Spryker\Zed\Payolution\Communication\Plugin\Oms\Condition\IsPreAuthorizationApprovedPlugin;
use Spryker\Zed\Payolution\Communication\Plugin\Oms\Condition\IsReAuthorizationApprovedPlugin;
use Spryker\Zed\Payolution\Communication\Plugin\Oms\Condition\IsRefundApprovedPlugin;
use Spryker\Zed\Payolution\Communication\Plugin\Oms\Condition\IsReversalApprovedPlugin;

class OmsDependencyInjector implements DependencyInjectorInterface
{

    /**
     * @param \Spryker\Shared\Kernel\ContainerInterface|\Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Shared\Kernel\ContainerInterface|\Spryker\Yves\Kernel\Container
     */
    public function inject(ContainerInterface $container)
    {
        $container = $this->injectCommands($container);
        $container = $this->injectConditions($container);

        return $container;
    }

    /**
     * @param \Spryker\Shared\Kernel\ContainerInterface $container
     *
     * @return \Spryker\Shared\Kernel\ContainerInterface
     */
    protected function injectCommands(ContainerInterface $container)
    {
        $container->extend(OmsDependencyProvider::COMMAND_PLUGINS, function (CommandCollectionInterface $commandCollection) {
            $commandCollection
                ->add(new PreAuthorizePlugin(), 'Payolution/PreAuthorize')
                ->add(new ReAuthorizePlugin(), 'Payolution/ReAuthorize')
                ->add(new RevertPlugin(), 'Payolution/Revert')
                ->add(new CapturePlugin(), 'Payolution/Capture')
                ->add(new RefundPlugin(), 'Payolution/Refund');

            return $commandCollection;
        });

        return $container;
    }

    /**
     * @param \Spryker\Shared\Kernel\ContainerInterface $container
     *
     * @return \Spryker\Shared\Kernel\ContainerInterface
     */
    protected function injectConditions(ContainerInterface $container)
    {
        $container->extend(OmsDependencyProvider::CONDITION_PLUGINS, function (ConditionCollectionInterface $conditionCollection) {
            $conditionCollection
                ->add(new IsPreAuthorizationApprovedPlugin(), 'Payolution/IsPreAuthorizationApproved')
                ->add(new IsReAuthorizationApprovedPlugin(), 'Payolution/IsReAuthorizationApproved')
                ->add(new IsReversalApprovedPlugin(), 'Payolution/IsReversalApproved')
                ->add(new IsCaptureApprovedPlugin(), 'Payolution/IsCaptureApproved')
                ->add(new IsRefundApprovedPlugin(), 'Payolution/IsRefundApproved');

            return $conditionCollection;
        });

        return $container;
    }

}
