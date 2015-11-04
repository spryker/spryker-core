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
     * @param string|null $highlightState
     * @param string|null $format
     * @param int|null $fontSize
     *
     * @return bool
     */
    public function draw(ProcessInterface $process, $highlightState = null, $format = null, $fontSize = null);

    /**
     * @param ProcessInterface $process
     * @param string|null $highlightState
     *
     * @return void
     */
    public function drawStates(ProcessInterface $process, $highlightState = null);

    /**
     * @param ProcessInterface $process
     *
     * @return void
     */
    public function drawTransitions(ProcessInterface $process);

    /**
     * @param StateInterface $state
     *
     * @return void
     */
    public function drawTransitionsEvents(StateInterface $state);

    /**
     * @param StateInterface $state
     *
     * @return void
     */
    public function drawTransitionsConditions(StateInterface $state);

    /**
     * @param ProcessInterface $process
     *
     * @return void
     */
    public function drawClusters(ProcessInterface $process);

}
