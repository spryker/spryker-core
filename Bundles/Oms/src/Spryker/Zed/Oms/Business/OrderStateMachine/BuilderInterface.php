<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Business\OrderStateMachine;

use Spryker\Zed\Oms\Business\Process\ProcessInterface;

interface BuilderInterface
{
    /**
     * @param string $processName
     * @param bool $regenerateCache
     *
     * @return \Spryker\Zed\Oms\Business\Process\ProcessInterface
     */
    public function createProcess($processName, bool $regenerateCache = false): ProcessInterface;
}
