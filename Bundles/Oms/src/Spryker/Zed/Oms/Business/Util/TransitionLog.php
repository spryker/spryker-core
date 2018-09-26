<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Business\Util;

use Orm\Zed\Oms\Persistence\SpyOmsTransitionLog;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Spryker\Zed\Oms\Business\Process\EventInterface;
use Spryker\Zed\Oms\Dependency\Plugin\Command\CommandInterface;
use Spryker\Zed\Oms\Dependency\Plugin\Condition\ConditionInterface;
use Spryker\Zed\Oms\Dependency\Service\OmsToUtilNetworkInterface;
use Spryker\Zed\Oms\Persistence\OmsQueryContainerInterface;

class TransitionLog implements TransitionLogInterface
{
    public const SAPI_CLI = 'cli';
    public const SAPI_PHPDBG = 'phpdbg';
    public const QUERY_STRING = 'QUERY_STRING';
    public const DOCUMENT_URI = 'DOCUMENT_URI';
    public const ARGV = 'argv';

    /**
     * @var \Spryker\Zed\Oms\Persistence\OmsQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var array
     */
    protected $logContext;

    /**
     * @var \Orm\Zed\Oms\Persistence\SpyOmsTransitionLog[]
     */
    protected $logEntities;

    /**
     * @var \Spryker\Zed\Oms\Dependency\Service\OmsToUtilNetworkInterface
     */
    protected $utilNetworkService;

    /**
     * @param \Spryker\Zed\Oms\Persistence\OmsQueryContainerInterface $queryContainer
     * @param array $logContext
     * @param \Spryker\Zed\Oms\Dependency\Service\OmsToUtilNetworkInterface $utilNetworkService
     */
    public function __construct(
        OmsQueryContainerInterface $queryContainer,
        array $logContext,
        OmsToUtilNetworkInterface $utilNetworkService
    ) {

        $this->queryContainer = $queryContainer;
        $this->logContext = $logContext;
        $this->utilNetworkService = $utilNetworkService;
    }

    /**
     * @param \Spryker\Zed\Oms\Business\Process\EventInterface $event
     *
     * @return void
     */
    public function setEvent(EventInterface $event)
    {
        $nameEvent = $event->getName();

        if ($event->isOnEnter()) {
            $nameEvent .= ' (on enter)';
        }

        foreach ($this->logEntities as $logEntity) {
            $logEntity->setEvent($nameEvent);
        }
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem[] $salesOrderItems
     *
     * @return void
     */
    public function init(array $salesOrderItems)
    {
        $this->logEntities = [];

        foreach ($salesOrderItems as $salesOrderItem) {
            $logEntity = $this->initEntity($salesOrderItem);
            $this->logEntities[$salesOrderItem->getIdSalesOrderItem()] = $logEntity;
        }
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $item
     * @param \Spryker\Zed\Oms\Dependency\Plugin\Command\CommandInterface $command
     *
     * @return void
     */
    public function addCommand(SpySalesOrderItem $item, CommandInterface $command)
    {
        $this->logEntities[$item->getIdSalesOrderItem()]->setCommand(get_class($command));
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $item
     * @param \Spryker\Zed\Oms\Dependency\Plugin\Condition\ConditionInterface $condition
     *
     * @return void
     */
    public function addCondition(SpySalesOrderItem $item, ConditionInterface $condition)
    {
        $this->logEntities[$item->getIdSalesOrderItem()]->setCondition(get_class($condition));
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $item
     * @param string $stateName
     *
     * @return void
     */
    public function addSourceState(SpySalesOrderItem $item, $stateName)
    {
        $this->logEntities[$item->getIdSalesOrderItem()]->setSourceState($stateName);
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $item
     * @param string $stateName
     *
     * @return void
     */
    public function addTargetState(SpySalesOrderItem $item, $stateName)
    {
        $this->logEntities[$item->getIdSalesOrderItem()]->setTargetState($stateName);
    }

    /**
     * @param bool $error
     *
     * @return void
     */
    public function setIsError($error)
    {
        foreach ($this->logEntities as $logEntity) {
            $logEntity->setIsError($error);
        }
    }

    /**
     * @param string $errorMessage
     *
     * @return void
     */
    public function setErrorMessage($errorMessage)
    {
        foreach ($this->logEntities as $logEntity) {
            $logEntity->setErrorMessage($errorMessage);
        }
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $salesOrderItem
     *
     * @return \Orm\Zed\Oms\Persistence\SpyOmsTransitionLog
     */
    protected function initEntity(SpySalesOrderItem $salesOrderItem)
    {
        $logEntity = $this->getEntity();
        $logEntity->setOrderItem($salesOrderItem);
        $logEntity->setQuantity($salesOrderItem->getQuantity());
        $logEntity->setFkSalesOrder($salesOrderItem->getFkSalesOrder());
        $logEntity->setFkOmsOrderProcess($salesOrderItem->getFkOmsOrderProcess());
        $logEntity->setHostname($this->utilNetworkService->getHostName());

        $path = 'N/A';
        if (PHP_SAPI === self::SAPI_CLI || PHP_SAPI === self::SAPI_PHPDBG) {
            $path = PHP_SAPI;
            if (isset($_SERVER[self::ARGV]) && is_array($_SERVER[self::ARGV])) {
                $path = implode(' ', $_SERVER[self::ARGV]);
            }
        } else {
            if (isset($_SERVER[self::DOCUMENT_URI])) {
                $path = $_SERVER[self::DOCUMENT_URI];
            }
        }
        $logEntity->setPath($path);

        $params = [];
        if (!empty($_SERVER[self::QUERY_STRING])) {
            $params = $this->getParamsFromQueryString($_SERVER[self::QUERY_STRING]);
        }

        $logEntity->setParams($params);

        return $logEntity;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $salesOrderItem
     *
     * @return void
     */
    public function save(SpySalesOrderItem $salesOrderItem)
    {
        $this->logEntities[$salesOrderItem->getIdSalesOrderItem()]->save();
    }

    /**
     * @return void
     */
    public function saveAll()
    {
        foreach ($this->logEntities as $logEntity) {
            if ($logEntity->isModified()) {
                $logEntity->save();
            }
        }
    }

    /**
     * @param array $params
     * @param array $result
     *
     * @return void
     */
    protected function getOutputParams(array $params, array &$result)
    {
        foreach ($params as $key => $value) {
            if (is_array($value)) {
                $this->getOutputParams($value, $result);
            } else {
                $result[] = $key . '=' . $value;
            }
        }
    }

    /**
     * @return \Orm\Zed\Oms\Persistence\SpyOmsTransitionLog
     */
    protected function getEntity()
    {
        return new SpyOmsTransitionLog();
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $order
     *
     * @return \Orm\Zed\Oms\Persistence\SpyOmsTransitionLog[]
     */
    public function getLogForOrder(SpySalesOrder $order)
    {
        return $this->queryContainer->queryLogForOrder($order)->find();
    }

    /**
     * @param string $queryString
     *
     * @return array
     */
    protected function getParamsFromQueryString($queryString)
    {
        return explode('&', $queryString);
    }
}
