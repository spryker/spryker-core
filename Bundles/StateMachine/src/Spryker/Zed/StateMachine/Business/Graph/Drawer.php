<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StateMachine\Business\Graph;

use Spryker\Service\UtilText\UtilTextService;
use Spryker\Shared\Graph\GraphInterface;
use Spryker\Zed\StateMachine\Business\Exception\DrawerException;
use Spryker\Zed\StateMachine\Business\Process\ProcessInterface;
use Spryker\Zed\StateMachine\Business\Process\StateInterface;
use Spryker\Zed\StateMachine\Business\Process\TransitionInterface;
use Spryker\Zed\StateMachine\Dependency\Plugin\StateMachineHandlerInterface;

class Drawer implements DrawerInterface
{
    public const ATTRIBUTE_FONT_SIZE = 'fontsize';

    public const EDGE_UPPER_HALF = 'upper half';
    public const EDGE_LOWER_HALF = 'lower half';
    public const EDGE_FULL = 'edge full';
    public const HIGHLIGHT_COLOR = '#FFFFCC';
    public const HAPPY_PATH_COLOR = '#70ab28';

    /**
     * @var array
     */
    protected $attributesProcess = [
        'fontname' => 'Verdana',
        'fillcolor' => '#cfcfcf',
        'style' => 'filled',
        'color' => '#ffffff',
        'fontsize' => 12,
        'fontcolor' => 'black',
    ];

    /**
     * @var array
     */
    protected $attributesState = [
        'fontname' => 'Verdana',
        'fontsize' => 14,
        'style' => 'filled',
        'fillcolor' => '#f9f9f9',
    ];

    /**
     * @var array
     */
    protected $attributesDiamond = [
        'fontname' => 'Verdana',
        'label' => '?',
        'shape' => 'diamond',
        'fontcolor' => 'white',
        'fontsize' => '1',
        'style' => 'filled',
        'fillcolor' => '#f9f9f9',
    ];

    /**
     * @var array
     */
    protected $attributesTransition = [
        'fontname' => 'Verdana',
        'fontsize' => 12,
    ];

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
     * @var \Spryker\Shared\Graph\GraphInterface
     */
    protected $graph;

    /**
     * @var \Spryker\Zed\StateMachine\Dependency\Plugin\StateMachineHandlerInterface
     */
    protected $stateMachineHandler;

    /**
     * @param \Spryker\Shared\Graph\GraphInterface $graph
     * @param \Spryker\Zed\StateMachine\Dependency\Plugin\StateMachineHandlerInterface $stateMachineHandler
     */
    public function __construct(GraphInterface $graph, StateMachineHandlerInterface $stateMachineHandler)
    {
        $this->graph = $graph;
        $this->stateMachineHandler = $stateMachineHandler;
    }

    /**
     * @param \Spryker\Zed\StateMachine\Business\Process\ProcessInterface $process
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
     * @param \Spryker\Zed\StateMachine\Business\Process\ProcessInterface $process
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
     * @param \Spryker\Zed\StateMachine\Business\Process\ProcessInterface $process
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
        $utilTextService = new UtilTextService();

        return $utilTextService->generateRandomString(32);
    }

    /**
     * @param \Spryker\Zed\StateMachine\Business\Process\StateInterface $state
     *
     * @throws \Spryker\Zed\StateMachine\Business\Exception\DrawerException
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
                throw new DrawerException('Transitions container seems to be empty.');
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
     * @param \Spryker\Zed\StateMachine\Business\Process\StateInterface $state
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
     * @param \Spryker\Zed\StateMachine\Business\Process\ProcessInterface $process
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
     * @param \Spryker\Zed\StateMachine\Business\Process\StateInterface $state
     * @param array $attributes
     * @param string|null $name
     * @param bool $highlighted
     *
     * @return void
     */
    protected function addNode(StateInterface $state, $attributes = [], $name = null, $highlighted = false)
    {
        $name = $name === null ? $state->getName() : $name;
        $labelName = $state->getDisplay() ?: $name;

        $label = [];
        $label[] = str_replace(' ', $this->br, trim($labelName));

        if ($state->hasFlags()) {
            $flags = implode(', ', $state->getFlags());
            $label[] = '<font color="violet" point-size="' . $this->fontSizeSmall . '">' . $flags . '</font>';
        }

        $attributes['label'] = implode($this->br, $label);

        if (!$state->hasOutgoingTransitions() || $this->hasOnlySelfReferences($state)) {
            $attributes['peripheries'] = 2;
        }

        if ($highlighted) {
            $attributes['fillcolor'] = self::HIGHLIGHT_COLOR;
        }

        $attributes = array_merge($this->attributesState, $attributes);
        $this->graph->addNode($name, $attributes, $state->getProcess()->getName());
    }

    /**
     * @param \Spryker\Zed\StateMachine\Business\Process\StateInterface $state
     *
     * @return bool
     */
    protected function hasOnlySelfReferences(StateInterface $state)
    {
        $hasOnlySelfReferences = true;
        $transitions = $state->getOutgoingTransitions();
        foreach ($transitions as $transition) {
            if ($transition->getTargetState()->getName() !== $state->getName()) {
                $hasOnlySelfReferences = false;
                break;
            }
        }

        return $hasOnlySelfReferences;
    }

    /**
     * @param \Spryker\Zed\StateMachine\Business\Process\TransitionInterface $transition
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
     * @param \Spryker\Zed\StateMachine\Business\Process\TransitionInterface $transition
     * @param array $label
     *
     * @return array
     */
    protected function addEdgeConditionText(TransitionInterface $transition, array $label)
    {
        if ($transition->hasCondition()) {
            $conditionLabel = $transition->getCondition();

            if (!isset($this->stateMachineHandler->getConditionPlugins()[$transition->getCondition()])) {
                $conditionLabel .= ' ' . $this->notImplemented;
            }

            $label[] = $conditionLabel;
        }

        return $label;
    }

    /**
     * @param \Spryker\Zed\StateMachine\Business\Process\TransitionInterface $transition
     * @param array $label
     *
     * @return array
     */
    protected function addEdgeEventText(TransitionInterface $transition, array $label)
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
                $commandLabel = 'command:' . $event->getCommand();

                if (!isset($this->stateMachineHandler->getCommandPlugins()[$event->getCommand()])) {
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
     * @param array $label
     *
     * @return string
     */
    protected function addEdgeElse(array $label)
    {
        if (!empty($label)) {
            $label = implode($this->brLeft, $label);
        } else {
            $label = 'else';
        }

        return $label;
    }

    /**
     * @param \Spryker\Zed\StateMachine\Business\Process\TransitionInterface $transition
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

        if ($transition->isHappyCase()) {
            $attributes['weight'] = '100';
            $attributes['color'] = self::HAPPY_PATH_COLOR;
        } elseif ($transition->hasEvent()) {
            $attributes['weight'] = '10';
        } else {
            $attributes['weight'] = '1';
        }

        return $attributes;
    }

    /**
     * @param \Spryker\Zed\StateMachine\Business\Process\TransitionInterface $transition
     * @param string|null $fromName
     *
     * @return string
     */
    protected function addEdgeFromState(TransitionInterface $transition, $fromName)
    {
        $fromName = $fromName !== null ? $fromName : $transition->getSourceState()->getName();

        return $fromName;
    }

    /**
     * @param \Spryker\Zed\StateMachine\Business\Process\TransitionInterface $transition
     * @param string|null $toName
     *
     * @return string
     */
    protected function addEdgeToState(TransitionInterface $transition, $toName)
    {
        $toName = $toName !== null ? $toName : $transition->getTargetState()->getName();

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
