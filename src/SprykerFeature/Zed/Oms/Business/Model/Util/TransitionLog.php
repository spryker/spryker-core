<?php
namespace SprykerFeature\Zed\Oms\Business\Model\Util;

use SprykerFeature\Shared\Library\System;
use SprykerFeature\Zed\Auth\Business\Model\Auth;
use SprykerFeature\Zed\Library\Silex\HttpFoundation\Request;
use SprykerFeature\Zed\Oms\Persistence\OmsQueryContainer;
use SprykerFeature\Zed\Oms\Communication\Plugin\Oms\Command\CommandInterface;
use SprykerFeature\Zed\Oms\Communication\Plugin\Oms\Condition\ConditionInterface;
use SprykerFeature\Zed\Oms\Business\Model\Process\EventInterface;
use SprykerFeature\Zed\Oms\Business\Model\Process\StatusInterface;
use PropelObjectCollection;

/**
 * Class TransitionLog
 * @package SprykerFeature\Zed\Oms\Business\Model\Util
 */
class TransitionLog implements TransitionLogInterface
{

    /**
     * @var \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItemTransitionLog[]
     */
    protected $logItems = array();

    /**
     * @var \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem[]
     */
    protected $items = array();

    /**
     * @var EventInterface
     */
    protected $event;

    /**
     * @var CommandInterface[]
     */
    protected $commands = array();

    /**
     * @var ConditionInterface[]
     */
    protected $conditions = array();

    /**
     * @var StatusInterface[]
     */
    protected $sources = array();

    /**
     * @var StatusInterface[]
     */
    protected $targets = array();

    /**
     * @var bool
     */
    protected $error = false;

    /**
     * @var string
     */
    protected $errorMessage;

    /**
     * @var OmsQueryContainer
     */
    protected $queryContainer;

    /**
     * @var array
     */
    protected $logContext;

    /**
     * @param OmsQueryContainer $queryContainer
     * @param array             $logContext
     */
    public function __construct(OmsQueryContainer $queryContainer, array $logContext)
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
     * @param \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem[] $items
     */
    public function addItems(array $items)
    {
        $this->items = $items;
    }

    /**
     * @param \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem $item
     * @param CommandInterface                                         $command
     */
    public function addCommand(\SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem $item, CommandInterface $command)
    {
        $this->commands[$item->getIdSalesOrderItem()] = $command;
    }

    /**
     * @param \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem $item
     * @param ConditionInterface                                       $condition
     */
    public function addCondition(\SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem $item, ConditionInterface $condition)
    {
        $this->conditions[$item->getIdSalesOrderItem()] = $condition;
    }

    /**
     * @param \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem $item
     * @param StatusInterface                                          $status
     */
    public function addSourceStatus(\SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem $item, StatusInterface $status)
    {
        $this->sources[$item->getIdSalesOrderItem()] = $status;
    }

    /**
     * @param \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem $item
     * @param StatusInterface                                          $status
     */
    public function addTargetStatus(\SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem $item, StatusInterface $status)
    {
        $this->targets[$item->getIdSalesOrderItem()] = $status;
    }

    /**
     * @param TODO $error
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
     * @param \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem $orderItem
     */
    public function save($orderItem)
    {
        $logItem = $this->getEntity();
        $this->logItems[] = $logItem;

        $logItem->setProcess($orderItem->getProcess());

        if (isset($this->event)) {
            $eventStr = $this->event->getName();

            if ($this->event->isOnEnter()) {
                $eventStr .= ' (on enter)'; // TODO die Aussage ist nicht korrekt
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

        if (isset($this->logContext["module"])) {
            $logItem->setModule($this->logContext["module"]);
        }

        if (isset($this->logContext["controller"])) {
            $logItem->setController($this->logContext["controller"]);
        }

        if (isset($this->logContext["action"])) {
            $logItem->setAction($this->logContext["action"]);
        }

        if (isset($this->logContext["params"])) {
            $params = array();
            $this->getOutputParams($this->logContext["params"], $params);
            $logItem->setParams($params);
        }

        $logItem->setOrder($orderItem->getOrder());
        $logItem->setOrderItem($orderItem);

        $logItem->setAclUser($this->getAclUser());

        $logItem->setError($this->error);
        $logItem->setErrorMessage($this->errorMessage);

        $logItem->save();

    }

    /**
     * @return void
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
        $auth = Auth::getInstance();
        if ($auth->hasIdentity()) {
            return $auth->getIdentity();
        }

        return null;
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
     * @return \SprykerFeature\Zed\Oms\Persistence\Propel\SpyOmsTransitionLog
     */
    protected function getEntity()
    {
        return new \SprykerFeature\Zed\Oms\Persistence\Propel\SpyOmsTransitionLog();
    }

    /**
     * @param \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder $order
     * @return array|mixed|PropelObjectCollection
     */
    public function getLogForOrder(\SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder $order)
    {
        return $this->queryContainer->getLogForOrder($order)->find();
    }

}
