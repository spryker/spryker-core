<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StateMachine\Business\StateMachine;

use Generated\Shared\Transfer\StateMachineProcessTransfer;
use LogicException;
use SimpleXMLElement;
use Spryker\Zed\StateMachine\Business\Process\EventInterface;
use Spryker\Zed\StateMachine\Business\Process\StateInterface;
use Spryker\Zed\StateMachine\Business\Process\TransitionInterface;

class Builder implements BuilderInterface
{

    /**
     * @var \SimpleXMLElement
     */
    protected $rootElement;

    /**
     * @var \Spryker\Zed\StateMachine\Business\Process\ProcessInterface[]
     */
    protected static $processBuffer = [];

    /**
     * @var \Spryker\Zed\StateMachine\Business\Process\EventInterface
     */
    protected $event;

    /**
     * @var \Spryker\Zed\StateMachine\Business\Process\StateInterface
     */
    protected $state;

    /**
     * @var \Spryker\Zed\StateMachine\Business\Process\TransitionInterface
     */
    protected $transition;

    /**
     * @var \Spryker\Zed\StateMachine\Business\Process\ProcessInterface
     */
    protected $process;

    /**
     * @param \Spryker\Zed\StateMachine\Business\Process\EventInterface $event
     * @param \Spryker\Zed\StateMachine\Business\Process\StateInterface $state
     * @param \Spryker\Zed\StateMachine\Business\Process\TransitionInterface $transition
     * @param \Spryker\Zed\StateMachine\Business\Process\ProcessInterface $process
     */
    public function __construct(
        EventInterface $event,
        StateInterface $state,
        TransitionInterface $transition,
        $process
    ) {
        $this->event = $event;
        $this->state = $state;
        $this->transition = $transition;
        $this->process = $process;
    }

    /**
     * @param StateMachineProcessTransfer $stateMachineProcessTransfer
     *
     * @return \Spryker\Zed\StateMachine\Business\Process\ProcessInterface
     */
    public function createProcess(StateMachineProcessTransfer $stateMachineProcessTransfer)
    {
        $processIdentifier = $this->createProcessIdentifier($stateMachineProcessTransfer);
        if (isset(self::$processBuffer[$processIdentifier])) {
            return self::$processBuffer[$processIdentifier];
        }

        $pathToXml = $this->buildPathToXml($stateMachineProcessTransfer);
        $this->rootElement = $this->loadXmlFromProcessName($pathToXml, $stateMachineProcessTransfer->getProcessName());

        $this->mergeSubProcessFiles($pathToXml);

        /** @var \Spryker\Zed\StateMachine\Business\Process\ProcessInterface[] $processMap */
        $processMap = [];

        list($processMap, $mainProcess) = $this->createSubProcess($processMap);

        $stateToProcessMap = $this->createStates($processMap);

        $this->createSubProcesses($processMap);

        $eventMap = $this->createEvents();

        $this->createTransitions($stateToProcessMap, $processMap, $eventMap);

        self::$processBuffer[$processIdentifier] = $mainProcess;

        return $mainProcess;

    }

    /**
     * @param string $pathToXml
     *
     * @return void
     */
    protected function mergeSubProcessFiles($pathToXml)
    {
        foreach ($this->rootElement->children() as $xmlProcess) {
            $processFile = $this->getAttributeString($xmlProcess, 'file');
            if (isset($processFile)) {
                $processFile = str_replace(' ', '_', $processFile);
                $xmlSubProcess = $this->loadXmlFromFileName($pathToXml, $processFile);
                $this->recursiveMerge($xmlSubProcess, $this->rootElement);
            }
        }
    }

    /**
     * @param \SimpleXMLElement $fromXmlElement
     * @param \SimpleXMLElement $intoXmlNode
     *
     * @return void
     */
    protected function recursiveMerge($fromXmlElement, $intoXmlNode)
    {
        $xmlElements = $fromXmlElement->children();
        if (!isset($xmlElements)) {
            return;
        }

        /** @var \SimpleXMLElement $xmlElement */
        foreach ($xmlElements as $xmlElement) {
            $child = $intoXmlNode->addChild($xmlElement->getName(), $xmlElement);
            $attributes = $xmlElement->attributes();
            foreach ($attributes as $k => $v) {
                $child->addAttribute($k, $v);
            }

            $this->recursiveMerge($xmlElement, $child);
        }
    }

