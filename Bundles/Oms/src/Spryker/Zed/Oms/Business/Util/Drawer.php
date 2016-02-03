<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Oms\Business\Util;

use Spryker\Tool\Graph\GraphInterface;
use Spryker\Tool\Graph\Graph;
use Spryker\Zed\Oms\Business\Process\ProcessInterface;
use Spryker\Zed\Oms\Communication\Plugin\Oms\Command\CommandByOrderInterface;
use Spryker\Zed\Oms\Business\Process\StateInterface;
use Spryker\Zed\Oms\Business\Process\TransitionInterface;

class Drawer implements DrawerInterface
{

    protected $attributesProcess = ['fontname' => 'Verdana', 'fillcolor' => '#cfcfcf', 'style' => 'filled', 'color' => '#ffffff', 'fontsize' => 12, 'fontcolor' => 'black'];

    protected $attributesState = ['fontname' => 'Verdana', 'fontsize' => 14, 'style' => 'filled', 'fillcolor' => '#f9f9f9'];

    protected $attributesDiamond = ['fontname' => 'Verdana', 'label' => '?', 'shape' => 'diamond', 'fontcolor' => 'white', 'fontsize' => '1', 'style' => 'filled', 'fillcolor' => '#f9f9f9'];

    protected $attributesTransition = ['fontname' => 'Verdana', 'fontsize' => 12];

    protected $brLeft = '<br align="left" />  ';

    protected $notImplemented = '<font color="red">(not implemented)</font>';

    protected $br = '<br/>';

    protected $format = 'svg';

    protected $fontSizeBig = null;

    protected $fontSizeSmall = null;

    protected $conditionModels = [];

    protected $commandModels = [];

    const ATTRIBUTE_FONT_SIZE = 'fontsize';

    const EDGE_UPPER_HALF = 'upper half';
    const EDGE_LOWER_HALF = 'lower half';
    const EDGE_FULL = 'edge full';

    /**
     * @var array
     */
    protected $conditions;

    /**
     * @var array
     */
    protected $commands;

    /**
     * @var \Spryker\Tool\Graph\Graph
     */
    protected $graph;

    /**
     * @param array $commands
     * @param array $conditions
     * @param \Spryker\Tool\Graph\GraphInterface $graph
     */
    public function __construct(array $commands, array $conditions, GraphInterface $graph)
    {
        $this->commandModels = $commands;
        $this->conditionModels = $conditions;
        $this->graph = $graph;
    }

    /**
     * @param \Spryker\Zed\Oms\Business\Process\ProcessInterface $process
     * @param string|null $highlightState
     * @param string|null $format
     * @param int|null $fontSize
     *
     * @return bool
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
     * @param \Spryker\Zed\Oms\Business\Process\StateInterface $state
     *
     * @return void
     */
    public function drawTransitionsEvents(StateInterface $state)
    {
        $events = $state->getEvents();
        foreach ($events as $event) {
            $transitions = $state->getOutgoingTransitionsByEvent($event);

            if (count($transitions) > 1) {
                $diamondId = uniqid();

                $this->graph->addNode($diamondId, $this->attributesDiamond, $state->getProcess()->getName());

                $this->addEdge(current($transitions), self::EDGE_UPPER_HALF, [], null, $diamondId);

                foreach ($transitions as $transition) {
                    $this->addEdge($transition, self::EDGE_LOWER_HALF, [], $diamondId);
                }
            } else {
                $this->addEdge(current($transitions), self::EDGE_FULL);
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
//            $this->graph->addCluster($group, $group, $attributes);
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
     * @param string $label
     *
     * @return array
     */
    protected function addEdgeConditionText(TransitionInterface $transition, $label)
    {
        if ($transition->hasCondition()) {
            $conditionLabel = $transition->getCondition();

            if (!isset($this->conditionModels[$transition->getCondition()])) {
                $conditionLabel .= ' ' . $this->notImplemented;
            }

            $label[] = $conditionLabel;
        }

        return $label;
    }

    /**
     * @param \Spryker\Zed\Oms\Business\Process\TransitionInterface $transition
     * @param string $label
     *
     * @return array
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

                if (isset($this->commandModels[$event->getCommand()])) {
                    $commandModel = $this->commandModels[$event->getCommand()];
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
     * @param string $label
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
     * @param string $fromName
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
