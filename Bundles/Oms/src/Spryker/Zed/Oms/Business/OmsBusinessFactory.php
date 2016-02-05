<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Oms\Business;

use Spryker\Shared\Graph\Graph;
use Spryker\Shared\Graph\Adapter\PhpDocumentorGraphAdapter;
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
use Spryker\Zed\Oms\Business\Util\OrderItemMatrix;
use Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject;
use Spryker\Zed\Oms\OmsDependencyProvider;

/**
 * @method \Spryker\Zed\Oms\OmsConfig getConfig()
 * @method \Spryker\Zed\Oms\Persistence\OmsQueryContainerInterface getQueryContainer()
 */
class OmsBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @param array $array
     *
     * @return \Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject
     */
    public function createUtilReadOnlyArrayObject(array $array = [])
    {
        return new ReadOnlyArrayObject($array);
    }

    /**
     * @param array $logContext
     *
     * @return \Spryker\Zed\Oms\Business\OrderStateMachine\OrderStateMachineInterface
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
     * @return \Spryker\Zed\Oms\Business\OrderStateMachine\BuilderInterface
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
     * @return \Spryker\Zed\Oms\Business\OrderStateMachine\DummyInterface
     */
    public function createModelDummy()
    {
        return new Dummy(
            $this->createOrderStateMachineBuilder()
        );
    }

    /**
     * @return \Spryker\Zed\Oms\Business\OrderStateMachine\FinderInterface
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
     * @return \Spryker\Zed\Oms\Business\OrderStateMachine\TimeoutInterface
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
     * @return \Spryker\Zed\Oms\Business\Util\TransitionLogInterface
     */
    public function createUtilTransitionLog(array $logContext)
    {
        $queryContainer = $this->getQueryContainer();

        return new TransitionLog($queryContainer, $logContext);
    }

    /**
     * @return \Spryker\Zed\Oms\Business\OrderStateMachine\PersistenceManagerInterface
     */
    public function createOrderStateMachinePersistenceManager()
    {
        return new PersistenceManager();
    }

    /**
     * @return \Spryker\Zed\Oms\Business\Process\EventInterface
     */
    public function createProcessEvent()
    {
        return new Event();
    }

    /**
     * @return \Spryker\Zed\Oms\Business\Process\StateInterface
     */
    public function createProcessState()
    {
        return new State();
    }

    /**
     * @return \Spryker\Zed\Oms\Business\Process\TransitionInterface
     */
    public function createProcessTransition()
    {
        return new Transition();
    }

    /**
     * @return \Spryker\Zed\Oms\Business\Process\ProcessInterface
     */
    public function createProcessProcess()
    {
        return new Process($this->createUtilDrawer());
    }

    /**
     * @return \Spryker\Zed\Oms\Business\Util\DrawerInterface
     */
    public function createUtilDrawer()
    {
        return new Drawer(
            $this->getProvidedDependency(OmsDependencyProvider::COMMAND_PLUGINS),
            $this->getProvidedDependency(OmsDependencyProvider::CONDITION_PLUGINS),
            $this->createGraph()
        );
    }

    /**
     * @return \Spryker\Shared\Graph\Graph
     */
    protected function createGraph()
    {
        $adapter = $this->createGraphAdapter();
        $graph = new Graph($adapter, 'Statemachine', $this->getConfig()->getGraphDefaults(), true, false);

        return $graph;
    }

    /**
     * @return \Spryker\Shared\Graph\Adapter\PhpDocumentorGraphAdapter
     */
    protected function createGraphAdapter()
    {
        return new PhpDocumentorGraphAdapter();
    }

    /**
     * @return \Spryker\Zed\Oms\Business\Util\OrderItemMatrix
     */
    public function createUtilOrderItemMatrix()
    {
        return new OrderItemMatrix($this->getQueryContainer(), $this->getConfig());
    }

    /**
     * @return \Spryker\Zed\Oms\Business\Process\ProcessSelector
     */
    public function createProcessSelector()
    {
        return new ProcessSelector($this->getConfig());
    }

}
