<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StateMachine\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\StateMachine\Business\StateMachine\Builder;
use Spryker\Zed\StateMachine\Business\StateMachine\BuilderInterface;
use Spryker\Zed\StateMachine\Business\StateMachine\Finder;
use Spryker\Zed\StateMachine\Business\StateMachine\HandlerResolver;
use Spryker\Zed\StateMachine\Business\StateMachine\HandlerResolverInterface;
use Spryker\Zed\StateMachine\Business\StateMachine\StateMachine;
use Spryker\Zed\StateMachine\Business\StateMachine\PersistenceManager;
use Spryker\Zed\StateMachine\Business\StateMachine\StateMachineInterface;
use Spryker\Zed\StateMachine\Business\StateMachine\Timeout;
use Spryker\Zed\StateMachine\Business\Process\Event;
use Spryker\Zed\StateMachine\Business\Process\Process;
use Spryker\Zed\StateMachine\Business\Process\ProcessInterface;
use Spryker\Zed\StateMachine\Business\Process\State;
use Spryker\Zed\StateMachine\Business\Process\Transition;
use Spryker\Zed\StateMachine\Business\Util\Drawer;
use Spryker\Zed\StateMachine\Business\Util\TransitionLog;
use Spryker\Zed\StateMachine\Dependency\Plugin\StateMachineHandlerInterface;
use Spryker\Zed\StateMachine\StateMachineDependencyProvider;

/**
 * @method \Spryker\Zed\StateMachine\StateMachineConfig getConfig()
 * @method \Spryker\Zed\StateMachine\Persistence\StateMachineQueryContainerInterface getQueryContainer()
 */
class StateMachineBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @param string $stateMachineName
     * @return StateMachineInterface
     */
    public function createStateMachine($stateMachineName)
    {
        return new StateMachine(
            $this->getQueryContainer(),
            $this->createStateMachineBuilder($stateMachineName),
            $this->createUtilTransitionLog(),
            $this->createStateMachineTimeout($stateMachineName),
            $this->createHandlerResolver()->findHandler($stateMachineName),
            $this->createStateMachineFinder($stateMachineName),
            $this->createStateMachinePersistenceManager()
        );
    }

    /**
     * @param string $stateMachineName
     * @return BuilderInterface
     */
    public function createStateMachineBuilder($stateMachineName)
    {
        return new Builder(
            $this->createProcessEvent(),
            $this->createProcessState(),
            $this->createProcessTransition(),
            $this->createProcessProcess($stateMachineName)
        );
    }

    /**
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
     * @return \Spryker\Zed\StateMachine\Business\Util\TransitionLogInterface
     */
    public function createUtilTransitionLog()
    {
        return new TransitionLog();
    }

    /**
     * @return \Spryker\Zed\StateMachine\Business\StateMachine\PersistenceManagerInterface
     */
    public function createStateMachinePersistenceManager()
    {
        return new PersistenceManager();
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
     * @return ProcessInterface
     */
    public function createProcessProcess($stateMachineName)
    {
        return new Process($this->createUtilDrawer($stateMachineName));
    }

    /**
     * @param string $stateMachineName
     * @return Util\DrawerInterface
     */
    public function createUtilDrawer($stateMachineName)
    {
        return new Drawer(
            $this->getGraph()->init('Statemachine', $this->getConfig()->getGraphDefaults(), true, false),
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
     * @return HandlerResolverInterface
     */
    protected function createHandlerResolver()
    {
        return new HandlerResolver($this->getStateMachineHandlerPlugins());
    }

    /**
     * @return StateMachineHandlerInterface[]
     */
    public function getStateMachineHandlerPlugins()
    {
         return $this->getProvidedDependency(StateMachineDependencyProvider::PLUGINS_STATE_MACHINE_HANDLERS);
    }

}
