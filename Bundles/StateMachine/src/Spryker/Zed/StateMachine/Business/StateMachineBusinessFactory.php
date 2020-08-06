<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StateMachine\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\StateMachine\Business\Graph\Drawer;
use Spryker\Zed\StateMachine\Business\Lock\ItemLock;
use Spryker\Zed\StateMachine\Business\Logger\PathFinder;
use Spryker\Zed\StateMachine\Business\Logger\TransitionLog;
use Spryker\Zed\StateMachine\Business\Process\Event;
use Spryker\Zed\StateMachine\Business\Process\Process;
use Spryker\Zed\StateMachine\Business\Process\State;
use Spryker\Zed\StateMachine\Business\Process\Transition;
use Spryker\Zed\StateMachine\Business\StateMachine\Builder;
use Spryker\Zed\StateMachine\Business\StateMachine\Condition;
use Spryker\Zed\StateMachine\Business\StateMachine\Finder;
use Spryker\Zed\StateMachine\Business\StateMachine\HandlerResolver;
use Spryker\Zed\StateMachine\Business\StateMachine\LockedTrigger;
use Spryker\Zed\StateMachine\Business\StateMachine\Persistence;
use Spryker\Zed\StateMachine\Business\StateMachine\StateUpdater;
use Spryker\Zed\StateMachine\Business\StateMachine\Timeout;
use Spryker\Zed\StateMachine\Business\StateMachine\Trigger;
use Spryker\Zed\StateMachine\StateMachineConfig;
use Spryker\Zed\StateMachine\StateMachineDependencyProvider;

/**
 * @method \Spryker\Zed\StateMachine\StateMachineConfig getConfig()
 * @method \Spryker\Zed\StateMachine\Persistence\StateMachineQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\StateMachine\Persistence\StateMachineRepositoryInterface getRepository()
 */
class StateMachineBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\StateMachine\Business\StateMachine\TriggerInterface
     */
    public function createLockedStateMachineTrigger()
    {
        return new LockedTrigger(
            $this->createStateMachineTrigger(),
            $this->createItemLock()
        );
    }

    /**
     * @return \Spryker\Zed\StateMachine\Business\StateMachine\TriggerInterface
     */
    public function createStateMachineTrigger()
    {
        return new Trigger(
            $this->createLoggerTransitionLog(),
            $this->createHandlerResolver(),
            $this->createStateMachineFinder(),
            $this->createStateMachinePersistence(),
            $this->createStateMachineCondition(),
            $this->createStateUpdater()
        );
    }

    /**
     * @return \Spryker\Zed\StateMachine\Business\Lock\ItemLockInterface
     */
    public function createItemLock()
    {
        return new ItemLock(
            $this->getQueryContainer(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\StateMachine\Business\StateMachine\ConditionInterface
     */
    public function createStateMachineCondition()
    {
        return new Condition(
            $this->createLoggerTransitionLog(),
            $this->createHandlerResolver(),
            $this->createStateMachineFinder(),
            $this->createStateMachinePersistence(),
            $this->createStateUpdater()
        );
    }

    /**
     * @return \Spryker\Zed\StateMachine\Business\StateMachine\StateUpdaterInterface
     */
    public function createStateUpdater()
    {
        return new StateUpdater(
            $this->createStateMachineTimeout(),
            $this->createHandlerResolver(),
            $this->createStateMachinePersistence(),
            $this->getQueryContainer()
        );
    }

    /**
     * @return \Spryker\Zed\StateMachine\Business\StateMachine\BuilderInterface
     */
    public function createStateMachineBuilder()
    {
        return new Builder(
            $this->createProcessEvent(),
            $this->createProcessState(),
            $this->createProcessTransition(),
            $this->createProcessProcess(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\StateMachine\Business\StateMachine\FinderInterface
     */
    public function createStateMachineFinder()
    {
        return new Finder(
            $this->createStateMachineBuilder(),
            $this->createHandlerResolver(),
            $this->getQueryContainer()
        );
    }

    /**
     * @return \Spryker\Zed\StateMachine\Business\StateMachine\TimeoutInterface
     */
    public function createStateMachineTimeout()
    {
        return new Timeout(
            $this->createStateMachinePersistence()
        );
    }

    /**
     * @return \Spryker\Zed\StateMachine\Business\Logger\TransitionLogInterface
     */
    public function createLoggerTransitionLog()
    {
        return new TransitionLog(
            $this->createPathFinder(),
            $this->getUtilNetworkService()
        );
    }

    /**
     * @return \Spryker\Zed\StateMachine\Business\StateMachine\PersistenceInterface
     */
    public function createStateMachinePersistence()
    {
        return new Persistence($this->getQueryContainer());
    }

    /**
     * @return \Spryker\Zed\StateMachine\Business\Process\EventInterface
     */
    public function createProcessEvent()
    {
        return new Event();
    }

    /**
     * @return \Spryker\Zed\StateMachine\Business\Process\StateInterface
     */
    public function createProcessState()
    {
        return new State();
    }

    /**
     * @return \Spryker\Zed\StateMachine\Business\Process\TransitionInterface
     */
    public function createProcessTransition()
    {
        return new Transition();
    }

    /**
     * @return \Spryker\Zed\StateMachine\Business\Process\ProcessInterface
     */
    public function createProcessProcess()
    {
        return new Process();
    }

    /**
     * @return \Spryker\Zed\StateMachine\Business\Logger\PathFinder
     */
    protected function createPathFinder()
    {
        return new PathFinder();
    }

    /**
     * @param string $stateMachineName
     *
     * @return \Spryker\Zed\StateMachine\Business\Graph\DrawerInterface
     */
    public function createGraphDrawer($stateMachineName)
    {
        return new Drawer(
            $this->getGraph()->init(StateMachineConfig::GRAPH_NAME, $this->getConfig()->getGraphDefaults(), true, false),
            $this->createHandlerResolver()->get($stateMachineName)
        );
    }

    /**
     * @return \Spryker\Zed\StateMachine\Business\StateMachine\HandlerResolverInterface
     */
    protected function createHandlerResolver()
    {
        return new HandlerResolver($this->getStateMachineHandlerPlugins());
    }

    /**
     * @return \Spryker\Zed\Graph\Communication\Plugin\GraphPlugin
     */
    protected function getGraph()
    {
        return $this->getProvidedDependency(StateMachineDependencyProvider::PLUGIN_GRAPH);
    }

    /**
     * @return \Spryker\Zed\StateMachine\Dependency\Plugin\StateMachineHandlerInterface[]
     */
    public function getStateMachineHandlerPlugins()
    {
        return $this->getProvidedDependency(StateMachineDependencyProvider::PLUGINS_STATE_MACHINE_HANDLERS);
    }

    /**
     * @return \Spryker\Service\UtilNetwork\UtilNetworkServiceInterface
     */
    protected function getUtilNetworkService()
    {
        return $this->getProvidedDependency(StateMachineDependencyProvider::SERVICE_NETWORK);
    }
}
