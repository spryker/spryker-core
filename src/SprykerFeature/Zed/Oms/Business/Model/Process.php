<?php

namespace SprykerFeature\Zed\Oms\Business\Model;

use SprykerFeature\Zed\Oms\Business\Model\Process\EventInterface;
use SprykerFeature\Zed\Oms\Business\Model\Process\StatusInterface;
use SprykerFeature\Zed\Oms\Business\Model\Process\TransitionInterface;
use SprykerFeature\Zed\Oms\Business\Model\Util\DrawerInterface;
use Exception;

class Process implements ProcessInterface
{

    protected $name;

    /**
     * @var StatusInterface[]
     */
    protected $statuses = array();

    /**
     * @var TransitionInterface[]
     */
    protected $transitions = array();

    protected $main;

    protected $file;

    /**
     * @var DrawerInterface
     */
    protected $drawer;

    /**
     * @var ProcessInterface[]
     */
    protected $subprocesses = array();

    /**
     * @param DrawerInterface $drawer
     */
    public function __construct(DrawerInterface $drawer)
    {
        $this->drawer = $drawer;
    }

    /**
     * @param bool   $highlightStatus
     * @param string $format
     * @param int    $fontsize
     *
     * @return bool
     */
    public function draw($highlightStatus = false, $format = null, $fontsize = null)
    {
        return $this->drawer->draw($this, $highlightStatus, $format, $fontsize);
    }

    /**
     * @param ProcessInterface[] $subprocesses
     */
    public function setSubprocesses($subprocesses)
    {
        $this->subprocesses = $subprocesses;
    }

    /**
     * @return ProcessInterface[]
     */
    public function getSubprocesses()
    {
        return $this->subprocesses;
    }

    /**
     * @return bool
     */
    public function hasSubprocesses()
    {
        return count($this->subprocesses) > 0;
    }

    /**
     * @param ProcessInterface $subprocess
     */
    public function addSubprocess(ProcessInterface $subprocess)
    {
        $this->subprocesses[] = $subprocess;
    }

    /**
     * @param mixed $main
     */
    public function setMain($main)
    {
        $this->main = $main;
    }

    /**
     * @return mixed
     */
    public function getMain()
    {
        return $this->main;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param StatusInterface[] $statuses
     */
    public function setStatuses($statuses)
    {
        $this->statuses = $statuses;
    }

    /**
     * @param StatusInterface $status
     */
    public function addStatus(StatusInterface $status)
    {
        $this->statuses[$status->getName()] = $status;
    }

    /**
     * @param string $statusId
     *
     * @return StatusInterface
     */
    public function getStatus($statusId)
    {
        return $this->statuses[$statusId];
    }

    /**
     * @param string $statusId
     *
     * @return bool
     */
    public function hasStatus($statusId)
    {
        return array_key_exists($statusId, $this->statuses);
    }

    /**
     * @param string $statusId
     *
     * @return StatusInterface
     * @throws Exception
     */
    public function getStatusFromAllProcesses($statusId)
    {
        $processes = $this->getAllProcesses();
        foreach ($processes as $process) {
            if ($process->hasStatus($statusId)) {
                return $process->getStatus($statusId);
            }
        }
        throw new Exception('Unknown status: ' . $statusId);
    }

    /**
     * @return StatusInterface[]
     */
    public function getStatuses()
    {
        return $this->statuses;
    }

    /**
     * @return bool
     */
    public function hasStatuses()
    {
        return !empty($this->statuses);
    }

    /**
     * @param TransitionInterface $transition
     */
    public function addTransition(TransitionInterface $transition)
    {
        $this->transitions[] = $transition;
    }

    /**
     * @param TransitionInterface[] $transitions
     */
    public function setTransitions($transitions)
    {
        $this->transitions = $transitions;
    }

    /**
     * @return TransitionInterface[]
     */
    public function getTransitions()
    {
        return $this->transitions;
    }

    /**
     * @return bool
     */
    public function hasTransitions()
    {
        return !empty($this->transitions);
    }

    /**
     * @return StatusInterface[]
     */
    public function getAllStatuses()
    {
        $statuses = array();
        if ($this->hasStatuses()) {
            $statuses = $this->getStatuses();
        }
        if ($this->hasSubprocesses()) {
            foreach ($this->getSubprocesses() as $subProcess) {
                if ($subProcess->hasStatuses()) {
                    $statuses = array_merge($statuses, $subProcess->getStatuses());
                }
            }
        }

        return $statuses;
    }

    /**
     * @return StatusInterface[]
     */
    public function getAllReservedStatuses()
    {
        $reservedStatuses = array();
        $statuses = $this->getAllStatuses();
        foreach ($statuses as $status) {
            if ($status->isReserved()) {
                $reservedStatuses[] = $status;
            }
        }

        return $reservedStatuses;
    }

    /**
     * @return TransitionInterface[]
     */
    public function getAllTransitions()
    {
        $transitions = array();
        if ($this->hasTransitions()) {
            $transitions = $this->getTransitions();
        }
        foreach ($this->getSubprocesses() as $subProcess) {
            if ($subProcess->hasTransitions()) {
                $transitions = array_merge($transitions, $subProcess->getTransitions());
            }
        }

        return $transitions;
    }

    /**
     * @return TransitionInterface[]
     */
    public function getAllTransitionsWithoutEvent()
    {
        $transitions = array();
        $allTransitions = $this->getAllTransitions();
        foreach ($allTransitions as $transition) {
            if (false === $transition->hasEvent()) {
                $transitions[] = $transition;
            }
        }

        return $transitions;
    }

    /**
     * @return EventInterface[]
     */
    public function getManualEvents()
    {
        $manuallyExecuteableEventList = array();
        $transitions = $this->getAllTransitions();
        foreach ($transitions as $transition) {
            if ($transition->hasEvent()) {
                $event = $transition->getEvent();
                if ($event->isManual()) {
                    $manuallyExecuteableEventList[] = $event;
                }
            }
        }

        return $manuallyExecuteableEventList;
    }

    /**
     * @return EventInterface[]
     */
    public function getManualEventsBySource()
    {
        $events = $this->getManualEvents();

        $eventsBySource = array();
        foreach ($events as $event) {
            $transitions = $event->getTransitions();
            foreach ($transitions as $transition) {
                $sourceName = $transition->getSource()->getName();
                if (!isset($eventsBySource[$sourceName])) {
                    $eventsBySource[$sourceName] = array();
                }
                if (!in_array($event->getName(), $eventsBySource[$sourceName])) {
                    $eventsBySource[$sourceName][] = $event->getName();
                }
            }
        }

        return $eventsBySource;
    }

    /**
     * @return ProcessInterface[]
     */
    public function getAllProcesses()
    {
        $processes = array();
        $processes[] = $this;
        $processes = array_merge($processes, $this->getSubprocesses());

        return $processes;
    }
    /**
     * @param mixed $file
     */
    public function setFile($file)
    {
        $this->file = $file;
    }

    /**
     * @return bool
     */
    public function hasFile()
    {
       return isset($this->file);
    }

    /**
     * @return mixed
     */
    public function getFile()
    {
        return $this->file;
    }

}
