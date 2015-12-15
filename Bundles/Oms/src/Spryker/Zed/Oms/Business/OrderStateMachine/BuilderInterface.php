<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Oms\Business\OrderStateMachine;

use Spryker\Zed\Oms\Business\Process\ProcessInterface;

interface BuilderInterface
{

    /**
     * @param string $processName
     *
     * @return ProcessInterface
     */
    public function createProcess($processName);

}
