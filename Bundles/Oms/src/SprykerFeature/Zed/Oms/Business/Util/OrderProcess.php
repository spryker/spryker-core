<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Oms\Business\Util;

use Orm\Zed\Oms\Persistence\SpyOmsOrderProcess;
use SprykerFeature\Zed\Oms\OmsConfig;
use SprykerFeature\Zed\Oms\Persistence\OmsQueryContainerInterface;

class OrderProcess
{

    /**
     * @var OmsQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var OmsConfig
     */
    protected $config;

    /**
     * @param OmsQueryContainerInterface $queryContainer
     * @param OmsConfig $config
     */
    public function __construct(OmsQueryContainerInterface $queryContainer, OmsConfig $config)
    {
        $this->queryContainer = $queryContainer;
        $this->config = $config;
    }

    /**
     * @return SpyOmsOrderProcess[]
     */
    public function getActiveProcesses()
    {
        return $this->queryContainer
            ->getActiveProcesses($this->config->getActiveProcesses())
            ->find();
    }

    /**
     * @param array $orderItemStates
     * @return array
     */
    public function getOrderItemStateNames(array $orderItemStates)
    {
        $results = $this->queryContainer->getOrderItemStates($orderItemStates)->find();

        $orderItemStates = [];
        foreach ($results as $result) {
            $orderItemStates[$result->getIdOmsOrderItemState()] = $result->getName();
        }

        return $orderItemStates;
    }

}
