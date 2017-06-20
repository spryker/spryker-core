<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Dependency\Timer;

interface DataImportToTimerInterface
{

    /**
     * @return void
     */
    public function start();

    /**
     * @return float
     */
    public function stop();

    /**
     * @param float $time
     *
     * @return string
     */
    public function secondsToTimeString($time);

}
