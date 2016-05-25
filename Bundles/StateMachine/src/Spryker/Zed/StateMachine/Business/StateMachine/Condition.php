<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StateMachine\Business\StateMachine;

use Generated\Shared\Transfer\StateMachineItemTransfer;
use Spryker\Zed\StateMachine\Business\Exception\ConditionNotFoundException;
use Spryker\Zed\StateMachine\Business\Logger\TransitionLogInterface;
use Spryker\Zed\StateMachine\Business\Process\StateInterface;
use Spryker\Zed\StateMachine\Dependency\Plugin\StateMachineHandlerInterface;

class Condition implements ConditionInterface
{

    /**
     * @var array
     */
    protected $eventCounter = [];

    /**
     * @var array
     */
    protected $processBuffer = [];

    /**
     * @var \Spryker\Zed\StateMachine\Business\Logger\TransitionLogInterface
     */
    protected $transitionLog;

    /**
     * @var \Spryker\Zed\StateMachine\Business\StateMachine\HandlerResolverInterface
     */
    protected $stateMachineHandlerResolver;

    /**
     * @var \Spryker\Zed\StateMachine\Business\StateMachine\FinderInterface
     */
    protected $finder;

    /**
     * @var \Spryker\Zed\StateMachine\Business\StateMachine\PersistenceInterface
     */
    protected $stateMachinePersistence;

    /**
     * @var \Spryker\Zed\StateMachine\Business\StateMachine\StateUpdaterInterface
     */
    protected $stateUpdater;

    /**
     * @param \Spryker\Zed\StateMachine\Business\Logger\TransitionLogInterface $transitionLog
     * @param \Spryker\Zed\StateMachine\Business\StateMachine\HandlerResolverInterface $stateMachineHandlerResolver
     * @param \Spryker\Zed\StateMachine\Business\StateMachine\FinderInterface $finder
     * @param \Spryker\Zed\StateMachine\Business\StateMachine\PersistenceInterface $stateMachinePersistence
     * @param \Spryker\Zed\StateMachine\Business\StateMachine\StateUpdaterInterface $stateUpdate
     */
    public function __construct(
        TransitionLogInterface $transitionLog,
        HandlerResolverInterface $stateMachineHandlerResolver,
        FinderInterface $finder,
        PersistenceInterface $stateMachinePersistence,
        StateUpdaterInterface $stateUpdate
    ) {
        $this->transitionLog = $transitionLog;
        $this->stateMachineHandlerResolver = $stateMachineHandlerResolver;
        $this->finder = $finder;
        $this->stateMachinePersistence = $stateMachinePersistence;
        $this->stateUpdater = $stateUpdate;
    }

    /**
     * @param \Spryker\Zed\StateMachine\Business\Process\TransitionInterface[] $transitions
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer $stateMachineItemTransfer
     * @param \Spryker\Zed\StateMachine\Business\Process\StateInterface $sourceState
     * @param \Spryker\Zed\StateMachine\Business\Logger\TransitionLogInterface $transactionLogger
     *
     * @throws \Spryker\Zed\StateMachine\Business\Exception\ConditionNotFoundException
     * @throws \Exception
     *
     * @return \Spryker\Zed\StateMachine\Business\Process\StateInterface
     */
    public function checkConditionForTransitions(
        array $transitions,
        StateMachineItemTransfer $stateMachineItemTransfer,
        StateInterface $sourceState,
        TransitionLogInterface $transactionLogger
    ) {
        $possibleTransitions = [];
        foreach ($transitions as $transition) {
            if ($transition->hasCondition()) {
                $conditionName = $transition->getCondition();
                $conditionPlugin = $this->getConditionPlugin(
                    $conditionName,
                    $stateMachineItemTransfer->getStateMachineName()
                );

                try {
                    $conditionCheck = $conditionPlugin->check($stateMachineItemTransfer);
                } catch (\Exception $e) {
                    $transactionLogger->setIsError(true);
                    $transactionLogger->setErrorMessage(get_class($conditionPlugin) . ' - ' . $e->getMessage());
                    $transactionLogger->saveAll();
                    throw $e;
                }

                if ($conditionCheck === true) {
                    $transactionLogger->addCondition($stateMachineItemTransfer, $conditionPlugin);
                    array_unshift($possibleTransitions, $transition);
                }
            } else {
                array_push($possibleTransitions, $transition);
            }
        }

        return $this->findTargetState($sourceState, $possibleTransitions);
    }

    /**
     * @param \Spryker\Zed\StateMachine\Business\Process\StateInterface $sourceState
     * @param \Spryker\Zed\StateMachine\Business\Process\TransitionInterface[] $possibleTransitions
     *
     * @return \Spryker\Zed\StateMachine\Business\Process\StateInterface
     */
    protected function findTargetState(StateInterface $sourceState, array $possibleTransitions)
    {
        $targetState = $sourceState;
        if (count($possibleTransitions) > 0) {
            /** @var \Spryker\Zed\StateMachine\Business\Process\TransitionInterface $selectedTransition */
            $selectedTransition = array_shift($possibleTransitions);
            $targetState = $selectedTransition->getTarget();
        }
        return $targetState;
    }

