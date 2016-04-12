<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StateMachine\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\StateMachine\Business\Graph\Drawer;
use Spryker\Zed\StateMachine\Business\Logger\TransitionLog;
use Spryker\Zed\StateMachine\Business\Process\Event;
use Spryker\Zed\StateMachine\Business\Process\Process;
use Spryker\Zed\StateMachine\Business\Process\State;
use Spryker\Zed\StateMachine\Business\Process\Transition;
use Spryker\Zed\StateMachine\Business\StateMachine\Builder;
use Spryker\Zed\StateMachine\Business\StateMachine\Condition;
use Spryker\Zed\StateMachine\Business\StateMachine\Finder;
use Spryker\Zed\StateMachine\Business\StateMachine\HandlerResolver;
use Spryker\Zed\StateMachine\Business\StateMachine\Persistence;
use Spryker\Zed\StateMachine\Business\StateMachine\Timeout;
use Spryker\Zed\StateMachine\Business\StateMachine\Trigger;
use Spryker\Zed\StateMachine\StateMachineConfig;
use Spryker\Zed\StateMachine\StateMachineDependencyProvider;

/**
 * @method \Spryker\Zed\StateMachine\StateMachineConfig getConfig()
 * @method \Spryker\Zed\StateMachine\Persistence\StateMachineQueryContainerInterface getQueryContainer()
 */
class StateMachineBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @param string $stateMachineName
     *
     * @return \Spryker\Zed\StateMachine\Business\StateMachine\TriggerInterface
     */
    public function createStateMachineTrigger($stateMachineName)
    {
        return new Trigger(
            $this->createStateMachineBuilder($stateMachineName),
            $this->createLoggerTransitionLog(),
            $this->createHandlerResolver()->findHandler($stateMachineName),
            $this->createStateMachineFinder($stateMachineName),
            $this->createStateMachinePersistence($stateMachineName),
            $this->createStateMachineCondition($stateMachineName)
        );
    }

    /**
     * @param string $stateMachineName
     *
     * @return \Spryker\Zed\StateMachine\Business\StateMachine\ConditionInterface
     */
    public function createStateMachineCondition($stateMachineName)
    {
        return new Condition(
            $this->createStateMachineBuilder($stateMachineName),
            $this->createLoggerTransitionLog(),
            $this->createHandlerResolver()->findHandler($stateMachineName),
            $this->createStateMachineFinder($stateMachineName),
            $this->createStateMachinePersistence($stateMachineName)
        );
    }

    /**
     * @param string $stateMachineName
     * @return \Spryker\Zed\StateMachine\Business\StateMachine\BuilderInterface
     */
    public function createStateMachineBuilder($stateMachineName)
    {
        return new Builder(
            $this->createProcessEvent(),
            $this->createProcessState(),
            $this->createProcessTransition(),
            $this->createProcessProcess($stateMachineName),
            $this->getConfig()
        );
    }

    /**
     * @param string $stateMachineName
     * @return \Spryker\Zed\StateMachine\Business\StateMachine\FinderInterface
     */
    public function createStateMachineFinder($stateMachineName)
    {
        return new Finder(
            $this->createStateMachineBuilder($stateMachineName),
            $this->createHandlerResolver()->findHandler($stateMachineName),
            $this->getQueryContainer()
        );
    }

    /**
     * @return \Spryker\Zed\StateMachine\Business\StateMachine\TimeoutInterface
     */
    public function createStateMachineTimeout($stateMachineName)
    {
        return new Timeout(
            $this->getQueryContainer(),
            $this->createHandlerResolver()->findHandler($stateMachineName)
        );
    }

    /**
     * @return \Spryker\Zed\StateMachine\Business\Logger\TransitionLogInterface
     */
    public function createLoggerTransitionLog()
    {
        return new TransitionLog();
    }

    /**
     * @return \Spryker\Zed\StateMachine\Business\StateMachine\PersistenceInterface
     */
    public function createStateMachinePersistence($stateMachineName)
    {
        return new Persistence(
            $this->createStateMachineTimeout($stateMachineName),
            $this->createHandlerResolver()->findHandler($stateMachineName),
            $this->getQueryContainer()
        );
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
     * @param string $stateMachineName
     * @return \Spryker\Zed\StateMachine\Business\Process\ProcessInterface
     */
    public function createProcessProcess($stateMachineName)
    {
        return new Process($this->createGraphDrawer($stateMachineName));
    }

    /**
     * @param string $stateMachineName
     * @return \Spryker\Zed\StateMachine\Business\Graph\DrawerInterface
     */
    public function createGraphDrawer($stateMachineName)
    {
        return new Drawer(
            $this->getGraph()->init(StateMachineConfig::GRAPH_NAME, $this->getConfig()->getGraphDefaults(), true, false),
            $this->createHandlerResolver()->findHandler($stateMachineName)
        );
    }

    /**
     * @return \Spryker\Zed\Graph\Communication\Plugin\GraphPlugin
     */
    protected function getGraph()
    {
        return $this->getProvidedDependency(StateMachineDependencyProvider::PLUGIN_GRAPH);
    }

    /**
     * @return \Spryker\Zed\StateMachine\Business\StateMachine\HandlerResolverInterface
     */
    protected function createHandlerResolver()
    {
        return new HandlerResolver($this->getStateMachineHandlerPlugins());
    }

    /**
     * @return \Spryker\Zed\StateMachine\Dependency\Plugin\StateMachineHandlerInterface[]
     */
    public function getStateMachineHandlerPlugins()
    {
         return $this->getProvidedDependency(StateMachineDependencyProvider::PLUGINS_STATE_MACHINE_HANDLERS);
    }

}
