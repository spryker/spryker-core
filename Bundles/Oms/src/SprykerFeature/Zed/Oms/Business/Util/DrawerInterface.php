<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Oms\Business\Util;

use SprykerFeature\Zed\Oms\Business\Process\ProcessInterface;
use SprykerFeature\Zed\Oms\Business\Process\StateInterface;

interface DrawerInterface
{

    /**
     * @param ProcessInterface $process
     * @param string $highlightState
     * @param null $format
     * @param int $fontsize
     *
     * @return bool
     */
    public function draw(ProcessInterface $process, $highlightState = null, $format = null, $fontsize = null);

    /**
     * @param ProcessInterface $process
     * @param string $highlightState
     */
    public function drawStates(ProcessInterface $process, $highlightState = null);

    /**
     * @param ProcessInterface $process
     */
    public function drawTransitions(ProcessInterface $process);

    /**
     * @param StateInterface $state
     */
    public function drawTransitionsEvents(StateInterface $state);

    /**
     * @param StateInterface $state
     */
    public function drawTransitionsConditions(StateInterface $state);

    /**
     * @param ProcessInterface $process
     */
    public function drawClusters(ProcessInterface $process);

}
