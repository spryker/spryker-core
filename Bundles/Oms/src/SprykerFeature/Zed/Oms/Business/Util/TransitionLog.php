<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Oms\Business\Util;

use SprykerFeature\Shared\Library\System;
use SprykerFeature\Zed\Oms\Persistence\OmsQueryContainerInterface;
use SprykerFeature\Zed\Oms\Communication\Plugin\Oms\Command\CommandInterface;
use SprykerFeature\Zed\Oms\Communication\Plugin\Oms\Condition\ConditionInterface;
use SprykerFeature\Zed\Oms\Business\Process\EventInterface;
use SprykerFeature\Zed\Oms\Business\Process\StateInterface;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem;
use SprykerFeature\Zed\Oms\Persistence\Propel\SpyOmsTransitionLog;

class TransitionLog implements TransitionLogInterface
{

    /**
     * @var array
     */
    protected $logItems = [];

    /**
     * @var SpySalesOrderItem[]
     */
    protected $items = [];

    /**
     * @var EventInterface
     */
    protected $event;

    /**
     * @var CommandInterface[]
     */
    protected $commands = [];

    /**
     * @var ConditionInterface[]
     */
    protected $conditions = [];

    /**
     * @var StateInterface[]
     */
    protected $sources = [];

    /**
     * @var StateInterface[]
     */
    protected $targets = [];

    /**
     * @var bool
     */
    protected $error = false;

    /**
     * @var string
     */
    protected $errorMessage;

    /**
     * @var OmsQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var array
     */
    protected $logContext;

    /**
     * @param OmsQueryContainerInterface $queryContainer
     * @param array $logContext
     */
    public function __construct(OmsQueryContainerInterface $queryContainer, array $logContext)
    {
        $this->queryContainer = $queryContainer;
        $this->logContext = $logContext;
    }

    /**
     * @param EventInterface $event
     */
    public function setEvent(EventInterface $event)
    {
        $this->event = $event;
    }

    /**
     * @param SpySalesOrderItem[] $items
     */
    public function addItems(array $items)
    {
        $this->items = $items;
    }

    /**
     * @param SpySalesOrderItem $item
     * @param CommandInterface $command
     */
    public function addCommand(SpySalesOrderItem $item, CommandInterface $command)
    {
        $this->commands[$item->getIdSalesOrderItem()] = $command;
    }

    /**
     * @param SpySalesOrderItem $item
     * @param ConditionInterface $condition
     */
    public function addCondition(SpySalesOrderItem $item, ConditionInterface $condition)
    {
        $this->conditions[$item->getIdSalesOrderItem()] = $condition;
    }

    /**
     * @param SpySalesOrderItem $item
     * @param StateInterface $state
     */
    public function addSourceState(SpySalesOrderItem $item, StateInterface $state)
    {
        $this->sources[$item->getIdSalesOrderItem()] = $state;
    }

    /**
     * @param SpySalesOrderItem $item
     * @param StateInterface $state
     */
    public function addTargetState(SpySalesOrderItem $item, StateInterface $state)
    {
        $this->targets[$item->getIdSalesOrderItem()] = $state;
    }

    /**
     * @param bool $error
     */
    public function setError($error)
    {
        $this->error = $error;
    }

    /**
     * @param string $errorMessage
     */
    public function setErrorMessage($errorMessage)
    {
        $this->errorMessage = $errorMessage;
    }

    /**
     * @param SpySalesOrderItem $orderItem
     */
    public function save(SpySalesOrderItem $orderItem)
    {
        $logItem = $this->getEntity();
        $this->logItems[] = $logItem;

        $logItem->setProcess($orderItem->getProcess());

        if (isset($this->event)) {
            $eventStr = $this->event->getName();

            if ($this->event->isOnEnter()) {
                $eventStr .= ' (on enter)';
            }
            $logItem->setEvent($eventStr);

        }
        $itemId = $orderItem->getIdSalesOrderItem();

        if (isset($this->sources[$itemId])) {
            $logItem->setSourceState($this->sources[$itemId]->getName());
        }

        if (isset($this->targets[$itemId])) {
            $logItem->setTargetState($this->targets[$itemId]->getName());
        }

        if (isset($this->commands[$itemId])) {
            $logItem->addCommand(get_class($this->commands[$itemId]));
        }

        if (isset($this->conditions[$itemId])) {
            $logItem->addCondition(get_class($this->conditions[$itemId]));
        }

        $logItem->setHostname(System::getHostname());

        if (isset($this->logContext['module'])) {
            $logItem->setModule($this->logContext['module']);
        } else {
            $logItem->setModule('Not available.');
        }

        if (isset($this->logContext['controller'])) {
            $logItem->setController($this->logContext['controller']);
        } else {
            $logItem->setController('Not available.');
        }

        if (isset($this->logContext['action'])) {
            $logItem->setAction($this->logContext['action']);
        } else {
            $logItem->setAction('Not available.');
        }

        if (isset($this->logContext['params'])) {
            $params = [];
            $this->getOutputParams($this->logContext['params'], $params);
            $logItem->setParams($params);
        } else {
            $logItem->setParams(['Not available.']);
        }

        $logItem->setOrder($orderItem->getOrder());
        $logItem->setOrderItem($orderItem);

        $logItem->setError($this->error);
        $logItem->setErrorMessage($this->errorMessage);

        $logItem->save();

    }

    /**
     */
    public function saveAll()
    {
        foreach ($this->items as $item) {
            if ($item->isModified()) {
                $this->save($item);
            }
        }
    }

    /**
     * @return mixed|null
     */
    protected function getAclUser()
    {
//        $auth = Auth::getInstance();
//        if ($auth->hasIdentity()) {
//            return $auth->getIdentity();
//        }

        return;
    }

    /**
     * @param array $params
     * @param array $result
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
     * @return SpyOmsTransitionLog
     */
    protected function getEntity()
    {
        return new SpyOmsTransitionLog();
    }

    /**
     * @param SpySalesOrder $order
     *
     * @return SpyOmsTransitionLog[]
     */
    public function getLogForOrder(SpySalesOrder $order)
    {
        return $this->queryContainer->queryLogForOrder($order)->find();
    }

}
