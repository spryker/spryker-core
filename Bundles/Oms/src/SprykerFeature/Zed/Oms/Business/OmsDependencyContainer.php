<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Oms\Business;

use Generated\Zed\Ide\FactoryAutoCompletion\OmsBusiness;
use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerFeature\Zed\Oms\Business\OrderStateMachine\BuilderInterface;
use SprykerFeature\Zed\Oms\Business\OrderStateMachine\DummyInterface;
use SprykerFeature\Zed\Oms\Business\OrderStateMachine\FinderInterface;
use SprykerFeature\Zed\Oms\Business\OrderStateMachine\OrderStateMachineInterface;
use SprykerFeature\Zed\Oms\Business\OrderStateMachine\PersistenceManagerInterface;
use SprykerFeature\Zed\Oms\Business\OrderStateMachine\TimeoutInterface;
use SprykerFeature\Zed\Oms\Business\Process\EventInterface;
use SprykerFeature\Zed\Oms\Business\Process\ProcessInterface;
use SprykerFeature\Zed\Oms\Business\Process\StateInterface;
use SprykerFeature\Zed\Oms\Business\Process\TransitionInterface;
use SprykerFeature\Zed\Oms\Business\ReferenceGenerator\CreditMemoReferenceGenerator;
use SprykerFeature\Zed\Oms\Business\Util\DrawerInterface;
use SprykerFeature\Zed\Oms\Business\Util\ReadOnlyArrayObject;
use SprykerFeature\Zed\Oms\Business\Util\TransitionLogInterface;
use SprykerFeature\Zed\Oms\OmsConfig;
use SprykerFeature\Zed\Oms\OmsDependencyProvider;
use SprykerFeature\Zed\Oms\Persistence\OmsQueryContainerInterface;
use SprykerFeature\Zed\SequenceNumber\Business\SequenceNumberFacade;

/**
 * @method OmsBusiness getFactory()
 * @method OmsConfig getConfig()
 * @method OmsQueryContainerInterface getQueryContainer()
 */
class OmsDependencyContainer extends AbstractBusinessDependencyContainer
{

    /**
     * @param array $array
     *
     * @return ReadOnlyArrayObject
     */
    public function createUtilReadOnlyArrayObject(array $array = [])
    {
        return $this->getFactory()->createUtilReadOnlyArrayObject($array);
    }

    /**
     * @param array $logContext
     *
     * @return OrderStateMachineInterface
     */
    public function createOrderStateMachineOrderStateMachine(array $logContext = [])
    {
        return $this->getFactory()->createOrderStateMachineOrderStateMachine(
            $this->getQueryContainer(),

            $this->createOrderStateMachineBuilder(),
            $this->createUtilTransitionLog($logContext),
            $this->createOrderStateMachineTimeout(),
            $this->createUtilReadOnlyArrayObject($this->getConfig()->getActiveProcesses()),

            $this->getProvidedDependency(OmsDependencyProvider::CONDITION_PLUGINS),
            $this->getProvidedDependency(OmsDependencyProvider::COMMAND_PLUGINS),

            $this->getFactory()
        );
    }

    /**
     * @param string $xmlFolder
     *
     * @return BuilderInterface
     */
    public function createOrderStateMachineBuilder($xmlFolder = null)
    {
        return $this->getFactory()->createOrderStateMachineBuilder(
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
        return $this->getFactory()->createOrderStateMachineDummy(
            $this->createOrderStateMachineBuilder()
        );
    }

    /**
     * @return FinderInterface
     */
    public function createOrderStateMachineFinder()
    {
        $config = $this->getConfig();

        return $this->getFactory()->createOrderStateMachineFinder(
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
        return $this->getFactory()->createOrderStateMachineTimeout(
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

        return $this->getFactory()
            ->createUtilTransitionLog($queryContainer, $logContext)
        ;
    }

    /**
     * @return PersistenceManagerInterface
     */
    public function createOrderStateMachinePersistenceManager()
    {
        return $this->getFactory()->createOrderStateMachinePersistenceManager();
    }

    /**
     * @return EventInterface
     */
    public function createProcessEvent()
    {
        return $this->getFactory()->createProcessEvent();
    }

    /**
     * @return StateInterface
     */
    public function createProcessState()
    {
        return $this->getFactory()->createProcessState();
    }

    /**
     * @return TransitionInterface
     */
    public function createProcessTransition()
    {
        return $this->getFactory()->createProcessTransition();
    }

    /**
     * @return ProcessInterface
     */
    public function createProcessProcess()
    {
        return $this->getFactory()
            ->createProcessProcess($this->createUtilDrawer())
        ;
    }

    /**
     * @return DrawerInterface
     */
    public function createUtilDrawer()
    {
        return $this->getFactory()
            ->createUtilDrawer(
                $this->getProvidedDependency(OmsDependencyProvider::COMMAND_PLUGINS),
                $this->getProvidedDependency(OmsDependencyProvider::CONDITION_PLUGINS)
            )
        ; // @TODO do not inject the whole config, just inject what is needed
    }

    /**
     * @return CreditMemoReferenceGenerator
     */
    protected function createCreditMemoReferenceGenerator()
    {
        return $this->getFactory()->createReferenceGeneratorCreditMemoReferenceGenerator(
            $this->createSequenceNumberFacade(),
            $this->getConfig()->getCreditMemoReferenceDefaults()
        );
    }

    /**
     * @return SequenceNumberFacade
     */
    protected function createSequenceNumberFacade()
    {
        return $this->getProvidedDependency(OmsDependencyProvider::FACADE_SEQUENCE_NUMBER);
    }

}
