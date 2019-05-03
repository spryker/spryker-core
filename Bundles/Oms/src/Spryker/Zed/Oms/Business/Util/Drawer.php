<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Business\Util;

use Spryker\Shared\Graph\GraphInterface;
use Spryker\Zed\Oms\Business\Exception\StatemachineException;
use Spryker\Zed\Oms\Business\Process\ProcessInterface;
use Spryker\Zed\Oms\Business\Process\StateInterface;
use Spryker\Zed\Oms\Business\Process\TransitionInterface;
use Spryker\Zed\Oms\Communication\Plugin\Oms\Command\CommandCollection;
use Spryker\Zed\Oms\Communication\Plugin\Oms\Condition\ConditionCollection;
use Spryker\Zed\Oms\Dependency\Plugin\Command\CommandByOrderInterface;
use Spryker\Zed\Oms\Dependency\Plugin\Command\CommandCollectionInterface;
use Spryker\Zed\Oms\Dependency\Plugin\Condition\ConditionCollectionInterface;
use Spryker\Zed\Oms\Dependency\Plugin\Condition\HasAwareCollectionInterface;
use Spryker\Zed\Oms\Dependency\Service\OmsToUtilTextInterface;

class Drawer implements DrawerInterface
{
    public const ATTRIBUTE_FONT_SIZE = 'fontsize';

    public const EDGE_UPPER_HALF = 'upper half';
    public const EDGE_LOWER_HALF = 'lower half';
    public const EDGE_FULL = 'edge full';

    /**
     * @var array
     */
    protected $attributesProcess = ['fontname' => 'Verdana', 'fillcolor' => '#cfcfcf', 'style' => 'filled', 'color' => '#ffffff', 'fontsize' => 12, 'fontcolor' => 'black'];

    /**
     * @var array
     */
    protected $attributesState = ['fontname' => 'Verdana', 'fontsize' => 14, 'style' => 'filled', 'fillcolor' => '#f9f9f9'];

    /**
     * @var array
     */
    protected $attributesDiamond = ['fontname' => 'Verdana', 'label' => '?', 'shape' => 'diamond', 'fontcolor' => 'white', 'fontsize' => '1', 'style' => 'filled', 'fillcolor' => '#f9f9f9'];

    /**
     * @var array
     */
    protected $attributesTransition = ['fontname' => 'Verdana', 'fontsize' => 12];

    /**
     * @var string
     */
    protected $brLeft = '<br align="left" />  ';

    /**
     * @var string
     */
    protected $notImplemented = '<font color="red">(not implemented)</font>';

    /**
     * @var string
     */
    protected $br = '<br/>';

    /**
     * @var string
     */
    protected $format = 'svg';

    /**
     * @var int|null
     */
    protected $fontSizeBig = null;

    /**
     * @var int|null
     */
    protected $fontSizeSmall = null;

    /**
     * @var \Spryker\Zed\Oms\Dependency\Plugin\Condition\ConditionCollectionInterface
     */
    protected $conditions;

    /**
     * @var \Spryker\Zed\Oms\Dependency\Plugin\Command\CommandCollectionInterface
     */
    protected $commands;

    /**
     * @var \Spryker\Shared\Graph\GraphInterface
     */
    protected $graph;

    /**
     * @var \Spryker\Zed\Oms\Dependency\Service\OmsToUtilTextInterface
     */
    protected $utilTextService;

    /**
     * @param \Spryker\Zed\Oms\Dependency\Plugin\Command\CommandCollectionInterface|array $commands
     * @param \Spryker\Zed\Oms\Dependency\Plugin\Condition\ConditionCollectionInterface|array $conditions
     * @param \Spryker\Shared\Graph\GraphInterface $graph
     * @param \Spryker\Zed\Oms\Dependency\Service\OmsToUtilTextInterface $utilTextService
     */
    public function __construct(
        $commands,
        $conditions,
        GraphInterface $graph,
        OmsToUtilTextInterface $utilTextService
    ) {
        $this->setCommands($commands);
        $this->setConditions($conditions);

        $this->graph = $graph;
        $this->utilTextService = $utilTextService;
    }

