<?php

namespace SprykerFeature\Zed\Oms\Business\Model\Util;

use SprykerFeature\Zed\Oms\Business\Model\Process\StatusInterface;
use SprykerFeature\Zed\Oms\Business\Model\ProcessInterface;

interface DrawerInterface
{
    /**
     * @param ProcessInterface $process
     * @param string $highlightStatus
     * @param null $format
     * @param int $fontsize
     *
     * @return bool
     */
    public function draw(ProcessInterface $process, $highlightStatus = null, $format = null, $fontsize = null);

    /**
     * @param ProcessInterface $process
     * @param string $highlightStatus
     */
    public function drawStatuses(ProcessInterface $process, $highlightStatus = null);

    /**
     * @param ProcessInterface $process
     */
    public function drawTransitions(ProcessInterface $process);

    /**
     * @param StatusInterface $status
     */
    public function drawTransitionsEvents(StatusInterface $status);

    /**
     * @param StatusInterface $status
     */
    public function drawTransitionsConditions(StatusInterface $status);

    /**
     * @param ProcessInterface $process
     */
    public function drawClusters(ProcessInterface $process);
}
