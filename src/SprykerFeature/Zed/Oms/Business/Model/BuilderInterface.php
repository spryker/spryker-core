<?php

namespace SprykerFeature\Zed\Oms\Business\Model;

interface BuilderInterface
{
    /**
     * @param string $processName
     *
     * @return ProcessInterface
     */
    public function createProcess($processName);
}