    /**
     * @param string $pathToXml
     * @param string $processName
     * @return SimpleXMLElement
     * @internal param string $fileName
     *
     */
    protected function loadXmlFromFileName($pathToXml, $fileName)
    {
        $xml = file_get_contents($pathToXml . DIRECTORY_SEPARATOR . $fileName. '.xml');

        return $this->loadXml($xml);
    }

    /**
     * @param string $processName
     *
     * @return \SimpleXMLElement
     */
    protected function loadXmlFromProcessName($pathToXml, $processName)
    {
        return $this->loadXmlFromFileName($pathToXml, $processName);
    }

    /**
     * @param string $xml
     *
     * @return \SimpleXMLElement
     */
    protected function loadXml($xml)
    {
        return new SimpleXMLElement($xml);
    }

    /**
     * @return array
     */
    protected function createEvents()
    {
        $eventMap = [];

        foreach ($this->rootElement as $xmlProcess) {
            if (!isset($xmlProcess->events)) {
                continue;
            }

            $xmlEvents = $xmlProcess->events->children();
            foreach ($xmlEvents as $xmlEvent) {
                $event = clone $this->event;
                $eventId = $this->getAttributeString($xmlEvent, 'name');
                $event->setCommand($this->getAttributeString($xmlEvent, 'command'));
                $event->setManual($this->getAttributeBoolean($xmlEvent, 'manual'));
                $event->setOnEnter($this->getAttributeBoolean($xmlEvent, 'onEnter'));
                $event->setTimeout($this->getAttributeString($xmlEvent, 'timeout'));
                if ($eventId === null) {
                    continue;
                }

                $event->setName($eventId);
                $eventMap[$event->getName()] = $event;
            }
        }

        return $eventMap;
    }

    /**
     * @param \Spryker\Zed\StateMachine\Business\Process\ProcessInterface[] $processMap
     *
     * @return array
     */
    protected function createSubProcess(array $processMap)
    {
        $mainProcess = null;
        $xmlProcesses = $this->rootElement->children();

        /** @var \SimpleXMLElement $xmlProcess */
        foreach ($xmlProcesses as $xmlProcess) {
            $process = clone $this->process;
            $processName = $this->getAttributeString($xmlProcess, 'name');
            $process->setName($processName);
            $processMap[$processName] = $process;
            $process->setMain($this->getAttributeBoolean($xmlProcess, 'main'));
            $process->setFile($this->getAttributeString($xmlProcess, 'file'));

            if ($process->getMain()) {
                $mainProcess = $process;
            }
        }

        return [$processMap, $mainProcess];
    }

    /**
     * @param \Spryker\Zed\StateMachine\Business\Process\ProcessInterface[] $processMap
     *
     * @return void
     */
    protected function createSubProcesses(array $processMap)
    {
        foreach ($this->rootElement as $xmlProcess) {
            $processName = $this->getAttributeString($xmlProcess, 'name');

            $process = $processMap[$processName];

            if (!empty($xmlProcess->subprocesses)) {
                $xmlSubProcesses = $xmlProcess->subprocesses->children();

                foreach ($xmlSubProcesses as $xmlSubProcess) {
                    $subProcessName = (string)$xmlSubProcess;
                    $subProcess = $processMap[$subProcessName];
                    $process->addSubProcess($subProcess);
                }
            }
        }
    }

    /**
     * @param \Spryker\Zed\StateMachine\Business\Process\ProcessInterface[] $processMap
     *
     * @return \Spryker\Zed\StateMachine\Business\Process\ProcessInterface[]
     */
    protected function createStates(array $processMap)
    {
        $stateToProcessMap = [];

        $xmlProcesses = $this->rootElement->children();
        foreach ($xmlProcesses as $xmlProcess) {
            $processName = $this->getAttributeString($xmlProcess, 'name');
            $process = $processMap[$processName];

            if (!empty($xmlProcess->states)) {
                $xmlStates = $xmlProcess->states->children();
                /** @var \SimpleXMLElement $xmlState */
                foreach ($xmlStates as $xmlState) {
                    $state = clone $this->state;
                    $state->setName($this->getAttributeString($xmlState, 'name'));
                    $state->setDisplay($this->getAttributeString($xmlState, 'display'));
                    $state->setProcess($process);

                    if ($xmlState->flag) {
                        $flags = $xmlState->children();
                        foreach ($flags->flag as $flag) {
                            $state->addFlag((string)$flag);
                        }
                    }

                    $process->addState($state);
                    $stateToProcessMap[$state->getName()] = $process;
                }
            }
        }

        return $stateToProcessMap;
    }