    /**
     * @param string $stateMachineName
     * @param string $processName
     *
     * @return \Generated\Shared\Transfer\StateMachineItemTransfer[] $itemsWithOnEnterEvent
     */
    public function checkConditionsForProcess($stateMachineName, $processName)
    {
        $process = $this->finder->findProcessByStateMachineAndProcessName($stateMachineName, $processName);
        $transitions = $process->getAllTransitionsWithoutEvent();

        $stateToTransitionsMap = $this->createStateToTransitionMap($transitions);

        $states = array_keys($stateToTransitionsMap);
        $stateMachineItemStateIds = $this->stateMachinePersistence->getStateMachineItemIdsByStatesProcessAndStateMachineName(
            $stateMachineName,
            $process->getName(),
            $states
        );

        $stateMachineItems = $this->stateMachineHandlerResolver
            ->get($stateMachineName)
            ->getStateMachineItemsByStateIds($stateMachineItemStateIds);

        if (count($stateMachineItems) === 0) {
            return [];
        }

        $stateMachineItems = $this->stateMachinePersistence
            ->updateStateMachineItemsFromPersistence($stateMachineItems);

        if (count($stateMachineItems) === 0) {
            return [];
        }

        $this->transitionLog->init($stateMachineItems);

        $sourceStateBuffer = $this->updateStateByTransition($stateMachineName, $stateToTransitionsMap, $stateMachineItems);

        $processes = [$process->getName() => $process];

        $this->stateUpdater->updateStateMachineItemState(
            $stateMachineItems,
            $processes,
            $sourceStateBuffer
        );

        $itemsWithOnEnterEvent = $this->finder->filterItemsWithOnEnterEvent(
            $stateMachineItems,
            $processes,
            $sourceStateBuffer
        );

        return $itemsWithOnEnterEvent;
    }

    /**
     * @param string $stateMachineName
     * @param array $stateToTransitionsMap
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer[] $stateMachineItems
     *
     * @throws \Exception
     *
     * @return array
     */
    protected function updateStateByTransition(
        $stateMachineName,
        array $stateToTransitionsMap,
        array $stateMachineItems
    ) {
        $targetStateMap = [];
        $sourceStateBuffer = [];
        foreach ($stateMachineItems as $i => $stateMachineItemTransfer) {
            $stateName = $stateMachineItemTransfer->getStateName();
            $sourceStateBuffer[$stateMachineItemTransfer->getIdentifier()] = $stateName;

            $process = $this->finder->findProcessByStateMachineAndProcessName(
                $stateMachineName,
                $stateMachineItemTransfer->getProcessName()
            );

            $sourceState = $process->getStateFromAllProcesses($stateName);

            $this->transitionLog->addSourceState($stateMachineItemTransfer, $sourceState->getName());

            $transitions = $stateToTransitionsMap[$stateMachineItemTransfer->getStateName()];

            $targetState = $sourceState;
            if (count($transitions) > 0) {
                $targetState = $this->checkConditionForTransitions(
                    $transitions,
                    $stateMachineItemTransfer,
                    $sourceState,
                    $this->transitionLog
                );
            }

            $this->transitionLog->addTargetState($stateMachineItemTransfer, $targetState->getName());

            $targetStateMap[$i] = $targetState->getName();
        }

        foreach ($stateMachineItems as $i => $stateMachineItemTransfer) {
            $this->stateMachinePersistence->getStateMachineItemState($stateMachineItems[$i], $targetStateMap[$i]);
        }

        return $sourceStateBuffer;
    }

    /**
     * @param array $transitions
     *
     * @return array
     */
    protected function createStateToTransitionMap(array $transitions)
    {
        $stateToTransitionsMap = [];
        foreach ($transitions as $transition) {
            $sourceStateName = $transition->getSource()->getName();
            if (array_key_exists($sourceStateName, $stateToTransitionsMap) === false) {
                $stateToTransitionsMap[$sourceStateName] = [];
            }
            $stateToTransitionsMap[$sourceStateName][] = $transition;
        }

        return $stateToTransitionsMap;
    }

    /**
     * @param string $conditionString
     * @param string $stateMachineName
     *
     * @throws \Spryker\Zed\StateMachine\Business\Exception\ConditionNotFoundException
     *
     * @return \Spryker\Zed\StateMachine\Dependency\Plugin\ConditionPluginInterface
     *
     */
    protected function getConditionPlugin($conditionString, $stateMachineName)
    {
        $stateMachineHandler = $this->stateMachineHandlerResolver->get($stateMachineName);

        $this->assertConditionIsSet($conditionString, $stateMachineHandler);

        return $stateMachineHandler->getConditionPlugins()[$conditionString];
    }

    /**
     * @param string $conditionString
     * @param \Spryker\Zed\StateMachine\Dependency\Plugin\StateMachineHandlerInterface $stateMachineHandler
     *
     * @throws \Spryker\Zed\StateMachine\Business\Exception\ConditionNotFoundException
     *
     * @return void
     */
    protected function assertConditionIsSet($conditionString, StateMachineHandlerInterface $stateMachineHandler)
    {
        if (!isset($stateMachineHandler->getConditionPlugins()[$conditionString])) {
            throw new ConditionNotFoundException(
                sprintf(
                    'Condition plugin "%s" not registered in "%s" class. Please add it to getConditionPlugins method.',
                    $conditionString,
                    get_class($this->stateMachineHandlerResolver)
                )
            );
        }
    }

}
