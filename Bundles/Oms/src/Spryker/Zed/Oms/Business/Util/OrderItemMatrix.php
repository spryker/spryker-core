<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Business\Util;

use Spryker\Zed\Oms\Dependency\Service\OmsToUtilSanitizeInterface;
use Spryker\Zed\Oms\OmsConfig;
use Spryker\Zed\Oms\Persistence\OmsQueryContainerInterface;
use Spryker\Zed\Oms\Persistence\OmsRepositoryInterface;

class OrderItemMatrix
{
    public const COL_STATE = 'COL_STATE';

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
     * @var array
     */
    protected $orderItemStateBlacklist = [];

    /**
     * @var \Spryker\Zed\Oms\Dependency\Service\OmsToUtilSanitizeInterface
     */
    protected $utilSanitizeService;

    /**
     * @var \Spryker\Zed\Oms\Persistence\OmsRepositoryInterface
     */
    protected $omsRepository;

    /**
     * @param \Spryker\Zed\Oms\Persistence\OmsQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\Oms\OmsConfig $config
     * @param \Spryker\Zed\Oms\Dependency\Service\OmsToUtilSanitizeInterface $utilSanitizeService
     * @param \Spryker\Zed\Oms\Persistence\OmsRepositoryInterface $omsRepository
     */
    public function __construct(
        OmsQueryContainerInterface $queryContainer,
        OmsConfig $config,
        OmsToUtilSanitizeInterface $utilSanitizeService,
        OmsRepositoryInterface $omsRepository
    ) {
        $this->queryContainer = $queryContainer;
        $this->config = $config;

        $this->processes = $this->getProcesses();
        $this->utilSanitizeService = $utilSanitizeService;
        $this->omsRepository = $omsRepository;
    }

    /**
     * @return array
     */
    public function getMatrix()
    {
        $results = [$this->getHeaderColumns()];
        $orderItemsMatrix = $this->getOrderItemsMatrix();
        $statesUsed = $this->getOrderItemStateNames(array_keys($orderItemsMatrix));

        foreach ($orderItemsMatrix as $idState => $grid) {
            $result = [
                self::COL_STATE => $statesUsed[$idState],
            ];

            foreach ($this->processes as $idProcess => $process) {
                $element = '';
                if (!empty($grid[$idProcess])) {
                    $element = $this->formatElement($grid[$idProcess], $idProcess, $idState);
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
     * @param array $gridInput
     * @param int $idProcess
     * @param int $idState
     *
     * @return string
     */
    protected function formatElement(array $gridInput, int $idProcess, int $idState): string
    {
        $grid = array_replace([
            'day' => 0,
            'week' => 0,
            'other' => 0,
        ], $gridInput);

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
     * @return \Orm\Zed\Oms\Persistence\SpyOmsOrderProcess[]|\Propel\Runtime\Collection\ObjectCollection
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
     * @return array
     */
    protected function getOrderItemsMatrix(): array
    {
        return $this->omsRepository->getMatrixOrderItems(array_keys($this->processes), $this->getStateBlacklist());
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
