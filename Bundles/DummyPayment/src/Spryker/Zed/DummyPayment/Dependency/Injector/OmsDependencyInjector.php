<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DummyPayment\Dependency\Injector;

use Spryker\Zed\DummyPayment\Communication\Plugin\Oms\Command\PayPlugin;
use Spryker\Zed\DummyPayment\Communication\Plugin\Oms\Command\RefundPlugin;
use Spryker\Zed\DummyPayment\Communication\Plugin\Oms\Condition\IsAuthorizedPlugin;
use Spryker\Zed\DummyPayment\Communication\Plugin\Oms\Condition\IsPayedPlugin;
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
            $commandCollection->add(new RefundPlugin(), 'DummyPayment/Refund');
            $commandCollection->add(new PayPlugin(), 'DummyPayment/Pay');

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
            $conditionCollection->add(new IsAuthorizedPlugin(), 'DummyPayment/IsAuthorized');
            $conditionCollection->add(new IsPayedPlugin(), 'DummyPayment/IsPayed');

            return $conditionCollection;
        });

        return $container;
    }
}