    /**
     * Converts array to collection for BC
     *
     * @param \Spryker\Zed\Oms\Dependency\Plugin\Condition\ConditionCollectionInterface|array $conditions
     *
     * @return void
     */
    protected function setConditions($conditions)
    {
        if ($conditions instanceof ConditionCollectionInterface) {
            $this->conditions = $conditions;

            return;
        }

        $conditionCollection = new ConditionCollection();
        foreach ($conditions as $name => $condition) {
            $conditionCollection->add($condition, $name);
        }

        $this->conditions = $conditionCollection;
    }

    /**
     * Converts array to collection for BC
     *
     * @param \Spryker\Zed\Oms\Dependency\Plugin\Command\CommandCollectionInterface|array $commands
     *
     * @return void
     */
    protected function setCommands($commands)
    {
        if ($commands instanceof CommandCollectionInterface) {
            $this->commands = $commands;

            return;
        }

        $commandCollection = new CommandCollection();
        foreach ($commands as $name => $command) {
            $commandCollection->add($command, $name);
        }

        $this->commands = $commandCollection;
    }

    /**
     * @param \Spryker\Zed\Oms\Business\Process\ProcessInterface $process
     * @param string|null $highlightState
     * @param string|null $format
     * @param int|null $fontSize
     *
     * @return string
     */
    public function draw(ProcessInterface $process, $highlightState = null, $format = null, $fontSize = null)
    {
        $this->init($format, $fontSize);

        $this->drawClusters($process);
        $this->drawStates($process, $highlightState);
        $this->drawTransitions($process);

        return $this->graph->render($this->format);
    }

    /**
     * @param \Spryker\Zed\Oms\Business\Process\ProcessInterface $process
     * @param string|null $highlightState
     *
     * @return void
     */
    public function drawStates(ProcessInterface $process, $highlightState = null)
    {
        $states = $process->getAllStates();
        foreach ($states as $state) {
            $isHighlighted = $highlightState === $state->getName();
            $this->addNode($state, [], null, $isHighlighted);
        }
    }

    /**
     * @param \Spryker\Zed\Oms\Business\Process\ProcessInterface $process
     *
     * @return void
     */
    public function drawTransitions(ProcessInterface $process)
    {
        $states = $process->getAllStates();
        foreach ($states as $state) {
            $this->drawTransitionsEvents($state);
            $this->drawTransitionsConditions($state);
        }
    }

    /**
     * @return string
     */
    protected function getDiamondId()
    {
        return $this->utilTextService->generateRandomString(32);
    }

    /**
     * @param \Spryker\Zed\Oms\Business\Process\StateInterface $state
     *
     * @throws \Spryker\Zed\Oms\Business\Exception\StatemachineException
     *
     * @return void
     */
    public function drawTransitionsEvents(StateInterface $state)
    {
        $events = $state->getEvents();
        foreach ($events as $event) {
            $transitions = $state->getOutgoingTransitionsByEvent($event);

            $currentTransition = current($transitions);
            if (!$currentTransition) {
                throw new StatemachineException('Transitions container seems to be empty.');
            }

            if (count($transitions) > 1) {
                $diamondId = $this->getDiamondId();

                $this->graph->addNode($diamondId, $this->attributesDiamond, $state->getProcess()->getName());
                $this->addEdge($currentTransition, self::EDGE_UPPER_HALF, [], null, $diamondId);

                foreach ($transitions as $transition) {
                    $this->addEdge($transition, self::EDGE_LOWER_HALF, [], $diamondId);
                }
            } else {
                $this->addEdge($currentTransition, self::EDGE_FULL);
            }
        }
    }

    /**
     * @param \Spryker\Zed\Oms\Business\Process\StateInterface $state
     *
     * @return void
     */
    public function drawTransitionsConditions(StateInterface $state)
    {
        $transitions = $state->getOutgoingTransitions();
        foreach ($transitions as $transition) {
            if ($transition->hasEvent()) {
                continue;
            }
            $this->addEdge($transition);
        }
    }

