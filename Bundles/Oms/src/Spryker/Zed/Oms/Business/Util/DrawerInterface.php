<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Business\Util;

use Spryker\Zed\Oms\Business\Process\ProcessInterface;
use Spryker\Zed\Oms\Business\Process\StateInterface;

interface DrawerInterface
{
    /**
     * @param \Spryker\Zed\Oms\Business\Process\ProcessInterface $process
     * @param string|null $highlightState
     * @param string|null $format
     * @param int|null $fontSize
     *
     * @return string
     */
    public function draw(ProcessInterface $process, $highlightState = null, $format = null, $fontSize = null);

    /**
     * @param \Spryker\Zed\Oms\Business\Process\ProcessInterface $process
     * @param string|null $highlightState
     *
     * @return void
     */
    public function drawStates(ProcessInterface $process, $highlightState = null);

    /**
     * @param \Spryker\Zed\Oms\Business\Process\ProcessInterface $process
     *
     * @return void
     */
    public function drawTransitions(ProcessInterface $process);

    /**
     * @param \Spryker\Zed\Oms\Business\Process\StateInterface $state
     *
     * @return void
     */
    public function drawTransitionsEvents(StateInterface $state);

    /**
     * @param \Spryker\Zed\Oms\Business\Process\StateInterface $state
     *
     * @return void
     */
    public function drawTransitionsConditions(StateInterface $state);

    /**
     * @param \Spryker\Zed\Oms\Business\Process\ProcessInterface $process
     *
     * @return void
     */
    public function drawClusters(ProcessInterface $process);
}
