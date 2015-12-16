<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Oms\Business;

use Spryker\Zed\Oms\Business\Process\ProcessSelector;
use Spryker\Zed\Oms\Business\Util\Drawer;
use Spryker\Zed\Oms\Business\Process\Process;
use Spryker\Zed\Oms\Business\Process\Transition;
use Spryker\Zed\Oms\Business\Process\State;
use Spryker\Zed\Oms\Business\Process\Event;
use Spryker\Zed\Oms\Business\OrderStateMachine\PersistenceManager;
use Spryker\Zed\Oms\Business\Util\TransitionLog;
use Spryker\Zed\Oms\Business\OrderStateMachine\Timeout;
use Spryker\Zed\Oms\Business\OrderStateMachine\Finder;
use Spryker\Zed\Oms\Business\OrderStateMachine\Dummy;
use Spryker\Zed\Oms\Business\OrderStateMachine\Builder;
use Spryker\Zed\Oms\Business\OrderStateMachine\OrderStateMachine;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Oms\Business\OrderStateMachine\BuilderInterface;
use Spryker\Zed\Oms\Business\OrderStateMachine\DummyInterface;
use Spryker\Zed\Oms\Business\OrderStateMachine\FinderInterface;
use Spryker\Zed\Oms\Business\OrderStateMachine\OrderStateMachineInterface;
use Spryker\Zed\Oms\Business\OrderStateMachine\PersistenceManagerInterface;
use Spryker\Zed\Oms\Business\OrderStateMachine\TimeoutInterface;
use Spryker\Zed\Oms\Business\Process\EventInterface;
use Spryker\Zed\Oms\Business\Process\ProcessInterface;
use Spryker\Zed\Oms\Business\Process\StateInterface;
use Spryker\Zed\Oms\Business\Process\TransitionInterface;
use Spryker\Zed\Oms\Business\Util\DrawerInterface;
use Spryker\Zed\Oms\Business\Util\OrderItemMatrix;
use Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject;
use Spryker\Zed\Oms\Business\Util\TransitionLogInterface;
use Spryker\Zed\Oms\OmsConfig;
use Spryker\Zed\Oms\OmsDependencyProvider;
use Spryker\Zed\Oms\Persistence\OmsQueryContainerInterface;

/**
 * @method OmsConfig getConfig()
 * @method OmsQueryContainerInterface getQueryContainer()
 */
class OmsDependencyContainer extends AbstractBusinessFactory
{

    /**
     * @param array $array
     *
     * @return ReadOnlyArrayObject
     */
    public function createUtilReadOnlyArrayObject(array $array = [])
    {
        return new ReadOnlyArrayObject($array);
    }

    /**
     * @param array $logContext
     *
     * @return OrderStateMachineInterface
     */
    public function createOrderStateMachineOrderStateMachine(array $logContext = [])
    {
        return new OrderStateMachine(
            $this->getQueryContainer(),

            $this->createOrderStateMachineBuilder(),
            $this->createUtilTransitionLog($logContext),
            $this->createOrderStateMachineTimeout(),
            $this->createUtilReadOnlyArrayObject($this->getConfig()->getActiveProcesses()),

            $this->getProvidedDependency(OmsDependencyProvider::CONDITION_PLUGINS),
            $this->getProvidedDependency(OmsDependencyProvider::COMMAND_PLUGINS)
        );
    }

    /**
     * @param string $xmlFolder
     *
     * @return BuilderInterface
     */
    public function createOrderStateMachineBuilder($xmlFolder = null)
    {
        return new Builder(
            $this->createProcessEvent(),
            $this->createProcessState(),
            $this->createProcessTransition(),
            $this->createProcessProcess(),
            $xmlFolder
        );
    }

    /**
     * @return DummyInterface
     */
    public function createModelDummy()
    {
        return new Dummy(
            $this->createOrderStateMachineBuilder()
        );
    }

    /**
     * @return FinderInterface
     */
    public function createOrderStateMachineFinder()
    {
        $config = $this->getConfig();

        return new Finder(
            $this->getQueryContainer(),
            $this->createOrderStateMachineBuilder(),
            $config->getActiveProcesses()
        );
    }

    /**
     * @return TimeoutInterface
     */
    public function createOrderStateMachineTimeout()
    {
        return new Timeout(
            $this->getQueryContainer()
        );
    }

    /**
     * @param array $logContext
     *
     * @return TransitionLogInterface
     */
    public function createUtilTransitionLog(array $logContext)
    {
        $queryContainer = $this->getQueryContainer();

        return new TransitionLog($queryContainer, $logContext);
    }

    /**
     * @return PersistenceManagerInterface
     */
    public function createOrderStateMachinePersistenceManager()
    {
        return new PersistenceManager();
    }

    /**
     * @return EventInterface
     */
    public function createProcessEvent()
    {
        return new Event();
    }

    /**
     * @return StateInterface
     */
    public function createProcessState()
    {
        return new State();
    }

    /**
     * @return TransitionInterface
     */
    public function createProcessTransition()
    {
        return new Transition();
    }

    /**
     * @return ProcessInterface
     */
    public function createProcessProcess()
    {
        return new Process($this->createUtilDrawer());
    }

    /**
     * @return DrawerInterface
     */
    public function createUtilDrawer()
    {
        return new Drawer(
                $this->getProvidedDependency(OmsDependencyProvider::COMMAND_PLUGINS),
                $this->getProvidedDependency(OmsDependencyProvider::CONDITION_PLUGINS)
            ); // @TODO do not inject the whole config, just inject what is needed
    }

    /**
     * @return OrderItemMatrix
     */
    public function createUtilOrderItemMatrix()
    {
        return new OrderItemMatrix($this->getQueryContainer(), $this->getConfig());
    }

    /**
     * @return ProcessSelector
     */
    public function createProcessSelector()
    {
        return new ProcessSelector($this->getConfig());
    }

}
