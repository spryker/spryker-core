<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\DummyPayment\Dependency\Injector;

use Spryker\Zed\DummyPayment\Dependency\Injector\OmsDependencyInjector;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Oms\OmsDependencyProvider;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group DummyPayment
 * @group Dependency
 * @group Injector
 * @group OmsDependencyInjectorTest
 */
class OmsDependencyInjectorTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testInjectBusinessLayerDependenciesShouldAddCommands()
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
    protected function getCommandCollectionFromContainer($container)
    {
        $commandCollection = $container[OmsDependencyProvider::COMMAND_PLUGINS];

        return $commandCollection;
    }

    /**
     * @return void
     */
    public function testInjectBusinessLayerDependenciesShouldAddConditions()
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
    protected function getConditionCollectionFromContainer($container)
    {
        $conditionCollection = $container[OmsDependencyProvider::CONDITION_PLUGINS];

        return $conditionCollection;
    }

}
