<?php

namespace SprykerFeature\Zed\Oms\Business\Model;

use SprykerFeature\Zed\Oms\Business\Model\Process\EventInterface;
use SprykerFeature\Zed\Oms\Business\Model\Process\StatusInterface;
use SprykerFeature\Zed\Oms\Business\Model\Process\TransitionInterface;

/**
 * Interface ProcessInterface
 * @package SprykerFeature\Zed\Oms\Business\Model
 */
interface ProcessInterface
{
    /**
     * @param string $highlightStatus
     * @param null   $format
     * @param int    $fontsize
     * @return bool
     */
    public function draw($highlightStatus = null, $format = null, $fontsize = null);

    /**
     * @param ProcessInterface[] $subprocesses
     */
    public function setSubprocesses($subprocesses);

    /**
     * @return ProcessInterface[]
     */
    public function getSubprocesses();

    /**
     * @return bool
     */
    public function hasSubprocesses();

    /**
     * @param ProcessInterface $subprocess
     */
    public function addSubprocess(ProcessInterface $subprocess);

    /**
     * @param mixed $main
     */
    public function setMain($main);

    /**
     * @return mixed
     */
    public function getMain();

    /**
     * @param mixed $name
     */
    public function setName($name);

    /**
     * @return mixed
     */
    public function getName();

    /**
     * @param StatusInterface[] $statuses
     */
    public function setStatuses($statuses);

    /**
     * @param StatusInterface $status
     */
    public function addStatus(StatusInterface $status);

    /**
     * @param string $statusId
     * @return StatusInterface
     */
    public function getStatus($statusId);

    /**
     * @param string $statusId
     * @return bool
     */
    public function hasStatus($statusId);

    /**
     * @param string $statusId
     * @return StatusInterface
     * @throws \Exception
     */
    public function getStatusFromAllProcesses($statusId);

    /**
     * @return StatusInterface[]
     */
    public function getStatuses();

    /**
     * @return bool
     */
    public function hasStatuses();

    /**
     * @param TransitionInterface $transition
     */
    public function addTransition(TransitionInterface $transition);

    /**
     * @param TransitionInterface[] $transitions
     */
    public function setTransitions($transitions);

    /**
     * @return TransitionInterface[]
     */
    public function getTransitions();

    /**
     * @return bool
     */
    public function hasTransitions();

    /**
     * @return StatusInterface[]
     */
    public function getAllStatuses();

    /**
     * @return StatusInterface[]
     */
    public function getAllReservedStatuses();

    /**
     * @return TransitionInterface[]
     */
    public function getAllTransitions();

    /**
     * @return TransitionInterface[]
     */
    public function getAllTransitionsWithoutEvent();

    /**
     * @return EventInterface[]
     */
    public function getManualEvents();

    /**
     * @return EventInterface[]
     */
    public function getManualEventsBySource();

    /**
     * @return ProcessInterface[]
     */
    public function getAllProcesses();

    /**
     * @param mixed $file
     */
    public function setFile($file);

    /**
     * @return bool
     */
    public function hasFile();

    /**
     * @return mixed
     */
    public function getFile();
}
