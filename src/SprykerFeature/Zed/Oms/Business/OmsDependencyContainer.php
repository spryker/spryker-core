<?php

namespace SprykerFeature\Zed\Oms\Business;

use SprykerEngine\Zed\Kernel\Business\AbstractDependencyContainer;
use SprykerFeature\Zed\Oms\Business\Model\BuilderInterface;
use SprykerFeature\Zed\Oms\Business\Model\DummyInterface;
use SprykerFeature\Zed\Oms\Business\Model\FinderInterface;
use SprykerFeature\Zed\Oms\Business\Model\ProcessInterface;
use SprykerFeature\Zed\Oms\Business\Model\PersistenceManagerInterface;
use SprykerFeature\Zed\Oms\Business\Model\OrderStateMachineInterface;
use SprykerFeature\Zed\Oms\Business\Model\OrderStateMachine\TimeoutInterface;
use SprykerFeature\Zed\Oms\Business\Model\Util\CollectionToArrayTransformerInterface;
use SprykerFeature\Zed\Oms\Business\Model\Util\DrawerInterface;
use SprykerFeature\Zed\Oms\Business\Model\Util\TransitionLogInterface;
use SprykerFeature\Zed\Oms\Business\Model\Util\ReadOnlyArrayObject;
use SprykerFeature\Zed\Oms\Business\Model\Process\EventInterface;
use SprykerFeature\Zed\Oms\Business\Model\Process\StatusInterface;
use SprykerFeature\Zed\Oms\Business\Model\Process\TransitionInterface;
use SprykerFeature\Zed\Oms\Persistence\OmsQueryContainer;
use Generated\Zed\Ide\FactoryAutoCompletion\OmsBusiness;

/**
 * @method OmsBusiness getFactory()
 */
class OmsDependencyContainer extends AbstractDependencyContainer
{
    /**
     * @return CollectionToArrayTransformerInterface
     */
    public function createModelUtilCollectionToArrayTransformer()
    {
        return $this->getFactory()->createModelUtilCollectionToArrayTransformer();
    }

    /**
     * @return ReadOnlyArrayObject
     */
    public function createModelUtilReadOnlyArrayObject()
    {
        return $this->getFactory()->createModelUtilReadOnlyArrayObject(array());
    }

    /**
     * @return OmsQueryContainer
     */
    public function createQueryContainer()
    {
        return $this->getLocator()->oms()->queryContainer();
    }

    /**
     * @param array $logContext
     *
     * @return OrderStateMachineInterface
     */
    public function createModelOrderStateMachine(array $logContext)
    {
        $settings = $this->createSettings();

        return $this->getFactory()->createModelOrderStateMachine(
            $this->createQueryContainer(),
            $this->createModelBuilder(),
            $this->createModelUtilTransitionLog($logContext),
            $this->createModelOrderStateMachineTimeout(),
            $this->createModelUtilCollectionToArrayTransformer(),
            $this->createModelUtilReadOnlyArrayObject(),
            $settings->getConditions(),
            $settings->getCommands(),
            $this->getFactory()
        );
    }

    /**
     * @param string $xmlFolder
     *
     * @return BuilderInterface
     */
    public function createModelBuilder($xmlFolder = null)
    {
        return $this->getFactory()->createModelBuilder(
            $this->createModelProcessEvent(),
            $this->createModelProcessStatus(),
            $this->createModelProcessTransition(),
            $this->createModelProcess(),
            $xmlFolder
        );
    }

    /**
     * @return DummyInterface
     */
    public function createModelDummy()
    {
        return $this->getFactory()->createModelDummy(
            $this->createModelBuilder()
        );
    }

    /**
     * @return FinderInterface
     */
    public function createModelFinder()
    {
        $settings = $this->createSettings();

        return $this->getFactory()->createModelFinder(
            $this->createQueryContainer(),
            $this->createModelBuilder(),
            $settings->getActiveProcesses()
        );
    }

    /**
     * @return OmsSettings
     */
    public function createSettings()
    {
        return $this->getFactory()->createOmsSettings();
    }

    /**
     * @return TimeoutInterface
     */
    public function createModelOrderStateMachineTimeout()
    {
        return $this->getFactory()->createModelOrderStateMachineTimeout(
            $this->createQueryContainer()
        );
    }

    /**
     * @param array $logContext
     *
     * @return TransitionLogInterface
     */
    public function createModelUtilTransitionLog(array $logContext)
    {
        $queryContainer = $this->createQueryContainer();

        return $this->getFactory()
            ->createModelUtilTransitionLog($queryContainer, $logContext);
    }

    /**
     * @return PersistenceManagerInterface
     */
    public function createModelPersistenceManager()
    {
        return $this->getFactory()->createModelPersistenceManager();
    }

    /**
     * @return EventInterface
     */
    public function createModelProcessEvent()
    {
        return $this->getFactory()->createModelProcessEvent();
    }

    /**
     * @return StatusInterface
     */
    public function createModelProcessStatus()
    {
        return $this->getFactory()->createModelProcessStatus();
    }

    /**
     * @return TransitionInterface
     */
    public function createModelProcessTransition()
    {
        return $this->getFactory()->createModelProcessTransition();
    }

    /**
     * @return ProcessInterface
     */
    public function createModelProcess()
    {
        return $this->getFactory()
            ->createModelProcess($this->createModelUtilDrawer());
    }

    /**
     * @return DrawerInterface
     */
    public function createModelUtilDrawer()
    {
        return $this->getFactory()
            ->createModelUtilDrawer($this->createSettings());
    }
}
