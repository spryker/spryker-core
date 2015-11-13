<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Oms\Business\Util;

use Orm\Zed\Oms\Persistence\SpyOmsOrderProcess;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use SprykerFeature\Zed\Oms\OmsConfig;
use SprykerFeature\Zed\Oms\Persistence\OmsQueryContainerInterface;
use SprykerFeature\Zed\Sales\Business\SalesFacade;

class OrderItemMatrix
{

    const COL_STATE = 'COL_STATE';

    /**
     * @var SalesFacade
     */
    protected $salesFacade;

    /**
     * @var OmsQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var OmsConfig
     */
    protected $config;

    /**
     * @var array
     */
    protected $processes;

    /**
     * @var SpySalesOrderItem[]
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
     * @param SalesFacade $salesFacade
     * @param OmsQueryContainerInterface $queryContainer
     * @param OmsConfig $config
     */
    public function __construct(SalesFacade $salesFacade, OmsQueryContainerInterface $queryContainer, OmsConfig $config)
    {
        $this->salesFacade = $salesFacade;
        $this->queryContainer = $queryContainer;
        $this->config = $config;


        $this->orderItemQuery = $orderItemQuery;
        $this->omsFacade = $omsFacade;

        $activeProcesses = $omsFacade->getActiveProcesses();

        $processes = [];
        foreach ($activeProcesses as $process) {
            $processes[$process->getIdOmsOrderProcess()] = $process->getName();
        }
        $this->processes = $processes;

        $orderItems = $this->orderItemQuery
            ->filterByFkOmsOrderProcess(array_keys($processes))
            ->find();

        $items = [];
        foreach ($orderItems as $orderItem) {
            $idState = $orderItem->getFkOmsOrderItemState();
            if (!in_array($idState, $this->orderItemStates)) {
                $this->orderItemStates[] = $idState;
            }
            $idProcess = $orderItem->getFkOmsOrderProcess();
            $items[$idState][$idProcess][] = $orderItem;
        }
        $this->orderItems = $items;
    }

    /**
     * @return array
     */
    public function getMatrix()
    {
        $results = $this->getHeaderColumns();

        $statesUsed = $this->omsFacade->getOrderItemStateNames($this->orderItemStates);
        foreach ($statesUsed as $key => $value) {
            if (in_array($value, $this->orderItemStateBlacklist)) {
                unset($statesUsed[$key]);
            }
        }

        foreach ($this->orderItems as $idState => $orderItemsPerProcess) {
            if (!isset($statesUsed[$idState])) {
                continue;
            }

            $result = [
                self::COL_STATE => $statesUsed[$idState]
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
    protected function getHeaderColumns() {
        $headersColumns = [
            self::COL_STATE => ''
        ];
        foreach ($this->processes as $id => $name) {
            $headersColumns[$name] = $name;
        }

        return $headersColumns;
    }

    /**
     * @param SpySalesOrderItem[] $orderItems
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
            $created = $orderItem->getLastStateChange();

            $lastDay = new \DateTime('-1 day');
            if ($created > $lastDay) {
                ++$grid['day'];
                continue;
            }

            $lastDay = new \DateTime('-7 day');
            if ($created > $lastDay) {
                ++$grid['week'];
                continue;
            }

            ++$grid['other'];
        }

        foreach ($grid as $key => $value) {
            $url = sprintf('/sales?id-order-item-process=%s&id-order-item-process-state=%s&filter=%s', $idProcess, $idState, $key);
            $grid[$key] = '<a href="' . $url . '">' . $value . '</a>';
        }

        return implode(' | ', $grid);
    }

}
