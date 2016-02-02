<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Oms\Business\Util;

use Spryker\Shared\Library\System;
use Spryker\Zed\Oms\Business\Process\EventInterface;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Orm\Zed\Oms\Persistence\SpyOmsTransitionLog;
use Spryker\Zed\Oms\Communication\Plugin\Oms\Command\CommandInterface;
use Spryker\Zed\Oms\Communication\Plugin\Oms\Condition\ConditionInterface;
use Spryker\Zed\Oms\Persistence\OmsQueryContainerInterface;

class TransitionLog implements TransitionLogInterface
{

    const SAPI_CLI = 'cli';
    const QUERY_STRING = 'QUERY_STRING';
    const DOCUMENT_URI = 'DOCUMENT_URI';
    const ARGV = 'argv';

    /**
     * @var \Spryker\Zed\Oms\Persistence\OmsQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var array
     */
    protected $logContext;

    /**
     * @var SpyOmsTransitionLog[]
     */
    protected $logEntities;

    /**
     * @param \Spryker\Zed\Oms\Persistence\OmsQueryContainerInterface $queryContainer
     * @param array $logContext
     */
    public function __construct(OmsQueryContainerInterface $queryContainer, array $logContext)
    {
        $this->queryContainer = $queryContainer;
        $this->logContext = $logContext;
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

    /***
     * @param SpySalesOrderItem[] $salesOrderItems
     *
     * @return void
     */
    /**
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
     * @param \Spryker\Zed\Oms\Communication\Plugin\Oms\Command\CommandInterface $command
     *
     * @return void
     */
    public function addCommand(SpySalesOrderItem $item, CommandInterface $command)
    {
        $this->logEntities[$item->getIdSalesOrderItem()]->setCommand(get_class($command));
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $item
     * @param \Spryker\Zed\Oms\Communication\Plugin\Oms\Condition\ConditionInterface $condition
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

        $logEntity->setHostname(System::getHostname());

        if (PHP_SAPI === self::SAPI_CLI) {
            $path = self::SAPI_CLI;
            if (isset($_SERVER[self::ARGV]) && is_array($_SERVER[self::ARGV])) {
                $path = implode(' ', $_SERVER[self::ARGV]);
            }
        } else {
            $path = $_SERVER[self::DOCUMENT_URI];
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
     * @param array &$result
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
     * @return SpyOmsTransitionLog[]
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
