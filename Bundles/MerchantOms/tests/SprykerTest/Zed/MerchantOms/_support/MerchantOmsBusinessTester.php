<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantOms;

use Codeception\Actor;
use Spryker\Service\UtilNetwork\UtilNetworkService;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\MerchantOms\Business\MerchantOmsBusinessFactory;
use Spryker\Zed\MerchantOms\Business\MerchantOmsFacade;
use Spryker\Zed\MerchantOms\Business\MerchantOmsFacadeInterface;
use Spryker\Zed\MerchantOms\Dependency\Facade\MerchantOmsToStateMachineFacadeBridge;
use Spryker\Zed\MerchantOms\MerchantOmsDependencyProvider;
use Spryker\Zed\StateMachine\Business\StateMachineBusinessFactory;
use Spryker\Zed\StateMachine\Business\StateMachineFacade;
use Spryker\Zed\StateMachine\Business\StateMachineFacadeInterface;
use Spryker\Zed\StateMachine\Dependency\Plugin\StateMachineHandlerInterface;
use Spryker\Zed\StateMachine\StateMachineDependencyProvider;
use SprykerTest\Zed\MerchantOms\Mocks\MerchantOmsConfigMock;
use SprykerTest\Zed\MerchantOms\Mocks\StateMachineConfigMock;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 *
 * @SuppressWarnings(PHPMD)
 */
class MerchantOmsBusinessTester extends Actor
{
    use _generated\MerchantOmsBusinessTesterActions;

    /**
     * Define custom actions here
     */

    /**
     * @param \Spryker\Zed\StateMachine\Dependency\Plugin\StateMachineHandlerInterface $stateMachineHandler
     * @param string $merchantOmsDefaultProcessName
     *
     * @return \Spryker\Zed\MerchantOms\Business\MerchantOmsFacadeInterface
     */
    public function createMerchantOmsFacade(StateMachineHandlerInterface $stateMachineHandler, string $merchantOmsDefaultProcessName): MerchantOmsFacadeInterface
    {
        $merchantOmsFacade = new MerchantOmsFacade();
        $merchantOmsConfig = new MerchantOmsConfigMock();
        $merchantOmsBusinessFactory = new MerchantOmsBusinessFactory();
        $merchantOmsDependencyProvider = new MerchantOmsDependencyProvider();
        $container = new Container();

        $merchantOmsDependencyProvider->provideBusinessLayerDependencies($container);
        $merchantOmsConfig->setMerchantOmsDefaultProcessName($merchantOmsDefaultProcessName);
        $container->set(MerchantOmsDependencyProvider::FACADE_STATE_MACHINE, function () use ($stateMachineHandler) {
            return new MerchantOmsToStateMachineFacadeBridge($this->createStateMachineFacade($stateMachineHandler));
        });
        $merchantOmsBusinessFactory->setContainer($container);
        $merchantOmsBusinessFactory->setConfig($merchantOmsConfig);
        $merchantOmsFacade->setFactory($merchantOmsBusinessFactory);

        return $merchantOmsFacade;
    }

    /**
     * @param \Spryker\Zed\StateMachine\Dependency\Plugin\StateMachineHandlerInterface $stateMachineHandler
     *
     * @return \Spryker\Zed\StateMachine\Business\StateMachineFacade
     */
    protected function createStateMachineFacade(StateMachineHandlerInterface $stateMachineHandler): StateMachineFacadeInterface
    {
        $stateMachineBusinessFactory = new StateMachineBusinessFactory();
        $stateMachineConfig = new StateMachineConfigMock();
        $stateMachineBusinessFactory->setConfig($stateMachineConfig);

        $container = new Container();
        $container->set(StateMachineDependencyProvider::PLUGINS_STATE_MACHINE_HANDLERS, function () use ($stateMachineHandler) {
            return [$stateMachineHandler];
        });

        $container->set(StateMachineDependencyProvider::SERVICE_NETWORK, function () {
            return new UtilNetworkService();
        });

        $stateMachineBusinessFactory->setContainer($container);

        $stateMachineFacade = new StateMachineFacade();
        $stateMachineFacade->setFactory($stateMachineBusinessFactory);

        return $stateMachineFacade;
    }
}