    /**
     * @param \Spryker\Zed\Oms\Business\Process\ProcessInterface $process
     *
     * @return void
     */
    public function drawClusters(ProcessInterface $process)
    {
        $processes = $process->getAllProcesses();
        foreach ($processes as $subProcess) {
            $group = $subProcess->getName();
            $attributes = $this->attributesProcess;
            $attributes['label'] = $group;

            $this->graph->addCluster($group, $attributes);
        }
    }

    /**
     * @param \Spryker\Zed\Oms\Business\Process\StateInterface $state
     * @param array $attributes
     * @param string|null $name
     * @param bool $highlighted
     *
     * @return void
     */
    protected function addNode(StateInterface $state, $attributes = [], $name = null, $highlighted = false)
    {
        $name = $name === null ? $state->getName() : $name;

        $label = [];
        $label[] = str_replace(' ', $this->br, trim($name));

        if ($state->isReserved()) {
            $label[] = '<font color="blue" point-size="' . $this->fontSizeSmall . '">' . 'reserved' . '</font>';
        }

        if ($state->hasFlags()) {
            $flags = implode(', ', $state->getFlags());
            $label[] = '<font color="violet" point-size="' . $this->fontSizeSmall . '">' . $flags . '</font>';
        }

        $attributes['label'] = implode($this->br, $label);

        if (!$state->hasOutgoingTransitions() || $this->hasOnlySelfReferences($state)) {
            $attributes['peripheries'] = 2;
        }

        if ($highlighted) {
            $attributes['fillcolor'] = '#FFFFCC';
        }

        $attributes = array_merge($this->attributesState, $attributes);
        $this->graph->addNode($name, $attributes, $state->getProcess()->getName());
    }

    /**
     * @param \Spryker\Zed\Oms\Business\Process\StateInterface $state
     *
     * @return bool
     */
    protected function hasOnlySelfReferences(StateInterface $state)
    {
        $hasOnlySelfReferences = true;
        $transitions = $state->getOutgoingTransitions();
        foreach ($transitions as $transition) {
            if ($transition->getTarget()->getName() !== $state->getName()) {
                $hasOnlySelfReferences = false;
                break;
            }
        }

        return $hasOnlySelfReferences;
    }

    /**
     * @param \Spryker\Zed\Oms\Business\Process\TransitionInterface $transition
     * @param string $type
     * @param array $attributes
     * @param string|null $fromName
     * @param string|null $toName
     *
     * @return void
     */
    protected function addEdge(TransitionInterface $transition, $type = self::EDGE_FULL, $attributes = [], $fromName = null, $toName = null)
    {
        $label = [];

        if ($type !== self::EDGE_LOWER_HALF) {
            $label = $this->addEdgeEventText($transition, $label);
        }

        if ($type !== self::EDGE_UPPER_HALF) {
            $label = $this->addEdgeConditionText($transition, $label);
        }

        $label = $this->addEdgeElse($label);
        $fromName = $this->addEdgeFromState($transition, $fromName);
        $toName = $this->addEdgeToState($transition, $toName);
        $attributes = $this->addEdgeAttributes($transition, $attributes, $label, $type);

        $this->graph->addEdge($fromName, $toName, $attributes);
    }

    /**
     * @param \Spryker\Zed\Oms\Business\Process\TransitionInterface $transition
     * @param string[] $label
     *
     * @return string[]
     */
    protected function addEdgeConditionText(TransitionInterface $transition, $label)
    {
        if ($transition->hasCondition()) {
            $conditionLabel = $transition->getCondition();

            if (!$this->inCollection($this->conditions, $transition->getCondition())) {
                $conditionLabel .= ' ' . $this->notImplemented;
            }

            $label[] = $conditionLabel;
        }

        return $label;
    }

