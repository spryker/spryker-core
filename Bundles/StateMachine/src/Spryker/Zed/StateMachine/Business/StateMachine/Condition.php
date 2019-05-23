<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StateMachine\Business\StateMachine;

use Exception;
use Generated\Shared\Transfer\StateMachineItemTransfer;
use Spryker\Zed\StateMachine\Business\Exception\ConditionNotFoundException;
use Spryker\Zed\StateMachine\Business\Logger\TransitionLogInterface;
use Spryker\Zed\StateMachine\Business\Process\ProcessInterface;
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
     * @return \Spryker\Zed\StateMachine\Business\Process\StateInterface
     */
    public function getTargetStatesFromTransitions(
        array $transitions,
        StateMachineItemTransfer $stateMachineItemTransfer,
        StateInterface $sourceState,
        TransitionLogInterface $transactionLogger
    ) {
        $possibleTransitions = [];
        foreach ($transitions as $transition) {
            if ($transition->hasCondition()) {
                $isValidCondition = $this->checkCondition(
                    $stateMachineItemTransfer,
                    $transactionLogger,
                    $transition->getCondition()
                );

                if ($isValidCondition) {
                    array_push($possibleTransitions, $transition);
                }
            } else {
                array_push($possibleTransitions, $transition);
            }
        }

        return $this->findTargetState($sourceState, $possibleTransitions);
    }

    /**
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer $stateMachineItemTransfer
     * @param \Spryker\Zed\StateMachine\Business\Logger\TransitionLogInterface $transactionLogger
     * @param string $conditionName
     *
     * @throws \Exception
     *
     * @return bool
     */
    protected function checkCondition(
        StateMachineItemTransfer $stateMachineItemTransfer,
        TransitionLogInterface $transactionLogger,
        $conditionName
    ) {
        $conditionPlugin = $this->getConditionPlugin(
            $conditionName,
            $stateMachineItemTransfer->getStateMachineName()
        );

        try {
            $conditionCheck = $conditionPlugin->check($stateMachineItemTransfer);
        } catch (Exception $e) {
            $transactionLogger->setIsError(true);
            $transactionLogger->setErrorMessage(get_class($conditionPlugin) . ' - ' . $e->getMessage());
            $transactionLogger->saveAll();
            throw $e;
        }

        if ($conditionCheck === true) {
            $transactionLogger->addCondition($stateMachineItemTransfer, $conditionPlugin);

            return true;
        }

        return false;
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
            $selectedTransition = array_shift($possibleTransitions);
            $targetState = $selectedTransition->getTargetState();
        }

        return $targetState;
    }

    /**
     * @param string $stateMachineName
     * @param string $processName
     *
     * @return \Generated\Shared\Transfer\StateMachineItemTransfer[][] $itemsWithOnEnterEvent
     */
    public function getOnEnterEventsForStatesWithoutTransition($stateMachineName, $processName)
    {
        $process = $this->finder->findProcessByStateMachineAndProcessName($stateMachineName, $processName);
        $transitions = $process->getAllTransitionsWithoutEvent();

        $stateToTransitionsMap = $this->createStateToTransitionMap($transitions);

        $stateMachineItems = $this->getItemsByStatesAndProcessName($stateMachineName, $stateToTransitionsMap, $process);

        $this->transitionLog->init($stateMachineItems);
        $sourceStates = $this->createStateMap($stateMachineItems);

        $this->persistAffectedStates($stateMachineName, $stateToTransitionsMap, $stateMachineItems);

        $processes = [$process->getName() => $process];

        $this->stateUpdater->updateStateMachineItemState(
            $stateMachineItems,
            $processes,
            $sourceStates
        );

        $itemsWithOnEnterEvent = $this->finder->filterItemsWithOnEnterEvent(
            $stateMachineItems,
            $processes,
            $sourceStates
        );

        return $itemsWithOnEnterEvent;
    }

    /**
     * @param string $stateMachineName
     * @param string[] $states
     * @param \Spryker\Zed\StateMachine\Business\Process\ProcessInterface $process

     * @return \Generated\Shared\Transfer\StateMachineItemTransfer[]
     */
    protected function getItemsByStatesAndProcessName(
        $stateMachineName,
        array $states,
        ProcessInterface $process
    ) {

        $stateMachineItemStateIds = $this->stateMachinePersistence->getStateMachineItemIdsByStatesProcessAndStateMachineName(
            $process->getName(),
            $stateMachineName,
            array_keys($states)
        );

        $stateMachineItems = $this->stateMachineHandlerResolver
            ->get($stateMachineName)
            ->getStateMachineItemsByStateIds($stateMachineItemStateIds);

        $stateMachineItems = $this->stateMachinePersistence
            ->updateStateMachineItemsFromPersistence($stateMachineItems);

        return $stateMachineItems;
    }

    /**
     * @param string $stateMachineName
     * @param array $states Keys are state names, values are collections of TransitionInterface.
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer[] $stateMachineItems
     *
     * @return void
     */
    protected function persistAffectedStates(
        $stateMachineName,
        array $states,
        array $stateMachineItems
    ) {
        $targetStateMap = [];
        foreach ($stateMachineItems as $i => $stateMachineItemTransfer) {
            $stateName = $stateMachineItemTransfer->getStateName();

            $process = $this->finder->findProcessByStateMachineAndProcessName(
                $stateMachineName,
                $stateMachineItemTransfer->getProcessName()
            );

            $sourceState = $process->getStateFromAllProcesses($stateName);

            $this->transitionLog->addSourceState($stateMachineItemTransfer, $sourceState->getName());

            $transitions = $states[$stateMachineItemTransfer->getStateName()];

            $targetState = $sourceState;
            if (count($transitions) > 0) {
                $targetState = $this->getTargetStatesFromTransitions(
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
            $this->stateMachinePersistence->saveStateMachineItem($stateMachineItems[$i], $targetStateMap[$i]);
        }
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
            $sourceStateName = $transition->getSourceState()->getName();
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
     * @return \Spryker\Zed\StateMachine\Dependency\Plugin\ConditionPluginInterface
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
                    'Condition plugin "%s" not registered in "%s" class. Please add it to getConditionPlugins() method.',
                    $conditionString,
                    get_class($this->stateMachineHandlerResolver)
                )
            );
        }
    }

    /**
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer[] $stateMachineItems
     *
     * @return string[]
     */
    protected function createStateMap(array $stateMachineItems)
    {
        $sourceStates = [];
        foreach ($stateMachineItems as $stateMachineItemTransfer) {
            $sourceStates[$stateMachineItemTransfer->getIdentifier()] = $stateMachineItemTransfer->getStateName();
        }

        return $sourceStates;
    }
}