    /**
     * @param \Spryker\Zed\StateMachine\Business\Process\ProcessInterface[] $stateToProcessMap
     * @param \Spryker\Zed\StateMachine\Business\Process\ProcessInterface[] $processMap
     * @param \Spryker\Zed\StateMachine\Business\Process\EventInterface[] $eventMap
     *
     * @throws \LogicException
     *
     * @return void
     */
    protected function createTransitions(array $stateToProcessMap, array $processMap, array $eventMap)
    {
        foreach ($this->rootElement as $xmlProcess) {
            if (!empty($xmlProcess->transitions)) {
                $xmlTransitions = $xmlProcess->transitions->children();

                $processName = $this->getAttributeString($xmlProcess, 'name');

                foreach ($xmlTransitions as $xmlTransition) {
                    $transition = clone $this->transition;

                    $transition->setCondition($this->getAttributeString($xmlTransition, 'condition'));

                    $transition->setHappy($this->getAttributeBoolean($xmlTransition, 'happy'));

                    $sourceName = (string)$xmlTransition->source;
                    $sourceProcess = $stateToProcessMap[$sourceName];
                    $sourceState = $sourceProcess->getState($sourceName);
                    $transition->setSource($sourceState);
                    $sourceState->addOutgoingTransition($transition);

                    $targetName = (string)$xmlTransition->target;

                    if (!isset($stateToProcessMap[$targetName])) {
                        throw new LogicException('Target: "' . $targetName . '" does not exist from source: "' . $sourceName . '"');
                    }
                    $targetProcess = $stateToProcessMap[$targetName];
                    $targetState = $targetProcess->getState($targetName);
                    $transition->setTarget($targetState);
                    $targetState->addIncomingTransition($transition);

                    if (isset($xmlTransition->event)) {
                        $eventId = (string)$xmlTransition->event;

                        if (!isset($eventMap[$eventId])) {
                            throw new LogicException('Event: "' . $eventId . '" does not exist from source: "' . $sourceName . '"');
                        }

                        $event = $eventMap[$eventId];
                        $event->addTransition($transition);
                        $transition->setEvent($event);
                    }

                    $processMap[$processName]->addTransition($transition);
                }
            }
        }
    }

    /**
     * @param \SimpleXMLElement $xmlElement
     * @param string $attributeName
     *
     * @return string
     */
    protected function getAttributeString(SimpleXMLElement $xmlElement, $attributeName)
    {
        $string = (string)$xmlElement->attributes()[$attributeName];
        $string = ($string === '') ? null : $string;

        return $string;
    }

    /**
     * @param \SimpleXMLElement $xmlElement
     * @param string $attributeName
     *
     * @return bool
     */
    protected function getAttributeBoolean(SimpleXMLElement $xmlElement, $attributeName)
    {
        return (string)$xmlElement->attributes()[$attributeName] === 'true';
    }

    /**
     * @param StateMachineProcessTransfer $stateMachineProcessTransfer
     *
     * @return string
     */
    protected function createProcessIdentifier(StateMachineProcessTransfer $stateMachineProcessTransfer)
    {
        return $stateMachineProcessTransfer->getStateMachineName() . '-' . $stateMachineProcessTransfer->getProcessName();
    }

    /**
     * @param StateMachineProcessTransfer $stateMachineProcessTransfer
     *
     * @return string
     */
    protected function buildPathToXml(StateMachineProcessTransfer $stateMachineProcessTransfer)
    {
        return APPLICATION_ROOT_DIR . '/config/Zed/StateMachine/' . $stateMachineProcessTransfer->getStateMachineName();
    }

}
