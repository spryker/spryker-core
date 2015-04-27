<?php

namespace SprykerFeature\Zed\Oms\Business\Model;

interface DummyInterface
{
    /**
     * @param $processName
     *
     * @return mixed
     */
    public function prepareItems($processName);

    /**
     * @param string $processName
     *
     * @return array
     */
    public function getOrderItems($processName);
}