    /**
     * @param \Spryker\Zed\Oms\Business\Process\TransitionInterface $transition
     * @param string[] $label
     *
     * @return string[]
     */
    protected function addEdgeEventText(TransitionInterface $transition, $label)
    {
        if ($transition->hasEvent()) {
            $event = $transition->getEvent();

            if ($event->isOnEnter()) {
                $label[] = '<b>' . $event->getName() . ' (on enter)</b>';
            } else {
                $label[] = '<b>' . $event->getName() . '</b>';
            }

            if ($event->hasTimeout()) {
                $label[] = 'timeout: ' . $event->getTimeout();
            }

            if ($event->hasCommand()) {
                $commandLabel = 'c:' . $event->getCommand();

                if ($this->inCollection($this->commands, $event->getCommand())) {
                    $commandModel = $this->commands->get($event->getCommand());
                    if ($commandModel instanceof CommandByOrderInterface) {
                        $commandLabel .= ' (by order)';
                    } else {
                        $commandLabel .= ' (by item)';
                    }
                } else {
                    $commandLabel .= ' ' . $this->notImplemented;
                }
                $label[] = $commandLabel;
            }

            if ($event->isManual()) {
                $label[] = 'manually executable';
            }
        } else {
            $label[] = '&infin;';
        }

        return $label;
    }

    /**
     * @param \Spryker\Zed\Oms\Dependency\Plugin\Condition\HasAwareCollectionInterface|mixed $collection
     * @param string $commandName
     *
     * @return bool
     */
    protected function inCollection($collection, $commandName)
    {
        if ($collection instanceof HasAwareCollectionInterface) {
            return $collection->has($commandName);
        }

        return false;
    }

    /**
     * @param string[] $label
     *
     * @return string
     */
    protected function addEdgeElse($label)
    {
        if (!empty($label)) {
            $label = implode($this->brLeft, $label);
        } else {
            $label = 'else';
        }

        return $label;
    }

    /**
     * @param \Spryker\Zed\Oms\Business\Process\TransitionInterface $transition
     * @param array $attributes
     * @param string $label
     * @param string $type
     *
     * @return array
     */
    protected function addEdgeAttributes(TransitionInterface $transition, array $attributes, $label, $type = self::EDGE_FULL)
    {
        $attributes = array_merge($this->attributesTransition, $attributes);
        $attributes['label'] = '  ' . $label;

        if ($transition->hasEvent() === false) {
            $attributes['style'] = 'dashed';
        }

        if ($type === self::EDGE_FULL || $type === self::EDGE_UPPER_HALF) {
            if ($transition->hasEvent() && $transition->getEvent()->isOnEnter()) {
                $attributes['arrowtail'] = 'crow';
                $attributes['dir'] = 'both';
            }
        }

        if ($transition->isHappy()) {
            $attributes['weight'] = '100';
            $attributes['color'] = '#70ab28'; // TODO eindeutig?
        } elseif ($transition->hasEvent()) {
            $attributes['weight'] = '10';
        } else {
            $attributes['weight'] = '1';
        }

        return $attributes;
    }

    /**
     * @param \Spryker\Zed\Oms\Business\Process\TransitionInterface $transition
     * @param string|null $fromName
     *
     * @return string
     */
    protected function addEdgeFromState(TransitionInterface $transition, $fromName)
    {
        $fromName = $fromName !== null ? $fromName : $transition->getSource()->getName();

        return $fromName;
    }

    /**
     * @param \Spryker\Zed\Oms\Business\Process\TransitionInterface $transition
     * @param string|null $toName
     *
     * @return string
     */
    protected function addEdgeToState(TransitionInterface $transition, $toName)
    {
        $toName = $toName !== null ? $toName : $transition->getTarget()->getName();

        return $toName;
    }

    /**
     * @param string|null $format
     * @param int|null $fontSize
     *
     * @return void
     */
    protected function init($format, $fontSize)
    {
        if ($format !== null) {
            $this->format = $format;
        }

        if ($fontSize !== null) {
            $this->attributesState[self::ATTRIBUTE_FONT_SIZE] = $fontSize;
            $this->attributesProcess[self::ATTRIBUTE_FONT_SIZE] = $fontSize - 2;
            $this->attributesTransition[self::ATTRIBUTE_FONT_SIZE] = $fontSize - 2;
            $this->fontSizeBig = $fontSize;
            $this->fontSizeSmall = $fontSize - 2;
        }
    }
}
