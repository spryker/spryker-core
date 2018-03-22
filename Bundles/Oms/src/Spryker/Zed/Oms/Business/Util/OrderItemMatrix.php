<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Business\Util;

use DateTime;
use Spryker\Zed\Oms\Dependency\Service\OmsToUtilSanitizeInterface;
use Spryker\Zed\Oms\OmsConfig;
use Spryker\Zed\Oms\Persistence\OmsQueryContainerInterface;

class OrderItemMatrix
{
    const COL_STATE = 'COL_STATE';

    /**
     * @var \Spryker\Zed\Oms\Persistence\OmsQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\Oms\OmsConfig
     */
    protected $config;

    /**
     * @var array
     */
    protected $processes;

    /**
     * @var \Orm\Zed\Sales\Persistence\SpySalesOrderItem[]
     */
    protected $orderItems;

    /**
     * @var array
     */
    protected $orderItemStates = [];

    /**
     * @var array
     */
    protected $orderItemStateBlacklist = [];

    /**
     * @var \Spryker\Zed\Oms\Dependency\Service\OmsToUtilSanitizeInterface
     */
    protected $utilSanitizeService;

    /**
     * @param \Spryker\Zed\Oms\Persistence\OmsQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\Oms\OmsConfig $config
     * @param \Spryker\Zed\Oms\Dependency\Service\OmsToUtilSanitizeInterface $utilSanitizeService
     */
    public function __construct(
        OmsQueryContainerInterface $queryContainer,
        OmsConfig $config,
        OmsToUtilSanitizeInterface $utilSanitizeService
    ) {
        $this->queryContainer = $queryContainer;
        $this->config = $config;

        $this->processes = $this->getProcesses();

        $orderItems = $this->queryContainer->queryMatrixOrderItems(array_keys($this->processes), $this->getStateBlacklist())
            ->find();
        $this->orderItems = $this->preProcessItems($orderItems);
        $this->utilSanitizeService = $utilSanitizeService;
    }

    /**
     * @return array
     */
    public function getMatrix()
    {
        $results = [$this->getHeaderColumns()];

        $statesUsed = $this->getOrderItemStateNames($this->orderItemStates);

        foreach ($this->orderItems as $idState => $orderItemsPerProcess) {
            if (!isset($statesUsed[$idState])) {
                continue;
            }

            $result = [
                self::COL_STATE => $statesUsed[$idState],
            ];

            foreach ($this->processes as $idProcess => $process) {
                $element = '';
                if (!empty($orderItemsPerProcess[$idProcess])) {
                    $element = $this->formatElement($orderItemsPerProcess[$idProcess], $idProcess, $idState);
                }

                $result[$process] = $element;
            }

            $results[] = $result;
        }

        return $results;
    }

    /**
     * @return array
     */
    protected function getHeaderColumns()
    {
        $headersColumns = [
            self::COL_STATE => '',
        ];
        foreach ($this->processes as $id => $name) {
            $headersColumns[$name] = $name;
        }

        return $headersColumns;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem[] $orderItems
     * @param int $idProcess
     * @param int $idState
     *
     * @return string
     */
    protected function formatElement($orderItems, $idProcess, $idState)
    {
        $grid = [
            'day' => 0,
            'week' => 0,
            'other' => 0,
        ];
        foreach ($orderItems as $orderItem) {
            $lastStateChange = $orderItem->getLastStateChange();

            $lastDay = new DateTime('-1 day');
            if ($lastStateChange > $lastDay) {
                ++$grid['day'];
                continue;
            }

            $lastDay = new DateTime('-7 day');
            if ($lastStateChange >= $lastDay) {
                ++$grid['week'];
                continue;
            }

            ++$grid['other'];
        }

        foreach ($grid as $key => $value) {
            if (!$value) {
                $grid[$key] = $value;
                continue;
            }

            $url = sprintf('/sales?id-order-item-process=%s&id-order-item-state=%s&filter=%s', $idProcess, $idState, $key);
            $grid[$key] = '<a href="' . $this->utilSanitizeService->escapeHtml($url) . '">' . $value . '</a>';
        }

        return implode(' | ', $grid);
    }

    /**
     * @return \Orm\Zed\Oms\Persistence\SpyOmsOrderProcess[]
     */
    protected function getActiveProcesses()
    {
        return $this->queryContainer
            ->queryActiveProcesses($this->config->getActiveProcesses())
            ->find();
    }

    /**
     * @param array $orderItemStates
     *
     * @return array
     */
    protected function getOrderItemStateNames(array $orderItemStates)
    {
        $results = $this->queryContainer->queryOrderItemStates($orderItemStates)->find();

        $orderItemStates = [];
        foreach ($results as $result) {
            $orderItemStates[$result->getIdOmsOrderItemState()] = $result->getName();
        }

        return $orderItemStates;
    }

    /**
     * @return array
     */
    protected function getStateBlacklist()
    {
        $blacklist = $this->config->getStateBlacklist();
        $result = $this->queryContainer->querySalesOrderItemStatesByName($blacklist)->find();
        $blacklist = [];
        foreach ($result as $row) {
            $blacklist[] = $row->getIdOmsOrderItemState();
        }

        return $blacklist;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem[]|\Propel\Runtime\Collection\ObjectCollection|mixed[] $orderItems
     *
     * @return array
     */
    protected function preProcessItems($orderItems)
    {
        $items = [];
        foreach ($orderItems as $orderItem) {
            $idState = $orderItem->getFkOmsOrderItemState();
            if (!in_array($idState, $this->orderItemStates)) {
                $this->orderItemStates[] = $idState;
            }
            $idProcess = $orderItem->getFkOmsOrderProcess();
            $items[$idState][$idProcess][] = $orderItem;
        }

        return $items;
    }

    /**
     * @return array
     */
    protected function getProcesses()
    {
        $activeProcesses = $this->getActiveProcesses();

        $processes = [];
        foreach ($activeProcesses as $process) {
            $processes[$process->getIdOmsOrderProcess()] = $process->getName();
        }
        asort($processes);

        return $processes;
    }
}
