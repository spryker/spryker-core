<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Dependency\Timer;

class DataImportToTimerBridge implements DataImportToTimerInterface
{

    /**
     * @var \PHP_Timer
     */
    protected $timer;

    /**
     * @param \PHP_Timer $timer
     */
    public function __construct($timer)
    {
        $this->timer = $timer;
    }

    /**
     * @return void
     */
    public function start()
    {
        $this->timer->start();
    }

    /**
     * @return float
     */
    public function stop()
    {
        return $this->timer->stop();
    }

    /**
     * @param float $time
     *
     * @return string
     */
    public function secondsToTimeString($time)
    {
        return $this->timer->secondsToTimeString($time);
    }

}
