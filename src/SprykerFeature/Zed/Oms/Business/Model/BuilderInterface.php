<?php

namespace SprykerFeature\Zed\Oms\Business\Model;

/**
 * Interface BuilderInterface
 * @package SprykerFeature\Zed\Oms\Business\Model
 */
interface BuilderInterface
{
    /**
     * @param string $processName
     * @return ProcessInterface
     */
    public function createProcess($processName);
}
