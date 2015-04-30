<?php

namespace SprykerFeature\Zed\Oms\Business\OrderStateMachine;

interface BuilderInterface
{
    /**
     * @param string $processName
     *
     * @return ProcessInterface
     */
    public function createProcess($processName);
}
