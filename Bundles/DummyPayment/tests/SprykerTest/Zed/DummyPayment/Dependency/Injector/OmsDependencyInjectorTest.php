<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\DummyPayment\Dependency\Injector;

use Codeception\Test\Unit;
use Spryker\Zed\DummyPayment\Dependency\Injector\OmsDependencyInjector;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Oms\Communication\Plugin\Oms\Command\CommandCollection;
use Spryker\Zed\Oms\Communication\Plugin\Oms\Condition\ConditionCollection;
use Spryker\Zed\Oms\OmsDependencyProvider;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group DummyPayment
 * @group Dependency
 * @group Injector
 * @group OmsDependencyInjectorTest
 * Add your own group annotations below this line
 */
class OmsDependencyInjectorTest extends Unit
{
    /**
     * @return void
     */
    public function testInjectBusinessLayerDependenciesShouldAddCommands(): void
    {
        $container = new Container();
        $omsDependencyProvider = new OmsDependencyProvider();
        $omsDependencyProvider->provideBusinessLayerDependencies($container);

        $omsDependencyInjector = new OmsDependencyInjector();
        $omsDependencyInjector->injectBusinessLayerDependencies($container);

        $commandCollection = $this->getCommandCollectionFromContainer($container);
        $commandCollection->has('DummyPayment/Refund');
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Oms\Communication\Plugin\Oms\Command\CommandCollection
     */
    protected function getCommandCollectionFromContainer(Container $container): CommandCollection
    {
        $commandCollection = $container[OmsDependencyProvider::COMMAND_PLUGINS];

        return $commandCollection;
    }

    /**
     * @return void
     */
    public function testInjectBusinessLayerDependenciesShouldAddConditions(): void
    {
        $container = new Container();
        $omsDependencyProvider = new OmsDependencyProvider();
        $omsDependencyProvider->provideBusinessLayerDependencies($container);

        $omsDependencyInjector = new OmsDependencyInjector();
        $omsDependencyInjector->injectBusinessLayerDependencies($container);

        $conditionCollection = $this->getConditionCollectionFromContainer($container);
        $conditionCollection->has('DummyPayment/IsAuthorized');
        $conditionCollection->has('DummyPayment/IsPayed');
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Oms\Communication\Plugin\Oms\Condition\ConditionCollection
     */
    protected function getConditionCollectionFromContainer(Container $container): ConditionCollection
    {
        $conditionCollection = $container[OmsDependencyProvider::CONDITION_PLUGINS];

        return $conditionCollection;
    }
}
